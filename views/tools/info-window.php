<?php
use yii\helpers\Url;
?>
<h3> <?php if(isset($session->session->user)) { ?>Администратор <?=$session->session->user->name;?><?php } ?> </h3>
<p><strong>Смена</strong>: <?=$session->shiftName;?></p>
<p><strong>Старт</strong>: <?=date('d.m.Y H:i:s', $session->start_timestamp);?></p>
<p><strong>Стоп</strong>: <?php if($session->stop_timestamp) echo date('d.m.Y H:i:s', $session->stop_timestamp); else echo '-';?></p>
<p><strong>Продолжительность</strong>: <?=$session->getDuration();?></p>
<p><strong>Редактировать</strong>: <a href="<?=Url::toRoute(['/worksess/user-session/update', 'id' => $session->id]);?>"><i class="glyphicon glyphicon-pencil"></i></a></p>
<p><strong>Удалить период</strong>: <a href="<?=Url::toRoute(['/worksess/user-session/delete', 'id' => $session->id]);?>" data-confirm="Вы уверены, что хотите удалить этот период?" style="color: red;" data-method="post"><i class="glyphicon glyphicon-remove"></i></a></p>

<hr style="clear: both;" />

<table class="table table-hover table-responsive">
    <tr>
        <td><strong>Заказов/Услуг</strong></td>
        <td><strong>Выручка</strong></td>
        <td><strong>Время работы</strong></td>
    </tr>
    <tr>
        <td>
            <?=$stat['count_orders'];?>/<?=$stat['count_elements'];?>
        </td>
        <td>
            <?=$stat['total'];?>
            <?=$module->currency;?>
        </td>
        <td class="worker-session-time">
            <?=$session->getDuration();?>
        </td>
    </tr>
</table>