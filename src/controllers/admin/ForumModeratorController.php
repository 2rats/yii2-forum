<?php

namespace rats\forum\controllers\admin;

use rats\forum\models\ForumModerator;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\filters\AccessControl;

/**
 * ForumModeratorController implements the CRUD actions for ForumModerator model.
 */
class ForumModeratorController extends AdminController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['forum-admin']
                        ]
                    ]
                ]
            ]
        );
    }

    /**
     * Creates a new ForumModerator model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ForumModerator();
        $model->fk_forum = $this->request->get('fk_forum');

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['admin/forum/view', 'id' => $model->fk_forum]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ForumModerator model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $forumId = $model->fk_forum;
        $model->delete();

        return $this->redirect(['admin/forum/view', 'id' => $forumId]);
    }

    /**
     * Finds the ForumModerator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ForumModerator the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ForumModerator::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUserList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $users = User::find()
                ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id && auth_assignment.item_name = "forum-moderator"')
                ->where(['id' => $id])->all();
            $out['results'] = ['id' => $id, 'text' => $users];
        }
        if (!is_null($q)) {
            $query = new Query();
            $query->select('id, username AS text')
                ->from('user')
                ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id && auth_assignment.item_name = "forum-moderator"')
                ->where(['like', 'username', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
