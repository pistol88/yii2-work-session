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
        $hours = floor($date/(60*60));
        
        if($hours > 0) {
            $min = floor(($date-($hours*(60*60)))/60);
        } else {
            $min = floor($date/60);
        }

        $return = '';
        
        if($hours > 0) {
            switch(substr($hours, -1)) {
                case 1: $h = 'час';
                break;
                case 2: case 3: case 4: $h = 'часа';
                break;
                default: $h = 'часов';
            }
            
            $return .= ' '.$hours.' '.$h;
        }

        if($min > 0) {
            switch(substr($min, -1)) {
                case 1: $m = 'минута';
                break;
                case 2: case 3: case 4: $m = 'минуты';
                break;
                default: $m = 'минут';
            }
            
            $return .= ' '.$min.'&nbsp;'.$m;
        }

        return $return;
    }
}
