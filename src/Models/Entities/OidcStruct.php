<?php

namespace Oidc\Models\Entities;

use Oidc\Models\Contracts\Oidc;

/**
 * Class OidcStruct
 * @package Oidc\Models\Entities
 */
class OidcStruct implements Oidc
{
    /**
     * @var string
     */
    protected $state = '';

    /**
     * @var string
     */
    protected $nonce = '';

    /**
     * @var string
     */
    protected $redirect = '';

    /**
     * @var string
     */
    protected $clientId = '';

    /**
     * @var string
     */
    protected $scope = '';

    /**
     * @var string
     */
    protected $responseType = '';

    /**
     * @var string
     */
    protected $loginHint = '';

    /**
     * @var string
     */
    protected $messageHint = '';

    /**
     * @param Oidc $struct
     * @return static
     */
    public static function from(Oidc $struct)
    {
        return (new static)
            ->setNonce($struct->getNonce())
            ->setRedirect($struct->getRedirect())
            ->setState($struct->getState())
            ->setMessageHint($struct->getMessageHint())
            ->setLoginHint($struct->getLoginHint())
            ->setScope($struct->getScope())
            ->setResponseType($struct->getResponseType())
            ->setClientId($struct->getClientId());
    }

    /**
     * @inheritDoc
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * @inheritDoc
     */
    public function getRedirect(): string
    {
        return $this->redirect;
    }

    /**
     * @inheritDoc
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @inheritDoc
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @inheritDoc
     */
    public function getResponseType(): string
    {
        return $this->responseType;
    }

    /**
     * @param string $nonce
     * @return $this
     */
    public function setNonce(string $nonce): self
    {
        $this->nonce = $nonce;

        return $this;
    }

    /**
     * @param string $redirect
     * @return $this
     */
    public function setRedirect(string $redirect): self
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * @param string $state
     * @return $this
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @param string $clientId
     * @return $this
     */
    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @param string $scope
     * @return $this
     */
    public function setScope(string $scope): self
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @param string $responseType
     * @return $this
     */
    public function setResponseType(string $responseType): self
    {
        $this->responseType = $responseType;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLoginHint(): string
    {
        return $this->loginHint;
    }

    /**
     * @param string $loginHint
     * @return $this
     */
    public function setLoginHint(string $loginHint): self
    {
        $this->loginHint = $loginHint;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessageHint(): string
    {
        return $this->messageHint;
    }

    /**
     * @param string $messageHint
     * @return $this
     */
    public function setMessageHint(string $messageHint): self
    {
        $this->messageHint = $messageHint;

        return $this;
    }
}
