<?php

namespace rats\forum\models\query;

use rats\forum\models\Thread;

/**
 * This is the ActiveQuery class for [[Thread]].
 *
 * @see Thread
 */
class ThreadQuery extends \yii\db\ActiveQuery
{
    /**
     * Returns only active models
     */
    public function active()
    {
        return $this->andWhere([
            'status' => [
                Thread::STATUS_ACTIVE_UNLOCKED,
                Thread::STATUS_ACTIVE_LOCKED
            ]
        ]);
    }
}
