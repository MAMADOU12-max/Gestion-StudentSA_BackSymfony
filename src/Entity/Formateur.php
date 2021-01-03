<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"formateur:read"}} ,
 *     collectionOperations={
 *           "adding"={
 *              "route_name"="addFormateur" ,
 *              "deserialize"= false ,
 *              "method"="POST" ,
 *               "security_post_denormalize"="is_granted('ROLE_CM')  " ,
 *              "security_message"="Only teachers can acced in the data students!"
 *          } ,
 *        "getAllformateursbyCm"={
 *              "method"="GET" ,
 *              "path"="/formateurs" ,
 *              "security_post_denormalize"="is_granted('ROLE_CM')  " ,
 *              "security_message"="Only teachers can acced in the data students!"
 *          }
 *     }  ,
 *      itemOperations={
 *          "getFormateurById"={
 *              "method"="GET" ,
 *               "path"="/formateurs/{id}" ,
 *               "security_post_denormalize"="is_granted('ROLE_FORMATEUR') || is_granted('ROLE_CM')"  ,
 *               "security_message"="Only teachers can acced in the data students!"
 *          }
 *     }
 *)
 */



class Formateur extends User
{
    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, mappedBy="formateurs")
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateurs")
     */
    private $groupes;

    public function __construct()
    {
        parent::__construct();
        $this->promos = new ArrayCollection();
        $this->groupes = new ArrayCollection();
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
            $promo->addFormateur($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->removeElement($promo)) {
            $promo->removeFormateur($this);
        }

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
            $groupe->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeFormateur($this);
        }

        return $this;
    }
}
