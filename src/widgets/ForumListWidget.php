<?php

namespace rats\forum\widgets;

use rats\forum\models\Forum;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

class ForumListWidget extends ListView
{
    public $categoryId = null;

    public $layout = "{items}{pager}";
    public $itemView = '@rats/forum/widgets/views/_forum';
    public $options = ['class' => 'forum-list'];
    public $emptyTextOptions = [
        'class' => 'text-center text-muted',
    ];
    public $pager = [
        'class' => \yii\bootstrap5\LinkPager::class,
        'options' => [
            'class' => 'd-flex',
        ],
        'listOptions' => [
            'class' => 'pagination mx-auto mt-3 mt-md-0 pt-3',
        ],
        'linkOptions' => [
            'class' => 'page-link text-dark',
        ],
    ];

    public function init()
    {
        $this->emptyText = \Yii::t('app', 'No forums');

        if (!$this->dataProvider) {
            $this->dataProvider = new ActiveDataProvider([
                'query' => Forum::find()->active()->ordered()->andWhere(['fk_category' => $this->categoryId]),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
        }
        parent::init();
    }
}
