<?php

use yii\db\Migration;

/**
 * Class m250227_112046_add_default_user_image_to_forum_file_table
 */
class m250227_112046_add_default_user_image_to_forum_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%forum_file}}', 'is_default_profile_image', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%forum_file}}', 'is_default_profile_image');
    }
}
