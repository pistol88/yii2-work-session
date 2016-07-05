<?php
namespace pistol88\worksess;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if(!$app->has('worksess')) {
            $app->set('worksess', ['class' => 'pistol88\worksess\Session']);
        }
        
        if(empty($app->modules['gridview'])) {
            $app->setModule('gridview', [
                'class' => '\kartik\grid\Module',
            ]);
        }
        
        return true;
    }
}