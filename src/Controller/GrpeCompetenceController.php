<?php

namespace App\Controller;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Competence;
use App\Entity\Niveau;
use App\Repository\CompetenceRepository;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GroupeCompetence ;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class GrpeCompetenceController extends AbstractController
{
    /**
     * @var GroupeCompetenceRepository
     */
    private $groupeCompetenceRepository;
    /**
     * @var CompetenceRepository
     */
    private $competenceRepository;
    /**
     * @var SerializerInterface
     */
    private $serialier;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * GrpeCompetenceController constructor.
     */
    public function __construct(GroupeCompetenceRepository $groupeCompetenceRepository, SerializerInterface $serializer,CompetenceRepository $competenceRepository,
                                EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $this->groupeCompetenceRepository = $groupeCompetenceRepository ;
        $this->competenceRepository = $competenceRepository ;
        $this->manager = $manager ;
        $this->serialier = $serializer ;
        $this->validator = $validator ;
    }


    /**
     * @Route(
     *      name="addGrpecompetence" ,
     *      path="/api/admin/grpecompetences" ,
     *     methods={"POST"} ,
     *     defaults={
     *         "__controller"="App\Controller\GrpeCompetenceController::postGrpecompetence",
     *         "_api_resource_class"=GroupeCompetence::class ,
     *         "_api_collection_operation_name"="adding"
     *     }
     *)
     */
    public function postGrpecompetence(Request $request, SerializerInterface $serializer)
    {
        $dataPostman =  json_decode($request->getContent());
//        dd($dataPostman) ;
        //instance groupe
        $grpCompetence = new GroupeCompetence() ;
        //recup groupe libelle
        $libelleGroupe = $dataPostman->libelle ;
        //verify if name groupe exist or not
        if($this->groupeCompetenceRepository->findOneBy(["libelle"=>$libelleGroupe])) {
            return new JsonResponse("this name'crew exists already, please select others!",Response::HTTP_BAD_REQUEST,[],true) ;
        }

          $grpCompetence->setLibelle($dataPostman->libelle) ;

//        dd($grpCompetence) ;
        $competences = $dataPostman->competence ;
//       dd($competences) ;

        foreach ($competences as $value) {
            //  count number competence
            $nbrCompetence =  count($competences) ;

//            if(isset($value->niveaux)){
//                $nbrNiveaux[]=$value->niveaux;
//            }

            //affectation if given exist competence
            if ($this->competenceRepository->findOneBy(['nomCompetence'=> $value->nomCompetence])) {
                $existCompetence = $this->competenceRepository->findOneBy(['nomCompetence'=> $value->nomCompetence]) ;
                $grpCompetence->addCompetence($existCompetence) ;
                 $this->manager->persist($grpCompetence);
            } else {
                //count number level of niveaux
                if (isset($value->niveaux)) {
                    //  count number level;
                   // dd("$value->niveaux") ;

                        $allLevels = $value->niveaux ;
                        //count if level == 3
                        if(count($allLevels) == 3) {
                            $competence = new Competence() ;
                            $competence->setNomCompetence($value->nomCompetence) ;
//                                  dd($competence) ;
                            foreach ($allLevels as $level){

                                $niveau = new Niveau() ;
                                $niveau->setLevel($level->level) ;
                                $competence->addNiveau($niveau) ;
                                $this->manager->persist($niveau);
                            }
                            $this->manager->persist($competence);
                            $grpCompetence->addCompetence($competence) ;
                            $this->manager->persist($grpCompetence);
                            } else {
                                return new JsonResponse('Please, put 3 levels only!!',400,[],true) ;
                            }
                    } else {
                        return new JsonResponse("Please, keep levels !",400,[],true) ;
                    }
            }
//            dd("fin") ;
            $this->manager->flush();
        }

//        if($nbrCompetence == count($nbrNiveaux) ) {
//
//            dd(count($nbrNiveaux), $nbrCompetence);
//        } else {
//            dd("not equal") ;
//        }

        return $this->json("Added");
    }


    /**
     * @Route(
     *      name="editGrpecompetence" ,
     *      path="/api/admin/grpecompetences/{id}" ,
     *     methods={"PUT"}
     *)
     */
    public function editGrpecompetence(Request $request, int $id) {
        $contentPostman = json_decode($request->getContent()) ;
        //call repository group
        $getIdgrpcompetence = $this->groupeCompetenceRepository->find($id) ;
//        dd($getIdgrpcompetence) ;
       $getIdgrpcompetence->setLibelle($contentPostman->libelle) ;

       if ($contentPostman->action == "add") {
           //recup compétence
           foreach ($contentPostman->competence as $value) {
               //if id competence exist
               $idCompetence = $this->competenceRepository->find($value->id) ;
               if($idCompetence) {
                   $getIdgrpcompetence->addCompetence($idCompetence) ;
               } else {
                   return new JsonResponse("id not found",404,[],true) ;
               }
           }
       } else if ($contentPostman->action == "remove") {
           foreach ($contentPostman->competence as $value) {
               //if id competence exist
               $idCompetence = $this->competenceRepository->find($value->id) ;
               if($idCompetence) {
                   $getIdgrpcompetence->removeCompetence($idCompetence) ;
               } else {
                   return new JsonResponse("id not found",404,[],true) ;
               }
           }
       }

       $this->manager->persist($getIdgrpcompetence);
       $this->manager->flush();
       return new JsonResponse("valid",200,[],true) ;
    }
}
