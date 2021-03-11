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

        // if(isset($contentPostman->grpeCompetence) && $contentPostman->grpeCompetence !== "") {
        //     $getCompetencefromId->addGrpeCompetence($contentPostman->grpeCompetence) ;
        //     // $manager->persist($getCompetencefromId);
        //     dd($getCompetencefromId);
        // }
        // dd($getCompetencefromId);

       if(isset($contentPostman->nomCompetence) && $contentPostman->nomCompetence !== "") {
           $getCompetencefromId->setNomCompetence($contentPostman->nomCompetence) ;
           $manager->persist($getCompetencefromId);
       }
       if(isset($contentPostman->libelle) && $contentPostman->libelle !== "") {
            $getCompetencefromId->setLibelle($contentPostman->libelle) ;
            $manager->persist($getCompetencefromId);
        }

        foreach ($contentPostman->niveaux as $value){

            $niveaux = $niveauRepository->find(['id'=>$value->id]) ;

            if(isset($value) && $value !== "") {
                    $niveaux->setLevel($value->level) ;
                    $niveaux->setCriteredEvaluation($value->criteredEvaluation);
                    $niveaux->setGroupedAction($value->groupedAction);
                    $manager->persist($niveaux);
            }
        }

        $manager->flush();

        // this return make error sometime one Angular Frontend
        //return new JsonResponse("Competence Updated",200,[],true) ;

        return new JsonResponse("Competence Updated",200) ;

   }

     //    Delete Competence
    /**
     * @Route(
     *      name="deleteCompetence" ,
     *      path="/api/admin/competences/{id}",
     *      methods={"DELETE"},
     *       defaults={
     *         "__controller"="App\Controller\CompetenceController::deleteCompetence",
     *         "_api_resource_class"=Competence::class,
     *         "_api_collection_operation_name"="deleteCompetence"
     *     }
     *)
     */
    public function deleteCompetence(Request $request ,$id, CompetenceRepository $competenceRepository
                                    , EntityManagerInterface $manager) {
        $competence = $competenceRepository->find($id);
        // dd($competence);
        $competence->setArchivage(true);

        $manager->persist($competence);
        $manager->flush();
        return new JsonResponse("success",201) ;
    }
   
}
