<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

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
 *          "addReferentiel" = {
 *                "route_name"="addReferentiel" ,
 *                "normalization_context"={"groups"={"postReferentiel:read"}}
 *          }
 *      } ,
 *     itemOperations={
*          "deleteReferentielById"={
*                 "path"="/admin/referentiels/{id}" ,
*                "method"="DELETE"  ,
*                "normalization_context"={"groups"={"deleteReferentielById:read"}}
*          },
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
     * @Groups({"referentiel:read","referentielCompetence:read","getReferentielById:read",
     *     "getAllpromo:read","getAllpromoprincipal:read","getallgroupe:read","getPromoId:read","getPromoprincipalbyId:read",
     *     "getPromorefbyId:read","getPromoFormateurById:read"})
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
     *  "getPromoRefbAppreneaAttenteById:read","postReferentiel:read"})
     * @ApiSubresource
     */
    private $grpcompetence;

    /**
     * @ORM\OneToMany(targetEntity=Promo::class, mappedBy="referentiels")
     */
    private $promos;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups({"referentielCompetence:read","postReferentiel:read","getReferentielById:read",
     *          "referentiel:read"})
     */
    private $programme;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"referentiel:read","referentielCompetence:read","postReferentiel:read","getReferentielById:read",
     *     "getAllpromo:read","getAllpromoprincipal:read","getallgroupe:read","getPromoId:read","getPromoprincipalbyId:read",
     *     "getPromorefbyId:read","getPromoFormateurById:read"})
     */
    private $critereDevaluation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"referentiel:read","referentielCompetence:read","postReferentiel:read","getReferentielById:read",
           *     "getAllpromo:read","getAllpromoprincipal:read","getallgroupe:read","getPromoId:read","getPromoprincipalbyId:read",
           *     "getPromorefbyId:read","getPromoFormateurById:read"})
     */
    private $critereDadmission;

    /**
     * @ORM\Column(type="string", length=255)
    * @Groups({"referentiel:read","referentielCompetence:read","postReferentiel:read","getReferentielById:read",
     *     "getAllpromo:read","getAllpromoprincipal:read","getallgroupe:read","getPromoId:read","getPromoprincipalbyId:read",
     *     "getPromorefbyId:read","getPromoFormateurById:read"})
     */
    private $presentation;

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
        if ($this->programme) {
            $data = stream_get_contents($this->programme);
            if (!$this->programme) {

                fclose($this->programme);
            }


            return base64_encode($data);
        }
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereDevaluation(): ?string
    {
        return $this->critereDevaluation;
    }

    public function setCritereDevaluation(?string $critereDevaluation): self
    {
        $this->critereDevaluation = $critereDevaluation;

        return $this;
    }

    public function getCritereDadmission(): ?string
    {
        return $this->critereDadmission;
    }

    public function setCritereDadmission(?string $critereDadmission): self
    {
        $this->critereDadmission = $critereDadmission;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }
}
