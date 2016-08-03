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
            [['user_id', 'session_id'], 'integer'],
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
        ];
    }
    
    public function getSession()
    {
        return $this->hasOne(Session::className(), ['id' => 'session_id']);
    }
    
    public function beforeSave($insert)
    {
        if($this->start) {
            $this->start_timestamp = strtotime($this->start);
        }
        
        if($this->stop) {
            $this->stop_timestamp = strtotime($this->stop);
        }
        
        return parent::beforeSave($insert);
    }
}
