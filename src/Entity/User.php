<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     * @var UuidInterface|null
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $login;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $avatar;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="integer", name="api_id")
     */
    private int $apiId;

    /**
     * @ORM\Column(type="string")
     */
    private string $provider;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $accessToken = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $roles = 'ROLE_USER';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\History", mappedBy="user", orphanRemoval=true)
     *
     * @var History[]|Collection<int, History>
     */
    private Collection $history;

    public function __construct(
        int $apiId,
        string $login,
        ?string $email,
        ?string $name,
        ?string $avatar,
        string $provider
    ) {
        $this->login = $login;
        $this->email = $email;
        $this->name = $name;
        $this->avatar = $avatar;
        $this->apiId = $apiId;
        $this->provider = $provider;

        $this->history = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getApiId(): int
    {
        return $this->apiId;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getHistory(): ArrayCollection
    {
        return $this->history;
    }

    public function updateToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getUsername(): string
    {
        return $this->getId()->toString();
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return explode(',', $this->roles);
    }

    public function getPassword(): void
    {
    }

    public function getSalt(): void
    {
    }

    public function eraseCredentials(): void
    {
    }
}
