<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

\pistol88\worksess\assets\BackendAsset::register($this);

$daysCount = cal_days_in_month(CAL_GREGORIAN, $m, $y);

$monthDays = range(1, $daysCount);

$this->title = $month.' '.$y.' - график работ';

$this->params['breadcrumbs'][] = ['label' => 'Расписание', 'url' => ['/worksess/schedule/index']];
$this->params['breadcrumbs'][] = $month;

$shifts = $module->shifts;

?>
<div class="order-stat">
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
    
    <h1><?=$this->title;?></h1>
        <p>Внимание! Изменения сохраняются только после нажатия кнопки "сохранить".</p>
        <?php
        $prevMonth = strtotime(date("$y-$m-01"))-864000;
        $nextMonth = strtotime(date("$y-$m-27"))+864000;
        ?>
        <p>
            <a href="<?=Url::toRoute(['/worksess/schedule/month/', 'y' => date('Y', $prevMonth), 'm' => date('m', $prevMonth)]);?>">&larr; <?=yii::t('order', 'Previous');?></a>
            |
            <a href="<?=Url::toRoute(['/worksess/schedule/month/', 'y' => date('Y', $nextMonth), 'm' => date('m', $nextMonth)]);?>"> <?=yii::t('order', 'Next');?> &rarr;</a>
        </p>
        <form action="" method="post">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
            <div class="control" style="text-align: right;">
                <input type="submit" name="submit-times" value="Сохранить" class="btn btn-primary" />
            </div>
            <?php foreach($shifts as $shiftId => $shiftName) { ?>
                <h3><?=$shiftName;?> (<?=$shiftId;?>)</h3>
                <table class="worksess-table table table-hover table-work-schedule">
                    <tr>
                        <td><strong>Сотрудник</strong></td>
                        <?php foreach($monthDays as $d) { ?>
                            <?php
                            if($d <= 9) {
                                $fd = "0$d";
                            } else {
                                $fd = $d;
                            }
                            ?>
                            <td title="<?=$days['dayname_'.date("w", strtotime("$y-$m-$fd"))];?>"><?=$d;?></td>
                        <?php } ?>

                    </tr>
                    <?php foreach($workers as $staffer) { ?>
                        <tr>
                            <td class="staffer">
                                <p><strong><?=$staffer->name;?></strong></p>
                                <?php if($cat = $staffer->category) { ?>
                                    <p><small><?=$cat->name;?></small></p>
                                <?php } ?>
                            </td>
                            <?php foreach($monthDays as $d) { ?>
                                <?php
                                if($d <= 9) {
                                    $fd = "0$d";
                                } else {
                                    $fd = $d;
                                }
                                ?>
                                <td>
                                    <?php
                                    $value = ArrayHelper::map(yii::$app->worksess->getWorkers("$y-$m-$fd", $shiftId), 'id', 'id');
                                    ?>
                                    <div <?php if("$y-$m-$fd" == date('Y-m-d')) echo 'class="active"'; ?>>
                                        <input <?php if(in_array($staffer->id, $value)) { ?>checked="checked"<?php } ?> name="user_id[<?=$y;?>-<?=$m;?>-<?=$d;?>][<?=$shiftId;?>][<?=$staffer->id;?>]" title="<?=$shiftName;?>" type="checkbox" name="workday" value="<?=$staffer->id;?>" />
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
            <div class="control" style="text-align: right;">
                <input type="submit" name="submit-times" value="Сохранить" class="btn btn-primary" />
            </div>
        </form>
</div>
