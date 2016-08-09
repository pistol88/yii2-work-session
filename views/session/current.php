<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use pistol88\worksess\widgets\ControlButton;
use pistol88\worksess\widgets\Info;
use pistol88\worksess\widgets\SessionGraph;

$this->title = 'Рабочая смена';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="session-index">
    
    <?php if(Yii::$app->session->hasFlash('success')) { ?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php } ?>
    <?php if(Yii::$app->session->hasFlash('fail')) { ?>
        <div class="alert alert-danger" role="alert">
            <?= Yii::$app->session->getFlash('fail') ?>
        </div>
    <?php } ?>

    <div class="session-admin">
        <h2>Смена</h2>
        
        <?=Info::widget();?>

        <?=ControlButton::widget();?>

        <hr />
        
        <?=SessionGraph::widget();?>
    </div>
    
</div>
