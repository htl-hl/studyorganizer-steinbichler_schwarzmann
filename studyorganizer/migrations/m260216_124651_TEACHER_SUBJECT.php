<?php

use yii\db\Migration;

class m260216_124651_TEACHER_SUBJECT extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("{{%TEACHER_SUBJECT}}", [
            'teacherId' => $this->integer()->notNull(),
            'subjectId' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey(
            'teacher-subject-id',
            '{{%TEACHER_SUBJECT}}',
            ['teacherId', 'subjectId']
        );

        $this->addForeignKey(
            'fk-teacher_subject-teacherId',
            '{{%TEACHER_SUBJECT}}',
            'teacherId',
            '{{%TEACHER}}',
            'id'
        );

        $this->addForeignKey(
            'fk-teacher_subject-subjectId',
            '{{%TEACHER_SUBJECT}}',
            'subjectId',
            '{{%SUBJECT}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("{{%TEACHER_SUBJECT}}");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260216_124651_TEACHER_SUBJECT cannot be reverted.\n";

        return false;
    }
    */
}
