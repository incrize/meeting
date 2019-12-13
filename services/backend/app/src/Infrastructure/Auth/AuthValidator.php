<?php
declare(strict_types=1);

namespace Meeting\Infrastructure\Auth;


use Spiral\Boot\EnvironmentInterface;

class AuthValidator
{
    /**
     * @var \Spiral\Boot\EnvironmentInterface
     */
    protected $environment;

    public function __construct(EnvironmentInterface $environment)
    {
        $this->environment = $environment;
    }

    public function validate(AuthRequest $request)
    {
        $secretKey = sprintf('API_CLIENT_%s_SECRET', strtolower($request->getField('username')));

        if (!$this->environment->get($secretKey)) {
            return false;
        }

        return $this->environment->get($secretKey) === $request->getField('password');
    }
}