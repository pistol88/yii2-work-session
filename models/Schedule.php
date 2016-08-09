<?php
namespace pistol88\worksess\models;

use yii;
use yii\helpers\Url;

class Schedule extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%work_session_schedule}}';
    }

    public function rules()
    {
        return [
            [['date', 'user_id'], 'required'],
            [['date', 'shift'], 'string'],
            [['user_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата',
            'shift' => 'Смена',
            'user_id' => 'Пользователь',
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
}
