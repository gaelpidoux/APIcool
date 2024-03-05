<?php

namespace App\Entity;

use App\Repository\RequestClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: RequestClientRepository::class)]
class RequestClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'requestClient')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DataClient $client = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getTOUT"])]
    #[Groups(["getrequest"])]

    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getTOUT"])]
    #[Groups(["getrequest"])]

    private ?string $tabledata = null;

    #[ORM\Column(length: 24)]
    #[Groups(["getTOUT"])]
    #[Groups(["getrequest"])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'request', fetch: 'EAGER',cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getTOUT"])]
    private ?StatsRequestClient $statsRequestClient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?DataClient
    {
        return $this->client;
    }

    public function setClient(DataClient $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getTabledata(): ?string
    {
        return $this->tabledata;
    }

    public function setTabledata(?string $tabledata): static
    {
        $this->tabledata = $tabledata;

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

    public function getStatsRequestClient(): ?StatsRequestClient
    {
        return $this->statsRequestClient;
    }
    
    public function setStatsRequestClient(?StatsRequestClient $statsRequestClient): static
    {
        $this->statsRequestClient = $statsRequestClient;
    
        return $this;
    }
}
