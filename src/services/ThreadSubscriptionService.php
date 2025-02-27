<?php

namespace rats\forum\services;

use Exception;
use rats\forum\ForumModule;
use rats\forum\models\Thread;
use rats\forum\models\ThreadSubscription;
use rats\forum\models\User;
use Yii;

/**
 * Service for managing forum thread subscriptions
 */
class ThreadSubscriptionService
{
    /**
     * Maximum number of subscriptions a user can create per hour
     */
    const MAX_SUBSCRIPTIONS_PER_HOUR = 20;

    /**
     * Maximum number of recipients in a BCC email
     */
    const MAX_BCC_RECIPIENTS = 50;

    /**
     * Subscribe user to a thread
     * 
     * @param int $threadId Thread ID
     * @param int $userId User ID
     * @return bool Whether subscription was successful
     * @throws Exception on failure
     */
    public function subscribe($threadId, $userId)
    {
        $user = User::find()->active()->andWhere(['id' => $userId])->one();
        if (!$user) {
            throw new Exception('User not found');
        }

        $thread = Thread::find()->active()->andWhere(['id' => $threadId])->one();
        if (!$thread) {
            throw new Exception('Thread not found');
        }

        if (!$this->checkRateLimit($userId)) {
            throw new Exception('Too many subscription attempts, please try again later');
        }

        $existingSubscription = ThreadSubscription::findOne([
            'fk_thread' => $threadId,
            'fk_user' => $userId,
        ]);

        if ($existingSubscription) {
            return true;
        }

        $subscription = new ThreadSubscription([
            'fk_thread' => $threadId,
            'fk_user' => $userId,
            'fk_last_post' => $thread->fk_last_post,
        ]);

        if (!$subscription->save()) {
            Yii::error($subscription->getErrors(), self::class);
            throw new Exception('Failed to save subscription.');
        }

        return true;
    }

    /**
     * Unsubscribe user from a thread
     * 
     * @param int $threadId Thread ID
     * @param int|null $userId User ID
     * @param string|null $token Security token for unauthenticated unsubscribe
     * @return bool Whether unsubscription was successful
     * @throws Exception on failure
     */
    public function unsubscribe($threadId, $userId = null, $token = null)
    {
        $conditions = ['fk_thread' => $threadId];

        if ($userId !== null) {
            $conditions['fk_user'] = $userId;
        } elseif ($token !== null) {
            $conditions['token'] = $token;
        } else {
            throw new Exception('Either user ID or token must be provided');
        }

        $subscription = ThreadSubscription::findOne($conditions);

        if (!$subscription) {
            throw new Exception('Subscription not found');
        }

        return $subscription->delete() !== false;
    }

    /**
     * Unsubscribe user from all threads
     *
     * @param int $userId User ID
     * @return int Number of subscriptions deleted
     * @throws Exception on failure
     */
    public function unsubscribeAll($userId)
    {
        if (!$userId) {
            throw new Exception('User ID is required');
        }

        return ThreadSubscription::deleteAll(['fk_user' => $userId]) > 0;
    }

    /**
     * Notify subscribers about a new post in a thread
     */
    public function notify(): void
    {
        $startTime = microtime(true);
        $subscriptions = $this->getGroupedSubscriptions();
        $dbDataRetrievalTime = round(microtime(true) - $startTime);

        $threadCount = count($subscriptions);
        $recipientCount = array_sum(array_map('count', $subscriptions));
        $emailCount = 0;

        foreach (array_chunk($subscriptions, 100, true) as $chunk) {
            foreach ($chunk as $threadId => $subscribers) {
                $thread = Thread::find()->active()->andWhere(['id' => $threadId])->one();
                if (!$thread) {
                    continue;
                }

                foreach (array_chunk($subscribers, self::MAX_BCC_RECIPIENTS, true) as $bccRecipients) {
                    if ($this->sendBatchNotificationEmail($bccRecipients, $thread)) {
                        $emailCount++;
                    } else {
                        Yii::error('Failed to send notification email for thread ' . $thread->id . ' - ' . print_r($bccRecipients, true), self::class);
                    }
                }

                // Update last notified post
                ThreadSubscription::updateAll(['fk_last_post' => $thread->fk_last_post], [
                    'fk_thread' => $threadId,
                    'fk_user' => array_keys($subscribers),
                ]);
            }
        }

        $endTime = microtime(true);
        $totalTime = round($endTime - $startTime);

        Yii::info("Thread subscription notification complete | {$threadCount} threads | {$recipientCount} recipients | {$emailCount} emails | DB time: {$dbDataRetrievalTime} ms | Total time: {$totalTime} ms", self::class);
    }

    /**
     * Returns grouped subscriptions for all threads that need to be notified
     * @return array<int, array<int, string>> Grouped subscriptions by thread ID, value is array of userID => userEmail
     */
    protected function getGroupedSubscriptions()
    {
        $grouped = [];
        foreach (ThreadSubscription::find()->toNotify()->asArray()->each() as $subscription) {
            $grouped[$subscription['thread']['id']][$subscription['user']['id']] = $subscription['user']['email'];
        }
        return $grouped;
    }

    /**
     * Send batch notification email with BCC
     * 
     * @param array $recipients Array of user id => email pairs
     * @param Thread $thread
     * @return bool Whether email was sent
     */
    protected function sendBatchNotificationEmail($recipients, $thread)
    {
        if (empty($recipients)) {
            return false;
        }

        $module = ForumModule::getInstance();

        return Yii::$app->mailer->compose($module->threadSubscriptionEmailView, [
            'thread' => $thread,
            'post' => $thread->lastPost,
        ])
            ->setFrom([$module->senderEmail => $module->senderName])
            ->setTo([$module->senderEmail => $module->senderName])
            ->setBcc($recipients)
            ->setSubject(Yii::t('app', 'New activity in subscribed thread'))
            ->send();
    }

    /**
     * Check if a user has subscribed to a thread
     * 
     * @param int $threadId Thread ID
     * @param int $userId User ID
     * @return bool Whether user has subscribed to the thread
     */
    public function hasUserSubscribed($threadId, $userId)
    {
        return ThreadSubscription::find()
            ->where([
                'fk_thread' => $threadId,
                'fk_user' => $userId,
            ])
            ->exists();
    }

    /**
     * Check rate limiting for subscriptions
     * 
     * @param int $userId User ID
     * @return bool Whether user is within rate limits
     */
    protected function checkRateLimit($userId)
    {
        $oneHourAgo = date('Y-m-d H:i:s', time() - 60 * 60);

        $recentCount = ThreadSubscription::find()
            ->where(['fk_user' => $userId])
            ->andWhere(['>=', 'created_at', $oneHourAgo])
            ->count();

        return $recentCount < self::MAX_SUBSCRIPTIONS_PER_HOUR;
    }
}
