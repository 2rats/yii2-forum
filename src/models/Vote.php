<?php

namespace rats\forum\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "forum_vote".
 *
 * @property int $id
 * @property int $fk_post
 * @property int $fk_user
 * @property int $value
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Post $post
 * @property User $user
 */
class Vote extends ActiveRecord
{
    const VALUE_LIKE = 0;
    const VALUE_DISLIKE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum_vote';
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
            [['fk_post', 'fk_user'], 'unique', 'targetAttribute' => ['fk_post', 'fk_user']],
            [['fk_post', 'fk_user', 'value'], 'required'],
            [['fk_post', 'fk_user', 'value'], 'integer'],
            [['value'], 'in', 'range' => [self::VALUE_LIKE, self::VALUE_DISLIKE]],
            [['fk_post'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['fk_post' => 'id']],
            [['fk_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['fk_user' => 'id']],
            [['fk_post', 'fk_user'], 'unique', 'targetAttribute' => ['fk_post', 'fk_user'], 'message' => 'User has already voted on this post.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fk_post' => Yii::t('app', 'Post'),
            'fk_user' => Yii::t('app', 'User'),
            'value' => Yii::t('app', 'Vote Type'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->value == self::VALUE_LIKE) {
            $this->post->updateCounters(['like_count' => 1]);
        } else {
            $this->post->updateCounters(['dislike_count' => 1]);
        }

        if ($changedAttributes['value'] === self::VALUE_LIKE) {
            $this->post->updateCounters(['like_count' => -1]);
        } elseif ($changedAttributes['value'] === self::VALUE_DISLIKE) {
            $this->post->updateCounters(['dislike_count' => -1]);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if ($this->value == self::VALUE_LIKE) {
            $this->post->updateCounters(['like_count' => -1]);
        } else {
            $this->post->updateCounters(['dislike_count' => -1]);
        }
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'fk_post']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'fk_user']);
    }
}
