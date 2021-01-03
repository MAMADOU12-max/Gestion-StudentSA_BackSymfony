<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\ExistsFilter;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"Archivage":"exact"})
 * @UniqueEntity("libelle")
 * @ApiResource(
 * normalizationContext={"groups"={"profil:read"}} ,
 *      attributes={ 
 *          "security_message"="You don't have permitted to acced in this resource,please verify"
 *     },
 *     collectionOperations={
 *           "getallprofil"={
 *                  "path"="/admin/profils" ,
 *                 "method"="GET"
 *         },
 *         "postallprofil"={
 *                "path"="/admin/profils" ,
 *                 "method"="POST"
 *          }
 *     },
 *     itemOperations={
 *           "getprofilbyid"={
 *               "path"="/admin/profils/{id}" ,
 *                 "method"="GET"
 *          },
 *           "putprofilbyid"={
 *               "path"="/admin/profils/{id}" ,
 *                 "method"="PUT"
 *          } ,
 *           "deleteprofilbyid"={
 *               "path"="/admin/profils/{id}" ,
 *                 "method"="DELETE" ,
 *          } ,
 *
 *          "get_get_subresource"={
 *               "path"="/admin/profils/{id}/users" ,
 *                 "method"="GET"
 *          }
 *     }
 * )
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil:read","profil:users"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read"})
     * @Assert\NotBlank
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @ApiSubresource()
     * @Groups({"profil:read"})
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
       * @Groups({"profil:read"})
     */
    private $Archivage;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->Archivage = false ;
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

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

}
