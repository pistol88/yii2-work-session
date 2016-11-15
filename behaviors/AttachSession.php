<?php
namespace pistol88\worksess\behaviors;

use yii;
use yii\base\Behavior;

class AttachSession extends Behavior
{
    public function getSessionTime($date = null)
    {
        return yii::$app->worksess->getTime($this->owner, $date);
    }
    
    public function getSessions($date = null)
    {
        return yii::$app->worksess->getSessions($this->owner, $date);
    }
    
    public function getSessionSeconds($date = null)
    {
        return yii::$app->worksess->getSeconds($this->owner, $date);
    }
    
    public function getSessionsBySession($session)
    {
        return yii::$app->worksess->getSessionsBySession($this->owner, $session);
    }
    
    public function hasWork($timestamp) {
        return yii::$app->worksess->hasWork($this->owner, $timestamp);
    }
}
