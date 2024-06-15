<?php

namespace rats\forum\models\query;

use rats\forum\models\User;

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
        return $this->andWhere(['status' => User::STATUS_ACTIVE]);
    }

    /**
     * Adds ordering by `ordering` column
     */
    public function ordered()
    {
        return $this->orderBy(['created_by' => SORT_ASC]);
    }
}
