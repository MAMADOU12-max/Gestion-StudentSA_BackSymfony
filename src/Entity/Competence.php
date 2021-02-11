<?php

namespace App\Entity;

use App\Repository\CompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "getAllcompetences"=  {
 *              "path"="admin/competences" ,
 *              "method"="GET",
 *              "normalization_context"={"groups"={"competence:read"}}
 *          } ,
 *           "postCompetence"=  {
 *              "path"="admin/competences" ,
 *              "method"="POST",
 *              "denormalization_context"={"groups"={"postcompetence:write"}}
 *          }
 *     } ,
 *     itemOperations={
 *          "getcompetenceById"=  {
 *              "path"="admin/competences/{id}" ,
 *              "method"="GET",
 *              "normalization_context"={"groups"={"competencebyid:read"}}
 *          }
 *     }
 * )
 */
class Competence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
      * @Groups({"grpecompetenceCompetence:read","grpecompetenceCompetenceById:read",
     *     "getGroupecompetenceById:read","competence:read","competencebyid:read",
     *"postcompetence:write","referentielCompetence:read","getPromoRefbyId:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpecompetenceCompetence:read","grpecompetenceCompetenceById:read",
     *     "getGroupecompetenceById:read","competence:read","competencebyid:read","postcompetence:write",
     *      "referentielCompetence:read","getPromoRefbyId:read"})
     * @Assert\NotBlank
     */
    private $nomCompetence;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="competences")
     */
    private $grpe_competence;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence",cascade={"persist"})
     *@ApiSubresource()
     *  @Groups({"competence:read","competencebyid:read","getGroupecompetenceById:read","postcompetence:write"})
     * @Assert\Count(
     *      min = 3,
     *      max = 3,
     *      minMessage = "You must specify at three levels",
     *      maxMessage = "You must specify at three levels"
     * )
     */
    private $niveaux;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
       * @Groups({"grpecompetenceCompetence:read","grpecompetenceCompetenceById:read","postgrpecompetence:read",
     *     "getGroupecompetenceById:read","competence:read","competencebyid:read","postcompetence:write","referentielCompetence:read","getPromoRefbyId:read"})
     */
    private $libelle;

    public function __construct()
    {
        $this->grpe_competence = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCompetence(): ?string
    {
        return $this->nomCompetence;
    }

    public function setNomCompetence(string $nomCompetence): self
    {
        $this->nomCompetence = $nomCompetence;

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGrpeCompetence(): Collection
    {
        return $this->grpe_competence;
    }

    public function addGrpeCompetence(GroupeCompetence $grpeCompetence): self
    {
        if (!$this->grpe_competence->contains($grpeCompetence)) {
            $this->grpe_competence[] = $grpeCompetence;
        }

        return $this;
    }

    public function removeGrpeCompetence(GroupeCompetence $grpeCompetence): self
    {
        $this->grpe_competence->removeElement($grpeCompetence);

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
}
