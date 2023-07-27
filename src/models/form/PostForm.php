<?php

namespace rats\forum\models\form;

use rats\forum\models\Post;
use rats\forum\models\Thread;
use Yii;
use yii\base\Model;

/**
 * PostForm is the model behind the login form.
 *
 */
class PostForm extends Model
{
    public $fk_parent;
    public $fk_thread;
    public $content;

    public $_post;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
            [['content'], 'validateMaxlength', 'params' => ['max' => 1000]],
            ['content', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['fk_parent'], 'exist', 'skipOnError' => false, 'targetClass' => Post::class, 'targetAttribute' => ['fk_parent' => 'id']],
            [['fk_thread'], 'exist', 'skipOnError' => false, 'targetClass' => Thread::class, 'targetAttribute' => ['fk_thread' => 'id']],
        ];
    }

    public function validateMaxlength($attribute, $params)
    {
        if (mb_strlen($this->$attribute) > $params['max']) {
            $this->addError($attribute, Yii::t(
                'app',
                '{attribute} should contain at most {max, number} characters. (currently {current})',
                [
                    'attribute' => $this->attributeLabels()[$attribute],
                    'max' => $params['max'],
                    'current' => mb_strlen($this->$attribute),
                ]
            ));
        }
    }

    public function attributeLabels()
    {
        return [
            'content' => \Yii::t('app', 'Post text'),
        ];
    }

    public function addPost()
    {
        $post = new Post([
            'fk_parent' => $this->fk_parent,
            'fk_thread' => $this->fk_thread,
            'content' => $this->content,
            'status' => Post::STATUS_ACTIVE,
        ]);
        if ($post->save()) {
            $this->_post = $post;
            return true;
        }
        return false;
    }
}
