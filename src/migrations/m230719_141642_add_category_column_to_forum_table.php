<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m230719_141642_add_category_column_to_forum_table  extends Migration
{

    private $table_name = 'forum_forum';

    public function safeUp()
    {
        // adds column to forum
        $this->addColumn($this->table_name, 'fk_category', $this->integer()->notNull());

        // creates index for column `fk_category`
        $this->createIndex(
            "{{%idx-{$this->table_name}-fk_category}}",
            "{{%{$this->table_name}}}",
            'fk_category'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-fk_category}}",
            "{{%{$this->table_name}}}",
            'fk_category',
            "{{%forum_category}}",
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // drops foreign key for table `{{%category}}`
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-fk_category}}",
            "{{%{$this->table_name}}}",
        );

        // drops index for column `fk_category`
        $this->dropIndex(
            "{{%idx-{$this->table_name}-fk_category}}",
            "{{%{$this->table_name}}}",
        );
        
        $this->dropColumn($this->table_name, 'fk_category');
    }
}
