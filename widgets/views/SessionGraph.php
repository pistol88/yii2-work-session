<?php
use pistol88\worksess\widgets\ControlButton;
use pistol88\worksess\widgets\Info;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>

<?php Pjax::begin(); ?>
<?php if(empty($workers)) { ?>
    <p>Работников нет.</p>
<?php } else { ?>

<div class="worksession-graph-container">
<?php if($control) { ?><a href="" class="worsess-graph-update"> <i class="glyphicon glyphicon-refresh"></i> Обновить</a><?php } ?>
    <table class="worksession-graph table table-hover table-condensed">
        <thead>
            <tr>
                <th align="right" class="worker-name"><small>Сотрудник / Время работы</small></th>
                <?php
                foreach($hours as $h) {
                    if($h == (int)date('H') && $date == date('Y-m-d')) {
                        echo '<td class="current hour"><div id="current-hour"></div><strong>'.$h.':00</strong></td>'; 
                    } else {
                        echo '<td class="hour">'.$h.':00</td>';
                    }
                }
                ?>

                <?php if($control) { ?>
                    <td>&nbsp;</td>
                <?php } ?>
            </tr>
        </thead>
        <?php foreach($workers as $worker) { ?>
            <?php $cdate = $date; ?>
            <tbody class="worker-line worker-line-<?=$worker->id;?>">
                <tr>
                    <th class="worker-name">
                        <p class="staffername"><a href="<?=Url::toRoute([yii::$app->getModule('worksess')->stafferProfileUrl, 'id' => $worker->id]);?>"><?=$worker->name;?></a></p>
                        <p><small><?php if($worker->category) { ?><?=$worker->category->name;?><?php } ?></small></p>
                    </th>
                    <?php
                    foreach($hours as $key => $h) {
                        $time = ' '.$h.':00';

                        $minutes = '';
                        for($m = 0; $m <= 59; $m = $m+2) {
                            $timestamp = strtotime($cdate.' '.$h.':'.$m);

                            $minutes .= '<div title="'.$cdate.'" data-timestamp="'.$timestamp.'">&nbsp;</div>';
                        }

                        if($key != 0 && $time == ' 0:00') {
                            $cdate = date('Y-m-d', strtotime($cdate)+86400);
                        }
                        
                        echo '<td class="worker-hour"><div class="hourContainer">'.$minutes.'</div></td>';
                    }
                    ?>
                    <?php if($control) { ?>
                        <td class="control">
                            <?=ControlButton::widget(['for' => $worker]);?>
                        </td>
                    <?php } ?>
                </tr>
                <?php if($control) { ?>
                    <tr>
                        <td colspan="200" class="session_status"><?=Info::widget(['for' => $worker, 'session' => $session]);?></td>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>
        <tfoot>
            <tr>
                <th align="right" class="worker-name">&nbsp;</th>
                <?php
                foreach($hours as $h) {
                    if($h == (int)date('H') && $date == date('Y-m-d')) {
                        echo '<td class="current hour"><div id="current-hour"></div><strong>'.$h.':00</strong></td>'; 
                    } else {
                        echo '<td class="hour">'.$h.':00</td>';
                    }
                }
                ?>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
    </table>
<?php } ?>
</div>

<script>

var session_info_window_data = '<?=$infoWindowUrl;?>';

if (typeof pistol88 != "undefined"  && typeof pistol88.worksess_graph != "undefined") {
    <?php foreach($workers as $worker) { ?>
        //((stop_timestamp IS NULL OR stop_timestamp > :time) AND start_timestamp < :time)
        <?php foreach($worker->getSessionsBySession($session) as $userSession) { ?>
            var start = <?=$userSession->start_timestamp;?>;
            <?php if($userSession->stop_timestamp) { ?>
                var stop = <?=$userSession->stop_timestamp;?>;
            <?php } else { ?>
                var stop = <?=time();?>; //current
            <?php } ?>
            pistol88.worksess_graph.render(<?=$worker->id;?>, start, stop);
        <?php } ?>
    <?php } ?>
}

window.onload = function() {
    <?php foreach($workers as $worker) { ?>
        //((stop_timestamp IS NULL OR stop_timestamp > :time) AND start_timestamp < :time)
        <?php foreach($worker->getSessionsBySession($session) as $userSession) { ?>
            var start = <?=$userSession->start_timestamp;?>;
            <?php if($userSession->stop_timestamp) { ?>
                var stop = <?=$userSession->stop_timestamp;?>;
            <?php } else { ?>
                var stop = <?=time();?>; //current
            <?php } ?>
            pistol88.worksess_graph.render(<?=$worker->id;?>, start, stop, <?=$userSession->id;?>);
        <?php } ?>
    <?php } ?>
}

</script>

<?php Pjax::end(); ?>

<div class="modal fade" id="session-info-window" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Очет по сессии</h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=yii::t('order', 'Close');?></button>
            </div>
        </div>
    </div>
</div>