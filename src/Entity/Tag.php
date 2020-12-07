<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @ApiResource(
 *    collectionOperations={
 *          "getAllTag"={
 *               "method"="GET" ,
 *                "path"="admin/tags" ,
 *                "normalization_context"={"groups"={"tagAll:read"}}
 *           } ,
 *            "postTag"={
 *               "method"="POST" ,
 *                "path"="admin/tags" ,
 *                "denormalization_context"={"groups"={"postTag:write"}}
 *           }
 *     } ,
 *     itemOperations={
 *          "getTagByItem"={
 *                  "method"="GET" ,
 *                "path"="admin/tags/{id}" ,
 *                "normalization_context"={"groups"={"getTagById:read"}}
 *           } ,
 *          "putTagByItem"={
 *                  "method"="PUT" ,
 *                "path"="admin/tags/{id}" ,
 *                "denormalization_context"={"groups"={"putTagById:read"}}
 *           }
 *      }
 * )
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"postgrpTag:write","putgrpTagstagById:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tagAll:read","getTagById:read","postTag:write","putTagById:read","getAllgrpTag:read","postgrpTag:write","getgrpTagtagById:read","putgrpTagstagById:read"})
     */
    private $nomTag;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, inversedBy="tags",cascade={"persist"})
     * @Groups({"tagAll:read","getTagById:read"})
     * @ApiSubresource
     */
    private $groupetags;

    public function __construct()
    {
        $this->groupetags = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTag(): ?string
    {
        return $this->nomTag;
    }

    public function setNomTag(string $nomTag): self
    {
        $this->nomTag = $nomTag;

        return $this;
    }

    /**
     * @return Collection|GroupeTag[]
     */
    public function getGroupetags(): Collection
    {
        return $this->groupetags;
    }

    public function addGroupetag(GroupeTag $groupetag): self
    {
        if (!$this->groupetags->contains($groupetag)) {
            $this->groupetags[] = $groupetag;
        }

        return $this;
    }

    public function removeGroupetag(GroupeTag $groupetag): self
    {
        $this->groupetags->removeElement($groupetag);

        return $this;
    }
}
