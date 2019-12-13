<?php
declare(strict_types=1);

namespace Meeting\Infrastructure\Auth;


use Spiral\Auth\ActorProviderInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Boot\EnvironmentInterface;

class AuthActorProvider implements ActorProviderInterface
{
    /**
     * @var \Spiral\Boot\EnvironmentInterface
     */
    protected $environment;

    public function __construct(EnvironmentInterface $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @inheritDoc
     */
    public function getActor(TokenInterface $token): ?object
    {
        $payload = $token->getPayload();

        if (!isset($payload['client'])) {
            return null;
        }

        $secretKey = sprintf('API_CLIENT_%s_SECRET', strtolower($payload['client']));
        $rolesKey = sprintf('API_CLIENT_%s_ROLES', strtolower($payload['client']));

        if (!$this->environment->get($secretKey)) {
            return null;
        }

        return new Actor(explode(',', $this->environment->get($rolesKey, '')));
    }
}