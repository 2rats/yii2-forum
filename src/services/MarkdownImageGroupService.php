<?php

namespace rats\forum\services;

use yii\helpers\Markdown;

class MarkdownImageGroupService
{
    public function groupImages(string $content): string
    {
        $group = false;
        $buffer = '';

        foreach (explode("\n", $content) as $line) {
            if ($this->isImageLine($line)) {
                if (!$group) {
                    $group = true;
                    $buffer .= '<div class="image-group">';
                }
                $buffer .=  Markdown::process($line, 'gfm-comment');
            }
            else {
                if ($group && !empty($line)) {
                    $group = false;
                    $buffer .= '</div>';
                }
                $buffer .= !empty($line) ? $line . "\n" : "\n";
            }
        }

        if ($group) {
            $buffer .= '</div>';
        }

        return $buffer;
    }

    private function isImageLine(string $line): bool
    {
        return preg_match('/!\[.*\]\((.*)\)/', $line) === 1;
    }
}
