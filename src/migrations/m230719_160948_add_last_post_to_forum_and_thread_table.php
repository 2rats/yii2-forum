<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m230719_160948_add_last_post_to_forum_and_thread_table  extends Migration
{
    public function safeUp()
    {
        // adds column to forum
        $this->addColumn('forum_forum', 'fk_last_post', $this->integer()->after('fk_parent'));

        // add foreign key for table `{{%post}}`
        $this->addForeignKey(
            "{{%fk-forum_forum-fk_last_post}}",
            "{{%forum_forum}}",
            'fk_last_post',
            "{{%forum_post}}",
            'id',
            'SET NULL'
        );

        // adds column to thread
        $this->addColumn('forum_thread', 'fk_last_post', $this->integer()->after('fk_forum'));

        // add foreign key for table `{{%post}}`
        $this->addForeignKey(
            "{{%fk-forum_thread-fk_last_post}}",
            "{{%forum_thread}}",
            'fk_last_post',
            "{{%forum_post}}",
            'id',
            'SET NULL'
        );
    }

    public function safeDown()
    {
        // drops foreign key for table `{{%post}}`
        $this->dropForeignKey(
            "{{%fk-forum_forum-fk_last_post}}",
            "{{%forum_forum}}",
        );

        $this->dropColumn('forum_forum', 'fk_last_post');

        // drops foreign key for table `{{%post}}`
        $this->dropForeignKey(
            "{{%fk-forum_thread-fk_last_post}}",
            "{{%forum_thread}}",
        );

        $this->dropColumn('forum_thread', 'fk_last_post');
    }
}
