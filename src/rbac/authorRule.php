<?php

namespace rats\forum\rbac;

use yii\rbac\Rule;

/**
 * Checks if created_by matches user passed via params
 */
class AuthorRule extends Rule
{
    public $name = 'forum-isAuthor';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['model']) ? $params['model']->created_by == $user : false;
    }
}
