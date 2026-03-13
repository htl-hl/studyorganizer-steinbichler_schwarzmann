<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Plain-text password input (not stored).
     */
    public ?string $password = null;

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

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
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
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
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

    public function isAdmin()
    {
        if ($this->role === 'Admin') {
            return true;
        }
        return false;
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
