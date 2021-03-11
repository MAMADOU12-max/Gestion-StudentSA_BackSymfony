<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Formateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PostService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class FormateurController extends AbstractController
{
    /**
     * @Route(
     *      name="addFormateur" ,
     *      path="/api/formateurs" ,
     *     methods={"POST"} ,
     *     defaults={
     *     "__controller"="App\Controller\FormateurController::addformateur",
     *         "_api_resource_class"=Formateur::class,
     *         "_api_collection_operation_name"="adding"
     *     }
     *)
     */

    public function addformateur( PostService $postService,Request $request, EntityManagerInterface $manager, ValidatorInterface $validator, SerializerInterface $serializer,
                                 UserPasswordEncoderInterface $encoder){


        $formateur = $postService->addUser($request, "FORMATEUR");
        $formateur = $serializer->denormalize($formateur, "App\Entity\Formateur");
        $errors = $validator->validate($formateur);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, "json");
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST, []);
        }
        $formateur->setArchivage(0);

        $password = $formateur->getPassword();
        $formateur->setPassword($encoder->encodePassword($formateur, $password));
        $persist = $manager->persist($formateur);
        $manager->flush($persist);
        return new JsonResponse("success", 200, [], true);


    }
}

