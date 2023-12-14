<?php

namespace App\Model;

use App\Database\Database;
use PDO;


class BaseModel
{
    protected $connection;

    public function getConnection()
    {
        // Instancier la classe Database et appeler la méthode connect() au moment de la demande
        $database = Database::getInstance();
        $this->connection = $database->getConnection();

        return $this->connection;
    }

    public function paginate($query, $params = [], $page = 1, $perPage = 10)
    {
        try {
            // Calcul du décalage
            $offset = ($page - 1) * $perPage;

            // Ajout de la clause LIMIT à la requête
            $query .= " LIMIT :offset, :perPage";

            $stmt = $this->getConnection()->prepare($query);

            // Liaison des paramètres
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);

            // Liaison des autres paramètres
            foreach ($params as $key => $value) {
                $stmt->bindParam(":$key", $value);
            }

            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Retourner les résultats
            return $results;
        } catch (Exception $e) {
            // Vous pouvez choisir de lever une exception ici ou de la gérer autrement
            throw new Exception($e->getMessage());
        }
    }
}
