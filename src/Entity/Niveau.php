<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NiveauRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 * @UniqueEntity("email")
 * @ApiResource()
 */
class Niveau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"competencebyid:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"competence:read","competencebyid:read","getGroupecompetenceById:read","postcompetence:write"})
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity=Competence::class, inversedBy="niveaux",cascade={"persist"})
     */
    private $competence;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *  @Groups({"competence:read","competencebyid:read","getGroupecompetenceById:read","postcompetence:write"})
     */
    private $criteredEvaluation;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"competence:read","competencebyid:read","getGroupecompetenceById:read","postcompetence:write"})
     */
    private $groupedAction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    public function setCompetence(?Competence $competence): self
    {
        $this->competence = $competence;

        return $this;
    }

    public function getCriteredEvaluation(): ?string
    {
        return $this->criteredEvaluation;
    }

    public function setCriteredEvaluation(?string $criteredEvaluation): self
    {
        $this->criteredEvaluation = $criteredEvaluation;

        return $this;
    }

    public function getGroupedAction(): ?string
    {
        return $this->groupedAction;
    }

    public function setGroupedAction(string $groupedAction): self
    {
        $this->groupedAction = $groupedAction;

        return $this;
    }
}
