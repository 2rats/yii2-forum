<?php

namespace rats\forum\widgets;

use rats\forum\ForumModule;
use yii\helpers\Url;
use Yii;

class MarkdownEditor extends \rats\markdown\MarkdownEditor
{
    public function init()
    {
        parent::init();

        $this->uploadUrl = Url::to(['/' . ForumModule::getInstance()->id . '/post/upload-image']);
        
        $this->editorOptions = [
            'showIcons' => ['code', 'table', 'horizontal-rule', 'heading-1', 'heading-2', 'heading-3'],
            'hideIcons' => ['guide', 'side-by-side', 'heading', 'quote'],
            'status' => false,
            'insertTexts' => [
                'image' => ["![" . Yii::t('app', 'Image description') . "](https://", ")"],
                'link' => ["[" . Yii::t('app', 'Link text'), "](https://)"],
                'table' => ["", "\n\n| Text | Text | Text |\n|------|------|------|\n| Text | Text | Text |\n"],
            ],
            'spellChecker' => false,
            'toolbarTips' => false,
            'placeholder' => Yii::t('app', 'Post text') . '..',
        ];
    }
}