<?php

namespace app\controllers;

use app\models\Task;
use app\models\TaskUser;
use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class BaseController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @throws ForbiddenHttpException|BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->loginRequired();  // ← Automatischer Redirect!
            return false;
        }

        if (!Yii::$app->user->isGuest) {
            TaskUser::autoSubmitExpired();
        }

        return parent::beforeAction($action);
    }



    public static function disableBrowserCache()
    {
        Yii::$app->response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        Yii::$app->response->headers->set('Pragma', 'no-cache');
        Yii::$app->response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    }
}