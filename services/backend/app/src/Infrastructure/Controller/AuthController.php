<?php

declare(strict_types=1);

namespace Meeting\Infrastructure\Controller;

use Meeting\Infrastructure\Auth\AuthRequest;
use Meeting\Infrastructure\Auth\AuthValidator;
use Spiral\Auth\AuthContextInterface;
use Spiral\Auth\TokenStorageInterface;

class AuthController
{
    /**
     * @var \Spiral\Auth\AuthContextInterface
     */
    protected $authContext;

    /**
     * @var \Meeting\Infrastructure\Auth\AuthValidator
     */
    protected $authValidator;

    /**
     * @var \Spiral\Auth\TokenStorageInterface
     */
    protected $tokenStorage;

    public function __construct(AuthContextInterface $authContext, TokenStorageInterface $tokenStorage, AuthValidator $authValidator)
    {
        $this->authContext = $authContext;
        $this->authValidator = $authValidator;
        $this->tokenStorage = $tokenStorage;
    }

    public function index(AuthRequest $request)
    {
        if (!$request->isValid()) {
            return [
                'status' => 400,
                'errors' => $request->getErrors()
            ];
        }

        if (!$this->authValidator->validate($request)) {
            return ['status' => 403];
        }

        $token = $this->tokenStorage->create(['client' => $request->getField('username')]);

        $this->authContext->start($token);

        return [
            'status'    => 200,
            'token'     => $token->getID(),
            'expiredAt' => $token->getExpiresAt()
        ];
    }
}