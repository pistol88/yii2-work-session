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
<?php if($control) { ?><a href="" class="worsess-graph-update"> <i class="glyphicon glyphicon-refresh"></i> Обновить</a><?php } ?>
    <table class="worksession-graph table table-hover table-condensed">
        <thead>
            <tr>
                <th align="right" class="worker-name"><small>Сотрудник / Время работы</small></th>
                <?php
                foreach($hours as $h) {
                    if($h == (int)date('H')) {
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
            <tbody class="worker-line">
                <tr>
                    <th class="worker-name">
                        <p class="staffername"><?=$worker->name;?></p>
                        <p><small><?php if($worker->category) { ?><?=$worker->category->name;?><?php } ?></small></p>
                    </th>
                    <?php
                    foreach($hours as $h) {
                        $time = ' '.$h.':00';

                        $minutes = '';
                        for($m = 0; $m <= 59; $m++) {
                            $timestamp = strtotime($date.' '.$h.':'.$m);

                            $active = '';
                            
                            $ws = $worker->hasWork($timestamp);
                            
                            if($timestamp > $session->start_timestamp && $ws) {
                                $active = ' active';
                            }

                            $minutes .= '<div class="'.$active.'" data-timestamp="'.$timestamp.'">&nbsp;</div>';
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
<?php } ?>
<?php Pjax::end(); ?>

