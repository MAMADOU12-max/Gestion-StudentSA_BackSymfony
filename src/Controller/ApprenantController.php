<?php

namespace App\Controller;

use App\Entity\Apprenant ;
use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PostService ;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse ;

class ApprenantController extends AbstractController
{
    /**
     * @Route(
     *      name="addApprenant" ,
     *      path="/api/apprenants" ,
     *     methods={"POST"} ,
     *     defaults={
     *     "__controller"="App\Controller\ApprenantController::addapprenant",
     *         "_api_resource_class"=Apprenant::class,
     *         "_api_collection_operation_name"="adding"
     *     }
     *)
     */
    public function addapprenant(PostService $postService, Request $request, EntityManagerInterface $manager, ValidatorInterface $validator, SerializerInterface $serializer,
    UserPasswordEncoderInterface $encoder)
    {


//            $apprenant = $postService->addUser($request,"APPRENANT") ;
//            $apprenant = $serializer->denormalize($apprenant,"App\Entity\Apprenant");
//
//        $errors = $validator->validate($apprenant);
//        if (count($errors)){
//            $errors = $serializer->serialize($errors,"json");
//             return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[]);
//        }
//        $apprenant->setArchivage(0) ;
//
//        $password = $apprenant->getPassword();
//        $apprenant->setPassword($encoder->encodePassword($apprenant,$password));
//        $persist =  $manager->persist($apprenant);
//        $manager->flush($persist);
//        return new JsonResponse("success",200, [], true) ;




        //all data
        $user = $request->request->all() ;

        //get profil

//        $profil = $user["profils"] ;
        $type ="";

            $user = $serializer->denormalize($user, "App\Entity\Apprenant");

        //recupération de l'image
        $photo = $request->files->get("photo");
        //specify entityApprenant

        if(!$photo)
        {
            return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
        }
        //$base64 = base64_decode($imagedata);
        $photoBlob = fopen($photo->getRealPath(),"rb");

        $user->setPhoto($photoBlob);

        $errors = $validator->validate($user);
        if (count($errors)){
            $errors = $serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $password = $user->getPassword();
        $user->setPassword($encoder->encodePassword($user,$password));
        $user->setArchivage(false);
        $user->setProfil($manager->getRepository(Profil::class)->findOneBy(['libelle'=>"APPRENANT"])) ;

//        $em = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush() ;
        return $user ;




    }
}
