<?php

namespace rats\forum\models;

/**
 * This is the model class for table "forum_category".
 *
 * @property int          $id
 * @property string       $name
 * @property string|null  $description
 * @property int          $ordering
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
    public static function tableName()
    {
        return 'forum_category';
    }

    public function rules()
    {
        return [
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
        return $this->hasMany(Forum::class, ['fk_category' => 'id'])->orderBy(['ordering' => SORT_ASC]);
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
}
