<?php

namespace rats\forum\widgets;

use rats\forum\models\User;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

class MembersListWidget extends ListView
{
    public $categoryId = null;

    public $layout = "{items}{pager}";
    public $itemView = '@rats/forum/widgets/views/_member';
    public $options = ['class' => 'member-list'];
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
        $this->emptyText = \Yii::t('app', 'No members');


        $staff = \Yii::$app->request->get('staff');


        if (!$this->dataProvider) {
            $query = User::find()->active()->ordered();

            // Apply the staff filter if the staff GET parameter is present
            if ($staff) {
                $authManager = \Yii::$app->authManager;

                // Get user IDs with the 'forum-admin' role
                $forumAdminRole = $authManager->getRole('forum-admin');
                $adminUserIds = $authManager->getUserIdsByRole($forumAdminRole->name);

                // Get user IDs with the 'forum-moderator' role
                $forumModeratorRole = $authManager->getRole('forum-moderator');
                $moderatorUserIds = $authManager->getUserIdsByRole($forumModeratorRole->name);

                // Merge the user IDs and filter the query
                $staffUserIds = array_merge($adminUserIds, $moderatorUserIds);
                $query->andWhere(['id' => $staffUserIds]);
            }

            $this->dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 15,
                ],
            ]);
        }

        parent::init();
    }
}
