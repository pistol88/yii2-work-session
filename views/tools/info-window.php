<h3> <?php if(isset($session->session->user)) { ?>Администратор <?=$session->session->user->name;?><?php } ?> </h3>
<p>Смена: <?=$session->shiftName;?></p>
<p>Старт: <?=date('d.m.Y H:i:s', $session->start_timestamp);?></p>
<p>Стоп: <?php if($session->stop_timestamp) echo date('d.m.Y H:i:s', $session->stop_timestamp); else echo '-';?></p>
<hr style="clear: both;" />

<table class="table table-hover table-responsive">
    <tr>
        <td><strong>Заказов/Услуг</strong></td>
        <td><strong>Выручка</strong></td>
        <td><strong>Время работы</strong></td>
    </tr>
    <tr>
        <td>
            <?=$stat['count_order'];?>/<?=$stat['count_elements'];?>
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