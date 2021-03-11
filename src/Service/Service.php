<?php
//
//
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\Serializer\SerializerInterface;
//use Symfony\Component\Validator\Validator\ValidatorInterface;
//use Symfony\Component\HttpFoundation\JsonResponse;
//
//
//class Service
//{
////    /**
////     * @var SerializerInterface
////     */
////    private $serialize;
////    /**
////     * @var ValidatorInterface
////     */
////    private $validator;
////    private $encoder;
////
////    /**
////     * UserController constructor.
////     */
////    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordEncoderInterface $encoder )
////    {
////        $this->serialize = $serializer ;
////        $this->validator = $validator ;
////        $this->encoder = $encoder ;
////
////    }
//
//
////    /**
////     * @Route(
////     *      name="addUser" ,
////     *      path="/api/admin/users" ,
////     *     methods={"POST"} ,
////     *     defaults={
////     *     "__controller"="App\Controller\UserController::addUser",
////     *         "_api_resource_class"=User::class,
////     *         "_api_collection_operation_name"="adding"
////     *     }
////     *
////     *)
////     */
//
//    public function addUser(Request $request) {
//
//        //all data
//        $user = $request->request->all() ;
//
//        //get profil
//        $profil = $user["profil"] ;
//
//        //recupération de l'image
//        $photo = $request->files->get("photo");
//
//        //specify entity
//        $user = $this->serialize->denormalize($user,"App\Entity\$entity",true);
//        if(!$photo)
//        {
//            return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
//        }
//        //$base64 = base64_decode($imagedata);
//        $photoBlob = fopen($photo->getRealPath(),"rb");
//
//        $user->setPhoto($photoBlob);
//
//        $errors = $this->validator->validate($user);
//        if (count($errors)){
//            $errors = $this->serialize->serialize($errors,"json");
//            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
//        }
//        $password = $user->getPassword();
//        $user->setPassword($this->encoder->encodePassword($user,$password));
//        $user->setArchivage(false);
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($user);
//        $em->flush();
//
//        return $this->json("success",201);
//
//
//    }
//}
