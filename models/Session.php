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
