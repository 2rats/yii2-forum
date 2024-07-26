<?php

namespace rats\forum\models;

use Yii;
use rats\forum\models\Forum;
use rats\forum\models\User;

/**
 * This is the model class for table "forum_moderator".
 *
 * @property int $id
 * @property int $fk_forum
 * @property int $fk_user
 *
 * @property Forum $magazine
 * @property User $user
 */
class ForumModerator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forum_moderator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_forum', 'fk_user'], 'required'],
            [['fk_forum', 'fk_user'], 'integer'],
            [['fk_forum'], 'exist', 'skipOnError' => true, 'targetClass' => Forum::class, 'targetAttribute' => ['fk_forum' => 'id']],
            [['fk_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['fk_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_forum' => 'Fk Forum',
            'fk_user' => 'Uživatel',
        ];
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'fk_user']);
    }
}
