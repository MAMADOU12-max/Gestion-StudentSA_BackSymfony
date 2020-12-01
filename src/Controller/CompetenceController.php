<?php

namespace App\Controller;


use App\Repository\CompetenceRepository;
use App\Repository\NiveauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Competence;
use Symfony\Component\Serializer\SerializerInterface;

class CompetenceController extends AbstractController
{
    /**
     * @Route(
     *      name="editCompetence" ,
     *      path="/api/admin/competences/{id}" ,
     *     methods={"PUT"}
     *)
     */
   public function editCompetence(Request $request, int $id, SerializerInterface $serializer, CompetenceRepository $competenceRepository,
                                  NiveauRepository $niveauRepository, EntityManagerInterface $manager) {

        $contentPostman = json_decode($request->getContent()) ;
        $getCompetencefromId = $competenceRepository->findOneBy(['id'=>$id]) ;

       if(isset($contentPostman->nomCompetence) && $contentPostman->nomCompetence !== "") {
           $getCompetencefromId->setNomCompetence($contentPostman->nomCompetence) ;
           $manager->persist($getCompetencefromId);
           $manager->flush();
       }

      foreach ($contentPostman->niveaux as $value){

          $niveaux = $niveauRepository->find(['id'=>$value->id]) ;

          if(isset($value) && $value !== "") {
               $niveaux->setLevel($value->level) ;
                $manager->persist($niveaux);
                $manager->flush();
           }
      }

      return new JsonResponse("Competence Upteded",200,[],true) ;

   }
}
