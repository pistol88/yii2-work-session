<?php
namespace pistol88\worksess\assets;

use yii\web\AssetBundle;

class SessionGraph extends AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];

    public $js = [
        'js/session_graph.js',
    ];
    
    public $css = [
        'css/session_graph.css',
    ];
    
    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        
        return parent::init();
    }
}
