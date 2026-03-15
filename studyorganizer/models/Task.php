<?php

namespace app\models;

use Exception;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "TASK".
 *
 * @property int $id
 * @property string $title
 * @property string $dueDate
 * @property int $isCompleted
 * @property string $description
 * @property int $subjectId
 *
 * @property Subject $subject
 * @property User[] $users
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * This property will be used to store the user IDs for the task assignment form.
     * @var array
     */
    public $userIds = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'TASK';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isCompleted'], 'default', 'value' => 0],
            [['title', 'dueDate', 'description', 'subjectId'], 'required'],
            [['dueDate', 'userIds'], 'safe'],
            [['isCompleted', 'subjectId'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique'],
            ['userIds', 'safe'],
            ['userIds', 'each', 'rule' => ['integer']],
            ['userIds', 'default', 'value' => []],
            [['subjectId'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['subjectId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'dueDate' => 'Due Date',
            'isCompleted' => 'Is Completed',
            'description' => 'Description',
            'subjectId' => 'Subject ID',
        ];
    }

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
            if (!$tu->save()) {
                Yii::error('TaskUser save failed: ' . json_encode($tu->errors));
            }
        }
    }

    /**
     * Gets query for [[Subject]].
     *
     * @return \yii\db\ActiveQuery|SubjectQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::class, ['id' => 'subjectId']);
    }

    /**
     * Gets query for [[Users]] via the junction table.
     * This defines the n:n relationship.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['id' => 'userId'])
            ->viaTable('TASK_USER', ['taskId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskQuery(get_called_class());
    }
}
