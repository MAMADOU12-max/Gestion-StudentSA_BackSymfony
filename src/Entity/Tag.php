<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomTag;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTag(): ?string
    {
        return $this->nomTag;
    }

    public function setNomTag(string $nomTag): self
    {
        $this->nomTag = $nomTag;

        return $this;
    }

    public function getGrpTagu(): ?GroupeTag
    {
        return $this->grpTagu;
    }

    public function setGrpTagu(?GroupeTag $grpTagu): self
    {
        $this->grpTagu = $grpTagu;

        return $this;
    }
}
