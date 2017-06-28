<?php

namespace app\controllers;

use app\models\MemberToken;
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
                        'actions' => ['trello-api', 'auth-trello', 'show-content', 'save-time-trello'],
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
            'auth-trello', 'show-content', 'save-time-trello'
        ];
        if (in_array($action->id, $excepts)) {
            Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionShowContent($uid)
    {
        $model = MemberToken::find()
            ->where(['member_id' => $uid])
            ->one();

        $content = $model ?
            $this->renderAjax('_partial/_estimation_fields') :
            $this->renderAjax('_partial/_auth_in_tracker') ;

        echo json_encode([
            'status' => 'ok',
            'content' => $content,
        ]);
        exit;
    }

    public function actionAuthTrello()
    {
        $token = Yii::$app->request->post('token', null);

        if (!Yii::$app->request->isAjax && !$token) {
            return $this->renderPartial('auth');
        }

        $client = new Client();
        $client->authenticate(Yii::$app->params['trello_api_key'], $token, Client::AUTH_URL_CLIENT_ID);

        $member = $client->api('token')->getMember($token);

        if (isset($member['id'])) {

            $model = new MemberToken();
            $model->member_id = $member['id'];
            $model->token = $token;
            $model->save();
            file_put_contents(Yii::$app->runtimePath.'/tokens.log', $member['id'] . ' - ' . $token . "\r\n", FILE_APPEND);
        }

        Yii::$app->end();
    }

    public function actionSaveTime()
    {
        $this->runAction('save-time-trello', [
            Yii::$app->request->post('boardId', ''),
            Yii::$app->request->post('listId', ''),
            Yii::$app->request->post('cardId', ''),
            Yii::$app->request->post('time', 0)
        ]);
    }

    public function actionSaveTimeTrello($b, $l, $c, $t, $uid)
    {
        $model = TrackTime::find()->where([
            'board_id' => $b,
            'list_id' => $l,
            'card_id' => $c
        ])->one();

        if (!$model) {
            $model = new TrackTime();
        }
        $model->board_id = $b;
        $model->list_id = $l;
        $model->card_id = $c;
        $model->time = $t;

        if ($model->save()) {
            $model = MemberToken::find()
                ->where(['member_id' => $uid])
                ->one();
            $token = $model->token;
            $client = new Client();
            $client->authenticate(Yii::$app->params['trello_api_key'], $token, Client::AUTH_URL_CLIENT_ID);
            $client->api('card')->actions()->addComment($c, '\@time_tracker estimated '.$t.' minutes');
            echo json_encode([
                'status' => 'ok',
            ]);
            exit;
        }
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
