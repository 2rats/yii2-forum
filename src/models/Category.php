<?php

namespace rats\forum\models;

use rats\forum\models\query\CategoryQuery;

/**
 * This is the model class for table "forum_category".
 *
 * @property int          $id
 * @property string       $name
 * @property string|null  $description
 * @property int          $ordering
 * @property int          $status
 * @property int          $created_by
 * @property int          $updated_by
 * @property string|null  $created_at
 * @property string|null  $updated_at
 * @property User         $createdBy
 * @property ForumForum[] $forumForums
 * @property User         $updatedBy
 */
class Category extends ActiveRecord
{
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return 'forum_category';
    }

    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['ordering', 'created_by', 'updated_by'], 'integer'],
            [['ordering', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 191],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'name' => \Yii::t('app', 'Name'),
            'description' => \Yii::t('app', 'Description'),
            'ordering' => \Yii::t('app', 'Ordering'),
            'status' => \Yii::t('app', 'Status'),
            'created_by' => \Yii::t('app', 'Created by'),
            'updated_by' => \Yii::t('app', 'Updated by'),
            'created_at' => \Yii::t('app', 'Created at'),
            'updated_at' => \Yii::t('app', 'Updated at'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert && $this->isNewRecord) {
            $this->ordering = static::find()->max('ordering') + 1;
        }

        return parent::beforeSave($insert);
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
     * Gets query for [[Forums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForums()
    {
        return $this->hasMany(Forum::class, ['fk_category' => 'id'])->ordered();
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
     * {@inheritdoc}
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }


    /**
     * Gets category status options.
     *
     * @return string
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_INACTIVE => \Yii::t('app', 'Inactive'),
            self::STATUS_ACTIVE => \Yii::t('app', 'Active'),
        ];
    }

    /**
     * Gets status in printable form.
     *
     * @return string
     */
    public function printStatus()
    {
        $statuses = self::getStatusOptions();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : Yii::t('app', 'Unknown');
    }
}
