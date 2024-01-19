<?php

namespace App\Http\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;
use Spatie\Csp\Scheme;

class Csp extends Basic
{
    public function configure()
    {
        parent::configure();

        $this->addDirective(
            Directive::STYLE,
            [
                'fonts.googleapis.com',
                'static.zdassets.com',
                'ekr.zdassets.com',
                'ekr.zendesk.com',
                'cmore.zendesk.com',
                '*.zopim.com',
                'zendesk-eu.my.sentry.io',
                Scheme::WSS . '//cmore.zendesk.com',
                Scheme::WSS . '//*.zopim.com',
                'cdn.jsdelivr.net', // Comments
                'maxcdn.bootstrapcdn.com', // Comments
                '*.hsforms.com',

                // Start login page
                'sha256-NerDAUWfwD31YdZHveMrq0GLjsNFMwxLpZl0dPUeCcw=',
                // Start login page

                // Start Zendesk
                'sha256-47DEQpj8HBSa+/TImW+5JCeuQeRkm5NMpJWZG3hSuFU=',
                'sha256-6uito/XfKGH6wYNy661OGqc3Rd6MOyQOZ2Gx1ePNlqw=',
                'sha256-ARFcK/p5kTf29SgYKVtm0SKy5+6VaWMJpRDFq7tlk/s=',
                'sha256-UtAv5b9/jfGqntWVjq04pRo+nTsje2UyXnRpfN+eO6g=',
                'sha256-LGNYc3AginjXXGzYcVuzVh9gBr8gZrSsVui/Suv9UoE=',
                'sha256-wydDOpwBFWZ4FyjJP7Tq2oCzBKbRU0yi+ukvM9sPyKU=',
                // Start Chat
                'sha256-0cKZ7NasE8Hv9XSsD/aAmn4JEt9C9vUseFQWp6DsKaY=',
                'sha256-+WqnFbk0G0wGfhlBz/QQwIWsnDEIGz2UsUNjwfZxfOk=',
                'sha256-N9Py0KkkgJNnpGTonkz2/YE+ho6Cst/KEfCVPiUxNwU=',
                'sha256-euZzKZ/OVg3VahCl2cjVQlLcN7IxUYShB4LlgDi9/KI=',
                'sha256-VUsJ4gCdT/6l9uvdudTza2T0So1X3Xa8QQvelDiAMEI=',
                'sha256-GQJqy2mhGBk7wgRGOg1hmyf3jLUm4+mSwjVnmqKwXjQ=',
                // End Chat
                // End Zendesk

                // Start morphdom/util.js [company-search]
                'sha256-tSETEqL9cqy/pOiPNHON9T2DAUqYM2U9mXcIayX2tIA=',

                // Start morphdom/util.js [modals-company-edit]
                'sha256-ct0VctZeN2NHCY4B6U7le9yxQbLipadwEZd3NWfJrcY=',
                'sha256-N6tSydZ64AHCaOWfwKbUhxXx2fRFDxHOaL3e3CO7GPI=',

                // Start morphdom/util.js [Subscribe]
                'sha256-Jc245MUcxLo0bxW5lnQa6U4kSSgRxAcwaKINRaUEx1g=',
                // End morphdom/util.js

                // Start morphdom/morphdom.js [Subscribe]
                'sha256-5FfDDKP0MV/tmM4J1ivBEuQWlHjFPGoYJpWMakSCIFg=',
                // End morphdom/morphdom.js

                // Start Contact Form
                'sha256-K9flzbVzWnVGoln6FNPgUlW32/n8CUJbkheAQ60Mu9s=',
                // 'sha256-6wP3V0Qa7Ca0uQvYwgaiN9WSe4xfYIVt+7YYFA1irDE=',
                // 'sha256-or+2WOnPBgKu8iT6B2GrpOMn7wnXFOIh+nQi9JOC9OE=',
                // End Contact Form

            ]
        );

        $this->addDirective(
            Directive::SCRIPT,
            [
                'static.zdassets.com',
                'ekr.zdassets.com',
                'ekr.zendesk.com',
                'cmore.zendesk.com',
                '*.zopim.com',
                'zendesk-eu.my.sentry.io',
                Scheme::WSS . '//cmore.zendesk.com',
                Scheme::WSS . '//*.zopim.com',
                Keyword::UNSAFE_EVAL,
                'js-eu1.hsforms.net', // Contact Form
                'cdn.jsdelivr.net', // Comments
                '*.hscollectedforms.net',
                '*.hsleadflows.net',
                '*.hsforms.net',
                '*.hs-scripts.com',
                '*.hs-analytics.net',
            ]
        );

        $this->addDirective(
            Directive::CONNECT,
            [
                'fonts.googleapis.com',
                'static.zdassets.com',
                'ekr.zdassets.com',
                'ekr.zendesk.com',
                'cmore.zendesk.com',
                '*.zopim.com',
                'zendesk-eu.my.sentry.io',
                'wss://cmore.zendesk.com',
                'wss://*.zopim.com',
                Keyword::UNSAFE_EVAL,
                Keyword::UNSAFE_INLINE,
                Scheme::HTTPS . '//js-eu1.hsforms.net', // Contact Form
                Scheme::HTTPS . '//forms-eu1.hsforms.com', // Contact Form
                Scheme::HTTPS . '//hubspot-forms-static-embed-eu1.s3.amazonaws.com',
                '*.hscollectedforms.net',
                '*.hsforms.com',
            ]
        );

        $this->addDirective(
            Directive::MEDIA,
            [
                'static.zdassets.com',
            ]
        );

        $this->addDirective(
            Directive::IMG,
            [
                'v2assets.zopim.io',
                'static.zdassets.com',
                '*.gravatar.com',
                'ui-avatars.com',
                Scheme::DATA,
                // Contact Form
                Scheme::HTTPS . '//js-eu1.hsforms.net',
                Scheme::HTTPS . '//forms-eu1.hsforms.com',
                Scheme::HTTPS . '//api.hubspot.com',
                Scheme::HTTPS . '//forms.hsforms.com',
                //End Form
                // Start Chat Zendesk
                Scheme::HTTPS . '//cmore.zendesk.com',
                Scheme::HTTPS . '//*.zdusercontent.com',
                // End Chat Zendesk
                '*.hsforms.net',
                '*.hsforms.com',
            ]
        );

        $this->addDirective(
            Directive::FONT,
            [
                Keyword::SELF,
                Scheme::DATA,
                'fonts.googleapis.com',
                'fonts.gstatic.com',
                'maxcdn.bootstrapcdn.com', // Comments
            ]
        );

        $this->addDirective(
            Directive::FRAME,
            [
                Keyword::SELF,
                'www.google.com',
                '*.hsforms.net',
                '*.hsforms.com',
            ]
        );
    }
}
