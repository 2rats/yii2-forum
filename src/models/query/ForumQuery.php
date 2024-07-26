<?php

namespace rats\forum\models\query;

use rats\forum\models\Forum;

/**
 * This is the ActiveQuery class for [[Forum]].
 *
 * @see Forum
 */
class ForumQuery extends \yii\db\ActiveQuery
{
    /**
     * Returns only active models
     */
    public function active()
    {
        return $this->andWhere([
            'status' => [
                Forum::STATUS_ACTIVE_UNLOCKED,
                Forum::STATUS_ACTIVE_LOCKED
            ]
        ]);
    }

    /**
     * Adds ordering by `ordering` column
     */
    public function ordered()
    {
        return $this->orderBy(['ordering' => SORT_ASC]);
    }

    /**
     * Returns only top level forums
     */
    public function topLevel()
    {
        return $this->andWhere(['fk_parent' => null]);
    }
}
