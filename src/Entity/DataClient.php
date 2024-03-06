<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\DataClientRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DataClientRepository::class)]
/**
* @UniqueEntity(fields={"login", "password", "databaseclient"}, message="Cette combinaison existe déjà.")
*/
class DataClient implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getClient"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique:true)]
    #[Groups(["getClient"])]
    #[Assert\NotBlank(message: "Un login ne peut être null")]
    #[Assert\NotNull(message: "Un login ne peut être null")]
    private ?string $login = null;

    /**
      * @var string The hashed password
      */
    #[ORM\Column(length: 255,unique:true)]
    #[Groups(["getClient"])]
    #[Assert\NotBlank(message: "Un password ne peut être null")]
    #[Assert\NotNull(message: "Un password ne peut être null")]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(["getClient"])]
    #[Assert\NotBlank(message: "Un port ne peut être null")]
    #[Assert\NotNull(message: "Un port ne peut être null")]
    private ?int $port = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClient"])]
    #[Assert\NotBlank(message: "Un ip ne peut être null")]
    #[Assert\NotNull(message: "Un ip ne peut être null")]
    private ?string $ip = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getClient"])]
    private ?string $TTL = null;

    #[ORM\Column(length: 255,unique:true)]
    #[Groups(["getClient"])]
    #[Assert\NotBlank(message: "Un database ne peut être null")]
    #[Assert\NotNull(message: "Un database ne peut être null")]
    private ?string $databaseclient = null;

    /**
     * @var Collection<RequestClient>
     */

    #[ORM\OneToMany(mappedBy: 'client',targetEntity: RequestClient::class, cascade: ['persist', 'remove'])]
    private Collection $requestClient;

    #[ORM\Column(length: 24)]
    #[Groups(["getClient"])]
    private ?string $status = 'on';

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->requestClient = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(int $port): static
    {
        $this->port = $port;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): static
    {
        $this->ip = $ip;

        return $this;
    }

    public function getTTL(): ?string
    {
        return $this->TTL;
    }

    public function setTTL(?string $TTL): static
    {
        $this->TTL = $TTL;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRequestClient(): Collection
    {
        return $this->requestClient;
    }

    public function setRequestClient(Collection $requestClient): static
{
    $this->requestClient = $requestClient;

    return $this;
}


    public function getDatabaseclient(): ?string
    {
        return $this->databaseclient;
    }

    public function setDatabaseclient(string $databaseclient): static
    {
        $this->databaseclient = $databaseclient;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
