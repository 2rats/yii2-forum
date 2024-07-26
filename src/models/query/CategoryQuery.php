<?php

namespace rats\forum\models\query;

use rats\forum\models\Category;

/**
 * This is the ActiveQuery class for [[Category]].
 *
 * @see Category
 */
class CategoryQuery extends \yii\db\ActiveQuery
{
    /**
     * Adds ordering by `ordering` column
     */
    public function ordered()
    {
        return $this->orderBy(['ordering' => SORT_ASC]);
    }

    /**
     * Returns only active models
     */
    public function active()
    {
        return $this->andWhere([
            'status' => Category::STATUS_ACTIVE
        ]);
    }
}
