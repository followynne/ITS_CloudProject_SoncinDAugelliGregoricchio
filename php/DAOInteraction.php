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
      $this->conn->setAttribute (PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
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
    try {
      $query = $this->conn->prepare($sqlQuery);
      $query->execute();
      return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      print("Error executing query, please check your data input.");
      die(print_r($e));
    }
  }

  /**
   * Given an array of @data, the func returns a SQL query
   * to: search for all results that match with ALL tags (AND logic), if given, and
   * optional exif specs (OR Logic).
   */
  function searchBlobsByColumn(array $data, $idcontainer){
    $sql = 'select Photo.ReferenceName from Photo ';
    if (isset($data['Tag.Name'])){
      $sql .= 'inner join PhotoTag on Photo.Id=PhotoTag.IdPhoto
              inner join Tag on PhotoTag.IdTag = Tag.Id ';
    }
    $sql .= 'where IdContainer = '. $idcontainer;
    foreach($data as $key=>$values){
      $querypar = $values;
      $sql .= ' and '. $key .' in (\''
            . $querypar .
            '\') ';
    }
    $sql .= 'group by Photo.ReferenceName ';
    if (isset($data['Tag.Name'])){
      $sql .= 'having count(Tag.Name) = '. count(explode(', ', $data['Tag.Name']));
    }
    print_r($sql);
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

  /**
   * Given a PhotoId and an exif array, the function updates the
   * Photo DBTable with the proper exif information related to the photo.
   */
  function insertExifData($idPhoto, array $data){
    try {
      $sqlQuery = 'update Photo set MB = :filesize, FileType = :filetype, Height = :height, Width = :width,
      Brand = :brand, Model = :model, Orientation = :orientation, Date = :date, Latitude = :latitude, Longitude = :longitude where Id = :id;';
      $query = $this->conn->prepare($sqlQuery);
      $null = null;
      $query->bindParam(':id', $idPhoto);
      $data['filesize'] != 'NULL' ? $query->bindParam(':filesize', $data['filesize']) : $query->bindParam(':filesize', $null, PDO::PARAM_NULL);
      $data['filetype'] != 'NULL' ? $query->bindParam(':filetype', $data['filetype'], PDO::PARAM_STR) : $query->bindParam(':filetype', $null, PDO::PARAM_NULL);
      $data['height'] != 'NULL' ? $query->bindParam(':height', $data['height']) : $query->bindParam(':height', $null, PDO::PARAM_NULL);
      $data['width'] != 'NULL' ? $query->bindParam(':width', $data['width']) : $query->bindParam(':width', $null, PDO::PARAM_NULL);
      $data['brand'] != 'NULL' ? $query->bindParam(':brand', $data['brand'], PDO::PARAM_STR) : $query->bindParam(':brand', $null, PDO::PARAM_NULL);
      $data['model'] != 'NULL' ? $query->bindParam(':model', $data['model'], PDO::PARAM_STR) : $query->bindParam(':model', $null, PDO::PARAM_NULL);
      $data['orientation'] != 'NULL' ? $query->bindParam(':orientation', $data['orientation'], PDO::PARAM_STR) : $query->bindParam(':orientation', $null, PDO::PARAM_NULL);
      $data['date'] != 'NULL' ? $query->bindParam(':date', $data['date'], PDO::PARAM_STR) : $query->bindParam(':date', $null, PDO::PARAM_NULL);
      $data['latitude'] != 'NULL' ? $query->bindParam(':latitude', $data['latitude']) : $query->bindParam(':latitude', $null, PDO::PARAM_NULL);
      $data['longitude'] != 'NULL' ? $query->bindParam(':longitude', $data['longitude']) : $query->bindParam(':longitude', $null, PDO::PARAM_NULL);
      $result = $query->execute();
    } catch (PDOException $e) {
      print("Error sending image data.");
      die(print_r($e));
    }
  }

  /**
   * Given a Photo Reference Name, the function retrieve from the
   * Photo DBTable the tags related to it.
   */
  function getTagsofBlob($blobname, $idcontainer){
    $sqlQuery = "select t.Name from Tag as t
                inner join PhotoTag as p on t.Id = p.IdTag
                inner join Photo as ph on p.IdPhoto = ph.Id
                where ph.ReferenceName = :name and ph.IdContainer = :idContainer";
    $query = $this->conn->prepare($sqlQuery);
    $query->bindParam(':name', $blobname);
    $query->bindParam(':idContainer', $idcontainer);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_COLUMN, 0);
    return implode(', ', $result);
  }

  /**
   * Given a Photo Reference Name, the function retrieve from the
   * Photo DBTable the exif information related to it.
   */
  function getExifForBlob($blobname, $idcontainer){
    try {
      $sqlQuery = 'select Name, MB, FileType, Height, Width, Brand, Model, Orientation,
      Date, Latitude, Longitude from Photo where ReferenceName = :name and IdContainer = :idContainer';
      $query = $this->conn->prepare($sqlQuery);
      $query->bindParam(':name', $blobname);
      $query->bindParam(':idContainer', $idcontainer);
      $query->execute();
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      foreach($result[0] as $key=>$value){
        if ($value == ""){
          unset($result[0][$key]);
        }
      }
      return $result;
    } catch (PDOException $e) {
      print("Error getting image data.");
      die(print_r($e));
    }
  }

  /**
  * Retrieve all Photos with Lat&Lon (set previously from Exif Data Upload)
  * to create list for markers creation in map page.
  */
  function retrieveDataForMapMarkers(){
    $sqlQuery = 'select Name, Latitude, Longitude from Photo where Latitude is not null and Longitude is not null';
    $result = $this->prepareAndExecuteQuery($sqlQuery);
    return $result;
  }
}
