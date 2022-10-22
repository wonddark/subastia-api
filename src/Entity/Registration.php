<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\RegistrationRepository;
use App\State\ActivateAccount;
use App\State\CreateRegistration;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
#[ApiResource]
#[Get(
    uriTemplate: "/registration/check/{id}",
    outputFormats: "json",
    normalizationContext: [
        "groups" => ["registration:get:read"]
    ],
    denormalizationContext: [
        "groups" => ["registration:get:write"]
    ]
)]
#[Post(
    uriTemplate: "/register",
    inputFormats: "json",
    outputFormats: "json",
    normalizationContext: [
        "groups" => ["registration:post:read"]
    ],
    denormalizationContext: [
        "groups" => ["registration:post:write"]
    ],
    processor: CreateRegistration::class
)]
#[Patch(
    uriTemplate: "/registration/activate/{id}",
    outputFormats: "json",
    normalizationContext: [
        "groups" => ["registration:patch:read"]
    ],
    denormalizationContext: [
        "groups" => ["registration:patch:write"]
    ],
    processor: ActivateAccount::class
)]
class Registration
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups([
        "registration:get:read",
        "registration:post:write",
        "registration:post:read"
    ])]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(["registration:post:write"])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 16)]
    private ?string $password = null;

    #[ORM\Column]
    private ?DateTimeImmutable $registeredAt;

    #[ORM\Column(length: 255)]
    #[Groups([
        "registration:get:read",
        "registration:post:write",
        "registration:post:read"
    ])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["registration:post:write"])]
    #[Assert\NotBlank]
    private ?string $lastname = null;

    #[ORM\Column]
    #[Groups(["registration:get:read", "registration:patch:read"])]
    private ?bool $isActive = false;

    public function __construct()
    {
        $this->registeredAt = new DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRegisteredAt(): ?DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(DateTimeImmutable $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
