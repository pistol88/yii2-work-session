<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$daysCount = cal_days_in_month(CAL_GREGORIAN, $m, $y);

$monthDays = range(1, $daysCount);

$this->title = $month.' '.$y.' - график работ';

$this->params['breadcrumbs'][] = ['label' => 'Раписание', 'url' => ['/worksess/schedule/index']];
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
                <input type="submit" name="submit-times" value="Сохранить" class="btn btn-submit" />
            </div>
            <table class="table table-hover table-work-schedule">
                <tr>
                    <th width="60">День</th>
                    <th>Сотрудники</th>
                </tr>
                <?php $prevStat = false; ?>
                <?php foreach($monthDays as $d) { ?>
                    <?php
                    if($d <= 9) {
                        $fd = "0$d";
                    } else {
                        $fd = $d;
                    }
                    ?>
                    <tr>
                        <td class="month">
                            <p><strong <?php if("$y-$m-$fd" == date('Y-m-d')) echo 'style="color: red;"'; ?>><?=$d;?></strong></p>
                            <p><?=$days['dayname_'.date("w", strtotime("$y-$m-$fd"))];?></p>
                        </td>
                        <td>
                            <?php foreach($shifts as $shiftId => $shiftName) { ?>
                                <?php
                                $value = ArrayHelper::map(yii::$app->worksess->getWorkers("$y-$m-$fd", $shiftId), 'id', 'id');
                                ?>
                                <div>
                                    <p><strong><?=$shiftName;?></strong></p>
                                    <?=Select2::widget([
                                        'attribute' => 'user_id['."$y-$m-$fd".']['.$shiftId.']',
                                        'name' => 'user_id['."$y-$m-$fd".']['.$shiftId.']',
                                        'value' => $value,
                                        'language' => 'ru',
                                        'maintainOrder' => true,
                                        'data' => ArrayHelper::map($workers, 'id', 'name'),
                                        'options' => ['multiple' => true, 'placeholder' => 'Выберите сотрудника ...'],
                                        'pluginOptions' => [
                                            'tags' => true,
                                            'allowClear' => true,
                                            'selectOptions' => ['class' => 'text-success'],
                                            'unselectOptions' => ['class' => 'text-danger'],
                                        ],
                                    ]); ?>
                                    <hr />
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <div class="control" style="text-align: right;">
                <input type="submit" name="submit-times" value="Сохранить" class="btn btn-submit" />
            </div>
        </form>
</div>

<style>
    .table-work-schedule tr:hover td {
        background: #c0e2ff;
    }
</style>