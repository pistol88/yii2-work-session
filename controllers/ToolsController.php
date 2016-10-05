<?php
namespace pistol88\worksess\controllers;

use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use pistol88\order\models\Order;
use pistol88\worksess\models\UserSession;

class ToolsController  extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles,
                    ]
                ]
            ],
        ];
    }
    
    public function actionInfoWindow($userSessionId)
    {
        $session = UserSession::findOne($userSessionId);
        
        $stat = yii::$app->order->getStatByDatePeriod($session->start, $session->stop);
        
        $orders = Order::findAll(['date' > $session->start, 'date' < $session->stop]);
        
        return $this->renderPartial('info-window', [
            'module' => yii::$app->getModule('order'),
            'session' => $session,
            'stat' => $stat,
            'orders' => $orders
        ]);
    }
}
