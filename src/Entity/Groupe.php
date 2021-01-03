<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "getallgroupe"={
 *              "path"="/admin/groupes" ,
 *              "method"="GET" ,
 *              "normalization_context"={"groups"={"getallgroupe:read"}}
 *          } ,
 *          "getallgroupeApprenant"={
 *              "path"="/admin/groupes/apprenants" ,
 *              "method"="GET" ,
 *              "normalization_context"={"groups"={"getallgroupeApprenant:read"}}
 *          } ,
 *           "postgroupe"={
 *              "path"="/admin/groupes" ,
 *              "method"="POST" ,
 *              "denormalization_context"={"groups"={"postgroupe:write"}}
 *          } ,
 *     } ,
 *     itemOperations={
 *          "getgroupebyId"={
 *              "path"="/admin/groupes/{id}" ,
 *              "method"="GET" ,
 *              "normalization_context"={"groups"={"getgroupebyId:read"}}
 *          } ,
 *         "putgroupebyId"={
 *              "path"="/admin/groupes/{id}" ,
 *              "method"="PUT" ,
 *              "denormalization_context"={"groups"={"putgroupebyId:read"}}
 *          } ,
 *         "getgroupeApprenantbyId"={
 *              "path"="/admin/groupes/{id}/apprenants/{id1}" ,
 *              "method"="DELETE" ,
 *              "denormalization_context"={"groups"={"getApprenantgroupebyId:read"}}
 *          }
 *     }
 * )
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"postApprenantPromo:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"getAllpromo:read"})
     * @Groups({"postApprenantPromo:write","getallgroupe:read","getallgroupeApprenant:read","postgroupe:write","getgroupebyId:read" ,
     *          "putgroupebyId:read","getApprenantgroupebyId:read","getPromoId:read","getPromoFormateurById:read"})
     */
    private $nomGroupe;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupes")
     * @Groups({"getallgroupe:read"})
     * @ApiSubresource
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, inversedBy="groupes",cascade={"persist"})
     * @ApiSubresource
     * @Groups({"getAllpromoprincipal:read","postApprenantPromo:write","getallgroupe:read","getallgroupeApprenant:read",
     *     "postgroupe:write","putgroupebyId:read","getApprenantgroupebyId:read","getPromoApprenantId:read"})
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="groupes")
     * @ApiSubresource
     * @Groups({"getallgroupe:read","postgroupe:write","getPromoApprenantId:read"})
     */
    private $formateurs;

    /**
     * @ORM\OneToMany(targetEntity=EtatBriefGroupe::class, mappedBy="groupe")
     */
    private $etatBriefgroupe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
        $this->formateurs = new ArrayCollection();
        $this->etatBriefgroupe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomGroupe(): ?string
    {
        return $this->nomGroupe;
    }

    public function setNomGroupe(string $nomGroupe): self
    {
        $this->nomGroupe = $nomGroupe;

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

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
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        $this->apprenants->removeElement($apprenant);

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
     * @return Collection|EtatBriefGroupe[]
     */
    public function getEtatBriefgroupe(): Collection
    {
        return $this->etatBriefgroupe;
    }

    public function addEtatBriefgroupe(EtatBriefGroupe $etatBriefgroupe): self
    {
        if (!$this->etatBriefgroupe->contains($etatBriefgroupe)) {
            $this->etatBriefgroupe[] = $etatBriefgroupe;
            $etatBriefgroupe->setGroupe($this);
        }

        return $this;
    }

    public function removeEtatBriefgroupe(EtatBriefGroupe $etatBriefgroupe): self
    {
        if ($this->etatBriefgroupe->removeElement($etatBriefgroupe)) {
            // set the owning side to null (unless already changed)
            if ($etatBriefgroupe->getGroupe() === $this) {
                $etatBriefgroupe->setGroupe(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
