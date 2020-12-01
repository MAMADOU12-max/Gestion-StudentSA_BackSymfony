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

}
