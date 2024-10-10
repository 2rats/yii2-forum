<?php

namespace rats\forum\models\form;

use rats\forum\models\Forum;
use rats\forum\models\Post;
use rats\forum\models\Thread;
use Yii;
use yii\base\Model;

class ThreadCreateForm extends Model
{
    private Forum $forum;

    public function __construct(Forum $forum, $config = [])
    {
        $this->forum = $forum;
        parent::__construct($config);
    }

    private ?Thread $newThread = null;

    public $name;
    public $content;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['content', 'name'], 'required'],
            [['name'], 'string', 'max' => 191],
            [['content', 'name'], 'string'],
            [['content'], 'validateMaxlength', 'params' => ['max' => 1000]],
            ['content', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
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
            'name' => \Yii::t('app', 'Name'),
            'content' => \Yii::t('app', 'Post text'),
        ];
    }

    public function save(): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        $thread = new Thread();
        $thread->fk_forum = $this->forum->id;
        $thread->status = Thread::STATUS_ACTIVE_UNLOCKED;
        $thread->name = $this->name;
        if ($thread->save()) {
            $post = new Post([
                'fk_thread' => $thread->id,
                'content' => $this->content,
                'status' => Post::STATUS_ACTIVE,
            ]);

            if ($post->save()) {
                $transaction->commit();
                $this->newThread = $thread;
                return true;
            }
        }


        $transaction->rollBack();
        return false;
    }

    public function getThread(): ?Thread
    {
        return $this->newThread;
    }
}
