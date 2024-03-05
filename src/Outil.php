<?php

namespace App;

use App\Entity\DataClient;
use mysqli;

class Outil
{
    public function TestConnexion(DataClient $dataClient)
    {
        // Paramètres de connexion à la base de données
        $servername = $dataClient->getIp() . ':' . strval($dataClient->getPort());
        $username = $dataClient->getLogin();
        $password = $dataClient->getPassword();
        $database = $dataClient->getDatabaseclient();
        
        // Connexion
        $conn = new mysqli($servername, $username, $password, $database);
        
        //On vérifie la connexion
        if($conn->connect_error)
        {return null;}

        return $conn;
    }
    public function RecoveryType(DataClient $dataClient)
    {
        $conn = $this->TestConnexion($dataClient);
        if($conn == null)
        {return [];}

        // Requête SELECT
        $sql = "SELECT type FROM devclientcfgverif";
        $result = $conn->query($sql);

        // Vérifier si la requête a réussi
        if ($result === false) {
        die("Erreur d'exécution de la requête : " . $conn->error);
        }

        $data = $result->fetch_all(MYSQLI_ASSOC);
        $typeList = array_column($data, 'type');

        // // Fermer la connexion
        $conn->close();
        return $typeList;

    }
    public function RecoveryValue(DataClient $dataClient, $type)
    {
        $conn = $this->TestConnexion($dataClient);
        if($conn == null) {return;}

        // Vérifier si la requête a réussi
        if ($type === false) {
            die("Erreur d'exécution de la requête : " . $conn->error);
            }

        $sql = "SELECT value FROM devclientprincipalverif where type = '$type'";
        $result = $conn->query($sql);

        // Vérifier si la requête a réussi
        if ($result === false) {
        die("Erreur d'exécution de la requête : " . $conn->error);
        }
        // Récupérer la première ligne sous forme de tableau associatif
        $data = $result->fetch_assoc();

        // Fermer la connexion
        $conn->close();

        // Retourner les données
        return $data['value'] ?? null;

    }
}

