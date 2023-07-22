<?php

namespace rats\forum\models;

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
            [['fk_parent'], 'exist', 'skipOnError' => true, 'targetClass' => Post::class, 'targetAttribute' => ['fk_parent' => 'id']],
            [['fk_thread'], 'exist', 'skipOnError' => true, 'targetClass' => Thread::class, 'targetAttribute' => ['fk_thread' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'fk_thread' => \Yii::t('app', 'Thread'),
            'fk_parent' => \Yii::t('app', 'Parent'),
            'content' => \Yii::t('app', 'Content'),
            'status' => \Yii::t('app', 'Status'),
            'created_by' => \Yii::t('app', 'Created by'),
            'updated_by' => \Yii::t('app', 'Updated by'),
            'created_at' => \Yii::t('app', 'Created at'),
            'updated_at' => \Yii::t('app', 'Updated at'),
        ];
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
     * Gets username of user who created Post or "removed" if Post was removed.
     *
     * @return string
     */
    public function printCreatedBy()
    {
        return htmlentities($this->status == $this::STATUS_DELETED ? '<' . \Yii::t('app', 'deleted') . '>' : $this->createdBy->username);
    }

    /**
     * Gets content of Post or "removed" if Post was removed.
     *
     * @return string
     */
    public function printContent()
    {
        return $this->status == $this::STATUS_DELETED ? htmlentities('<' . \Yii::t('app', 'deleted') . '>') : nl2br($this->content);
    }

    /**
     * Gets User roles or "removed" if Post was removed.
     *
     * @return string[]
     */
    public function getCreatedByRoles()
    {
        if ($this->status == $this::STATUS_DELETED || User::STATUS_DELETED == $this->createdBy->status) {
            return [htmlentities('<' . \Yii::t('app', 'deleted') . '>')];
        }

        return $this->createdBy->roles;
    }

    /**
     * Gets content of Post or "removed" if Post was removed.
     *
     * @return string
     */
    public function printCreatedBySignature()
    {
        if ($this->status == $this::STATUS_DELETED || User::STATUS_DELETED == $this->createdBy->status) {
            return null;
        }

        return $this->createdBy->signature;
    }
}
