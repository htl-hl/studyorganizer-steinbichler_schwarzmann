<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "SUBJECT".
 *
 * @property int $id
 * @property string $name
 *
 * @property Task[] $tasks
 * @property TeacherSubject[] $teacherSubjects
 * @property Teacher[] $teachers
 */
class Subject extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'SUBJECT';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery|TaskQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['subjectId' => 'id']);
    }

    /**
     * Gets query for [[TeacherSubjects]].
     *
     * @return \yii\db\ActiveQuery|TeacherSubjectQuery
     */
    public function getTeacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class, ['subjectId' => 'id']);
    }

    /**
     * Gets query for [[Teachers]].
     *
     * @return \yii\db\ActiveQuery|TeacherQuery
     */
    public function getTeachers()
    {
        return $this->hasMany(Teacher::class, ['id' => 'teacherId'])->viaTable('TEACHER_SUBJECT', ['subjectId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return SubjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubjectQuery(get_called_class());
    }

}
