<?php

use yii\db\Migration;

/**
 * Class m240303_132642_update_rbac
 */
class m240303_132642_update_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $rule = new \app\rbac\ForumModeratorRule();
        $auth->add($rule);

        $editForum = $auth->createPermission('editForum');
        $editForum->description = 'Edit forum';
        $auth->add($editForum);

        $editAssignedForum = $auth->createPermission('editAssignedForum');
        $editAssignedForum->description = 'Edit assigned forum';
        $editAssignedForum->ruleName = $rule->name;
        $auth->add($editAssignedForum);

        // editAssignedForum will be used from editForum
        $auth->addChild($editAssignedForum, $editForum);

        $user = $auth->getRole('user');
        // let user edit assigned magazines
        $auth->addChild($user, $editAssignedForum);

        $admin = $auth->getRole('admin');
        $auth->addChild($admin, $editForum);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->remove($auth->getPermission('editForum'));
        $auth->remove($auth->getPermission('editAssignedForum'));
        $auth->remove($auth->getRule('isAssignedToForum'));
    }
}
