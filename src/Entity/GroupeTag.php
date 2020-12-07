<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "getAllgroupTags"={
 *              "method"="GET" ,
 *              "path"="/admin/grptags" ,
 *               "normalization_context"={"groups"={"getAllgrpTag:read"}}
 *          } ,
 *            "postgroupTags"={
 *              "method"="POST" ,
 *              "path"="/admin/grptags" ,
 *               "denormalization_context"={"groups"={"postgrpTag:write"}}
 *          }
 *     } ,
 *     itemOperations={
 *          "getgroupTagsById"={
 *              "method"="GET" ,
 *              "path"="/admin/grptags/{id}" ,
 *               "normalization_context"={"groups"={"getgrpTagById:read"}}
 *          } ,
 *          "getgroupTagsTagById"={
 *              "method"="GET" ,
 *              "path"="/admin/grptags/{id}/tags" ,
 *               "normalization_context"={"groups"={"getgrpTagtagById:read"}}
 *          } ,
 *          "putgroupTagstagById"={
 *              "method"="PUT" ,
 *              "path"="/admin/grptags/{id}" ,
 *               "de    normalization_context"={"groups"={"putgrpTagstagById:read"}}
 *          }
 *     }
 * )
 */
class GroupeTag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tagAll:read","getTagById:read","getAllgrpTag:read","postgrpTag:write","getgrpTagById:read","getgrpTagtagById:read","putgrpTagstagById:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="groupetags",cascade={"persist"})
     * @ApiSubresource
     * @Groups({"getAllgrpTag:read","postgrpTag:write","getgrpTagtagById:read","putgrpTagstagById:read"})
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addGroupetag($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeGroupetag($this);
        }

        return $this;
    }
}
