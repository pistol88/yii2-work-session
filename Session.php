<?php
namespace pistol88\worksess;

use pistol88\worksess\models\Session as SessionModel;
use pistol88\worksess\models\UserSession;
use pistol88\worksess\models\Schedule;
use pistol88\worksess\events\SessionEvent;
use yii\base\Component;
use yii;

class Session extends Component
{
    const EVENT_SESSION_START = 'start';
    const EVENT_SESSION_STOP = 'stop';
    
    public function init()
    {
        parent::init();
    }
    
    public function start($for = null, $shift = null)
    {
        if(!$for) {
            $model = new SessionModel;
            $model->user_id = yii::$app->user->id;
            $model->shift = $shift;
        } else {
            if(!$current = $this->soon()) {
                return false;
            }

            if($this->soon($for)) {
                return false;
            }
            
            $model = new UserSession;
            $model->session_id = $current->id;
            $model->user_id = $for->getId();
        }

        $model->start = date('Y-m-d H:i:s');

        $return = $model->save();
        
        $sessionEvent = new SessionEvent(['model' => $model]);
        $this->trigger(self::EVENT_SESSION_START, $sessionEvent);
        
        return $return;
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
            
            $return = $today->save();
            
            $sessionEvent = new SessionEvent(['model' => $today]);
            $this->trigger(self::EVENT_SESSION_STOP, $sessionEvent);
            
            return $return;
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
            return UserSession::findAll(['DATE_FORMAT(start, "%Y-%m-%d")' => date('Y-m-d'), 'user_id' => $for->getId()]);
        }

        return SessionModel::findAll(['DATE_FORMAT(start, "%Y-%m-%d")' => date('Y-m-d')]);
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
    }

    public function getUserSessions($userId)
    {
        return UserSession::find()->where(['user_id' => $userId]);
    }

    public function getSessionsBySession($for = null, $session = null)
    {
        return UserSession::find()->where(['session_id' => $session->id, 'user_id' => $for->getId()])->all();
    }

    //Был ли работник на рабочем месте в эту секунду
    public function hasWork($for, $timestamp = null)
    {
        if(!$timestamp) {
            $timestamp = time();
        }
        //echo date('d.m.Y H:i:s', $timestamp)."<br />";
        if($timestamp > time()) {
            return false;
        }

        return UserSession::find()
            ->where('((stop_timestamp IS NULL OR stop_timestamp > :time) AND start_timestamp < :time) AND user_id = :user_id', [':user_id' => $for->id, ':time' => $timestamp])
            ->one(); 
    }
    
    //Общее число сотрудников в сессию (день)
    public function getWorkersCount($session = null, $shift = null)
    {
        if(empty($session)) {
            $session = $this->soon();
        }
        
        if(!$session) {
            return 0;
        }
        
        $query = UserSession::find()->select('user_id')->where(['session_id' => $session->id])->distinct();
		
		if(!empty($shift)) {
			$query->where(['shift' => $shift]);
		}
		
		return $query->count();
    }
    
    //Общее число сотрудников за период времени
    public function getWorkersCountByDate($session, $start = null, $stop = null)
    {
		if(!$stop | $stop == '0000-00-00 00:00:00') {
			return UserSession::find()->select('user_id')->distinct()->where('session_id = :session_id AND start <= :start', [':start' => $start, ':session_id' => $session->id])->count();
		}
		
        return UserSession::find()->select('user_id')->distinct()->where('session_id = :session_id AND start <= :start AND stop <= :stop', [':start' => $start, ':stop' => $stop, ':session_id' => $session->id])->count();
    }
	
    //Сотрудники, которые должны выйти по графику в этот день
    public function getWorkers($date = null, $shiftId = null)
    {
        if(!$date) {
            $date = date('Y-m-d');
        }
        
        $userModel = yii::$app->getModule('worksess')->userModel;
        
        if($userIds = Schedule::find()->select('user_id')->distinct()->where(['date' => $date, 'shift' => $shiftId])->select('user_id')->distinct()) {
            return $userModel::findAll(['id' => $userIds]);
        } else {
            return [];
        }
    }
	
	//Время работы сотрудника за сессию
	public function getUserWorkTimeBySession($for = null, $session)
	{
		$sum =  $this->getSecondsBySession($for, $session);
		
		return self::getDate($sum);
	}
	
	//Время работы сотрудника за дату
    public function getUserWorkTimeByDate($for = null, $date = null)
    {
        $sum = $this->getSecondsByDate($for, $date);

        return self::getDate($sum);
    }
	
	//Время работы сотрудника за дату
    public function getSecondsByDate($for = null, $date = null)
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
	
    public function getSecondsBySession($for = null, $session)
    {
        if($for) {
            $sum = UserSession::find()->where(['session_id' => $session->id, 'user_id' => $for->getId()])->sum('stop_timestamp-start_timestamp');
            if($sum === null) {
                if($sess = UserSession::find()->where(['session_id' => $session->id, 'user_id' => $for->getId()])->one()) {
                    $sum = time()-$sess->start_timestamp;
                }
            }
        } else {
            $sum = SessionModel::find()->where(['session_id' => $session->id])->sum('stop_timestamp-start_timestamp');
            if($sum === null) {
                if($sess = SessionModel::find()->where(['session_id' => $session->id])->one()) {
                    $sum = time()-$sess->start_timestamp;
                }
            }
        }
        
        return $sum;
    }
	
    public static function getDate($date)
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
