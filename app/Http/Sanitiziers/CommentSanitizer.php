<?php

namespace App\Http\Sanitiziers;


use Spatie\Comments\Support\CommentSanitizer as Comments;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class CommentSanitizer extends Comments
{
    public function sanitize(string $text): string
    {
        $config = (new HtmlSanitizerConfig())
            ->allowRelativeLinks()
            ->allowRelativeMedias()
            ->allowSafeElements()
            ->dropElement('img')
            ->dropElement('a');

        $sanitizer = new HtmlSanitizer($config);

        return $sanitizer->sanitize($text);
    }
}
