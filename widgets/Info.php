<?php
namespace pistol88\worksess\widgets;

use yii\helpers\Html;
use yii;

class Info extends \yii\base\Widget
{
    public $for = null;
    
    public function init()
    {
        return parent::init();
    }

    public function run()
    {
        $userId = null;
        
        if($this->for) {
            $userId = $this->for->getId();
        }
        
        if(yii::$app->worksess->soon($this->for)) {
            $message = $this->date(yii::$app->worksess->soon($this->for)->start);
        } elseif(yii::$app->worksess->today($this->for)) {
            $message = $this->date(yii::$app->worksess->today($this->for)->start, yii::$app->worksess->today($this->for)->stop);
        } else {
            $message = 'Сегодня сессии не было';
        }
        
        return Html::tag('div', Html::tag('p', $message), ['class' => 'worksess-info'.$userId]);
    }
    
    public function date($start, $stop = false)
    {
        $start = strtotime($start);
        $stop = strtotime($stop);
        
        if(date('d.m.Y') == date('d.m.Y', $start) && date('d.m.Y') == date('d.m.Y', $stop)) {
            $sum = yii::$app->worksess->getTime($this->for);
            $return = 'Последняя сессия: '.date('H:i', $start).' - '.date('H:i', $stop);
            if($sum) {
                $return .= ' (общее время: '.$sum.')';
            }
            
            return Html::tag('span', $return, ['class' => 'worksess-many-today']);
        } elseif(date('d.m.Y') == date('d.m.Y', $start)) {
            return Html::tag('span', 'Сессия начата: '.date('H:i', $start), ['class' => 'worksess-first-today']);
        }
        
        if($stop) {
            return Html::tag('span', date('d.m.Y H:i', $start).' - '.date('d.m.Y H:i', $stop), ['class' => 'worksess-long']);
        }
        
        return Html::tag('span', date('d.m.Y H:i', $start), ['class' => 'worksess-soon']);
    }
}
