<?php

namespace app\controllers;

use app\models\TrackTime;
use Trello\Client;
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
                        'actions' => ['save-time','save-token'],
                        'allow' => true,
                        'roles' => ['?','@'],
                        'verbs' => ['PUT'],
                    ],
                    [
                        'actions' => ['get-time-track'],
                        'allow' => true,
                        'roles' => ['?','@'],
                        'verbs' => ['POST'],
                    ],
                    [
                        'actions' => ['trello-api', 'auth-trello'],
                        'allow' => true,
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

    public function beforeAction($action) {
        $excepts = [
            'auth-trello'
        ];
        if(in_array($action->id, $excepts)) {
            Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionAuthTrello()
    {
        $token = Yii::$app->request->post('token', null);

        if (!Yii::$app->request->isAjax && !$token) {
            return $this->render('auth');
        }

        $client = new Client();
        $client->authenticate(Yii::$app->params['trello_api_key'], $token, Client::AUTH_URL_CLIENT_ID);

        $member = $client->api('token')->getMember($token);

        if (isset($member['id'])) {
            file_put_contents(Yii::$app->runtimePath.'/tokens.log', $member['id'] . ' - ' . $token . "\r\n", FILE_APPEND);
        }

        Yii::$app->end();
    }

    public function actionSaveTime()
    {
        $boardId = Yii::$app->request->post('boardId');
        $listId = Yii::$app->request->post('listId');
        $cardId = Yii::$app->request->post('cardId');
        $time = Yii::$app->request->post('time');

        $model = TrackTime::find()->where([
            'board_id' => $boardId,
            'list_id' => $listId,
            'card_id' => $cardId
        ])->one();

        if (!$model) {
            $model = new TrackTime();
        }
        $model->board_id = $boardId;
        $model->list_id = $listId;
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
