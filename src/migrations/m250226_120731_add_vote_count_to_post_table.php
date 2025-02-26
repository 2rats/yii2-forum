<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m250226_120731_add_vote_count_to_post_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn("{{%forum_post}}", "like_count", $this->integer()->defaultValue(0));
        $this->addColumn("{{%forum_post}}", "dislike_count", $this->integer()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn("{{%forum_post}}", "like_count");
        $this->dropColumn("{{%forum_post}}", "dislike_count");
    }
}
