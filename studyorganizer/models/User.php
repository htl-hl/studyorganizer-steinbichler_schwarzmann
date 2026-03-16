<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property Task[] $tasks
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Plain-text password input (not stored).
     */
    public ?string $password = null;

    public $subjectIds = [];

    public ?bool $isActive = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'USER';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['email', 'username', 'role'], 'required'],
            [['password'], 'required', 'when' => function ($model) {
                return $model->isNewRecord;
            }],
            [['password'], 'string', 'min' => 6],
            [['email', 'username', 'passwordHash', 'authKey', 'accessToken'], 'string', 'max' => 255],
            [['role'], 'string', 'max' => 8],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['authKey'], 'unique'],
            [['accessToken'], 'unique'],

            ['isActive', 'safe'],
            ['subjectIds', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'passwordHash' => Yii::t('app', 'Password Hash'),
            'authKey' => Yii::t('app', 'Auth Key'),
            'accessToken' => Yii::t('app', 'Access Token'),
            'role' => Yii::t('app', 'Role'),
        ];
    }

    /**
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!is_array($this->subjectIds)) {
            $this->subjectIds = [];
        }

        if (!empty($this->password)) {
            $this->setPassword($this->password);
        }

        if ($insert) {
            if (empty($this->authKey)) {
                $this->generateAuthKey();
            }
            if (empty($this->accessToken)) {
                $this->accessToken = Yii::$app->security->generateRandomString();
            }
        }

        return true;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->role === 'Teacher') {
            $teacher = Teacher::findOne(['userId' => $this->id]);
            if (!$teacher) {
                $teacher = new Teacher();
                $teacher->userId = $this->id;
                $teacher->isActive = $this->isActive;
                if (!$teacher->save()) {
                    Yii::error('Teacher could not be created: ' . json_encode($teacher->errors));
                    Yii::$app->session->setFlash('danger', 'Teacher could not be created');
                } else {
                    Yii::$app->session->setFlash('success', 'Teacher was successfully created');
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }

    /**
     */
    public function afterFind()
    {
        parent::afterFind();

        if ($this->isTeacher() && $this->teacher) {
            $subjects = $this->getSubjects()->select('id')->column();
            $this->subjectIds = $subjects ?: [];
        } else {
            $this->subjectIds = [];
        }
    }

    public function beforeValidate(): bool
    {
        if (!$this->isTeacher()) {
            $this->subjectIds = [];
        }

        return parent::beforeValidate();
    }


    public function getTeacher(): ActiveQuery
    {
        return $this->hasOne(Teacher::class, ['userId' => 'id']);
    }

    /**
     * Gets query for [[Tasks]] via the junction table.
     * This defines the n:n relationship from User to Tasks.
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['id' => 'taskId'])
            ->viaTable('TASK_USER', ['userId' => 'id']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function hasTask(int $taskId): bool
    {
        return $this->getTasks()
            ->andWhere(['TASK.id' => $taskId])
            ->exists();
    }

    /**
     * @throws InvalidConfigException
     */
    public function getSubjects(): ActiveQuery
    {
        return $this->hasMany(Subject::class, ['id' => 'id'])
            ->via('teacher', function ($query) {
                $query->joinWith('subjects');
            });
    }

    public function teachesSubject($subjectId): bool
    {
        if (!$this->isTeacher()) {
            return false;
        }

        foreach ($this->teacher->subjects as $subject) {
            if ($subject->id == $subjectId) {
                return true;
            }
        }

        return false;
    }

    public static function findIdentity($id): ?User
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?User
    {
        return static::find()->where(['accessToken' => $token])->one();
    }

    public static function findByUsername(string $username): ?User
    {
        return static::find()->where(['username' => $username])->one();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'Teacher';
    }

    public function getAuthKey(): string
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->authKey === $authKey;
    }

    /**
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->passwordHash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->passwordHash);
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey(): void
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }
}
