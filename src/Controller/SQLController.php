<?php

namespace App\Controller;


use App\Repository\DataClientRepository;
use App\Repository\RequestClientRepository;
use App\Repository\StatsRequestClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Annotations as OA;
class SQLController extends AbstractController
{
    
    /**
    * Page de test SQLController
    * @OA\Parameter(name="", in="", description="return un message ", required=true))
    * @OA\Response(response=200, description="",
    * )
    * @OA\Tag(name="TEST")
    */
    #[Route('/sqlcontroller', name: 'app_sql')]
    public function index(): JsonResponse
    {
        return $this->json([
            'MESSAGE' => 'Welcome to your new controller!',
            'PATH' => 'src/Controller/SQLController.php',
        ]);
    }
    /**
    * Cette method retourne les valeurs par type et par Id client <!> Pas obliger de mettre un nom entier
    * @OA\Parameter(name="CLIENT_ID", in="header", description="Id du client", required=true, @OA\Schema(type="integer"))
    * @OA\Parameter(name="TYPE", in="header", description="type de la verif", required=true, @OA\Schema(type="string"))
    * @OA\Response(response=200, description="JSON MESSAGE Return type: value: status: statsId: statsNaming:")
    * @OA\Response(response=404, description="JSON ERROR ID|type|StatsRequestClient not found" )
    * @OA\Tag(name="REQUEST")
    */
    #[Route('/api/request', name: 'getRequestClientType.sql', methods: ['POST'])]
    public function getRequestClientType(Request $request, RequestClientRepository $requestRepository, StatsRequestClientRepository $statsRequestClientRepository, SerializerInterface $serializer, EntityManagerInterface $entity): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $clientId = $requestData['Client_id'];
        $type = $requestData['type'];

        if(!$clientId){
            $stringMessage = [
                'ERROR' => "ID not found"];
                return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND); }
        if(!$type){
        $stringMessage = [
            'ERROR' => "Type not found"];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND); }
                
    
        $clientRequests = $requestRepository->findByType($clientId, $type);

        $jsonClientRequests = $serializer->serialize($clientRequests, 'json', ['groups' => 'getTOUT']);
        $decodedClientRequests = json_decode($jsonClientRequests, true);

        foreach ($decodedClientRequests as $clientRequest) {
            $statsRequestId = $clientRequest['statsRequestId'];
    
            $statsUp = $statsRequestClientRepository->find($statsRequestId);
    
            if ($statsUp !== null) {
                $statsUp->setNaming($statsUp->getNaming() + 1);
            
                $entity->persist($statsUp);
                $entity->flush();
            } else {
                $stringMessage = [
                    'ERROR' => "StatsRequestClient not found"];
                return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
            }
        }
        $stringMessage = [
            'MESSAGE' => $jsonClientRequests];
        return new JsonResponse($stringMessage, JsonResponse::HTTP_OK);    
    }
    /**
    * Cette method retourne les informations d'une request à l'aide de sont id 
    * @OA\Parameter(name="Request_ID",in="path", description="Id de la request", required=true, @OA\Schema(type="integer"))
    * @OA\Response(response=200, description="JSON MESSAGE request")
    * @OA\Response(response=404, description="JSON ERROR Client|No REQUEST")
    * @OA\Tag(name="REQUEST")
    */ 
    #[Route('/api/request/{id}', name:"getRequestClient.sql", methods:["GET"])]
    public function getRequestClient(int $id,DataClientRepository $dataClientRepository, SerializerInterface $serializer): JsonResponse{
    
        $client = $dataClientRepository->find($id);
        if (!$client) {
            $stringMessage = [
                'ERROR' => 'Client not found'];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
        }
        $clientRequests = $client->getRequestClient();
        if (!$clientRequests) {
            $stringMessage = [
                'ERROR' => 'NO REQUEST'];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
        }
        $jsonClientRequests = $serializer->serialize($clientRequests, 'json', ['groups' => 'getrequest']);
        $stringMessage = [
            'MESSAGE' => $jsonClientRequests];
        return new JsonResponse(json_encode($stringMessage), JsonResponse::HTTP_OK, [], true);
    }
   /**
    * Cette method retourne les informations d'une stats à l'aide de sont id 
    * @OA\Parameter(name="Stats_ID",in="path", description="Id de la stats", required=true, @OA\Schema(type="integer"))
    * @OA\Response(response=200, description="JSON MESSAGE info stats")
    * @OA\Response(response=404, description="JSON ERROR ID_Stats not found")
    * @OA\Tag(name="Stats")
    */ 
    #[Route('/api/stats/{id}', name:"getStats.sql", methods:["GET"])]
    #[IsGranted("ROLE_ADMIN", statusCode: 423)]
    public function getStats(int $id, StatsRequestClientRepository $repository, SerializerInterface $serializer): JsonResponse{
        $client = $repository->find($id);
        if (!$client) {
            $stringMessage = [
                'ERROR' => 'ID_Stats not found'];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
        }
        $jsonClient = $serializer->serialize($client, 'json', ['groups' => "getStats"]);
        $stringMessage = [
            'MESSAGE' => $jsonClient];
        return new JsonResponse(json_encode($stringMessage), JsonResponse::HTTP_OK, [], true);
    } 
    
    /**
    * Cette method retourne toute les data connexion
    * @OA\Response(response=200, description="ALL data connexion")
    * @OA\Tag(name="ALL")
    */ 
    #[Route('/api/client', name:"getAllClient.sql", methods: ["GET"])]
    public function getAllClient(DataClientRepository $repository, TagAwareCacheInterface $cache, SerializerInterface $serializer){
        $idCacheGetAllClient = "getAllClient";
        $jsonStats = $cache->get($idCacheGetAllClient, function (ItemInterface $item) use ($repository, $serializer)
    {
        $item->tag("clientCache");
        $client = $repository->findBy(['status' => 'on']);        
            return $serializer->serialize($client, 'json', ['groups' => "getClient"]);
    });
        return new JsonResponse($jsonStats, JsonResponse::HTTP_OK, [], true);
    }

   }
