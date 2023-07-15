<?php

/**
 * @author kazda01, mifka01
 */

use yii\db\Migration;

class m230714_191010_create_user_table extends Migration
{

    private $table_name = 'forum-user';

    public function safeUp()
    {
        $table_options = Yii::$app->params['migrationTableOptions'] ?? null;
        $user_table_name = Yii::$app->params['userTableName'] ?? 'user';

        if ($this->db->driverName === 'mysql' && $table_options === null) {
            $table_options = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable("{{%{$this->table_name}}}", [
            'id' => $this->primaryKey(),
            'username' => $this->string(191)->notNull(),
            'email' => $this->string(191),
            'real_name' => $this->string(191),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'signature' => $this->text(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ], $table_options);

        // creates index for column `id`
        $this->createIndex(
            "{{%idx-{$this->table_name}-id}}",
            "{{%{$this->table_name}}}",
            'id'
        );

        // creates index for column `created_by`
        $this->createIndex(
            "{{%idx-{$this->table_name}-created_by}}",
            "{{%{$this->table_name}}}",
            'created_by'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            "{{%idx-{$this->table_name}-updated_by}}",
            "{{%{$this->table_name}}}",
            'updated_by'
        );

        // add foreign key for id
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-id}}",
            "{{%{$this->table_name}}}",
            'id',
            "{{%{$user_table_name}}}",
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-created_by}}",
            "{{%{$this->table_name}}}",
            'created_by',
            "{{%{$user_table_name}}}",
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            "{{%fk-{$this->table_name}-updated_by}}",
            "{{%{$this->table_name}}}",
            'updated_by',
            "{{%{$user_table_name}}}",
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {

        // drops foreign key for id
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-id}}",
            "{{%{$this->table_name}}}",
        );

        // drops index for column `id`
        $this->dropIndex(
            "{{%idx-{$this->table_name}-id}}",
            "{{%{$this->table_name}}}",
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-created_by}}",
            "{{%{$this->table_name}}}",
        );

        // drops index for column `created_by`
        $this->dropIndex(
            "{{%idx-{$this->table_name}-created_by}}",
            "{{%{$this->table_name}}}",
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            "{{%fk-{$this->table_name}-updated_by}}",
            "{{%{$this->table_name}}}",
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            "{{%idx-{$this->table_name}-updated_by}}",
            "{{%{$this->table_name}}}",
        );

        $this->dropTable("{{%{$this->table_name}}}");
    }
}
