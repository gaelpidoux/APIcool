<?php

namespace App\Entity;

use App\Repository\StatsRequestClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatsRequestClientRepository::class)]
class StatsRequestClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'statsRequestClient', targetEntity: RequestClient::class)]
    private Collection $request;

    #[ORM\Column(nullable: true)]
    #[Groups(["getStats", "getALLClient"])]
    private ?int $naming = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getStats", "getALLClient"])]
    private ?string $statsnaming = null;

    public function __construct()
    {
        $this->request = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, RequestClient>
     */
    public function getRequest(): Collection
    {
        return $this->request;
    }

    public function addRequest(RequestClient $request): static
    {
        if (!$this->request->contains($request)) {
            $this->request->add($request);
            $request->setStatsRequestClient($this);
        }

        return $this;
    }

    public function removeRequest(RequestClient $request): static
    {
        if ($this->request->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getStatsRequestClient() === $this) {
                $request->setStatsRequestClient(null);
            }
        }

        return $this;
    }

    public function getNaming(): ?int
    {
        return $this->naming;
    }

    public function setNaming(?int $naming): static
    {
        $this->naming = $naming;

        return $this;
    }

    public function getStatsnaming(): ?string
    {
        return $this->statsnaming;
    }

    public function setStatsnaming(?string $statsnaming): static
    {
        $this->statsnaming = $statsnaming;

        return $this;
    }
}
