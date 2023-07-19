<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m230719_152514_add_counter_columns_to_thread_and_forum_table  extends Migration
{
    public function safeUp()
    {
        // adds column to forum
        $this->addColumn('forum_forum', 'threads', $this->integer()->notNull()->defaultValue(0)->after('status'));
        $this->addColumn('forum_forum', 'posts', $this->integer()->notNull()->defaultValue(0)->after('threads'));
        $this->addColumn('forum_thread', 'posts', $this->integer()->notNull()->defaultValue(0)->after('status'));
    }

    public function safeDown()
    {
        $this->dropColumn('forum_forum', 'posts');
        $this->dropColumn('forum_forum', 'threads');
        $this->dropColumn('forum_thread', 'posts');
    }
}
