<?php
// api/src/DataProvider/PromoDataProvider.php

namespace App\DataProvider;

use App\Entity\Promo;
use App\Repository\PromoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use Symfony\Component\HttpFoundation\Request;

final class PromoDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
      * @var promoRepository
     */
    private $promoRepository ;

    public function __construct(PromoRepository $promoRepository)
    {

        $this->promoRepository = $promoRepository ;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Promo::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
            $dataPostman =  json_decode($request->getContent());
         // Retrieve data from anywhere you want, in a custom format
       if($operationName == "getPromoApprenantAttente") {
           return $this->promoRepository->promoApprenantAttente() ;
       }
//            else if ($operationName == "getPromoRefbyId") {
//                  dd("cool");
//                   return $this->promoRepository->getPromoRefbyId() ;
//            }
         //  return $this->promoRepository->promoApprenantAttente() ;
        else if($operationName == "getPromoRefbAppreneaAttenteById") {
             dd("cool") ;
             $data =explode('/',$context[request_url]) ;
        }
    }
}
