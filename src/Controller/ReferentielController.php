<?php

namespace App\Controller;

use App\Repository\CompetenceRepository;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\ReferentielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReferentielController extends AbstractController
{
    /**
     * @var GroupeCompetenceRepository
     */
    private $groupeCompetenceRepository;
    /**
     * @var SerializerInterface
     */
    private $serialier;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var ReferentielRepository
     */
    private $referentielRepository;

    /**
     * ReferentielController constructor.
     */
    public function __construct(GroupeCompetenceRepository $groupeCompetenceRepository, SerializerInterface $serializer,CompetenceRepository $gro,
                                EntityManagerInterface $manager, ReferentielRepository $referentielRepository)
    {
        $this->groupeCompetenceRepository = $groupeCompetenceRepository ;
        $this->referentielRepository = $referentielRepository ;
        $this->manager = $manager ;
        $this->serialier = $serializer ;
    }


    /**
     * @Route(
     *      name="editReferenciel" ,
     *      path="/api/admin/referentiels/{id}" ,
     *     methods={"PUT"}
     *)
     */
    public function editReferenciel(Request $request, int $id) {
        $contentPostman = json_decode($request->getContent()) ;
        //call repository referentiel
        $getIdReferentiel = $this->referentielRepository->find($id) ;
//        dd($getIdReferentiel) ;
        $getIdReferentiel->setLibelle($contentPostman->libelle) ;

        if ($contentPostman->action == "add") {
            //recup groupe grpCompetence
            foreach ($contentPostman->grpCompetence as $value) {
                //if id grpCompetence exist
                $idGrpCompetence = $this->groupeCompetenceRepository->find($value->id) ;
                if($idGrpCompetence) {
                    $getIdReferentiel->addGrpcompetence($idGrpCompetence) ;
                } else {
                    return new JsonResponse("id not found",404,[],true) ;
                }
            }
        } else if ($contentPostman->action == "remove") {
            foreach ($contentPostman->grpCompetence as $value) {
                //if id grpCompetence exist
                $idGrpCompetence = $this->groupeCompetenceRepository->find($value->id) ;
                if($idGrpCompetence) {
                    $getIdReferentiel->removeGrpcompetence($idGrpCompetence) ;
                } else {
                    return new JsonResponse("id not found",404,[],true) ;
                }
            }
        }

        $this->manager->persist($getIdReferentiel);
        $this->manager->flush();
        return new JsonResponse("valid",200,[],true) ;
    }
}
