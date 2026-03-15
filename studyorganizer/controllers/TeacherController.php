<?php

namespace app\controllers;

use app\models\Teacher;
use app\models\TeacherSearch;
use app\models\TeacherSubject;
use app\models\User;
use Exception;
use Throwable;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TeacherController implements the CRUD actions for Teacher model.
 */
class TeacherController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Teacher models.
     *
     * @return string
     */

    public function actionIndex(): string
    {
        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Teacher model.
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
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return Response
     */
    public function actionCreate(): Response
    {
        return $this->redirect(['/user/create', 'role' => 'Teacher']);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    // controllers/TeacherController.php
    public function actionUpdate(int $id)
    {
        $user = $this->findModel($id); // ← findet User mit role='Teacher'
        $teacher = $user->getTeacher()->one();
        if (!$teacher) {
            $teacher = new Teacher(['userId' => $user->id]);
        }

        // ✅ FIX: subjectIds vor Form explizit setzen (für listBox selected!)
        if ($user->isTeacher() && $user->teacher) {
            $user->subjectIds = $user->teacher->subjects
                ? ArrayHelper::getColumn($user->teacher->subjects, 'id')
                : [];
        }

        if ($this->request->isPost) {
            $post = $this->request->post();

            // ✅ FIX: Selektives Laden - verhindert TypeErrors!
            if (isset($post['User'])) {
                $user->username = $post['User']['username'] ?? $user->username;
                $user->email = $post['User']['email'] ?? $user->email;

                // ✅ Array sicherstellen
                $user->subjectIds = is_array($post['User']['subjectIds'])
                    ? $post['User']['subjectIds']
                    : [];
            }

            if (isset($post['Teacher'])) {
                $teacher->isActive = (bool)($post['Teacher']['isActive'] ?? false);
                $teacher->removeTeacherStatus = (bool)($post['Teacher']['removeTeacherStatus'] ?? false);
            }

            if ($user->validate() && $teacher->validate()) {
                $user->save(false);
                $teacher->save(false);

                if ($teacher->removeTeacherStatus) {
                    Yii::$app->session->setFlash('success', 'Teacher-Status entfernt!');
                    return $this->redirect(['user/view', 'id' => $user->id]);
                }

                // Subjects speichern
                $subjectIds = $user->subjectIds ?? [];
                TeacherSubject::deleteAll(['teacherId' => $teacher->id]);
                foreach ($subjectIds as $subjectId) {
                    $ts = new TeacherSubject();
                    $ts->teacherId = $teacher->id;
                    $ts->subjectId = (int)$subjectId;
                    $ts->save(false);
                }

                Yii::$app->session->setFlash('success', 'Teacher updated successfully');
                return $this->redirect(['view', 'id' => $user->id]);
            }
        }

        return $this->render('update', [
            'model' => $user,
            'teacher' => $teacher,
        ]);
    }


    /**
     * Deletes an existing Teacher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $teacher = Teacher::findOne(['id' => $id]);
        if (!$teacher) {
            Yii::$app->session->setFlash('error', 'Teacher not found');
            return $this->redirect(['index']);
        }

        $user = $teacher->user;
        if (!$user) {
            Yii::$app->session->setFlash('error', 'Associated user not found');
            return $this->redirect(['index']);
        }

        try {
            $user->delete();
            Yii::$app->session->setFlash('success', 'Teacher deleted successfully');
            return $this->redirect(['index']);
        } catch (Throwable $e) {
            Yii::$app->session->setFlash('error', 'Could not delete teacher, Related records may exist');
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Teacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): User
    {
        $user = User::findOne(['id' => $id, 'role' => 'Teacher']);
        if (!$user) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        // optional: falls du sicherstellen willst, dass auch ein Teacher-Record existiert
        if (!$user->teacher) {
            throw new NotFoundHttpException('Teacher record missing for this user.');
        }

        return $user;
    }
}
