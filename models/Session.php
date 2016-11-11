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
            [['report', 'shift'], 'string'],
            [['user_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start' => 'Время начала',
            'stop' => 'Время конца',
            'shift' => 'Сессия',
            'report' => 'Отчет',
        ];
    }
    
    public function getShiftName()
    {
        if(empty($this->shift)) {
            return null;
        }
        
        $shifts = yii::$app->getModule('worksess')->shifts;

        foreach($shifts as $shiftId => $shiftName) {
            if($shiftId == $this->shift) {
                return $shiftName;
            }
        }
        
        return null;
    }
    
    public function getUser()
    {
        $userModel = yii::$app->getModule('worksess')->adminModel;
        if($userModel && class_exists($userModel)) {
            return $this->hasOne($userModel::className(), ['id' => 'user_id']);
        }
        
        return null;
    }
    
    public function getDuration()
    {
        $worksess = yii::$app->worksess;
        
        $start = $this->start_timestamp;
        $stop = $this->stop_timestamp;
        
        if(!$stop) {
            $stop = time();
        }
        
        return $worksess::getDate($stop-$start);
    }
    
    public function getUsers()
    {
        $userModel = yii::$app->getModule('worksess')->userModel;
        
        $userIds = $this->hasMany(UserSession::className(), ['session_id' => 'id'])->select('user_id')->distinct();
        
        if(yii::$app->has('organisation') && $organisation = yii::$app->organisation->get()) {
            return $userModel::find()->where('(organisation_id = :organisation_id OR organisation_id IS NULL)', ['organisation_id' => $organisation->id])->andWhere(['id' => $userIds])->all();
        } else {
            return $userModel::find()->where(['id' => $userIds])->all();
        }
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
