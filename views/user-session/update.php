<?php
use yii\helpers\Html;

$this->title = 'Редактировать сессию: ' . ' ' . $model->start;
$this->params['breadcrumbs'][] = 'Обновить';

?>
<div class="user-session-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
