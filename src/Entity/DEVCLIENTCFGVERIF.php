<?php

namespace App\Entity;

use App\Repository\DEVCLIENTCFGVERIFRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DEVCLIENTCFGVERIFRepository::class)]
class DEVCLIENTCFGVERIF
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Type = null;

    #[ORM\OneToOne(mappedBy: 'VERIF_id', cascade: ['persist', 'remove'])]
    private ?DEVCLIENTPRINCIPALVERIF $Verif_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hint = null;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getVerifId(): ?DEVCLIENTPRINCIPALVERIF
    {
        return $this->Verif_id;
    }

    public function setVerifId(DEVCLIENTPRINCIPALVERIF $Verif_id): static
    {
        $this->Verif_id = $Verif_id;

        return $this;
    }

    public function getHint(): ?string
    {
        return $this->hint;
    }

    public function setHint(?string $hint): static
    {
        $this->hint = $hint;

        return $this;
    }
}
