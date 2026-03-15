<?php
// models/TeacherSubject.php
namespace app\models;

use yii\db\ActiveRecord;

class TeacherSubject extends ActiveRecord
{
    public static function tableName()
    {
        return 'TEACHER_SUBJECT';
    }
}
