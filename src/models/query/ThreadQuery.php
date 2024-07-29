<?php

namespace rats\forum\models\query;

use rats\forum\models\Thread;
use yii\db\Expression;


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
            'forum_thread.status' => [
                Thread::STATUS_ACTIVE_UNLOCKED,
                Thread::STATUS_ACTIVE_LOCKED
            ]
        ]);
    }

    /**
     * Returns only new models (created within the last 24 hours)
     */
    public function new()
    {
        return $this->andWhere(['>=', 'created_at', new Expression('DATE_SUB(NOW(), INTERVAL 1 DAY)')]);
    }

    public function orderByLastPost()
    {
        return $this->innerJoinWith([
            'lastPost' => function ($query) {
                $query->active();
            }
        ])->orderBy(['forum_post.created_at' => SORT_DESC]);
    }
}
