<?php

namespace rats\forum\rbac;

use rats\forum\models\Post;
use rats\forum\models\User;
use yii\rbac\Rule;

/**
 * Checks if created_by of last post created by user is the same as user trying to edit the post.
 */
class LastAuthorPostRule extends Rule
{
    public $name = 'forum-isLastAuthorPost';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (!isset($params['model'])) {
            return false;
        }

        $post = $params['model'];
        $userModel = User::findOne($user);
        if ($post instanceof Post && $user !== null) {
            return $post->created_by == $user && $post->id == $userModel->last_post_id;
        }

        return false;
    }
}
