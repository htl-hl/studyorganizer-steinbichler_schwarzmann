<?php

namespace app\controllers;

use app\models\Teacher;
use app\models\User;
use app\models\UserSearch;
use Throwable;
use Yii;
use yii\db\Exception as DbException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws DbException
     */
    public function actionCreate()
    {
        $model = new User();
        $role = Yii::$app->request->get('role');
        if ($role) $model->role = $role;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

            // Wenn Role = Teacher, Teacher + Subjects anlegen
            if ($model->isTeacher()) {

                $subjectIds = $model->subjectIds ?? [];

                // Teacher erstellen oder vorhandenen laden
                $teacher = Teacher::findOne(['userId' => $model->id]);
                if (!$teacher) {
                    $teacher = new Teacher();
                    $teacher->userId = $model->id;
                }

                $teacher->save();

                // Alte Subject-Zuweisungen löschen
                Yii::$app->db->createCommand()
                    ->delete('TEACHER_SUBJECT', ['teacherId' => $teacher->id])
                    ->execute();

                // Neue Subjects zuweisen
                foreach ($subjectIds as $subjectId) {
                    Yii::$app->db->createCommand()
                        ->insert('TEACHER_SUBJECT', [
                            'teacherId' => $teacher->id,
                            'subjectId' => $subjectId,
                        ])
                        ->execute();
                }

                return $this->redirect(['teacher/view', 'id' => $model->id]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {

            if ($model->load($this->request->post()) && $model->validate() && $model->save(false)) {

                if ($model->isTeacher()) {
                    $subjectIds = $model->subjectIds ?? [];

                    $teacher = Teacher::findOne(['userId' => $model->id]);
                    if (!$teacher) {
                        $teacher = new Teacher();
                        $teacher->userId = $model->id;
                        $teacher->isActive = $model->isActive ?? 1;
                        $teacher->save(false);
                    }

                    // Alte Subject-Zuweisungen löschen
                    Yii::$app->db->createCommand()
                        ->delete('TEACHER_SUBJECT', ['teacherId' => $teacher->id])
                        ->execute();

                    // Neue Subjects zuweisen
                    foreach ($subjectIds as $subjectId) {
                        Yii::$app->db->createCommand()
                            ->insert('TEACHER_SUBJECT', [
                                'teacherId' => $teacher->id,
                                'subjectId' => $subjectId,
                            ])
                            ->execute();
                    }

                    return $this->redirect(['teacher/view', 'id' => $model->id]);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete(int $id): Response
    {
        try {
            $deletedRows = $this->findModel($id)->delete();
            if ($deletedRows) {
                Yii::$app->session->setFlash('success', 'User deleted successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'User could not be deleted.');
            }
        } catch (DbException $e) {
            Yii::$app->session->setFlash('error', 'User could not be deleted because related records exist.');
        } catch (Throwable $e) {
            Yii::$app->session->setFlash('error', 'User Id could not be found');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
