<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "TASK".
 *
 * @property int $id
 * @property string $title
 * @property string $dueDate
 * @property string $description
 * @property int $subjectId
 *
 * @property Subject $subject
 * @property User[] $users
 */
class Task extends ActiveRecord
{
    /**
     * This property will be used to store the user IDs for the task assignment form.
     * @var array
     */
    public $userIds = [];

    public $taskDocumentFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'TASK';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'dueDate', 'description', 'subjectId'], 'required'],
            [['dueDate', 'userIds'], 'safe'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique'],
            ['userIds', 'safe'],
            ['userIds', 'each', 'rule' => ['integer']],
            ['userIds', 'default', 'value' => []],
            [['subjectId'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['subjectId' => 'id']],

            [['taskDocumentFile'], 'safe'],
            [['task_document'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'dueDate' => 'Due Date',
            'description' => 'Description',
            'subjectId' => 'Subject ID',
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->userIds = $this->getUsers()->select('id')->column();
    }

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if (!is_array($this->userIds)) {
                $this->userIds = [];
            }
            return true;
        }
        return false;
    }

    public function uploadTaskDocument($file)
    {
        if ($file) {
            $this->task_document = file_get_contents($file->tempName);
        }
    }

    // Return-Dokument speichern (Update)
    public function uploadReturnDocument($file)
    {
        if ($file) {
            $this->return_document = file_get_contents($file->tempName);
        }
    }

    /**
     * Nach dem Speichern: Zuordnungen in TASK_USER aktualisieren.
     * @throws Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Safety: Immer Array
        $userIds = is_array($this->userIds) ? $this->userIds : [];

        // Alte Zuordnungen löschen
        TaskUser::deleteAll(['taskId' => $this->id]);

        // Neue Zuordnungen eintragen
        foreach ($userIds as $userId) {
            if (!$userId) {
                continue;
            }
            $tu = new TaskUser();
            $tu->taskId = $this->id;
            $tu->userId = (int)$userId;
            $tu->isCompleted = false;
            if (!$tu->save()) {
                Yii::error('TaskUser save failed: ' . json_encode($tu->errors));
            }
        }
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return ActiveQuery
     */
    public function getSubject(): ActiveQuery
    {
        return $this->hasOne(Subject::class, ['id' => 'subjectId']);
    }

    public function getTaskUser()
    {
        return $this->hasOne(TaskUser::class, ['taskId' => 'id'])->where(['userId' => Yii::$app->user->id])->one();
    }

    /**
     * Gets query for [[Users]] via the junction table.
     * This defines the n:n relationship.
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'userId'])
            ->viaTable('TASK_USER', ['taskId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find(): TaskQuery
    {
        return new TaskQuery(get_called_class());
    }
}
