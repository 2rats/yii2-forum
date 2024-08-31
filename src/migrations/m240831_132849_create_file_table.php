<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m240831_132849_create_file_table extends Migration
{
    private $table_name = 'forum_file';

    public function safeUp()
    {
        $table_options = Yii::$app->params['migrationTableOptions'] ?? null;
        $user_table_name = Yii::$app->params['userTableName'] ?? 'user';

        if ($this->db->driverName === 'mysql' && $table_options === null) {
            $table_options = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%{$this->table_name}}}", [
            'id' => $this->primaryKey(),
            'filename' => $this->string()->notNull(),
            'fk_user' => $this->integer()->defaultValue(null),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()->defaultExpression('NOW()'),
        ], $table_options);

        // creates index for column `fk_user`
        $this->createIndex(
            "{{%idx-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
            'fk_user'
        );

        // add foreign key for user table
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
            'fk_user',
            "{{%{$user_table_name}}}",
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // drops foreign key for user
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
        );

        // drops index for column `user`
        $this->dropIndex(
            "{{%idx-{$this->table_name}-fk_user}}",
            "{{%{$this->table_name}}}",
        );

        $this->dropTable("{{%{$this->table_name}}}");
    }
}
