<?php
namespace pistol88\worksess\models;

use yii;
use yii\helpers\Url;

class UserSession extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%work_session_user}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'start'], 'required'],
            [['start', 'stop'], 'string'],
            [['report'], 'string'],
            [['user_id', 'session_id', 'start_timestamp', 'stop_timestamp'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start' => 'Время начала',
            'session_id' => 'Материнская сессия',
            'stop' => 'Время конца',
            'report' => 'Отчет',
            'user_id' => 'Пользователь',
            'start_timestamp' => 'Время начала',
            'stop_timestamp' => 'Время конца',
        ];
    }
    
    public function getShiftName()
    {
        return $this->session->shiftName;
    }
    
    public function getDuration()
    {
        $worksess = yii::$app->worksess;
        
        $start = $this->start_timestamp;
        $stop = $this->stop_timestamp;

        if(!$stop) {
            $stop = time();
        }
        
        return $worksess::getDate(($stop-$start));
    }
    
    public function getSession()
    {
        return $this->hasOne(Session::className(), ['id' => 'session_id']);
    }

    public function beforeSave($insert)
    {
        if($this->start) {
            $this->start = date('Y-m-d H:i:s', strtotime($this->start));
            $this->start_timestamp = strtotime($this->start);
        }
        
        if($this->stop) {
            $this->stop = date('Y-m-d H:i:s', strtotime($this->stop));
            $this->stop_timestamp = strtotime($this->stop);
        }
        
        return parent::beforeSave($insert);
    }
}
