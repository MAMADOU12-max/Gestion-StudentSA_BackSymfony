<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\Cm;
use App\Entity\Formateur;
use App\Entity\Profil;
use App\Service\PostService ;
use App\Service\UploadService ;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends AbstractController
{

    /**
     * @var SerializerInterface
     */
    private $serialize;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(SerializerInterface $serializer, UserRepository $userRepository,
    EntityManagerInterface $manager, ValidatorInterface $validator,
     UserPasswordEncoderInterface $encoder )
    {
        $this->serialize = $serializer ;
        $this->validator = $validator ;
        $this->encoder = $encoder ;
        $this->manager = $manager ;
        $this->userRepository = $userRepository ;
    }

    /**
     * @Route(
     *      name="addUser" ,
     *      path="/api/admin/users" ,
     *     methods={"POST"} ,
     *     defaults={
     *     "__controller"="App\Controller\UserController::addUser",
     *         "_api_resource_class"=User::class,
     *         "_api_collection_operation_name"="adding"
     *     }
     *
     *)
    */
    public function adUser( Request $request) {

        //all data
        $user = $request->request->all() ;

        //get profil
        $profil = $user["profils"] ;
         if($profil == "ADMIN") {
             $user = $this->serialize->denormalize($user, "App\Entity\Admin");
        } elseif ($profil =="APPRENANT") {
             $user = $this->serialize->denormalize($user, "App\Entity\Apprenant");
        } elseif ($profil =="FORMATEUR") {
             $user = $this->serialize->denormalize($user, "App\Entity\Formateur");
        }elseif ($profil =="CM") {
             $user = $this->serialize->denormalize($user, "App\Entity\Cm");
        }
        //recupération de l'image
        $photo = $request->files->get("photo");
        //is not obliged
        if($photo)
        {
            //  return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
            //$base64 = base64_decode($imagedata);
            $photoBlob = fopen($photo->getRealPath(),"rb");

            $user->setPhoto($photoBlob);
        }


        $errors = $this->validator->validate($user);
        if (count($errors)){
            $errors = $this->serialize->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        $password = $user->getPassword();
        $user->setPassword($this->encoder->encodePassword($user,$password));
        $user->setArchivage(false);


        $user->setProfil($this->manager->getRepository(Profil::class)->findOneBy(['libelle'=>$profil])) ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json("success",201);

    }


    // /**
    //  * @Route(
    //  *      name="updated" ,
    //  *      path="/api/admin/users/{id}" ,
    //  *       methods={"PUT"}
    //  *)
    //  * @Route(
    //  *      name="UpdatedApprenant" ,
    //  *      path="/api/apprenants/{id}" ,
    //  *     methods={"PUT"} ,
    //  *     defaults={
    //  *           "__controller"="App\Controller\UserController::addUser",
    //  *         "_api_resource_class"=Apprenant::class,
    //  *         "_api_collection_operation_name"="adding"
    //  *     }
    //  *
    //  *)
    //  */
    // public function cool(Request $request , PostService $postService,$id) {
    //    return  $postService->putData($request, $id) ;

    // }


    /*Test normal edit user */

     /**
     * @Route(
     *      name="updated" ,
     *      path="/api/admin/users/{id}" ,
     *       methods={"PUT"}
     *)
     */
    public function putUser(Request $request, PostService $postService, 
    EntityManagerInterface $manager,SerializerInterface $serializer,UserRepository $u, $id) {
        $userForm= $postService->UpdateUser($request, 'photo');
        // dd($userForm);
         $user = $u->find($id);
         foreach ($userForm as $key => $value) {
             if($key === 'profils'){
                 $value = $serializer->denormalize($value, Profil::class);
             }
             $setter = 'set'.ucfirst(trim(strtolower($key)));
             //dd($setter);
             if(method_exists(User::class, $setter)) {
                 $user->$setter($value);
                 //dd($user);
             }
         }
         $manager->flush();
         return new JsonResponse("success",200,[],true);
 
     }
    

}
