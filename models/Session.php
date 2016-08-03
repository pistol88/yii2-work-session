<?php
namespace pistol88\worksess\models;

use yii;
use yii\helpers\Url;

class Session extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%work_session}}';
    }

    public function rules()
    {
        return [
            ['start', 'required'],
            [['start', 'stop'], 'string'],
            [['report'], 'string'],
            [['user_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start' => 'Время начала',
            'stop' => 'Время конца',
            'report' => 'Отчет',
        ];
    }
    
    public function getUser()
    {
        $userModel = yii::$app->getModule('worksess')->adminModel;
        if($userModel && class_exists($userModel)) {
            return $this->hasOne($userModel::className(), ['id' => 'user_id']);
        }
        
        return null;
    }
    
    public function getUserSessions()
    {
        return $this->hasMany(UserSession::className(), ['session_id' => 'id']);
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
