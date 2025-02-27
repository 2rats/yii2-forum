<?php

namespace rats\forum\models\query;

/**
 * This is the ActiveQuery class for [[ThreadSubscription]].
 *
 * @see ThreadSubscription
 */
class ThreadSubscriptionQuery extends \yii\db\ActiveQuery
{
    /**
     * Returns all subscriptions that should be notified
     */
    public function toNotify()
    {
        return $this->joinWith([
                'thread' => function (ThreadQuery $query) {
                    $query->active();
                },
                'user',
            ])->joinWith([
                'thread.lastPost' => function (PostQuery $query) {
                    $query->active();
                },
            ], false)
            ->andWhere('forum_thread_subscription.fk_last_post < forum_thread.fk_last_post OR forum_thread_subscription.fk_last_post IS NULL')
            ->andWhere('forum_thread_subscription.fk_user != forum_post.created_by');
    }
}
