<?php

namespace app\rbac;

use app\models\ForumModerator;
use yii\rbac\Rule;

/**
 * Checks user can edit forum by checking the forum is assigned to the user
 */
class ForumModeratorRule extends Rule
{
    public $name = 'isAssignedToForum';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (!isset($params['forum'])) {
            return false;
        }

        return ForumModerator::find()->where([
            'fk_forum' => $params['forum']->id,
            'fk_user' => $user
        ])->exists();
    }
}
