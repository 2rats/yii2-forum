<?php

namespace rats\forum\controllers\admin;

use rats\forum\models\search\UserSearch;
use rats\forum\models\User;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminController
{
    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     *
     * @param int $id
     *
     * @return string
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $this->getView()->blocks['forumViewUrl'] = $model->getUrl();

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return string|\yii\web\Response
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        $this->getView()->blocks['forumViewUrl'] = $user->getUrl();

        if (
            !$user->load($this->request->post())
        ) {
            return $this->render('update', [
                'model' => $user,
            ]);
        }

        // for Ajax validation
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($user);
        }

        $transaction = \Yii::$app->db->beginTransaction();

        if (
            $this->request->isPost && $user->save()
        ) {
            // assign role
            $role = \Yii::$app->authManager->getRole($this->request->post('role'));
            if (!is_null($role)) {
                $has_perms = false;

                if ('forum-moderator' == $role->name || 'forum-user' == $role->name) {
                    if (\Yii::$app->authManager->checkAccess(\Yii::$app->user->identity->id, 'forum-assignModerator')) {
                        $has_perms = true;
                    }
                }

                if ($has_perms) {
                    foreach(\Yii::$app->authManager->getRolesByUser($user->id) as $userRole) {
                        if($userRole->name == 'forum-moderator' || $userRole->name == 'forum-user') {
                            \Yii::$app->authManager->revoke($userRole, $user->id);
                        }
                    }
                    \Yii::$app->authManager->assign($role, $user->id);
                    $transaction->commit();

                    return $this->redirect(['view', 'id' => $user->id]);
                }
            }
        }

        $transaction->rollBack();

        return $this->render('update', [
            'model' => $user,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = $this->findModel($id);
        $user_role = implode('', array_keys(\Yii::$app->authManager->getRolesByUser($id)));

        if (str_contains($user_role, 'forum-moderator') || str_contains($user_role, 'forum-user')) {
            if (\Yii::$app->authManager->checkAccess(\Yii::$app->user->identity->id, 'forum-assignModerator')) {
                $user->status = User::STATUS_DELETED;
            }
        }

        $user->save();

        return $this->redirect(['index']);
    }

    public function actionMute($id, $revert = false)
    {
        $user = $this->findModel($id);
        $user_role = implode('', array_keys(\Yii::$app->authManager->getRolesByUser($id)));
        if (str_contains($user_role, 'forum-moderator') || str_contains($user_role, 'forum-user')) {
            if (\Yii::$app->authManager->checkAccess(\Yii::$app->user->identity->id, 'forum-muteUser')) {
                if ($revert) {
                    $user->status = User::STATUS_ACTIVE;
                } else {
                    $user->status = User::STATUS_MUTED;
                }
            }
        }
        $user->save();

        return $this->redirect(\Yii::$app->request->referrer ?: \Yii::$app->homeUrl);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return User the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
