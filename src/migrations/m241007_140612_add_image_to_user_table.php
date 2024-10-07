<?php

use yii\db\Migration;

/**
 * Class m241007_140612_add_image_to_user_table
 */
class m241007_140612_add_image_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('forum_user', 'fk_image', $this->integer()->after('signature')->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('forum_user', 'fk_image');
    }
}
