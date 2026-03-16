<?php

namespace app\models;

use yii\base\Exception;
use yii\base\Model;
use Yii;
class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $role = 'User';

    public function rules(): array
    {
        return [
            [['username', 'email'], 'trim'],
            [['username', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            ['password', 'string', 'min' => 6],
            ['role', 'in', 'range' => ['User']],
        ];
    }

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function register(): ?User
    {
        if (!$this->validate()) {
            echo "Sth went wrong!";
            die;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->role = $this->role ?: 'User';
        $user->accessToken = Yii::$app->getSecurity()->generateRandomString();
        if (!$user->save()) {
            foreach ($user->getErrors() as $attribute => $errors) {
                foreach ($errors as $error) {
                    $this->addError($attribute, $error);
                    echo $error;
                }
            }
            return null;
        }

        return $user;
    }
}
