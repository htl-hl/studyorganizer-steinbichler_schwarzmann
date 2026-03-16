<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "TASK_USER".
 *
 * @property int $userId
 * @property int $taskId
 *
 * @property Task $task
 * @property User $user
 */
class TaskUser extends \yii\db\ActiveRecord
{


    public $returnDocumentFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'TASK_USER';
    }

    public static function primaryKey(): array
    {
        return ['taskId', 'userId'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'taskId'], 'required'],
            [['userId', 'taskId'], 'integer'],
            [['isCompleted'], 'boolean'],
            [['returnDocumentFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf, doc, docx, md', 'maxSize' => 10*1024*1024],
            [['return_document'], 'safe'],
            [['file_extension'], 'string', 'max' => 10]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'taskId' => 'Task ID',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'taskId']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }
}
