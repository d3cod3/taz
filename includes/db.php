<?php

namespace D3COD3;

class MULEDB {

  private static $MULE_config = array(

    "db" => array(
      "host" => "localhost",
      "port" => 3306,
      "name" => "muledb",
      "username" => "mula",
      "password" => "YOUR_PASSWORD" // change this!
    )

  );

  private static $db = false;
  private static $dbh;

  public static function MULEValidate($data){
    $data = trim($data);              // Strip unnecessary characters (extra space, tab, newline)
    $data = stripslashes($data);      // Remove backslashes (\)
    $data = htmlspecialchars($data);  // save as HTML escaped code
    return $data;
  }

  public static function MULECheckDBConnection(){
    if(self::$dbh !== null){
      return 1;
    }else{
      return 0;
    }
  }

  public static function MULEConnect(){
    /**
    * Try connecting to Database Server
    */

    if(self::MULECheckDBConnection() !== 1){
      try{
        self::$dbh = new \PDO("mysql:dbname=" . self::$MULE_config['db']['name'] . ";host=". self::$MULE_config['db']['host'] . ";port=". self::$MULE_config['db']['port'] . ";charset=utf8",
        self::$MULE_config['db']['username'],
        self::$MULE_config['db']['password'],
        array(
          \PDO::ATTR_PERSISTENT => true,
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        )
      );

      self::$db = true;
      return true;
    }catch(\PDOException $e) {
      /**
      * Couldn't connect to Database
      */
      return false;
    }
  }else{
    /**
    * Already connected to Database
    */
    return false;
  }

}

public static function MULEGETData(){
  //SELECT * FROM `ml_content ORDER BY id`
  if(self::$db === true){

    $plug = "";

    $query = "SELECT * FROM ml_content ORDER BY id DESC LIMIT 20";

    try {

      $sql = self::$dbh->prepare($query);
      $sql->execute();
      $result = $sql->fetchAll();

      $jsonArray = array();

      $plug .= '<div><ul>';
      if(count($result) > 0){
        for($i=0;$i<count($result);$i++){
          $jsonArray[$i]['data'] = $result[$i]['data'];
          $jsonArray[$i]['latitude'] = $result[$i]['latitude'];
          $jsonArray[$i]['longitude'] = $result[$i]['longitude'];
          $jsonArray[$i]['angle'] = $result[$i]['angle'];

          $plug .= "<li><img src='".$result[$i]['data']."'";
          $plug .= 'style="max-width:300px;"></img>';
          $plug .= " | ".$result[$i]['latitude']." | ".$result[$i]['longitude']." | ".$result[$i]['altitude']." | ".$result[$i]['angle']."</li>";
        }

      }
      $plug .= '</ul></div>';

      //write to json file
      $fp = fopen('muledata.json', 'w');
      fwrite($fp, json_encode($jsonArray));
      fclose($fp);

      // $plug is for testing
      return $plug;

    }catch ( PDOException $e ) {
      return false;
    }

  }
}

public static function MULEADDData($data,$lat,$long,$alt,$angle){
  // INSERT INTO `ml_content` VALUES (null, :data, :lat, :long, :alt, :angle)
  if(self::$db === true){

    $query = "INSERT INTO ml_content VALUES(null, :data, :lat, :long, :alt, :angle)";

    try {
      $vdata = self::MULEValidate($data);
      $vlat = self::MULEValidate($lat);
      $vlong = self::MULEValidate($long);
      $valt = self::MULEValidate($alt);
      $vangle = self::MULEValidate($angle);

      $sql = self::$dbh->prepare($query);
      $sql->bindValue(":data", $vdata);
      $sql->bindValue(":lat", $vlat);
      $sql->bindValue(":long", $vlong);
      $sql->bindValue(":alt", $valt);
      $sql->bindValue(":angle", $vangle);
      $sql->execute();

      return true;
    }catch ( PDOException $e ) {
      return false;
    }

  }
}

}

?>
