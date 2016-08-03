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
            $model->user_id = yii::$app->user->id;
        } else {
            if(!$current = $this->soon()) {
                return false;
            }
            $model = new UserSession;
            $model->session_id = $current->id;
            $model->user_id = $for->getId();
        }


        $model->start = date('Y-m-d H:i:s');

        return $model->save();
    }
    
    public function stop($for)
    {
        if(!$today = $this->soon($for)) {
            return false;
        } else {
            $today->stop = date('Y-m-d H:i:s');
            
            if(!$for) {
                foreach($today->userSessions as $userSession) {
                    $userSession->stop = date('Y-m-d H:i:s');
                    $userSession->save();
                }
            }
            
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
    
    public function getTime($for = null, $date = null)
    {
        $sum = $this->getSeconds($for, $date);

        return self::getDate($sum);
    }
    
    public function getSessions($for = null, $date = null)
    {
        if(!$date) {
            $date = date('Y-m-d');
        }
        
        if($for) {
            return UserSession::find()->where(['DATE_FORMAT(start, "%Y-%m-%d")' => $date, 'user_id' => $for->getId()])->all();
        } else {
            return SessionModel::find()->where(['DATE_FORMAT(start, "%Y-%m-%d")' => $date])->all();
        }
        
        return $sum;
    }
    
    //Общее число сотрудников за смену (день)
    public function getWorkersCount($date = null)
    {
        if(empty($date)) {
            $date = date('Y-m-d');
        }
        
        return UserSession::find()->select('user_id')->distinct()->where(['DATE_FORMAT(start, "%Y-%m-%d")' => $date])->count();
    }
    
    //Был ли работник на рабочем месте в эту секунду
    public function hasWork($for, $timestamp = null)
    {
        if(!$timestamp) {
            $timestamp = time();
        }
        
        if($timestamp > time()) {
            return false;
        }

        return UserSession::find()
            ->select('id')
            ->where('((stop_timestamp IS NULL OR stop_timestamp > :time) AND start_timestamp < :time) AND user_id = :user_id', [':user_id' => $for->id, ':time' => $timestamp])
            ->count();
    }
    
    public function getSeconds($for = null, $date = null)
    {
        if(!$date) {
            $date = date('Y-m-d');
        }

        if($for) {
            $sum = UserSession::find()->where(['DATE_FORMAT(start, "%Y-%m-%d")' => $date, 'user_id' => $for->getId()])->sum('stop_timestamp-start_timestamp');
            if($sum === null) {
                if($sess = UserSession::find()->where(['DATE_FORMAT(start, "%Y-%m-%d")' => $date, 'user_id' => $for->getId()])->one()) {
                    $sum = time()-$sess->start_timestamp;
                }
            }
        } else {
            $sum = SessionModel::find()->where(['DATE_FORMAT(start, "%Y-%m-%d")' => $date])->sum('stop_timestamp-start_timestamp');
            if($sum === null) {
                if($sess = SessionModel::find()->where(['DATE_FORMAT(start, "%Y-%m-%d")' => $date])->one()) {
                    $sum = time()-$sess->start_timestamp;
                }
            }
        }
        
        return $sum;
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
