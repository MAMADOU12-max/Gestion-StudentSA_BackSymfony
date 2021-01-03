<?php

namespace App\Entity;

use App\Repository\BriefRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BriefRepository::class)
 */
class Brief
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
    private $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $briefName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $context;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $livrableAttendu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modalite_pedagogique;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $critere_evaluation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagePromo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Archivage;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getBriefName(): ?string
    {
        return $this->briefName;
    }

    public function setBriefName(string $briefName): self
    {
        $this->briefName = $briefName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getLivrableAttendu(): ?string
    {
        return $this->livrableAttendu;
    }

    public function setLivrableAttendu(string $livrableAttendu): self
    {
        $this->livrableAttendu = $livrableAttendu;

        return $this;
    }

    public function getModalitePedagogique(): ?string
    {
        return $this->modalite_pedagogique;
    }

    public function setModalitePedagogique(string $modalite_pedagogique): self
    {
        $this->modalite_pedagogique = $modalite_pedagogique;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critere_evaluation;
    }

    public function setCritereEvaluation(string $critere_evaluation): self
    {
        $this->critere_evaluation = $critere_evaluation;

        return $this;
    }

    public function getImagePromo(): ?string
    {
        return $this->imagePromo;
    }

    public function setImagePromo(string $imagePromo): self
    {
        $this->imagePromo = $imagePromo;

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->Archivage;
    }

    public function setArchivage(bool $Archivage): self
    {
        $this->Archivage = $Archivage;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
