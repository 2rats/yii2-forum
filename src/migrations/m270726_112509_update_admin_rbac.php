<?php

use yii\db\Migration;

/**
 * Class m270726_112509_update_admin_rbac
 */
class m270726_112509_update_admin_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $editForum = $auth->getPermission('forum-editForum');
        $editThread = $auth->getPermission('forum-editThread');
        $editPost = $auth->getPermission('forum-editPost');

        $admin = $auth->getRole('forum-admin');
        $auth->addChild($admin, $editForum);
        $auth->addChild($admin, $editThread);
        $auth->addChild($admin, $editPost);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        
        $admin = $auth->getRole('forum-admin');
        $editForum = $auth->getPermission('forum-editForum');
        $editThread = $auth->getPermission('forum-editThread');
        $editPost = $auth->getPermission('forum-editPost');

        $auth->removeChild($admin, $editForum);
        $auth->removeChild($admin, $editThread);
        $auth->removeChild($admin, $editPost);
    }
}
