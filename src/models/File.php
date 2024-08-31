<?php

namespace rats\forum\models;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use yii\helpers\Url;
use Yii;

/**
 * This is the model class for table "forum_file".
 *
 * @property int $id
 * @property string $filename
 * @property int|null $fk_user
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property User $fkUser
 */
class File extends \yii\db\ActiveRecord
{
    public const UPLOAD_PATH = 'uploads' . DIRECTORY_SEPARATOR;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum_file';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'fk_user',
                'updatedByAttribute' => false,
                'defaultValue' => 1,
            ],
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
            [['filename'], 'required'],
            [['fk_user'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['filename'], 'string', 'max' => 255],
            [['fk_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['fk_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'filename' => Yii::t('app', 'Filename'),
            'fk_user' => Yii::t('app', 'Fk User'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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

    public function getFileUrl()
    {
        return Url::to(['/' . self::UPLOAD_PATH . $this->filename], true);
    }
}
