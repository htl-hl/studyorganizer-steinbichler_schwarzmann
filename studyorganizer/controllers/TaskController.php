<?php

namespace app\controllers;

use app\models\Subject;
use app\models\Task;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Task models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $subjects = Subject::find()->with('tasks')->all();

        return $this->render('index', [
            'subjects' => $subjects,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int|null $subjectId
     * @return string|\yii\web\Response
     */
    public function actionCreate(int $subjectId)
    {
        $user = Yii::$app->user->identity;

        if (
            !$user->isAdmin() &&
            !$user->teachesSubject($subjectId)
        ) {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to create tasks for this subject.');
        }

        $model = new Task();
        $model->subjectId = $subjectId;
        $model->isCompleted = 0;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $post = Yii::$app->request->post();
                $model->userIds = $post['Task']['userIds'] ?? [];

                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {

            $post = Yii::$app->request->post();
            $model->userIds = $post['Task']['userIds'] ?? [];

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // Get all users to display in the assignment form
        $users = ArrayHelper::map(User::find()->all(), 'id', 'username');

        return $this->render('update', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
