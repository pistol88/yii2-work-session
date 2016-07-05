<?php
namespace pistol88\worksess\widgets;

use pistol88\worksess\models\Session;
use yii\helpers\Html;
use yii\helpers\Url;
use yii;

class ControlButton extends \yii\base\Widget
{
    public $startText = 'Начать';
    public $stopText = 'Остановить';
    public $for = null;
    
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
        
        if(yii::$app->worksess->soon($this->for)) {
            return Html::a($this->stopText, ['/worksess/session/stop', 'userId' => $userId], ['data-user-id' => $userId, 'class' => 'worksess-button worksess-stop btn btn-danger']);
        } else {    
            return Html::a($this->startText, ['/worksess/session/start', 'userId' => $userId], ['data-user-id' => $userId, 'class' => 'worksess-button worksess-start btn btn-success']); 
       }
    }
}
