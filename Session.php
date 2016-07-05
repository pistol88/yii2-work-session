<?php
namespace pistol88\worksess;

use pistol88\worksess\models\Session as SessionModel;
use pistol88\worksess\models\UserSession;
use yii\base\Component;
use yii;

class Session extends Component
{
    public function init()
    {
        parent::init();
    }
    
    public function start($for)
    {
        if(!$for) {
            $model = new SessionModel;
        } else {
            $model = new UserSession;
            $model->user_id = $for->getId();
        }

        if($today = $this->soon($for)) {
            return false;
        } else {
            $model->start = date('Y-m-d H:i:s');

            return $model->save();
        }
    }
    
    public function stop($for)
    {
        if(!$today = $this->soon($for)) {
            return false;
        } else {
            $today->stop = date('Y-m-d H:i:s');

            return $today->save();
        }
    }
    
    public function soon($for = null)
    {
        if($for) {
            return UserSession::findOne(['user_id' => $for->getId(), 'stop' => null]);
        }

        return SessionModel::findOne(['stop' => null]);
    }
    
    public function today($for = null)
    {
        if($for) {
            return UserSession::findOne(['DATE_FORMAT(start, "%Y-%m-%d")' => date('Y-m-d'), 'user_id' => $for->getId()]);
        }

        return SessionModel::findOne(['DATE_FORMAT(start, "%Y-%m-%d")' => date('Y-m-d')]);
    }
    
    public function getHours($for = null, $date = null)
    {
        if(!$date) {
            $date = date('Y-m-d');
        }

        if($for) {
            $sum = UserSession::find()->where(['DATE_FORMAT(start, "%Y-%m-%d")' => $date, 'user_id' => $for->getId()])->sum('stop_timestamp-start_timestamp');
        } else {
            $sum = SessionModel::find()->where(['DATE_FORMAT(start, "%Y-%m-%d")' => $date])->sum('stop_timestamp-start_timestamp');
        }

        return self::getDate($sum);
    }
    
    private static function getDate($date)
    {
        $days = floor($date/86400);
        $hours = floor($date/(60*60));
        $min = floor($date/60);

        if($days > 0) {
            switch(substr($days, -2)) {
                case 1: $d = 'день';
                break;
                case 2: case 3: case 4: $d = 'дня';
                break;
                default: $d = 'дней';
            }
        }

        if($hours > 0) {
            switch(substr($hours, -2)) {
                case 1: $h = 'час';
                break;
                case 2: case 3: case 4: $h = 'часа';
                break;
                default: $h = 'часов';
            }
        }

        if($min > 0) {
            switch(substr($min, -2)) {
                case 1: $m = 'минута';
                break;
                case 2: case 3: case 4: $m = 'минуты';
                break;
                default: $m = 'минут';
            }
        }

        $return = '';
        
        if ($days > 0) {
            $return .= $days.' '.$d;
        }

        if ($hours > 0) {
            $return .= ' '.$hours.' '.$h;
        }

        if ($min > 0) {
            $return .= ' '.$min.'&nbsp;'.$m;
        }

        return $return;
    }
}
