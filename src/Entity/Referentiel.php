<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "getAllReference" = {
 *              "path"="admin/referentiels" ,
 *              "method"="GET" ,
 *              "normalization_context"={"groups"={"referentiel:read"}}
 *          } ,
 *          "getAllreferencewithCompetence" = {
 *                "path"="/admin/referentiels/grpecompetences" ,
 *                 "method"="GET"  ,
 *                 "normalization_context"={"groups"={"referentielCompetence:read"}}
 *          } ,
 *          "postReferentiel" = {
 *               "path"="/admin/referentiels" ,
 *                "method"="POST"  ,
 *                "normalization_context"={"groups"={"postReferentiel:read"}}
 *          }
 *      } ,
 *     itemOperations={
 *          "getReferentielById"={
 *                 "path"="/admin/referentiels/{id}" ,
 *                "method"="GET"  ,
 *                "normalization_context"={"groups"={"getReferentielById:read"}}
 *          }
 *     }
 * )
 */
//,
// *         "getReferentielComptById"={
// *                 "path"="/admin/referentiels/{id}/grpecompetences/{id1}" ,
// *                "method"="GET"  ,
// *
// *                "normalization_context"={"groups"={"getReferentielComptById:read"}}
// *          }
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read","referentielCompetence:read","postReferentiel:read","getReferentielById:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     * @Groups({"referentiel:read","referentielCompetence:read","getReferentielById:read"})
     * @ApiSubresource
     */
    private $grpcompetence;

    public function __construct()
    {
        $this->grpcompetence = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGrpcompetence(): Collection
    {
        return $this->grpcompetence;
    }

    public function addGrpcompetence(GroupeCompetence $grpcompetence): self
    {
        if (!$this->grpcompetence->contains($grpcompetence)) {
            $this->grpcompetence[] = $grpcompetence;
        }

        return $this;
    }

    public function removeGrpcompetence(GroupeCompetence $grpcompetence): self
    {
        $this->grpcompetence->removeElement($grpcompetence);

        return $this;
    }
}
