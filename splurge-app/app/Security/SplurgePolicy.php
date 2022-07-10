<?php

namespace App\Security;

use Spatie\Csp\Policies\Policy;

use Spatie\Csp\Directive;

use Spatie\Csp\Keyword;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;


class SplurgePolicy extends Policy {

    public function configure() {
        
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF)
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, Keyword::SELF)
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, Keyword::SELF)
            ->addDirective(Directive::STYLE, Keyword::SELF)
            ->addNonceForDirective(Directive::SCRIPT);
    


        $this->addDirective(Directive::SCRIPT,
         ['www.google.com', 'apps.elfsight.com', 'kit.fontawesome.com', 'static.elfsight.com']);

         $this->addDirective(Directive::CONNECT, ['*.fontawesome.com', '*.googleapis.com', 'apps.elfsight.com']);


         $this->addDirective(Directive::FONT,
         ['*.googleapis.com', 'fonts.gstatic.com', 'ka-f.fontawesome.com']);

         $this->addDirective(Directive::STYLE, ['*.googleapis.com', '*.fontawesome.com']);


         $this->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE);
         
    }

    public function shouldBeApplied(Request $request, Response $response): bool
    {
        if (config('app.debug') && ($response->isClientError() || $response->isServerError())) {
            return false;
        }

        return parent::shouldBeApplied($request, $response);
    }

}

