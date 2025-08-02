<?php

declare(strict_types=1);

namespace Ofm\Security\Domain\Model;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * @param non-empty-string $userIdentifier
     * @param non-empty-string $clubId
     * @param array<string>    $roles
     */
    public function __construct(
        private string $userIdentifier,
        private string $clubId,
        private array $roles,
    ) {
    }

    public function getUserIdentifier(): string
    {
        return $this->userIdentifier;
    }

    public function getClubId(): string
    {
        return $this->clubId;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }
}
