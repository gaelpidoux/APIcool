<?php

namespace App\Entity;

use App\Repository\DEVCLIENTPRINCIPALVERIFRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DEVCLIENTPRINCIPALVERIFRepository::class)]
class DEVCLIENTPRINCIPALVERIF
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'VERIF_id', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?DEVCLIENTCFGVERIF $VERIF_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $Value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVERIFId(): ?DEVCLIENTCFGVERIF
    {
        return $this->VERIF_id;
    }

    public function setVERIFId(DEVCLIENTCFGVERIF $VERIF_id): static
    {
        $this->VERIF_id = $VERIF_id;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->Value;
    }

    public function setValue(string $Value): static
    {
        $this->Value = $Value;

        return $this;
    }
}
