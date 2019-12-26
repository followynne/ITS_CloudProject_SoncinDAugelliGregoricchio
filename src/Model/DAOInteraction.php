<?php

declare(strict_types=1);

namespace SimpleMVC\Model;
//chdir(dirname(__DIR__));

use \PDO;
use PDOException;

/**
 * This class opens connection with Microsoft SQL Server and interacts
 * for CRUD operations.
 */
class DAOInteraction
{

  private $conn;
  private $idContainer;

  /**
   * The constructor gets a PDO Instance.
   */
  function __construct(PDO $pdo)
  {
    $this->conn = $pdo;
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->conn->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
  }

  /*
  * Set the ID Container for the user operations.
  */
  function setIdContainer($id)
  {
    $this->idContainer = $id;
  }

  /**
   * Given a @SQLQuery without parameters, the func prepare a query
   * on the PDO object, execute and fetch all matches.
   */
  function prepareAndExecuteQuery(string $sqlQuery)
  {
    try {
      $query = $this->conn->prepare($sqlQuery);
      $query->execute();
      return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      print("Error executing query, please check your data input.");
      die();
    }
  }

  /**
   * Given mail and password, check
   * 1) if user already exist
   * 2) if password sent is equal to the one stored in DB.
   */
  function validateLogin($mail, $password)
  {
    $result = $this->checkUserExistence($mail);
    if (!empty($result)) {
      if ($mail == $result['mail'] && password_verify($password, $result['pwd'])) {
        return $result;
      }
    }
    return false;
  }

  function checkUserExistence($mail)
  {
    try {
      $sqlQuery = "SELECT u.name, u.mail, u.pwd, c.IdContainer, c.ContainerName
                    FROM Utente u, Container c
                    WHERE u.Id = c.IdUtente AND mail=:mail;";
      $query = $this->conn->prepare($sqlQuery);
      $query->execute([':mail' => '' . $mail . '']);
      return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      print("Error retrieving user data.");
      die(print_r($e));
    }
  }

  function registerUser(array $arr)
  {
    $id = (int) $this->insertUser($arr);
    if ($id != 0) {
      $idcont = (int) $this->addDataContainer($id, $arr['containername']);
      if ($idcont != 0) {
        return 'correct';
      }
    }
  }

  function insertUser(array $arr)
  {
    try {
      $sql = 'insert into Utente (Name, Mail, Pwd) values (:user,  :mail, :pwd);';
      $set = $this->conn->prepare($sql);
      $set->bindParam(':user', $arr['user']);
      $set->bindParam(':mail', $arr['email']);
      $set->bindParam(':pwd', $arr['pwd']);
      $set->execute();
      return $this->conn->lastInsertId();
    } catch (PDOException $e) {
      return 'error saving user data';
    }
  }

  function addDataContainer($idU, $containername)
  {
    try {
      $sqlQuery = "Insert INTO Container(IdUtente, ContainerName) VALUES (:idU, :name);";
      $query = $this->conn->prepare($sqlQuery);
      $query->execute([':idU' => '' . $idU . '', ':name' => '' . $containername . '']);
      return $this->conn->lastInsertId();
    } catch (PDOException $ex) {
      return 'error';
    }
  }

  /**
   * Given a @par blob ReferenceName and the actual blob Name,
   * it adds blob information on the Database.
   */
  function addDataPhotos($refName, $name)
  {
    try {
      $sqlQuery = "Insert INTO Photo(IdContainer, ReferenceName, Name) VALUES (:idC, :refName, :name);";
      $query = $this->conn->prepare($sqlQuery);
      $query->execute([':idC' => $this->idContainer, ':refName' => '' . $refName . '', ':name' => '' . $name . '']);
      return $id = $this->conn->lastInsertId();
    } catch (PDOException $e) {
      print("Error sending image data.");
      die(print_r($e));
    }
  }

  /**
   * Given a @par PhotoId and PhotoTag, insert a new record N:N in the table PhotoTag.
   */
  function addDataPhotoTag($idP, $idT)
  {
    try {
      $sqlQuery = " Insert INTO PhotoTag(IdPhoto, IdTag) VALUES (:idP, :idT);";
      $query = $this->conn->prepare($sqlQuery);
      $query->execute([':idP' => $idP, ':idT' => $idT]);
    } catch (PDOException $e) {
      print("Error sending image data.");
      die(print_r($e));
    }
  }

  /**
   * Given a @par tag, check if it's already in the table Tag.
   * If the tag is not there, it adds the tag and return its id, otherwise it searches the tag and returns
   * its current id.
   */
  function addDataTag($tag)
  {
    $sqlQuery = "BEGIN
                 IF NOT EXISTS (SELECT * FROM Tag 
                 WHERE Name =:name)
                   BEGIN
                   INSERT INTO Tag (Name)
                   VALUES (:name2)
                   END
                 END";
    try {
      $query = $this->conn->prepare($sqlQuery);
      $result = $query->execute([':name' => '' . $tag . '', ':name2' => '' . $tag . '']);
      if ($result === true) {
        $sqlQuery2 = "SELECT Id FROM Tag WHERE Name =:name3;";
        $query2 = $this->conn->prepare($sqlQuery2);
        $query2->execute([':name3' => '' . $tag . '']);
        $result2 = $query2->fetch();
        return $result2[0];
      } else {
        return $id = $this->conn->lastInsertId();
      }
    } catch (PDOException $e) {
      print("Error sending image data.");
      die(print_r($e));
    }
  }

