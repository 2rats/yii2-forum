<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m240726_122357_add_status_column_to_category_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('forum_category', 'status', $this->integer()->after('ordering')->defaultValue(1));
    }

    public function safeDown()
    {
        $this->dropColumn('forum_category', 'status');
    }
}
