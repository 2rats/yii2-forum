<?php

namespace rats\forum\models\query;

use rats\forum\models\User;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /**
     * Returns only active models
     */
    public function active()
    {
        return $this->andWhere(['in', 'status', [User::STATUS_ACTIVE, User::STATUS_MUTED]]);
    }

    /**
     * Adds ordering by `ordering` column
     */
    public function ordered()
    {
        return $this->orderBy(['created_at' => SORT_ASC]);
    }

    /**
     * Returns only new models (created within the last 24 hours)
     */
    public function new()
    {
        return $this->andWhere(['>=', 'created_at', new Expression('DATE_SUB(NOW(), INTERVAL 1 DAY)')]);
    }
}
