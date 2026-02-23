<?php

use yii\db\Migration;

class m260216_131642_TASK extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("{{%TASK}}", [
            'id' => $this->primaryKey(),
            'title' => $this->string()->unique()->notNull(),
            'dueDate' => $this->dateTime()->notNull(),
            'isCompleted' => $this->boolean()->notNull()->defaultValue(false),
            'description' => $this->text()->notNull(),
            'userId' => $this->integer()->notNull(),
            'subjectId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'user-Id',
            '{{%TASK}}',
            'userId',
            '{{%USER}}',
            'id'
        );

        $this->addForeignKey(
            'subject-Id',
            '{{%TASK}}',
            'subjectId',
            '{{%SUBJECT}}',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("{{%TASK}}");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260216_131642_TASK cannot be reverted.\n";

        return false;
    }
    */
}
