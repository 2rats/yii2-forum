<?php

namespace rats\forum\models\query;

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
}
