<?php

namespace Oidc\Http\Requests;

use Oidc\Models\Entities\OidcStruct;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OidcRequest
 * @package Oidc\Http\Requests
 */
class OidcRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'state' => 'required|string',
            'nonce' => 'required|string',
            'client_id' => 'string',
            'scope' => 'string',
            'response_type' => 'string',
            'login_hint' => 'string',
            'redirect_uri' => 'required|string',
            'message_hint' => 'string'
        ];
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->loginStateExists() && $this->userIsRequester();
    }

    /**
     * @return bool
     */
    protected function userIsRequester(): bool
    {
        $oidc = $this->getOidc();
        $loginHint = $oidc ? $oidc->getLoginHint() : '';

        return $loginHint == $this->getUserId();
    }

    /**
     * @return bool
     */
    protected function loginStateExists(): bool
    {
        $oidc = $this->getOidc();

        return $oidc && $oidc->getState();
    }

    /**
     * @return OidcStruct|null
     */
    protected function getOidc(): ?OidcStruct
    {
        $route = $this->route();

        return $route ? ($route->parameters()[OidcStruct::class] ?? null) : null;
    }

    /**
     * @return string|int
     */
    protected function getUserId()
    {
        $user = $this->user();

        return $user ? $user->getAuthIdentifier() : '';
    }
}
