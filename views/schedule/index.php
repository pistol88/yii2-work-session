<?php
use yii\helpers\Url;

$this->title = 'График работ';

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-stat">
    <h1><?=$this->title;?></h1>
    
    <div class="container">
        
        <div class="row">
            <div class="col-md-4">
                <?php $prevYear = $year-1; ?>
                <h2><?=$prevYear;?></h2>
                <?php for($m = 1; $m <= 12; $m++) { ?>
                    <?php if($m <= 9) $m = "0$m"; ?>

                    <li><a <?php if(date('Y-m') == date('Y-m', strtotime("$prevYear-$m"))) echo 'style="color: red;"';?> href="<?=Url::toRoute(['/worksess/schedule/month', 'y' => $year, 'm' => $m]);?>"><?=$months["month_$m"];?></a></li>
                <?php } ?>
            </div>
            <div class="col-md-4">
                <h2><?=$year;?></h2>
                <?php for($m = 1; $m <= 12; $m++) { ?>
                    <?php if($m <= 9) $m = "0$m"; ?>

                    <li><a <?php if(date('Y-m') == date('Y-m', strtotime("$year-$m"))) echo 'style="color: red;"';?> href="<?=Url::toRoute(['/worksess/schedule/month', 'y' => $year, 'm' => $m]);?>"><?=$months["month_$m"];?></a></li>
                <?php } ?>
            </div>
            <div class="col-md-4">
                <?php $nextYear = $year+1; ?>
                <h2><?=$nextYear;?></h2>
                <?php for($m = 1; $m <= 12; $m++) { ?>
                    <?php if($m <= 9) $m = "0$m"; ?>

                    <li><a <?php if(date('Y-m') == date('Y-m', strtotime("$nextYear-$m"))) echo 'style="color: red;"';?> href="<?=Url::toRoute(['/worksess/schedule/month', 'y' => $year, 'm' => $m]);?>"><?=$months["month_$m"];?></a></li>
                <?php } ?>
            </div>
        </div>
        <ul>

        </ul>
    </div>
</div>

<style>
.order-stat {
    font-size: 16px;
}

.order-stat .bad-result {
    padding: 2px;
    font-size: 70%;
    background-color: #BB3D3D;
    color: white;
}

.order-stat .good-result {
    padding: 2px;
    font-size: 70%;
    background-color: #96B796;
    color: white;
}
</style>