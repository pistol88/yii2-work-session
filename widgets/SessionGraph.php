<?php
namespace pistol88\worksess\widgets;

use yii\helpers\Html;
use yii;

class SessionGraph extends \yii\base\Widget
{
    public $for = null;
    public $hoursCount = 12;
    public $session = null;
    public $control = true;
    public $workers = null;
    
    public function init()
    {
        \pistol88\worksess\assets\SessionGraph::register($this->getView());
        
        if(!$this->session) {
            $this->session = yii::$app->worksess->soon();
        }
        
        return parent::init();
    }

    public function run()
    {
        if($this->workers === null) {
            $workers = [];
            if($this->session) {
                $workers = yii::$app->worksess->getWorkers(date('Y-m-d', strtotime($this->session->start)), $this->session->shift);
            }
            
            if(!$workers) {
                $workers = yii::$app->getModule('worksess')->getWorkersList();
            }
        } else {
            $workers = $this->workers;
        }

        $session = $this->session;
        
        if(!$session) {
            $session = yii::$app->worksess->soon();
        } elseif($session->stop_timestamp) {
            $this->hoursCount = ceil(($session->stop_timestamp-$session->start_timestamp)/60/60)+1;
        }

        if(!$session) {
            return null;
        }
        
        $date = date('Y-m-d', $session->start_timestamp);
        
        $startHour = (int)date('H', $session->start_timestamp);
        
        $i = 1;
        $h = $startHour;
        $hours = [];
        
        while($i <= $this->hoursCount) {
            $i++;
            $hours[] = $h;
            $h++;
            if($h >= 24) {
                $h = '0';
            }
        }
        
        return $this->render('SessionGraph',
            [
                'date' => $date,
                'control' => $this->control,
                'workers' => $workers,
                'session' => $session,
                'hours' => $hours
            ]);
    }
}
