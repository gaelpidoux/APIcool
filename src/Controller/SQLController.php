<?php

namespace App\Controller;

use App\Entity\DataClient;
use App\Entity\RequestClient;
use App\Entity\StatsRequestClient;
use App\Repository\DataClientRepository;
use App\Repository\RequestClientRepository;
use App\Repository\StatsRequestClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use App\Outil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
class SQLController extends AbstractController
{
    
/**
         * Page de test
         * @OA\Parameter(name="", in="", description="return un message ", required=true, @OA\Schema(type="integer"))
         * @OA\Response(response=200, description="",
         * )
         * @OA\Tag(name="TEST")
         */
    //ROUTE DE TEST 
    #[Route('/sqlcontroller', name: 'app_sql')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SQLController.php',
        ]);
    }

    /** 
    * Cette method enregistre une connexion data d'un client, il renvoie sont ID.
    * @OA\Parameter(name="login", in="header", description="Login du CLient", required=true, @OA\Schema(type="string"))
    * @OA\Parameter(name="password", in="header", description="Password du CLient (Enregistrer en claire)", required=true, @OA\Schema(type="string"))
    * @OA\Parameter(name="port", in="header", description="Port du client", required=true, @OA\Schema(type="integer"))
    * @OA\Parameter(name="ip", in="header", description="Ip du Client", required=true, @OA\Schema(type="string"))
    * @OA\Parameter(name="ttl", in="header", description="TTL si il y en a un", @OA\Schema(type="integer"))
    * @OA\Parameter(name="databaseclient", in="header", description="Nom de la database du Client", required=true, @OA\Schema(type="string"))
    * @OA\RequestBody(
    *      description="Example request body",
    *      required=true,
    *      @OA\JsonContent(
    *          type="object",
    *          @OA\Property(property="login", type="string", description="test"),
    *          @OA\Property(property="password", type="string", description="Description for property2"),
    *          @OA\Property(property="port", type="integer", description="Description for property2"),
    *          @OA\Property(property="ip", type="string", description="Description for property2"),
    *          @OA\Property(property="ttl", type="integer", description="Description for property3"),
    *          @OA\Property(property="databaseclient", type="string", description="Description for property3"))
    *      )
    * )
    * @OA\Response(response=201, description="'La connexion du client à comme id: +ID du nouveau client",)
    * @OA\Tag(name="CLIENT")
    */
    #[Route('/api/createclient', name:'sql.createclient', methods:["POST"])]
    public function CreateConnexionClientsql(Request $request, ValidatorInterface $validator,UrlGeneratorInterface $urlGenerator, SerializerInterface $serializer, EntityManagerInterface $entity): JsonResponse
    {
        
        $client = $serializer->deserialize($request->getContent(), DataClient::class,"json");
        $client->setStatus('on');

        $Outil = new Outil();
        $result = $Outil->TestConnexion($client);

        if ($result != null) {
            $entity->persist($client);
            $entity->flush();
            echo 'La connexion du client à comme id: ' . $client->getId();
        }


        $ListType = $Outil->RecoveryType($client);
        for($i=0; $i < count($ListType) ;$i++)
        {
            $requestClient = new RequestClient();
            $requestClient->setClient($client);

            $statsRequestClient = new StatsRequestClient;
            $statsRequestClient->setNaming(0);
            $statsRequestClient->setStatsnaming((int)$client->getId());
    
            
            $requestClient->setStatsRequestClient($statsRequestClient);
            $requestClient->setType($ListType[$i]);
            $requestClient->setTabledata($Outil->RecoveryValue($client,$ListType[$i]));
            $requestClient->setStatus('on');

            $entity->persist($statsRequestClient);
            $entity->persist($requestClient);

        }
        

        $entity->flush();

        return new JsonResponse($client, JsonResponse::HTTP_CREATED, ["Location" => ""],false);
    }
    /**
    * Cette method permet de changer les information de connexion d'un client <!> Pas de test de connexion ni une mise a jour de request
    * @OA\Parameter(name="Client_id", in="path", required=true, description="Id du client", @OA\Schema(type="integer"))
    * @OA\Parameter(name="login", in="header", description="Login du CLient", @OA\Schema(type="string"))
    * @OA\Parameter(name="password", in="header", description="Password du CLient (Enregistrer en claire)", @OA\Schema(type="string"))
    * @OA\Parameter(name="port", in="header", description="Port du client", @OA\Schema(type="integer"))
    * @OA\Parameter(name="ip", in="header", description="Ip du Client", @OA\Schema(type="string"))
    * @OA\Parameter(name="ttl", in="header", description="TTL si il y en a un", @OA\Schema(type="integer"))
    * @OA\Parameter(name="databaseclient", in="header", description="Nom de la database du Client", @OA\Schema(type="string"))
    * @OA\Response(response=200, description="JSON MESSAGE new information OK",)
    * @OA\Response(response=404, description="JSON ERROR ID not found",)
    * @OA\Tag(name="CLIENT")
    */ 
    #[Route('/api/client/{id}', name:"updateClient.sql", methods:["PUT"])]
    public function updateClient(int $id,DataClientRepository $repository, Request $request, SerializerInterface $serializer, EntityManagerInterface $entity): JsonResponse{
        
        $dataClient = $repository->find($id);
        if($dataClient === null){
            $stringMessage = ['ERROR' => 'ID not found'];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
        }

        $updatedQuestion = $serializer->deserialize($request->getContent(),DataClient::class,"json",[AbstractNormalizer::OBJECT_TO_POPULATE => $dataClient]);
        $updatedQuestion->setStatus('on');
        /*$Outil = new Outil();
        $result = $Outil->testconnexion($updatedQuestion);
        if ($result) {echo 'La connexion du client est valide et a bien était modifier!';}
        else {echo "La connexion du client n'est pas valide mais a bien était modifier!";}
        echo "La connexion du client n'est pas valide mais a bien était modifier!";
        */
        $entity->persist($updatedQuestion);
        $entity->flush();
        $stringMessage = ['message' => 'new information OK'];
        return new JsonResponse($stringMessage, JsonResponse::HTTP_OK);
    }
    /**
    * Cette method permet de changer le status du CLient et de Request
    * @OA\Parameter(name="Client_ID",in="path", description="Id du client", required=true, @OA\Schema(type="integer"))
    * @OA\Response(response=200, description="JSON MESSAGE new information OK VALUE $status",)
    * @OA\Response(response=404, description="JSON ERROR ID not found",)
    * @OA\Tag(name="CLIENT")
    */ 
    #[Route('/api/client/softdeleteClient/{id}', name:"softdeleteClient.sql", methods: ["PUT"])]
    public function softdeleteClient(int $id, DataClientRepository $repository, EntityManagerInterface $entity)
    {
        $client = $repository->find($id);

        if($client === null){
            $stringMessage = ['ERROR' => 'ID not found'];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
        }

        $clientBool = $client->getStatus();

        if($clientBool == "off") { $client->setStatus('on');}
        else{ $client->setStatus('off');}
        $entity->persist($client);
        $entity->flush();

        $clientBool = $client->getStatus();
        $stringMessage = [
            'message' => 'new information OK', 
            'value' => $clientBool];
        return new JsonResponse($stringMessage , JsonResponse::HTTP_OK);
    }
    /**
    * Cette method permet de softdelete ou de delete elle afecte les information de connexion d'un client ainsi que ses informations requets
    * @OA\Parameter(name="bool",in="path", description="Bool true = softdelete false = delete", required=true, @OA\Schema(type="bool"))
    * @OA\Parameter(name="Client_ID",in="path", description="Id du client", required=true, @OA\Schema(type="integer"))
    * @OA\Response(response=200, description="JSON MESSAGE softdelete|delete OK")
    * @OA\Response(response=404, description="JSON ERROR ID not found|$bool ce format n'est pas valide" )
    * @OA\Tag(name="CLIENT")
    */ 
    #[Route('/api/client/deleteClient/{id}/{bool}', name:"deleteClient.sql", methods: ["DELETE"])]
    public function deleteClient(int $id, DataClientRepository $repository, string $bool, EntityManagerInterface $entity){
        $client = $repository->find($id);
        if($client === null){
            $stringMessage = ['ERROR' => 'ID not found'];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
        }

        if($bool =='true' || $bool == 'false'){
            $booleanValue = filter_var($bool, FILTER_VALIDATE_BOOLEAN);
            if($booleanValue)
            {
                $client->setStatus('off');
                $entity->persist($client);
                $stringMessage = [
                    'message' => 'softdelete OK',];
            }
            else{
                $entity->remove($client);
                $stringMessage = [
                    'message' => 'delete OK',];
            }
            $entity->flush();     
       
          return new JsonResponse($stringMessage, JsonResponse::HTTP_OK);
        }
      else{
        $stringMessage = [
            'ERROR' => $bool. "ce format n'est pas valide",];
          return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
      }
    }
   
    

