<?php

namespace rats\forum\services;

use yii\helpers\Markdown;

class MarkdownImageGroupService
{
    public function groupImages(string $content): string
    {
        $group = false;
        $buffer = '';

        // wrap every image tag with new lines
        $content = preg_replace_callback('/!\[([^\]]+)\]\(([^\)]+)\)/', function ($matches) {
            return "\n\n" . $matches[0] . "\n";
        }, $content);

        foreach (explode("\n", $content) as $line) {
            if ($this->isImageLine($line)) {
                if (!$group) {
                    $group = true;
                    $buffer .= '<div class="image-group">';
                }
                $buffer .=  Markdown::process($line, 'gfm-comment');
            } else {
                if ($group && !empty(preg_replace('/\s+/', '', $line))) {
                    $group = false;
                    $buffer .= "</div>\n";
                }
                $buffer .= !empty($line) ? $line . "\n" : "\n";
            }
        }

        if ($group) {
            $buffer .= "</div>\n";
        }

        return $buffer;
    }

    private function isImageLine(string $line): bool
    {
        return preg_match('/!\[.*\]\((.*)\)/', $line) === 1;
    }
}
