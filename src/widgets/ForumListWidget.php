<?php

namespace rats\forum\widgets;

use rats\forum\models\Forum;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/**
 * ForumListWidget renders a list of forums.
 *
 * Properties:
 *
 * @property integer|null $categoryId The ID of the category to filter forums by.
 * @property integer $pageSize The number of items to display per page. Set to 0 to disable pagination. Default is 20.
 */
class ForumListWidget extends ListView
{
    public $categoryId = null;
    public $pageSize = 20;

    public $layout = "{items}{pager}";
    public $itemView = '@rats/forum/widgets/views/_forum';
    public $options = ['class' => 'forum-list'];
    public $emptyTextOptions = [
        'class' => 'text-center text-secondary',
    ];

    public $pager = [
        'class' => \yii\bootstrap5\LinkPager::class,
        'options' => [
            'class' => 'd-flex',
        ],
        'listOptions' => [
            'class' => 'pagination mx-auto mt-3 mt-md-0 pt-3',
        ],
    ];

    public function init()
    {
        $this->emptyText = \Yii::t('app', 'No forums');

        if (!$this->dataProvider) {
            $paginationConfig = $this->pageSize > 0 ? ['pageSize' => $this->pageSize] : false;

            $this->dataProvider = new ActiveDataProvider(
                [
                    'query' => Forum::find()->active()->ordered()->andWhere(['fk_category' => $this->categoryId])->with(
                        [
                            'lastPost',
                            'lastPost.thread',
                            'lastPost.createdBy'
                        ]
                    ),
                'pagination' => $paginationConfig,
                ]
            );
        }
        parent::init();
    }
}

