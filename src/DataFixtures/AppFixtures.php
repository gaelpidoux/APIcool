<?php

namespace App\DataFixtures;

use App\Entity\DataClient;
use App\Entity\DEVCLIENTCFGVERIF;
use App\Entity\DEVCLIENTPRINCIPALVERIF;
use App\Entity\RequestClient;
use App\Entity\StatsRequestClient;
use App\Repository\StatsRequestClientRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use App\Outil;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\TestServiceContainerRealRefPass;


class AppFixtures extends Fixture
{
/**
  * @var Generator
  */
private Generator $faker;
/**
  * Password Hasher
  * 
  * @var UserPasswordHasherInterface
  */

 private UserPasswordHasherInterface $userPasswordHasher;
 public function __construct(UserPasswordHasherInterface $userPasswordHasher)
 {
     $this->faker = Factory::create();
     $this->userPasswordHasher = $userPasswordHasher;
 }
    public function load(ObjectManager $manager): void
    { 
      printf("\nFixtures> ");
      
       //GENERATION DES VALEUR DANS DEV_CLIENT...
       $Listnbr = [0,0,0,0,0,0];
       $ListVerif = ['SERVICE_RUNNING','COUNT_LOG','MEMORY_PERF','CPU_PERF','STORAGE_SPACE','NUMBER_OF_FILE'];

       for ($i = 0; $i < 10; $i++) {
          printf("\nGENERATION DES VALEUR DANS DEV_CLIENT $i> ");
          $nbr = $this->faker->numberBetween(0, 5);
          $randomVerif = $ListVerif[$nbr];

          $Listnbr[$nbr]++;
          $randomVerif = $randomVerif . "_" . $Listnbr[$nbr];

          $devClientCfgVerif = new DEVCLIENTCFGVERIF();
          $devClientCfgVerif->setHint($this->faker->regexify('[a-zA]{' . $this->faker->numberBetween(1, 10) . '}'));
          $devClientCfgVerif->setType($randomVerif);

          $devClientPrincipalVerif = new DEVCLIENTPRINCIPALVERIF();
          $devClientPrincipalVerif->setVERIFId($devClientCfgVerif);
          $devClientPrincipalVerif->setType($randomVerif);
          $devClientPrincipalVerif->setValue($this->faker->numberBetween(0 ,100));

          $manager->persist($devClientCfgVerif);
          $manager->persist($devClientPrincipalVerif);
       }

      //GENERATION DE NOM DE CLIENT A SUPPR APRES RENDUS
        $password = $this->faker->password(4,10);
        for ($i = 0; $i < 5; $i++) {
          printf("\nGENERATION DE NOM DE CLIENT N $i> ");
            $client = new DataClient();
            $client->setLogin($this->faker->userName());
            $client->setPassword($this->userPasswordHasher->hashPassword($client, $password));
            $client->setport($this->faker->randomNumber(4 ,false));
            $client->setIp($this->faker->numberBetween(0 ,255). "." . $this->faker->numberBetween(0 ,255). "." . $this->faker->numberBetween(0 ,255) . "." . $this->faker->numberBetween(0 ,255));
            $client->setTTL($this->faker->optional(0.5)->numberBetween(1 ,255));
            $client->setDatabaseclient($this->faker->regexify('[a-zA]{' . $this->faker->numberBetween(1, 10) . '}'));
            $client->setStatus('on');
            
            $manager->persist($client);   
       }

       
      //GENERATION DU CLIENT LOCAL
       printf("\nGENERATION DU CLIENT LOCAL> ");
       $client->setLogin("gpidoux");
       //$client->setPassword($this->userPasswordHasher->hashPassword($client, "gpidoux"));
       $client->setPassword("gpidoux");
       $client->setPort(3306);
       $client->setIp("localhost");
       $client->setDatabaseclient("bddhotline");
       $client->setStatus('on');

       $manager->persist($client);
      

      //GENERATION DE REQUEST_CLIENT DU LOCAL
      printf("\nGENERATION DE REQUEST_CLIENT DU LOCAL> ");
      $Outil = new Outil();
      $ListType = $Outil->RecoveryType($client);
      for($i=0; $i < count($ListType) ;$i++)
      {
        $request = new RequestClient();
        $request->setClient($client);

        //GENERATION DE STATS DU REQUEST
        printf("\nGENERATION DE STATS DU REQUEST $i> ");
        $stats = new StatsRequestClient();
        $stats->setNaming(0);
        $stats->setStatsnaming('test');

        

        $request->setStatsRequestClient($stats);
        $request->setType($ListType[$i]);
        $request->setTabledata($Outil->RecoveryValue($client,$ListType[$i]));
        $request->setStatus('on');
        
        $manager->persist($stats);
        $manager->persist($request);
      }
       $manager->flush();
       printf("\n\nFin Fixtures> \n");
    }
  }