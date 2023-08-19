<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m230819_150948_add_ordering_column_to_category_and_forum_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('forum_category', 'ordering', $this->integer()->after('description')->defaultValue(0));
        $this->addColumn('forum_forum', 'ordering', $this->integer()->after('posts')->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('forum_category', 'ordering');
        $this->dropColumn('forum_forum', 'ordering');
    }
}
