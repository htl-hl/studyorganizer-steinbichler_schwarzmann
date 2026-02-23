<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "TASK".
 *
 * @property int $id
 * @property string $title
 * @property string $dueDate
 * @property int $isCompleted
 * @property string $description
 * @property int $userId
 * @property int $subjectId
 *
 * @property Subject $sUBJECT
 * @property User $uSER
 */
class Task extends \yii\db\ActiveRecord
{


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
            [['title', 'dueDate', 'description', 'userId', 'subjectId'], 'required'],
            [['dueDate'], 'safe'],
            [['isCompleted', 'userId', 'subjectId'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique'],
            [['subjectId'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::class, 'targetAttribute' => ['subjectId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
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
            'userId' => 'User ID',
            'subjectId' => 'Subject ID',
        ];
    }

    /**
     * Gets query for [[SUBJECT]].
     *
     * @return \yii\db\ActiveQuery|SubjectQuery
     */
    public function getSUBJECT()
    {
        return $this->hasOne(Subject::class, ['id' => 'subjectId']);
    }

    /**
     * Gets query for [[USER]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUSER()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
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
