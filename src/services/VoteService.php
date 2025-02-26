<?php

namespace rats\forum\services;

use Exception;
use rats\forum\ForumModule;
use rats\forum\models\Post;
use rats\forum\models\Thread;
use rats\forum\models\ThreadSubscription;
use rats\forum\models\User;
use rats\forum\models\Vote;
use Yii;

class VoteService
{
    /**
     * Vote on a post
     * 
     * @param int|Post|null $post
     * @param int $userId
     * @param int $value
     * @throws Exception
     */
    public function vote($post, int $userId, int $value): void
    {
        if (is_int($post)) {
            $post = Post::findOne($post);
        }
        if (!$post) {
            throw new Exception('Post not found');
        }

        $vote = new Vote([
            'fk_post' => $post->id,
            'fk_user' => $userId,
            'value' => $value
        ]);

        if ($this->hasUserVoted($userId, $post->id, null)) {
            $vote = Vote::find()->where([
                'fk_user' => $userId,
                'fk_post' => $post->id,
            ])->one();

            if ($vote->value == $value) {
                if ($vote->delete() <= 0) {
                    throw new Exception('Failed to delete old vote');
                }
                return;
            }
            $vote->value = $value;
        }

        if (!$vote->save()) {
            Yii::error($vote->getErrors());
            throw new Exception('Failed to save vote');
        }
    }

    /**
     * Check if a user has voted on a post
     *
     * @param int|null $userId
     * @param int $postId
     * @param ?int $value if null, check if user has voted at all
     * @return bool
     */
    public function hasUserVoted($userId, int $postId, $value)
    {
        if ($userId === null) {
            return false;
        }

        $conditions = [
            'fk_user' => $userId,
            'fk_post' => $postId
        ];
        if ($value !== null) {
            $conditions['value'] = $value;
        }
        return Vote::find()->where($conditions)->exists();
    }

    /**
     * Get vote count for a specific type and post
     *
     * @param int|Post $post
     * @param int $value
     * @return int
     */
    public function getVoteCount($post, $type): int
    {
        if (is_int($post)) {
            $post = Post::findOne($post);
            if (!$post) {
                return 0;
            }
        }
        return $post->{$post->getVoteAttribute($type)};
    }
}