  /**
   * Given a PhotoId and an exif array, the function updates the
   * Photo DBTable with the proper exif information related to the photo.
   */
  function insertExifData($idPhoto, array $data)
  {
    try {
      $sqlQuery = 'update Photo set MB = :filesize, FileType = :filetype, Height = :height, Width = :width,
      Brand = :brand, Model = :model, Orientation = :orientation, Date = :date, Latitude = :latitude, Longitude = :longitude WHERE Id = :id;';
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
  function getBlobTags($blobname)
  {
    $sqlQuery = "select t.Name from Tag as t
                inner join PhotoTag as p on t.Id = p.IdTag
                inner join Photo as ph on p.IdPhoto = ph.Id
                WHERE ph.ReferenceName = :name and ph.IdContainer = :idContainer";
    $query = $this->conn->prepare($sqlQuery);
    $query->bindParam(':name', $blobname);
    $query->bindParam(':idContainer', $this->idContainer);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_COLUMN, 0);
    return implode(', ', $result);
  }

  /**
   * Given a Photo Reference Name, the function retrieve from the
   * Photo DBTable the exif information related to it.
   */
  function getBlobExif($blobname)
  {
    try {
      $sqlQuery = 'SELECT Name, MB, FileType, Height, Width, Brand, Model, Orientation,
      Date, Latitude, Longitude from Photo WHERE ReferenceName = :name and IdContainer = :idContainer';
      $query = $this->conn->prepare($sqlQuery);
      $query->bindParam(':name', $blobname);
      $query->bindParam(':idContainer', $this->idContainer);
      $query->execute();
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      foreach ($result[0] as $key => $value) {
        if ($value == "") {
          unset($result[0][$key]);
        }
      }
      return $result;
    } catch (PDOException $e) {
      print("Error getting image data.");
    }
  }

  /*
  * Given a Reference Name, deletes a Blob Information from the DB.
  */
  function deleteBlob(string $name)
  {
    try {
      $res = $this->deleteBlobTags($name);
      $sqlQuery = 'DELETE Photo FROM Photo
                  WHERE ReferenceName = :name AND IdContainer = :idContainer';
      $query = $this->conn->prepare($sqlQuery);
      $query->bindParam(':name', $name);
      $query->bindParam(':idContainer', $this->idContainer);
      $query->execute();
      return 'successful delete';
    } catch (PDOException $e) {
      throw new PDOException;
    }
  }

  /*
  * Given a Reference Name, deletes all Blob specific-tags from the DB.
  */
  function deleteBlobTags(string $name)
  {
    try {
      $sqlQuery = 'DELETE PhotoTag FROM PhotoTag pt inner join Photo p on p.Id = pt.IdPhoto
                  WHERE p.ReferenceName = :name and p.IdContainer = :idContainer';
      $query = $this->conn->prepare($sqlQuery);
      $query->bindParam(':name', $name);
      $query->bindParam(':idContainer', $this->idContainer);
      $s = $query->execute();
      return 'done';
    } catch (PDOException $e) {
      throw new PDOException;
    }
  }

  /**
   * Given an array of @data, the func returns a SQL query
   * to: search for all results that match with ALL tags (AND logic), if given, and
   * optional exif specs (OR Logic).
   */
  function searchBlobsByColumn(array $data)
  {
    $sql = 'SELECT Photo.ReferenceName FROM Photo';
    if (isset($data['Tag.Name'])) {
      $sql .= ' INNER JOIN PhotoTag ON Photo.Id=PhotoTag.IdPhoto
               INNER JOIN Tag ON PhotoTag.IdTag = Tag.Id
               WHERE IdContainer = ' . $this->idContainer . ' AND ';
    } else {
      $sql .= ' WHERE ';
    }
    foreach ($data as $key => $values) {
      $querypar = $values;
      $sql .= $key . ' IN (\''
        . $querypar . '\') AND ';
    }
    $sql = substr($sql, 0, (strlen($sql) - 4)) . 'GROUP BY Photo.ReferenceName ';
    if (isset($data['Tag.Name'])) {
      $sql .= 'HAVING COUNT(Tag.Name) = ' . count(explode(', ', $data['Tag.Name']));
    }
    return $this->prepareAndExecuteQuery($sql);
  }

  /**
   * Retrieve all Photos with Lat&Lon (set previously from Exif Data Upload)
   * to create list for markers creation in map page.
   */
  function retrieveDataForMapMarkers()
  {
    $sqlQuery = 'select ReferenceName, Latitude, Longitude from Photo WHERE Latitude is not null and Longitude is not null';
    $result = $this->prepareAndExecuteQuery($sqlQuery);
    return $result;
  }
}
