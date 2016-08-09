<?php
namespace pistol88\worksess\widgets;

use yii\helpers\Html;
use yii;

class Info extends \yii\base\Widget
{
    public $for = null;
    public $session = null;
    
    public function init()
    {
        \pistol88\worksess\assets\WidgetAsset::register($this->getView());
        
        return parent::init();
    }

    public function run()
    {
        $userId = null;
        
        if($this->for) {
            $userId = $this->for->getId();
        }
        
        if($soon = yii::$app->worksess->soon($this->for)) {
            $message = $this->date($soon);
        } elseif($this->session && $this->for && $insess = yii::$app->worksess->hasWork($this->for)) {
            $message = $this->date($today, false);
        } elseif($today = yii::$app->worksess->today($this->for)) {
            $message = $this->date($today);
        } else {
            $message = 'Cессии не было';
        }
        
        return Html::tag('div', Html::tag('p', $message), ['class' => 'worksess-info'.$userId]);
    }
    
    public function date($session, $toDay = true)
    {
        if(!$session) {
            return null;
        }

        if(!isset($session->start)) {
            return null;
        }
        
        $start = strtotime($session->start);
        $stop = strtotime($session->stop);
        
        //Текущая сессия закончилась
        if($session->stop_timestamp) {
            $sum = yii::$app->worksess->getTime($this->for);
            $return = 'Последняя сессия: '.date('H:i', $start).' - '.date('H:i', $stop);
            if($sum) {
                $return .= ' (общее время: '.$sum.')';
            }
            
            $sessionInfo = Html::tag('p', $return, ['class' => 'worksess-many-today']);
        //Текущая сессия продолжается
        } else {
            $sessionInfo = Html::tag('p', 'Сессия начата: '.date('H:i', $start), ['class' => 'worksess-first-today']);
        }

        if(!$this->for && $session->user_id && $session->user) {
            $sessionInfo .= Html::tag('p', $session->shiftName.' | Администратор: '.$session->user->name, ['class' => 'worksess-administrator']);
        }
        
        return Html::tag('div', $sessionInfo, ['class' => 'session-info-widget']);
    }
}
