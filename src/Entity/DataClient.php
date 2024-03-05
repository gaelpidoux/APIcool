<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\DataClientRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: DataClientRepository::class)]
class DataClient implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getClient"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClient"])]
    private ?string $login = null;

    /**
      * @var string The hashed password
      */
    #[ORM\Column(length: 255)]
    #[Groups(["getClient"])]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(["getClient"])]
    private ?int $port = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClient"])]
    private ?string $ip = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getClient"])]
    private ?string $TTL = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClient"])]
    private ?string $databaseclient = null;

      /**
     * @var Collection<RequestClient>
     */

    #[ORM\OneToMany(mappedBy: 'client',targetEntity: RequestClient::class, cascade: ['persist', 'remove'])]
    private Collection $requestClient;

    #[ORM\Column(length: 24)]
    #[Groups(["getClient"])]
    private ?string $status = 'on';

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

    // public function setRequestClient(?RequestClient $requestClient): static
    // {
    //     $this->requestClient = $requestClient;

    //     return $this;
    // }
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
}
