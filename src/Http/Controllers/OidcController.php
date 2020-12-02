<?php

namespace Oidc\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Oidc\Http\Requests\OidcRequest;
use Oidc\Models\Entities\OidcStruct;
use Oidc\Http\Middleware\SoftRedirect;

/**
 * Class OidcController
 * @package Oidc\Http\Controllers
 */
class OidcController extends Controller
{
    /**
     * Refer the OIDC login redirect back to the controller method that initiated it,
     * this time, with the necessary state and nonce values needed to proceed with the target request.
     *
     * @param OidcRequest $request
     * @param OidcStruct $oidc
     * @return Response
     */
    public function auth(OidcRequest $request, OidcStruct $oidc)
    {
        $data = json_decode($oidc->getMessageHint(), true);
        $uri = route($data['route'], $data['params']);

        return SoftRedirect::response($uri, 'GET', $request->all() + $data['query']);
    }
}
