<?php

namespace Oidc\Models\Contracts;

/**
 * Interface Oidc
 * @package Oidc\Models\Contracts
 */
interface Oidc
{
    /**
     * @return string
     */
    public function getState(): string;

    /**
     * @return string
     */
    public function getNonce(): string;

    /**
     * @return string
     */
    public function getRedirect(): string;

    /**
     * @return string
     */
    public function getClientId(): string;

    /**
     * @return string
     */
    public function getScope(): string;

    /**
     * @return string
     */
    public function getResponseType(): string;

    /**
     * @return string
     */
    public function getLoginHint(): string;

    /**
     * @return string
     */
    public function getMessageHint(): string;
}
