<?php

namespace rats\forum\models;

use rats\forum\ForumModule;
use rats\forum\models\query\ThreadQuery;
use yii\bootstrap5\Html;

/**
 * This is the model class for table "forum_thread".
 *
 * @property int         $id
 * @property int         $fk_forum
 * @property string      $name
 * @property int         $status
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $seo_keywords
 * @property int         $threads
 * @property int         $views
 * @property int         $pinned
 * @property int         $created_by
 * @property int         $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property User        $createdBy
 * @property Forum       $forum
 * @property Post[]      $posts
 * @property User        $updatedBy
 */
class Thread extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE_UNLOCKED = 1;
    const STATUS_ACTIVE_LOCKED = 2;

    public const PINNED_FALSE = 0;
    public const PINNED_TRUE = 1;

    public static function tableName()
    {
        return 'forum_thread';
    }

    public function rules()
    {
        return [
            [['fk_forum', 'name'], 'required'],
            [['fk_forum', 'status', 'views', 'pinned', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'seo_title', 'seo_description', 'seo_keywords'], 'string', 'max' => 191],
            [['fk_forum'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::class, 'targetAttribute' => ['fk_forum' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'fk_forum' => \Yii::t('app', 'Forum'),
            'fk_last_post' => \Yii::t('app', 'Last post'),
            'name' => \Yii::t('app', 'Name'),
            'status' => \Yii::t('app', 'Status'),
            'posts' => \Yii::t('app', 'Posts'),
            'views' => \Yii::t('app', 'Views'),
            'pinned' => \Yii::t('app', 'Pinned'),
            'created_by' => \Yii::t('app', 'Created by'),
            'updated_by' => \Yii::t('app', 'Updated by'),
            'created_at' => \Yii::t('app', 'Created at'),
            'updated_at' => \Yii::t('app', 'Updated at'),
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->forum->updateCounters(['threads' => 1]);
            $this->forum->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        $this->forum->fk_last_post = $this->forum->getPosts()->max('id');
        $this->forum->updateCounters(['threads' => -1]);
        $this->forum->updateCounters(['posts' => $this->posts * -1]);
        $this->forum->save();
        parent::afterDelete();
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
     * Gets query for [[Forum]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForum()
    {
        return $this->hasOne(Forum::class, ['id' => 'fk_forum']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['fk_thread' => 'id'])->orderBy('id ASC');
    }

    /**
     * Gets query for [[LastPost]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLastPost()
    {
        return $this->hasOne(Post::class, ['id' => 'fk_last_post']);
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
     * @return string slug
     */
    public function getSlug()
    {
        return \yii\helpers\Inflector::slug($this->name);
    }

    /**
     * Gets thread status options.
     *
     * @return string
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_INACTIVE => \Yii::t('app', 'Inactive'),
            self::STATUS_ACTIVE_LOCKED => \Yii::t('app', 'Locked'),
            self::STATUS_ACTIVE_UNLOCKED => \Yii::t('app', 'Unlocked'),
        ];
    }

    /**
     * Gets thread status in printable form.
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
     * @return ThreadQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ThreadQuery(get_called_class());
    }

    /**
     * @return bool whether thread is locked
     */
    public function isLocked(): bool
    {
        return $this->status === self::STATUS_ACTIVE_LOCKED;
    }

    /**
     * @return bool whether thread is pinned
     */
    public function isPinned(): bool
    {
        return $this->pinned === self::PINNED_TRUE;
    }

    /**
     * @return bool whether thread is active
     */
    public function isActive()
    {
        return $this->status !== self::STATUS_INACTIVE;
    }

    /**
     * Get SEO title
     *
     * @return string
     */
    public function getSeoTitle()
    {
        if (!empty($this->seo_title)) {
            return $this->seo_title;
        }

        return $this->name;
    }

    /**
     * Get SEO description
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seo_description;
    }

    /**
     * Get SEO keywords
     *
     * @return string
     */
    public function getSeoKeywords()
    {
        return $this->seo_keywords;
    }

    public function getUrl(array $params = []): string {
        return \yii\helpers\Url::to(['/' . ForumModule::getInstance()->id . '/thread/view', 'id' => $this->id, 'path' => $this->getSlug()] + $params);
    }
}
