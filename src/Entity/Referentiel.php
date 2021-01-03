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
     * @Groups({"referentiel:read","referentielCompetence:read","postReferentiel:read","getReferentielById:read",
     *     "getAllpromo:read","getAllpromoprincipal:read","getallgroupe:read","getPromoId:read","getPromoprincipalbyId:read",
     *     "getPromorefbyId:read","getPromoFormateurById:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     * @Groups({"referentiel:read","referentielCompetence:read","getReferentielById:read","getPromorefbyId:read","getPromoRefbyId:read",
     *  "getPromoRefbAppreneaAttenteById:read"})
     * @ApiSubresource
     */
    private $grpcompetence;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiels")
     */
    private $promos;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $programme;

    public function __construct()
    {
        $this->grpcompetence = new ArrayCollection();
        $this->promos = new ArrayCollection();
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

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
            $promo->setReferentiels($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            // set the owning side to null (unless already changed)
            if ($promo->getReferentiels() === $this) {
                $promo->setReferentiels(null);
            }
        }

        return $this;
    }

    public function getProgramme()
    {
        return $this->programme;
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

        return $this;
    }
}
