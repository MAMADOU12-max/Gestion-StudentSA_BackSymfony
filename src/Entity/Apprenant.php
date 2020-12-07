<?php

namespace App\Entity;

use App\Repository\ApprenantRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"apprenants:read"}} ,
 *     collectionOperations={
 *           "getallapprenants"={
 *                "method"="GET",
 *                "path"="/apprenants" ,
 *                "security_post_denormalize"="is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM')" ,
 *                "security_message"="Only teachers can acced in the data students!"
 *          } ,
 *          "adding"={
 *              "route_name"="addApprenant" ,
 *              "deserialize"= false ,
 *               "security_post_denormalize"="is_granted('ROLE_FORMATEUR')  " ,
 *              "security_message"="Only teachers can acced in the data students!"
 *          }
 *     } ,
 *     itemOperations={
 *         "getApprenantById"={
 *              "method"="GET" ,
 *              "path"="/apprenants/{id}" ,
 *               "security_post_denormalize"="is_granted('ROLE_FORMATEUR') or is_granted('ROLE_CM') or is_granted('ROLE_APPRENANT')" ,
 *               "security_message"="Only teachers can acced in the student's data !"
 *          },
 *          "UpdatedApprenant"={
 *              "deserialize"= false ,
 *               "security_post_denormalize"="is_granted('ROLE_FORMATEUR') or is is_granted('ROLE_APPRENANT')" ,
 *              "security_message"="Only teachers can acced in the data students!"
 *          }
 *     }
 * )
 */



class Apprenant extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="apprenants")
     */
    private $groupes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Attente;
    /**
     * @var false
     */
    private $setAttente;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="apprenants")
     */
    private $promo;

    public function __construct()
    {
        parent::__construct();
        $this->groupes = new ArrayCollection();
        $this->setAttente = false ;
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
            $groupe->addApprenant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeApprenant($this);
        }

        return $this;
    }

    public function getAttente(): ?bool
    {
        return $this->Attente;
    }

    public function setAttente(bool $Attente): self
    {
        $this->Attente = $Attente;

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
}
