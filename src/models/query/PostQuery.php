<?php

namespace rats\forum\models\query;

use rats\forum\models\Post;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[Post]].
 *
 * @see Post
 */
class PostQuery extends \yii\db\ActiveQuery
{
    /**
     * Returns only active models
     */
    public function active()
    {
        return $this->andWhere(['forum_post.status' => Post::STATUS_ACTIVE]);
    }

    /**
     * Returns only new models (created within the last 24 hours)
     */
    public function new()
    {
        return $this->andWhere(['>=', 'created_at', new Expression('DATE_SUB(NOW(), INTERVAL 1 DAY)')]);
    }

    /**
     * Returns only models created by the specified user
     */
    public function createdBy(int $id) {
        return $this->andWhere(['created_by' => $id]);
    }
}
