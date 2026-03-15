<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "TEACHER".
 *
 * @property int $id
 * @property int $userId
 * @property int $isActive
 *
 * @property SUBJECT[] $subjects
 */
class Teacher extends ActiveRecord
{

    public $removeTeacherStatus = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'TEACHER';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['userId'], 'required'],
            [['userId'], 'integer'],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],

            [['isActive'], 'required'],
            [['isActive'], 'boolean'],

            [['userId'], 'unique', 'message' => 'Dieser User ist bereits als Teacher eingetragen.'],

            ['removeTeacherStatus', 'boolean']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'userId' => 'User ID',
            'isActive' => 'Is Active',
        ];
    }

    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->removeTeacherStatus && !$insert) {
                if ($this->user) {
                    $this->user->role = 'User';
                    $this->user->save(false);
                }

                // Subjects löschen
                \app\models\TeacherSubject::deleteAll(['teacherId' => $this->id]);
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);


        if ($this->removeTeacherStatus && !$insert) {
            $this->delete();
        }
    }

    /**
     * Gets query for [[Subjects]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getSubjects(): ActiveQuery
    {
        return $this->hasMany(Subject::class, ['id' => 'subjectId'])
            ->viaTable('TEACHER_SUBJECT', ['teacherId' => 'id']);
    }
    /**
     * {@inheritdoc}
     * @return TeacherQuery the active query used by this AR class.
     */
    public static function find(): TeacherQuery
    {
        return new TeacherQuery(get_called_class());
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

    public function getIsActiveAsString(): string
    {
        if ($this->isActive) {
            return "Yes";
        } else {
            return "No";
        }
    }
}
