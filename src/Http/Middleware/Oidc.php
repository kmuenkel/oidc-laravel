<?php

namespace Oidc\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Oidc\Models\Entities\OidcStruct;

/**
 * Class Oidc
 * @package Oidc\Http\Middleware
 */
class Oidc
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('state')) {
            $oidcStruct = app(OidcStruct::class)
                ->setState($request->get('state', ''))
                ->setNonce($request->get('nonce', ''))
                ->setClientId($request->get('client_id', ''))
                ->setScope($request->get('scope', ''))
                ->setResponseType($request->get('response_type', ''))
                ->setLoginHint($request->get('login_hint', ''))
                ->setRedirect($request->get('redirect_uri', ''))
                ->setMessageHint($request->get('message_hint', ''));

            $request->route()->setParameter(OidcStruct::class, $oidcStruct);
        }

        return $next($request);
    }
}
