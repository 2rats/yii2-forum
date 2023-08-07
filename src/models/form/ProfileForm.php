<?php

namespace rats\forum\models\form;

use rats\forum\models\Post;
use rats\forum\models\Thread;
use rats\forum\models\User;
use Yii;
use yii\base\Model;

/**
 * ProfileForm is the model behind the profile update form.
 *
 */
class ProfileForm extends Model
{
    public $real_name;
    public $username;
    public $email;

    public $_profile;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'email', 'real_name'], 'string', 'max' => 191],
            [['username', 'email'], 'required'],
            [['real_name'], 'default'],
            [['email'], 'email'],
            [['username', 'email'], 'validateUnique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'real_name' => \Yii::t('app', 'Real name'),
            'username' => \Yii::t('app', 'Username'),
            'email' => \Yii::t('app', 'Email'),
        ];
    }

    public function init()
    {
        $this->_profile = User::find(Yii::$app->user->identity->id)->one();
        $this->real_name = $this->_profile->real_name;
        $this->username = $this->_profile->username;
        $this->email = $this->_profile->email;
    }

    public function validateUnique($attribute, $params)
    {
        $user = User::find()->where([$attribute => $this->$attribute])->andWhere(['!=', 'id', Yii::$app->user->identity->id])->one();
        if($user) {
            $this->addError($attribute, Yii::t(
                'yii',
                '{attribute} "{value}" has already been taken.',
                [
                    'attribute' => $user->attributeLabels()[$attribute],
                    'value' => $this->$attribute,
                ]
            ));
        }
    }

    

    public function save()
    {
        $profile = User::findOne(Yii::$app->user->id);
        $profile->username = $this->username;
        $profile->real_name = $this->real_name;
        $profile->email = $this->email;
        if ($profile->save()) {
            $this->_profile = $profile;
            return true;
        }
        return false;
    }
}
