<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 *   @ORM\Entity(repositoryClass=UserRepository::class)
 *   @ORM\InheritanceType("SINGLE_TABLE")
 *   @ORM\DiscriminatorColumn(name="type", type="string")
 *   @ORM\DiscriminatorMap({"admin"="Admin","apprenant"="Apprenant", "cm"="Cm" , "formateur"="Formateur" ,"user"="User"})
 *   @ApiFilter(SearchFilter::class, properties={"Archivage":"exact"})
 *   @ApiResource(
 *      normalizationContext={"groups"={"users:read"}} ,
 *     collectionOperations={
 *           "get"={
 *               "security_post_denormalize"="is_granted('ROLE_ADMIN')" ,
 *               "security_message"="Only admins can get users" ,
 *                "path"="/admin/users"
 *          },
 *          "adding"={
 *              "route_name"="addUser" ,
 *              "security_post_denormalize"="is_granted('ROLE_ADMIN')"   ,
 *              "security_message"="Only admins can add users." ,
 *              "deserialize"= false
 *           }
 *     },
 *      itemOperations={
 *               "getusersbyId"={
 *                  "path"="/admin/users/{id}" ,
 *                  "security_post_denormalize"="is_granted('ROLE_ADMIN')"   ,
 *                "security_message"="Only admins can add users." ,
 *                 "method"="GET"
 *              },
 *                 "deletebyId"={
 *                  "path"="/admin/users/{id}" ,
 *                  "security_post_denormalize"="is_granted('ROLE_ADMIN')"   ,
 *                "security_message"="Only admins can add users." ,
 *                 "method"="DELETE"
 *              }

 *     }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer") 
     * @Groups({"profil:read","users:read","postApprenantPromo:write","postgroupe:write","putgroupebyId:read","getPromoprincipalbyId:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"users:read","apprenants:read","formateur:read","profil:read","getAllpromo:read","getAllpromoprincipal:read",
     *     "getallgroupe:read","getallgroupeApprenant:read","getPromoId:read","getPromoprincipalbyId:read","getAllpromoApprenantAttente:read"})
     */
    private $username;

    /**
     * @Groups({"users:read"})
    */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read","apprenants:read","formateur:read","profil:read","getAllpromoprincipal:read","getallgroupeApprenant:read"
     *          ,"getPromoId:read","getPromoprincipalbyId:read","getAllpromoApprenantAttente:read","getPromoFormateurById:read"})
     * @Assert\NotBlank(message="Give your firstname please!")
     * @Assert\Length(min=3)
     */
    private $firtname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read","apprenants:read","formateur:read","profil:read","getAllpromo:read","getAllpromoprincipal:read",
     *     "getallgroupe:read","getallgroupeApprenant:read","getPromoId:read","getPromoprincipalbyId:read",
     *     "getAllpromoApprenantAttente:read","getPromoFormateurById:read"})
     * @Assert\NotBlank(message="you must give a lastname")
     * @Assert\Length(min=3)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read","apprenants:read","formateur:read","profil:read","getAllpromo:read","getAllpromoprincipal:read"
     *         ,"getallgroupe:read","getallgroupeApprenant:read","getPromoId:read","getPromoprincipalbyId:read",
     *          "getAllpromoApprenantAttente:read","getPromoFormateurById:read"})
     * @Assert\NotBlank(message="Enter a valid mail please!")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.! Oops"
     * )
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     */
    private $profil;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $Archivage;

    /**
     * @Groups({"users:read","profil:read"})
     * @ORM\Column(type="blob", nullable=true)
     */
    private $photo;

    public function __construct()
    {
        $this->profils = new ArrayCollection();
        $this->Archivage = false ;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirtname(): ?string
    {
        return $this->firtname;
    }

    public function setFirtname(string $firtname): self
    {
        $this->firtname = $firtname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

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

    public function getPhoto()
    {
        $photo = $this->photo ;
        // for photo
        if($photo) {
           return (base64_encode(stream_get_contents($this->photo))) ; 
        }
        return $photo ;
    }

    public function setPhoto($photo): self
    {
        $this->photo = $photo;

        return $this;
    }

}
