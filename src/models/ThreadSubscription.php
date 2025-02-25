<?php

namespace rats\forum\models;

use rats\forum\models\query\ThreadSubscriptionQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "forum_thread_subscription".
 *
 * @property int $id
 * @property int $fk_user
 * @property int $fk_thread
 * @property int $fk_last_post
 * @property string $token
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Post $lastPost
 * @property Thread $thread
 * @property User $user
 */
class ThreadSubscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum_thread_subscription';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()')
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_user', 'fk_thread', 'fk_last_post'], 'required'],
            [['fk_user', 'fk_thread', 'fk_last_post'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['token'], 'string', 'max' => 64],
            [['fk_thread', 'fk_user'], 'unique', 'targetAttribute' => ['fk_thread', 'fk_user']],
            [['token'], 'unique'],
            [['fk_thread'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::class, 'targetAttribute' => ['fk_thread' => 'id']],
            [['fk_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['fk_user' => 'id']],
            [['fk_last_post'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['fk_last_post' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fk_user' => Yii::t('app', 'User'),
            'fk_thread' => Yii::t('app', 'Thread'),
            'fk_last_post' => Yii::t('app', 'Last Post'),
            'token' => Yii::t('app', 'Token'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->token = Yii::$app->security->generateRandomString(64);
            }
            return true;
        }
        return false;
    }

    /**
     * Gets query for [[LastPost]].
     *
     * @return \yii\db\ActiveQuery|PostQuery
     */
    public function getLastPost()
    {
        return $this->hasOne(Post::class, ['id' => 'fk_last_post']);
    }

    /**
     * Gets query for [[Thread]].
     *
     * @return \yii\db\ActiveQuery|ThreadQuery
     */
    public function getThread()
    {
        return $this->hasOne(Thread::class, ['id' => 'fk_thread']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'fk_user']);
    }

    /**
     * {@inheritdoc}
     * @return ThreadSubscriptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ThreadSubscriptionQuery(get_called_class());
    }
}
