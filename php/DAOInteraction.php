<?php
// for databases datas
declare(strict_types=1);

namespace AzureClasses;

use Dotenv\Dotenv;
use \PDO;

class DAOInteraction {

  private $conn;

  function __construct(){
    $dotenv = Dotenv::create(__DIR__.'/../');
    $dotenv->load();
    try {
      $this->conn = new PDO($_ENV['DB_STRING'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
      print("Error connecting to SQL Server.");
      die(print_r($e));
    }
  }

  function prepareAndExecuteQuery($sqlQuery){
    $query = $this->conn->prepare($sqlQuery);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  function searchBlobsByTag(array $tags){
    $querypar = "";
    foreach($tags as $tag){
        $querypar .= "'" . $tag . "',";
    }
    $sql = 'select Photo.Name from Photo
            inner join Container on Photo.IdContainer = Container.IdContainer
            inner join PhotoTag on Photo.Id=PhotoTag.IdPhoto
            inner join Tag on PhotoTag.IdTag = Tag.Id
            where ContainerName = \'test\' and Tag.Name in ('
            . substr($querypar, 0, strlen($querypar)-1) .
            ') group by Photo.Name having count(*) =' . count($tags);
    return $this->prepareAndExecuteQuery($sql);
  }

}

 ?>
