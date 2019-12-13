<?php

declare(strict_types=1);

namespace Meeting\Infrastructure\Controller;

use Psr\Http\Message\ResponseInterface;
use Spiral\Auth\AuthContextInterface;
use Spiral\Http\Request\InputManager;
use Spiral\Http\ResponseWrapper;

class ApiController
{
    /**
     * @var \Spiral\Http\Request\InputManager
     */
    protected $input;

    /**
     * @var \Spiral\Http\ResponseWrapper
     */
    protected $response;

    /** @var \Spiral\Auth\AuthContextInterface */
    protected $auth;

    public function __construct(AuthContextInterface $auth, InputManager $input, ResponseWrapper $response)
    {
        $this->auth = $auth;
        $this->input = $input;
        $this->response = $response;
    }

    public function index(): ResponseInterface
    {
        return $this->response->json([
            'some' => 'json',
            'actor' => $this->auth->getActor()
        ]);
    }
}