<?php
declare(strict_types=1);

namespace AzureClasses;

use Dotenv\Dotenv;
use \PDO;
/**
 * This class opens connection with Microsoft SQL Server and interacts
 * for CRUD operations.
 */
class DAOInteraction {

  private $conn;

  /**
   * The constructor gets the .env credential file and use them credentials
   * to create a PDO Connection.
   */
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

  /**
   * Given a @SQLQuery, the func prepare a query
   * on the PDO object (created on object construction),
   * execute and fetch all matches.
   */
  function prepareAndExecuteQuery($sqlQuery){
    $query = $this->conn->prepare($sqlQuery);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }
  /*WIP
  function userRegistrationInDatabase($dataarray){
    do {
      $containername = substr($dataarray['username'], 0, 8) . '_' . rand();
    } while (checkIfContainerNameAlreadyExists($containername));

  }

  function checkIfContainerNameAlreadyExists($containername){
    $sql = "select * from";
  }*/

  /**
   * Given an array of @tags, the func returns a SQL query
   * to: search for all results that match with ALL tags given.
   */
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

  function checkUser($mail, $password){
    //$pwd = password_hash($post['pwd'], PASSWORD_DEFAULT);
    $sqlQuery = "SELECT mail, pwd FROM utente WHERE mail=:mail AND pwd=:password;";
    $query = $this->conn->prepare($sqlQuery);
    $query->execute([':mail' => ''.$mail.'', ':password' => ''.$password.'']);
    $result = $query->fetchAll();
    print_r($result);

    // password_verify($post['pwd'], $dataToCheck['pwd'];
    if ($mail == $result[0]['mail'] && $password == $result[0]['pwd']){
       if (isset($mail)){
    return  $mail;
       }else{
        return "";
       }
    return $mail;
        }
    else {
    //$templates->render('_homepage',$_SESSION['mail']);
    //return $templates->render('start',$_SESSION['start']);
    //echo "sessione non compiuta";
    }
  }
}
