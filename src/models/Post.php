<?php

namespace rats\forum\models;

use rats\forum\ForumModule;
use rats\forum\models\query\PostQuery;
use Yii;
use yii\bootstrap5\Html;
use yii\helpers\Markdown;

/**
 * This is the model class for table "forum_post".
 *
 * @property int         $id
 * @property int         $fk_thread
 * @property int|null    $fk_parent
 * @property string      $content
 * @property int         $status
 * @property int         $created_by
 * @property int         $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property User        $createdBy
 * @property Post        $parent
 * @property Thread      $thread
 * @property Vote[]      $votes
 * @property Post[]      $posts
 * @property User        $updatedBy
 */
class Post extends ActiveRecord
{
    public const STATUS_DELETED = 0;
    public const STATUS_ACTIVE = 1;

    public const IMAGE_UPLOAD_DIR = 'post-images';

    public static function tableName()
    {
        return 'forum_post';
    }

    public function rules()
    {
        return [
            [['fk_thread', 'content'], 'required'],
            [['fk_thread', 'fk_parent', 'status', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['fk_parent'], 'exist', 'skipOnError' => false, 'targetClass' => Post::class, 'targetAttribute' => ['fk_parent' => 'id']],
            [['fk_thread'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::class, 'targetAttribute' => ['fk_thread' => 'id']],
            [['fk_parent', 'fk_thread'], 'validateSameThread'],
            [['fk_thread'], 'validateLockedThread'],
        ];
    }

    public function validateLockedThread($attribute, $params)
    {
        if ($this->thread->isLocked() && $this->isNewRecord) {
            $this->addError($attribute, Yii::t('app', 'You can not post in a locked thread.'));
        }
    }

    public function validateSameThread($attribute, $params)
    {
        if ($this->fk_parent !== null && $this->fk_parent !== '' && $this->parent !== null) {
            if ((int) $this->fk_thread !== (int) $this->parent->fk_thread) {
                $this->addError($attribute, Yii::t('app', 'You can not reply to Posts from different thread.'));
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'fk_thread' => \Yii::t('app', 'Thread'),
            'fk_parent' => \Yii::t('app', 'Parent'),
            'content' => \Yii::t('app', 'Post text'),
            'status' => \Yii::t('app', 'Status'),
            'created_by' => \Yii::t('app', 'Created by'),
            'updated_by' => \Yii::t('app', 'Updated by'),
            'created_at' => \Yii::t('app', 'Created at'),
            'updated_at' => \Yii::t('app', 'Updated at'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->thread->fk_last_post = $this->id;
            $this->thread->updateCounters(['posts' => 1]);
            $this->thread->save();
            $this->thread->forum->fk_last_post = $this->id;
            $this->thread->forum->updateCounters(['posts' => 1]);
            $this->thread->forum->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Post::class, ['id' => 'fk_parent']);
    }

    /**
     * Gets query for [[Thread]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getThread()
    {
        return $this->hasOne(Thread::class, ['id' => 'fk_thread']);
    }

    /**
     * Gets query for [[Votes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Vote::class, ['fk_post' => 'id']);
    }

    /**
     * Gets query for [[Replies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(Post::class, ['fk_parent' => 'id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Replaces text parts with emojis
     * 
     * @param string text
     * @return string
     */
    public static function parseEmojis($text)
    {
        return strtr($text, [
            ':)' => '🙂',
            ';)' => '😉',
            ':D' => '😃',
            '8)' => '😎',
            'B)' => '😎',
            ':P' => '😋',
            ':o' => '😮',
            ':?' => '😕',
            ':(' => '😞',
            ':x' => '😠',
            ':|' => '😐',
            ":'(" => '😥',
            'xd' => '😆',
            ':lol:' => '😆',
            ':mrgreen:' => '😁',
            ':oops:' => '😳',
            ':shock:' => '😲',
            ':roll:' => '🙄',
            ':evil:' => '👿',
            ':twisted:' => '😈',
        ]);
    }

    /**
     * Gets content of Post or "removed" if Post was removed.
     *
     * @return string
     */
    public function printContent($trim = false)
    {
        $parsedContent = self::parseEmojis($this->content);
        if ($this->isDeleted())
            return htmlentities('<' . \Yii::t('app', 'deleted') . '>');
        if (!$trim)
            return Markdown::process(($parsedContent), 'gfm-comment');

        $limit = 5;
        // take first rows
        $parsedContent = preg_split('#\n#', $parsedContent, $limit + 1);
        // if longer than limit, append "..."
        if (sizeof($parsedContent) > $limit) {
            $parsedContent[$limit] = "\n...";
        }
        // join first rows
        $parsedContent = implode("\n", array_slice($parsedContent, 0, $limit + 1));
        $parsedContent = Markdown::process($parsedContent, 'gfm-comment');
        return strip_tags($parsedContent);
    }


    /**
     * Gets post status options.
     *
     * @return string
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => \Yii::t('app', 'Inactive'),
            self::STATUS_DELETED => \Yii::t('app', 'Deleted'),
        ];
    }

    /**
     * Gets post status in printable form.
     *
     * @return string
     */
    public function printStatus()
    {
        $statuses = self::getStatusOptions();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : Yii::t('app', 'Unknown status');
    }

    /**
     * {@inheritdoc}
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }

    /**
     * @return bool whether post is deleted
     */
    public function isDeleted(): bool
    {
        return $this->status === self::STATUS_DELETED;
    }

    /**
     * @return bool whether post is active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isEdited(): bool
    {
        return $this->updated_at > $this->created_at;
    }
}
