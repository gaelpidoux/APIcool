<?php

namespace App\Controller;

use App\Entity\DataClient;
use App\Entity\RequestClient;
use App\Entity\StatsRequestClient;
use App\Repository\DataClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Outil;
use App\Repository\RequestClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;

class ClientController extends AbstractController
{

    /**
    * Page de test clientcontroller
    * @OA\Parameter(name="", in="", description="return un message ", required=true))
    * @OA\Response(response=200, description="",
    * )
    * @OA\Tag(name="TEST")
    */
    #[Route('/clientcontroller', name: 'app_client')]
    public function index(): JsonResponse
    {
        return $this->json([
            'MESSAGE' => 'Welcome to your new controller!',
            'PATH' => 'src/Controller/ClientController.php',
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
        $entity->persist($updatedQuestion);
        $entity->flush();
        $stringMessage = ['MESSAGE' => 'new information OK'];
        return new JsonResponse($stringMessage, JsonResponse::HTTP_OK);
    }
    /**
    * Cette method permet de changer le status du CLient et de Request <!> On ou off
    * @OA\Parameter(name="Client_ID",in="path", description="Id du client", required=true, @OA\Schema(type="integer"))
    * @OA\Response(response=200, description="JSON MESSAGE new information OK VALUE $status",)
    * @OA\Response(response=404, description="JSON ERROR ID not found",)
    * @OA\Tag(name="CLIENT")
    */ 
    #[Route('/api/client/softdeleteClient/{id}', name:"softdeleteClient.sql", methods: ["DELETE"])]
    public function softdeleteClient(int $id, DataClientRepository $repository, RequestClientRepository $requestClientRepository, EntityManagerInterface $entity)
    {
        $client = $repository->find($id);

        if($client === null){
            $stringMessage = ['ERROR' => 'ID not found'];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
        }

        $clientBool = $client->getStatus();
        $requestClient = $requestClientRepository->findByClient($id);
        for($i = 0; $i < count($requestClient); $i++){
            $test = $requestClientRepository->find($requestClient[$i]);
            if($clientBool == "off") { 
                $client->setStatus('on');
                $test->setStatus('on');
            }
            else{ 
                $client->setStatus('off');
                $test->setStatus('off');
            }
        }
       



        $entity->persist($client);
        $entity->persist($test);
        $entity->flush();

        $clientBool = $client->getStatus();
        $stringMessage = [
            'MESSAGE' => 'new information OK', 
            'VALUE' => $clientBool];
        return new JsonResponse($stringMessage , JsonResponse::HTTP_OK);
    }
    /**
    * Cette method permet de delete elle afecte les information de connexion d'un client ainsi que ses informations requets
    * @OA\Parameter(name="Client_ID",in="path", description="Id du client", required=true, @OA\Schema(type="integer"))
    * @OA\Response(response=200, description="JSON MESSAGE delete OK")
    * @OA\Response(response=404, description="JSON ERROR ID not found" )
    * @OA\Tag(name="CLIENT")
    */ 
    #[Route('/api/client/deleteClient/{id}', name:"deleteClient.sql", methods: ["DELETE"])]
    public function deleteClient(int $id, DataClientRepository $repository, EntityManagerInterface $entity){
        $client = $repository->find($id);
        if($client === null){
            $stringMessage = ['ERROR' => 'ID not found'];
            return new JsonResponse($stringMessage, JsonResponse::HTTP_NOT_FOUND);
        }
        else{
            $entity->remove($client);
            $stringMessage = ['MESSAGE' => 'delete OK',];
        }
        $entity->flush();     
       
        return new JsonResponse($stringMessage, JsonResponse::HTTP_OK);
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

}
