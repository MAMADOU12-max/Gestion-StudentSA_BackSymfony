<?php

namespace App\Entity;

use App\Repository\ProfilDeSortieRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProfilDeSortieRepository::class)
 *  @ApiFilter(SearchFilter::class, properties={"Archivage":"exact"})
* @UniqueEntity("libelle")
 * @ApiResource(
 *      normalizationContext={"groups"={"profildesortie:read"}} ,
*       attributes={ 
*           "security_post_denormalize"="is_granted('ROLE_ADMIN')" ,
*          "security_message"="Only admins can get profils de soortie"
*     },
 *      collectionOperations={
 *            "getPs"= {
 *                   "path"="/admin/profilsdesortie",
 *                   "method"="GET" ,
 *              } ,
 *         "post"={
   *              "path"="/admin/profilsdesortie",
   *                "method"="POST"
   *           }
 *         },
 * )
 */
// http://127.0.0.1:8000/api/profil_de_sorties/{id} =>path items
// *  @ApiFilter(SearchFilter::class, properties={"Archivage":"exact"})
// * @UniqueEntity("libelle")
// *  @ApiResource(
// *      normalizationContext={"groups"={"profildesortie:read"}} ,
// *       attributes={ 
// *           "security_post_denormalize"="is_granted('ROLE_ADMIN')" ,
// *          "security_message"="Only admins can get profils de soortie"
// *     },
// *     collectionOperations={
// *               "getprofilsortie"={
//    *                "path"="/admin/profils",
//    *                   "method"="GET" ,
//    *                   "normalization_context"={"groups"={"getallprofilsortie:read"}}
//    *          },
//    *          "post"={
//    *              "path"="/admin/profildesortie",
//    *                "method"="POST"
//    *           }
// *     },
// *      itemOperations={
// *               "deleteProfilsortie"={
// *                   "path"="/admin/profildesortie/{id}" ,
// *                    "method"="Delete"
// *              } ,
// *                    "putprofiildesortiebyId"={
// *                   "path"="/admin/profildesortie/{id}" ,
// *                    "method"="PUT"
// *              }

// *     }
// * )


class ProfilDeSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profildesortie:read","getallprofilsortie:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
      * @Groups({"profildesortie:read","getallprofilsortie:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
      * @Groups({"profildesortie:read"})
     */
    private $Archivage;
  
    public function __construct()
    {
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
