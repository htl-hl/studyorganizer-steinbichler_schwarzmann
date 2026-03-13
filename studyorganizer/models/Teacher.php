<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "TEACHER".
 *
 * @property int $id
 * @property string $username
 * @property int $isActive
 *
 * @property SUBJECT[] $subjects
 * @property TEACHERSUBJECT[] $tEACHERSUBJECTs
 */
class Teacher extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'TEACHER';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'isActive'], 'required'],
            [['isActive'], 'integer'],
            [['username'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'isActive' => 'Is Active',
        ];
    }

    /**
     * Gets query for [[Subjects]].
     *
     * @return \yii\db\ActiveQuery|SUBJECTQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(SUBJECT::class, ['id' => 'subjectId'])->viaTable('TEACHER_SUBJECT', ['teacherId' => 'id']);
    }

    /**
     * Gets query for [[TEACHERSUBJECTs]].
     *
     * @return \yii\db\ActiveQuery|TEACHERSUBJECTQuery
     */
    public function getTEACHERSUBJECTs()
    {
        return $this->hasMany(TEACHERSUBJECT::class, ['teacherId' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeacherQuery(get_called_class());
    }

}
