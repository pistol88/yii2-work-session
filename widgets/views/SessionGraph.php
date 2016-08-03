<?php
use pistol88\worksess\widgets\ControlButton;
use pistol88\worksess\widgets\Info;
use yii\helpers\Url;
use yii\widgets\Pjax;
?>
<?php Pjax::begin(); ?>
<h3>Смена <?=date('d.m.Y');?></h3>
<a href="" class="worsess-graph-update"> <i class="glyphicon glyphicon-refresh"></i> Обновить</a>
<table class="worksession-graph table table-hover table-condensed">
    <thead>
        <tr>
            <td align="right">&nbsp;</td>
            <?php
            foreach($hours as $h) {
                if($h == (int)date('H')) {
                    echo '<td class="current hour"><div id="current-hour"></div><strong>'.$h.':00</strong></td>'; 
                } else {
                    echo '<td class="hour">'.$h.':00</td>';
                }
            }
            ?>
            <td>&nbsp;</td>
        </tr>
    </thead>
    <?php foreach($workers as $worker) { ?>
        <tbody class="worker-line">
            <tr>
                <th class="worker-name">
                    <p><?=$worker->name;?></p>
                    <p><small><?php if($worker->category) { ?><?=$worker->category->name;?><?php } ?></small></p>
                </th>
                <?php
                foreach($hours as $h) {
                    $time = ' '.$h.':00';

                    if($h <= (int)date('H')) {
                        $minutes = '';
                        for($m = 0; $m < 60; $m = $m+5) {
                            $timestamp = strtotime(date('Y-m-d').' '.$h.':'.$m);
                            $timestamp = $timestamp;

                            $active = '';
                            if($timestamp > $session->start_timestamp && $worker->hasWork($timestamp)) {
                                $active = ' active';
                            }

                            $minutes .= '<div class="'.$active.'">&nbsp;</div>';
                        }
                    } else {
                        $minutes = '&nbsp;';
                    }

                    echo '<td class="worker-hour"><div class="hourContainer">'.$minutes.'</div></td>';
                }
                ?>
                <td class="control">
                    <?=ControlButton::widget(['for' => $worker]);?>
                </td>
            </tr>
            <tr>
                <td colspan="200" class="session_status"><?=Info::widget(['for' => $worker, 'session' => $session]);?></td>
            </tr>
        </tbody>
    <?php } ?>
    <tfoot>
        <tr>
            <td align="right">&nbsp;</td>
            <?php
            foreach($hours as $h) {
                if($h == (int)date('H')) {
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
<script>
if (typeof pistol88 != "undefined" && typeof pistol88.worksess_graph != "undefined") {
    pistol88.worksess_graph.render();
    console.log('render');
}
</script>
<?php Pjax::end(); ?>

