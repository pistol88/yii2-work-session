<?php
namespace pistol88\worksess\widgets;

use yii\helpers\Html;
use yii;

class SessionGraph extends \yii\base\Widget
{
    public $for = null;
    
    public function init()
    {
        \pistol88\worksess\assets\SessionGraph::register($this->getView());
        
        return parent::init();
    }

    public function run()
    {
        $workers = yii::$app->getModule('worksess')->getWorkersList();
        $session = yii::$app->worksess->soon();
        
        if(!$session) {
            return null;
        }
        
        $startHour = (int)date('H', $session->start_timestamp);
        $stopHour = date('H');
        
        $i = 1;
        $h = $startHour;
        $hours = [];
        while($i <= 8) {
            $i++;
            $hours[] = $h;
            $h++;
            if($h > 24) {
                $h = '0';
            }
        }
        
        return $this->render('SessionGraph', ['workers' => $workers, 'session' => $session, 'hours' => $hours]);
    }
}
