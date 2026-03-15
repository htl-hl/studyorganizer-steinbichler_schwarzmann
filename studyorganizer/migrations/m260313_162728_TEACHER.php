<?php

use yii\db\Migration;

class m260313_162728_TEACHER extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%TEACHER}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'isActive' => $this->boolean()->notNull()->defaultValue(true)
        ]);

        $this->addForeignKey(
            'fk_teacher-user',
            '{{%TEACHER}}',
            'userId',
            '{{%USER}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_teacher-user',
            '{{%TEACHER}}'
        );

        $this->dropTable('{{%TEACHER}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260313_162728_TEACHER cannot be reverted.\n";

        return false;
    }
    */
}
