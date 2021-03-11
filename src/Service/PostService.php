<?php


namespace App\Service;

use App\Entity\Admin;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\Entity\Profil;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class PostService
{

        /**
     * @var SerializerInterface
     */
    private $serialize;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * PostController constructor.
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $manager
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $encoder
     * @param UserRepository $userRepository
     */
    public function __construct(SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator,
                                UserPasswordEncoderInterface $encoder,UserRepository $userRepository)
    {
        $this->serialize = $serializer ;
        $this->validator = $validator ;
        $this->encoder = $encoder ;
        $this->manager = $manager ;
        $this->userRepository = $userRepository ;
    }

     public function addUser(Request $request,$profil) {

//         $user = $request->request->all() ;
//
//
//         $photo = $request->files->get("photo");
//         if($photo) {
//             $file = $photo->getRealPath() ;
//             $photoBlob = fopen($file,"r+");
//              $user["photo"] = $photoBlob ;
//             // $base64 = base64_decode($imagedata);
////             $user->setPhoto($photoBlob);
////             return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
//         }
//         //get image
//
//         if($profil == "APPRENANT") {
//             $type = Apprenant::class ;
//         }elseif ($profil == "FORMATEUR") {
//             $type = Formateur::class ;
//         }
//         $users = $this->serialize->denormalize($user,$type);
////         else {
////             $type = User::class ;
////         }
////         $users->setPassword($this->encoder->encodePassword($users,$user["password"]));
//         return $user ;


    }



    public function putData(Request $request, $id) {

        $dataId = $this->userRepository->find($id) ;
        $data = $request->request->all();
        

        foreach ($data as $key => $value) {
            if ( $key !== "_method" || !$value) {
                $dataId->{"set".ucfirst($key)}($value) ;
            }
        }

//         $photo = $request->files->get("photo") ;
//         $photoBlob = fopen($photo->getRealPath(),"rb");
//            dd($data);
//         if($photo) {
//
//             $dataId->setPhoto($photoBlob);
//
//         }

         //recupération de l'image
        $photo = $request->files->get("photo");
        //is not obliged
        if($photo)
        {
            //  return new JsonResponse("veuillez mettre une images",Response::HTTP_BAD_REQUEST,[],true);
            //$base64 = base64_decode($imagedata);
            $photoBlob = fopen($photo->getRealPath(),"rb");

           $dataId->setPhoto($photoBlob);
        }

        $errors = $this->validator->validate($dataId);
        if (count($errors)){
            $errors = $this->serialize->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }
        //dd($dataId);
        $this->manager->persist($dataId);
        $this->manager->flush();

        return new JsonResponse("success",201) ;

    }

    // Update User
    public function UpdateUser(Request $request, string $filename = null)
    {
        $row = $request->getContent();
        $delimitor = "multipart/form-data; boundary=";
        // dd($delimitor);
        $boundary = "--".explode($delimitor, $request->headers->get("content-type"))[1];
        // dd($boundary);
        $elements = str_replace([$boundary,'Content-Disposition: form-data;',"name="],"",$row);
        //dd($elements);
        $tabElements = explode("\r\n\r\n", $elements);
        //dd($tabElements);
        $data = [];

        for ($i = 0; isset($tabElements[$i+1]); $i++) {

            $key = str_replace(["\r\n",'"','"'],'',$tabElements[$i]);
            //dd($key);
            if (strchr($key, $filename)) {
                $file = fopen('php://memory', 'r+');
                fwrite($file, $tabElements[$i+1]);
                rewind($file);
                $data[$filename] = $file;
            } else {
                $val = str_replace(["\r\n",'--'], '', $tabElements[$i+1]);
                $data[$key] = $val;
            }
        }
        //dd($data);
        return $data;
    }
}
