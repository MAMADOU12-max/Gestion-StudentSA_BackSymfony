<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "getAllpromo"= {
 *                "path"="/admin/promo" ,
 *                 "method"="GET" ,
 *                 "normalization_context"={"groups"={"getAllpromo:read"}}
 *           } ,
 *           "getAllpromoPrincipal"= {
 *                "path"="/admin/promo/principal" ,
 *                 "method"="GET" ,
 *                 "normalization_context"={"groups"={"getAllpromoprincipal:read"}}
 *           } ,
 *          "getPromoApprenantAttente"= {
 *                   "path"="/admin/promo/apprenants/attente" ,
 *                 "method"="GET" ,
 *                 "normalization_context"={"groups"={"getAllpromoApprenantAttente:read"}}
 *          } ,
 *          "postApprenantPromo"={
 *                  "path"="/admin/promo" ,
 *                 "method"="POST" ,
 *                 "denormalization_context"={"groups"={"postApprenantPromo:write"}}
 *          }
 *     } ,
 *     itemOperations={
 *         "getPromoId"={
 *              "path"="/admin/promo/{id}" ,
 *              "method"="GET" ,
 *              "normalization_context"={"groups"={"getPromoId:read"}}
 *          } ,
 *           "getPromoprincipalbyId"={
 *              "path"="/admin/promo/{id}/principal" ,
 *              "method"="GET" ,
 *              "normalization_context"={"groups"={"getPromoprincipalbyId:read"}}
 *          } ,
 *           "getPromoRefbyId"={
 *              "path"="/admin/promo/{id}/referentiels" ,
 *              "method"="GET" ,
 *              "normalization_context"={"groups"={"getPromoRefbyId:read"}}
 *          } ,
 *           "getPromoRefbAppreneaAttenteById"={
 *              "path"="/admin/promo/{id}/apprenants/attente" ,
 *              "method"="GET" ,
 *              "normalization_context"={"groups"={"getPromoRefbAppreneaAttenteById:read"}}
 *          } ,
 *          "getPromoFormateurById"={
 *              "path"="/admin/promo/{id}/formateurs" ,
 *              "method"="GET" ,
 *              "normalization_context"={"groups"={"getPromoFormateurById:read"}}
 *          },
 *          "putPromoReferById"={
 *              "path"="/admin/promo/{id}/referentiels" ,
 *              "method"="PUT" ,
 *              "denormalization_context"={"groups"={"putPromoReferById:write"}}
 *          }
 *     }
 * )
 */
class Promo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getAllpromo:read","getAllpromoprincipal:read","postApprenantPromo:write","getallgroupe:read","getPromoId:read",
     *          "getPromoprincipalbyId:read","getAllpromoApprenantAttente:read","getPromoRefbyId:read","getPromoRefbAppreneaAttenteById:read",
     *          "getPromoFormateurById:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"getAllpromo:read","getAllpromoprincipal:read","postApprenantPromo:write","getallgroupe:read","getPromoId:read",
     *          "getPromoprincipalbyId:read","getAllpromoApprenantAttente:read","getPromoRefbyId:read","getPromoRefbAppreneaAttenteById:read",
     *          "getPromoFormateurById:read"})
     */
    private $annee;

    /**
     * @ORM\Column(type="date")
     * @Groups({"getAllpromo:read","getAllpromoprincipal:read","postApprenantPromo:write","getallgroupe:read","getPromoId:read",
     *          "getPromoprincipalbyId:read","getAllpromoApprenantAttente:read","getPromoRefbyId:read","getPromoRefbAppreneaAttenteById:read",
     *          "getPromoFormateurById:read"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups({"getAllpromo:read","getAllpromoprincipal:read","postApprenantPromo:write","getallgroupe:read","getPromoId:read",
     *     "getPromoprincipalbyId:read","getAllpromoApprenantAttente:read","getPromoRefbyId:read","getPromoRefbAppreneaAttenteById:read",
     *      "getPromoFormateurById:read"})
     */
    private $dateFin;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promos")
     * @ApiSubresource
     * @Groups({"getAllpromo:read","getAllpromoprincipal:read","getPromoId:read","getPromoprincipalbyId:read","getPromoFormateurById:read"})
     */
    private $formateurs;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promo",cascade={"persist"})
     * @Groups({"getAllpromo:read","getAllpromoprincipal:read","postApprenantPromo:write","getPromoId:read","getPromoFormateurById:read"})
     * @ApiSubresource
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Referentiel::class, inversedBy="promos")
     * @Groups({"getAllpromo:read","getAllpromoprincipal:read","getallgroupe:read","getPromoId:read","getPromoprincipalbyId:read",
     *      "getPromoRefbyId:read","getPromoRefbAppreneaAttenteById:read","getPromoFormateurById:read"})
     * @ApiSubresource
     */
    private $referentiels;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promo")
     * @ApiSubresource
     * @Groups({"getPromoprincipalbyId:read","getAllpromoApprenantAttente:read"})
     */
    private $apprenants;

    public function __construct()
    {
        $this->formateurs = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
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

    public function getAnnee(): ?\DateTimeInterface
    {
        return $this->annee;
    }

    public function setAnnee(\DateTimeInterface $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        $this->formateurs->removeElement($formateur);

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromo($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromo() === $this) {
                $groupe->setPromo(null);
            }
        }

        return $this;
    }

    public function getReferentiels(): ?Referentiel
    {
        return $this->referentiels;
    }

    public function setReferentiels(?Referentiel $referentiels): self
    {
        $this->referentiels = $referentiels;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setPromo($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getPromo() === $this) {
                $apprenant->setPromo(null);
            }
        }

        return $this;
    }
}
