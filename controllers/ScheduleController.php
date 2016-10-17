<?php
namespace pistol88\worksess\controllers;

use yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use pistol88\worksess\models\Schedule;

class ScheduleController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'save' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        $model = new Schedule;

        $workers = yii::$app->getModule('worksess')->getWorkersList();

        $year = date('Y');
        
        return $this->render('index', [
            'workers' => $workers,
            'days' => self::getDays(),
            'months' => self::getMonths(),
            'year' => $year,
            'model' => $model,
        ]);
    }
    
    public function actionMonth($y = null, $m = null)
    {
        $m = Html::encode($m);
        $y = Html::encode($y);
        
        if($data = yii::$app->request->post('user_id')) {
            Schedule::deleteAll("date_format(date, '%Y%m') = :date", [':date' => $y.$m]);
            
            foreach($data as $date => $shifts) {
                foreach($shifts as $shiftId => $userIds) {
                    if(is_array($userIds)) {
                        foreach($userIds as $userId) {
                            $model = new Schedule;
                            $model->date = $date;
                            $model->shift = $shiftId;
                            $model->user_id = $userId;
                            $model->save();
                        }
                    }
                }
            }

            yii::$app->session->setFlash('success', 'Данные успешно сохранены');
            
            return $this->redirect(['/worksess/schedule/month', 'y' => $y, 'm' => $m]);
        }

        $model = new Schedule;

        $workers = yii::$app->getModule('worksess')->getWorkersList();
        
        return $this->render('month', [
            'm' => $m,
            'y' => $y,
            'module' => $this->module,
            'months' => self::getMonths(),
            'days' => self::getDays(),
            'month' => yii::t('order', "month_$m"),
            'model' => $model,
            'workers' => $workers,
        ]);
    }
    
    private static function getMonths()
    {
        $months = [];
        $months['month_01'] = 'Январь';
        $months['month_02'] = 'Февраль';
        $months['month_03'] = 'Март';
        $months['month_04'] = 'Апрель';
        $months['month_05'] = 'Май';
        $months['month_06'] = 'Июнь';
        $months['month_07'] = 'Июль';
        $months['month_08'] = 'Август';
        $months['month_09'] = 'Сентябрь';
        $months['month_10'] = 'Октябрь';
        $months['month_11'] = 'Ноябрь';
        $months['month_12'] = 'Декабрь';
        
        return $months;
    }
    
    private static function getDays()
    {
        $days = [];
        $days['dayname_0'] = 'вск';
        $days['dayname_1'] = 'пнд';
        $days['dayname_2'] = 'втр';
        $days['dayname_3'] = 'срд';
        $days['dayname_4'] = 'чтв';
        $days['dayname_5'] = 'птн';
        $days['dayname_6'] = 'сб';
        
        return $days;
    }
}
