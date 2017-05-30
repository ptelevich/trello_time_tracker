<?php

namespace app\controllers;

use app\models\TrackTime;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class TrackerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['save-time'],
                        'allow' => true,
                        'roles' => ['?','@'],
                        'verbs' => ['PUT'],
                    ],
                    [
                        'actions' => ['get-time-track'],
                        'allow' => true,
                        'roles' => ['?','@'],
                        'verbs' => ['POST'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionSaveTime()
    {
        $cardId = Yii::$app->request->post('id');
        $time = Yii::$app->request->post('time');

        $model = TrackTime::find()->where(['card_id' => $cardId])->one();
        if (!$model) {
            $model = new TrackTime();
        }
        $model->card_id = $cardId;
        $model->time = $time;

        return $model->save() ? 'success' : 'error';
    }

    public function actionGetTimeTrack()
    {
        $cardId = Yii::$app->request->post('id');
        $model = TrackTime::find()->where(['card_id' => $cardId])->one();
        if ($model) {
            return $model->time;
        }
        return null;
    }
}
