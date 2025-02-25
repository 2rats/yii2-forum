<?php

namespace rats\forum\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Exception;
use rats\forum\models\Thread;
use rats\forum\models\ThreadSubscription;
use rats\forum\services\ThreadSubscriptionService;
use yii\data\ActiveDataProvider;

class ThreadSubscriptionController extends Controller
{
    public const CRON_TOKEN = '0bad77baa671b856bba75f510109eb86';

    public function init()
    {
        if ($this->layout === null) {
            $this->layout = $this->module->forumLayout;
        }
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['subscribe', 'unsubscribe-authenticated', 'unsubscribe-all', 'user-subscriptions'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['subscribe', 'unsubscribe-authenticated', 'unsubscribe-all', 'user-subscriptions'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'subscribe' => ['post'],
                    'unsubscribe-authenticated' => ['post'],
                    'unsubscribe-all' => ['post'],
                    'unsubscribe' => ['get'],
                ],
            ],
        ];
    }

    /**
     * Subscribe to thread
     */
    public function actionSubscribe($threadId)
    {
        $service = new ThreadSubscriptionService();

        try {
            $success = $service->subscribe($threadId, Yii::$app->user->id);

            if ($success) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'You have subscribed to this thread.'));
            }
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
        }

        return $this->redirect(Thread::findOne($threadId)->getUrl());
    }

    /**
     * Unsubscribe from thread (authenticated)
     */
    public function actionUnsubscribeAuthenticated($threadId)
    {
        $service = new ThreadSubscriptionService();

        try {
            $success = $service->unsubscribe($threadId, Yii::$app->user->id);

            if ($success) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'You have unsubscribed from this thread.'));
            }
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
        }

        return $this->redirect(Thread::findOne($threadId)->getUrl());
    }

    /**
     * Unsubscribe from all threads
     */
    public function actionUnsubscribeAll()
    {
        $service = new ThreadSubscriptionService();

        try {
            $count = $service->unsubscribeAll(Yii::$app->user->id);

            Yii::$app->session->setFlash('success', Yii::t('app', 'You have been unsubscribed from {count} threads.', [
                'count' => $count,
            ]));
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
        }

        return $this->redirect(['user-subscriptions']);
    }

    /**
     * Unsubscribe from thread using token (from email)
     */
    public function actionUnsubscribe($token)
    {
        if (!$token) {
            throw new NotFoundHttpException('Invalid token.');
        }

        $service = new ThreadSubscriptionService();

        try {
            $success = $service->unsubscribe(null, null, $token);

            if ($success) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'You have been unsubscribed from this thread.'));
            }
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', Yii::t('app', $e->getMessage()));
        }

        return $this->redirect(['site/index']);
    }

    /**
     * List user subscriptions
     */
    public function actionUserSubscriptions()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ThreadSubscription::find()
                ->where(['fk_user' => Yii::$app->user->id])
                ->orderBy(['created_at' => SORT_DESC])
                ->with('thread'),
            'pagination' => [
                'pageSizeParam' => false,
            ],
        ]);

        return $this->render('user-subscriptions', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCron($token)
    {
        if ($token !== self::CRON_TOKEN) {
            return;
        }
        $service = new ThreadSubscriptionService();
        $service->notify();
    }
}
