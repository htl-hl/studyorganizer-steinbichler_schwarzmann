<?php

use yii\db\Migration;

class m260305_114731_add_admin extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->insert('{{%USER}}', [
            'email' => 'admin@example.com',
            'username' => 'admin',
            'passwordHash' => Yii::$app->getSecurity()->generatePasswordHash("admin"),
            'authKey' => Yii::$app->getSecurity()->generateRandomString(),
            'accessToken' => Yii::$app->getSecurity()->generateRandomString(),
            'role' => 'Admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260305_114731_add_admin cannot be reverted.\n";

        return false;
    }
    */
}
