<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

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
     * @return bool|Response
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        // Cache-Block für Login/Register (falls SiteController erbt)
        if (in_array($action->id, ['login', 'register'])) {
            $this->disableBrowserCache();
        }

        // ALLE anderen Actions: Guest → Login
        if (Yii::$app->user->isGuest && !in_array($action->id, ['error'])) {
            return $this->redirect(['site/login']);
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