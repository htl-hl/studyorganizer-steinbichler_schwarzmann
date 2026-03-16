<?php

namespace app\controllers;

use app\models\Subject;
use app\models\Task;
use app\models\TaskUser;
use app\models\User;
use Exception;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\web\RangeNotSatisfiableHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
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
    public function actionIndex(): string
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
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param int|null $subjectId
     * @return string|Response
     * @throws ForbiddenHttpException|Exception
     */
    public function actionCreate(int $subjectId)
    {
        $user = Yii::$app->user->identity;

        if (
            !$user->isAdmin() &&
            !$user->teachesSubject($subjectId)
        ) {
            throw new ForbiddenHttpException('You are not allowed to create tasks for this subject.');
        }

        $model = new Task();
        $model->subjectId = $subjectId;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->taskDocumentFile = UploadedFile::getInstance($model, 'taskDocumentFile');
                $model->uploadTaskDocument($model->taskDocumentFile);
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
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\Exception
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {

            $model->taskDocumentFile = UploadedFile::getInstance($model, 'taskDocumentFile');
            if ($model->taskDocumentFile) {
                $model->uploadTaskDocument($model->taskDocumentFile);
            }

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
     * @return Response
     * @throws StaleObjectException|NotFoundHttpException|Throwable
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionSubmit(int $id): string
    {
        $task = $this->findModel($id);
        $taskUser = TaskUser::findOne([
            'taskId' => $id,
            'userId' => Yii::$app->user->id
        ]);

        if (!$taskUser) {
            throw new NotFoundHttpException('Task not assigned to you!');
        }

        return $this->render('submit', [
            'model' => $task,
            'taskUser' => $taskUser
        ]);
    }


    /**
     * @throws \yii\db\Exception
     * @throws NotFoundHttpException
     */
    public function actionSubmitReturn(int $id)
    {
        $task = $this->findModel($id);

        $taskUser = TaskUser::findOne([
            'taskId' => $id,
            'userId' => Yii::$app->user->id
        ]);

        if (!$taskUser) {
            throw new NotFoundHttpException('Task is not assigned!');
        }

        if ($this->request->isPost) {
            $taskUser->returnDocumentFile = UploadedFile::getInstance($taskUser, 'returnDocumentFile');

            if ($taskUser->load($this->request->post()) && $taskUser->validate()) {
                if ($taskUser->returnDocumentFile) {
                    $taskUser->return_document = file_get_contents($taskUser->returnDocumentFile->tempName);
                }

                $taskUser->isCompleted = true;

                if ($taskUser->save(false)) {
                    Yii::$app->session->setFlash('success', 'submitted task!');
                    return $this->redirect(['view', 'id' => $id]);
                }
            }

            Yii::$app->session->setFlash('error', 'error while submitting');
        }

        return $this->render('submit', [
            'model' => $task,
            'taskUser' => $taskUser
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionSubmissions(int $id): string
    {
        $task = $this->findModel($id);

        // Nur Lehrer/Admin sehen Submissions!
        $user = Yii::$app->user->identity;
        if (!$user->isAdmin() && !$user->teachesSubject($task->subjectId)) {
            throw new ForbiddenHttpException('You are not allowed to view submissions.');
        }

        $taskUsers = TaskUser::find()
            ->select([
                'taskId',
                'userId',
                'isCompleted',
                'return_document',
                'u.username'
            ])
            ->innerJoin('USER u', 'u.id = userId')
            ->where(['taskId' => $id])
            ->asArray()
            ->all();

        return $this->render('submissions', [
            'model' => $task,
            'taskUsers' => $taskUsers,
        ]);
    }


    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Task
    {
        if (($model = Task::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @throws RangeNotSatisfiableHttpException
     */
    public function actionDownloadTaskDoc($id)
    {
        $task = Task::findOne($id);
        return Yii::$app->response->sendContentAsFile(
            $task->task_document,
            'task_' . $id . '_document.pdf'
        );
    }

    /**
     * @throws RangeNotSatisfiableHttpException
     */
    public function actionDownloadReturnDoc($id)
    {
        $task = Task::findOne($id);
        return Yii::$app->response->sendContentAsFile(
            $task->getTaskUser()->return_document,
            'task_' . $id . '_return.pdf'
        );
    }
}
