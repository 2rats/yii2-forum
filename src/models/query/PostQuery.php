<?php

namespace rats\forum\models\query;

use rats\forum\models\Post;

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
        return $this->andWhere(['status' => Post::STATUS_ACTIVE]);
    }
}
