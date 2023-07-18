<?php

namespace rats\forum\models;

use Yii;

/**
 * This is the model class for table "forum_thread".
 *
 * @property int $id
 * @property int $fk_forum
 * @property string $name
 * @property int $status
 * @property int $views
 * @property int $pinned
 * @property int $created_by
 * @property int $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property User $createdBy
 * @property Forum $forum
 * @property Post[] $posts
 * @property User $updatedBy
 */
class Thread extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const PINNED_FALSE = 0;
    const PINNED_TRUE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum_thread';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_forum', 'name', 'created_by', 'updated_by'], 'required'],
            [['fk_forum', 'status', 'views', 'pinned', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 191],
            [['fk_forum'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::class, 'targetAttribute' => ['fk_forum' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fk_forum' => Yii::t('app', 'Forum'),
            'name' => Yii::t('app', 'Name'),
            'status' => Yii::t('app', 'Status'),
            'views' => Yii::t('app', 'Views'),
            'pinned' => Yii::t('app', 'Pinned'),
            'created_by' => Yii::t('app', 'Created by'),
            'updated_by' => Yii::t('app', 'Updated by'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
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
        return $this->hasOne(Post::class, ['fk_thread' => 'id'])
            ->orderBy('created_at DESC');
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
     * @return String slug
     */
    public function getSlug()
    {
        return \yii\helpers\Inflector::slug($this->name);
    }
}
