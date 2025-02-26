<?php

namespace rats\forum\widgets;

use rats\forum\models\User;
use rats\forum\models\Vote;
use yii\data\ActiveDataProvider;

class VoteWidget extends \yii\base\Widget
{
    /**
     * @var \rats\forum\models\Post
     */
    public $post;

    /**
     * @var int|null
     */
    public $userId;

    private function getVoteDataProvider(int $value): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Vote::find()->where([
                'fk_post' => $this->post->id,
                'value' => $value
            ]),
            'pagination' => [
                'pageSize' => 10,
                'pageParam' => 'page_' . $value,
                'pageSizeParam' => false,
            ]
        ]);
    }

    public function run()
    {
        $voteService = new \rats\forum\services\VoteService();

        return $this->render('vote', [
            'likeVoteDataProvider' => $this->getVoteDataProvider(Vote::VALUE_LIKE),
            'dislikeVoteDataProvider' => $this->getVoteDataProvider(Vote::VALUE_DISLIKE),
            'post' => $this->post,
            'userId' => $this->userId,
            'canVote' => $this->userId !== null && !User::findOne($this->userId)->isMuted(),
            'likeCount' => $voteService->getVoteCount($this->post, Vote::VALUE_LIKE),
            'dislikeCount' => $voteService->getVoteCount($this->post, Vote::VALUE_DISLIKE),
            'userLiked' => $voteService->hasUserVoted($this->userId, $this->post->id, Vote::VALUE_LIKE),
            'userDisliked' => $voteService->hasUserVoted($this->userId, $this->post->id, Vote::VALUE_DISLIKE),
        ]);
    }
}
