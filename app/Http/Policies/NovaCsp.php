<?php

namespace App\Http\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Policy;
use Spatie\Csp\Scheme;

class NovaCsp extends Policy
{
    public function configure()
    {
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF)
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, [
                Keyword::SELF,
                'www.gravatar.com',
                Scheme::DATA,
            ])
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE);

        $this->addDirective(
            Directive::STYLE,
            [
                Keyword::SELF,
                Keyword::UNSAFE_HASHES,
                Keyword::UNSAFE_INLINE,
            ]
        );

        $this->addDirective(
            Directive::SCRIPT,
            [
                Keyword::SELF,
                Keyword::UNSAFE_HASHES,
                Keyword::UNSAFE_INLINE,
            ]
        );

        $this->addDirective(
            Directive::FONT,
            [
                Keyword::SELF,
                Scheme::DATA,
            ]
        );
    }
}