/**
    * Cette method retourne les informations d'un client à l'aide de sont id 
    * @OA\Parameter(name="CLIENT_ID",in="path", description="Id du client", required=true, @OA\Schema(type="integer"))
    * @OA\Response(response=200, description="Return les informations du client")
    * @OA\Response(response=404, description="JSON ERROR ID not found" )
    * @OA\Tag(name="CLIENT")
    */    
    #[Route('/api/client/{id}', name:"getclient.sql", methods:["GET"])]
    public function getClient(int $id, DataClientRepository $repository, SerializerInterface $serializer): JsonResponse{
        $client = $repository->find($id);
        if($client === null){
            $stringMessage = ['ERROR' => 'ID not found'];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
        }
        $jsonClient = $serializer->serialize($client, 'json', ['groups' => "getClient"]);
        return new JsonResponse($jsonClient, JsonResponse::HTTP_OK, [], true);
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
    * @OA\Response(response=404, description="JSON ERROR Client|REQUEST not found")
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
                'ERROR' => 'REQUEST not found'];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
        }
        $jsonClientRequests = $serializer->serialize($clientRequests, 'json', ['groups' => 'getrequest']);
        $stringMessage = [
            'MESSAGE' => $jsonClientRequests];
        return new JsonResponse($stringMessage, JsonResponse::HTTP_OK, [], true);
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
        return new JsonResponse($stringMessage, JsonResponse::HTTP_OK, [], true);
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
