<?php
/* 
	-Easy Controll (ECTR)-
	Author: Just Adil
	Group: Just a Code Space
	GitHub: https://GitHub.com/A01L
	Version: ECTR 0.6v ULTIMATE
 */


//Set all config for gl-obal
require_once "config.php";
require_once 'Mobile_Detect.php';
include('qrlib.php');
//For function QrGen
$qr = new QR_BarCode();
session_start();


//Randomeizer number
function rand_n($length = 6)
	{
		$arr = array(
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

//Get and saver file
	function get_file($path, $name, $newn = "null"){
		if (!@copy($_FILES["$name"]['tmp_name'], $path.$_FILES["$name"]['name'])){
			return 1;
			}
		else {
			$fn = $_FILES["$name"]['name'];
			$type = format($fn);
			if ($newn != "null") {
				rename("$path$fn", "$path$newn.$type");
				return "$newn.$type";
			}
			else{
				$fnn=str_replace( " " , "_" , $_FILES["$name"]['name']);
				rename("$path$fn", "$path$fnn");
				return "$fnn";
			}
		}
	}


//Get foramt from file
	function format($file){
		 $temp= explode('.',$file);
		 $extension = end($temp);
		 return $extension;
	}

//Start classes

class DBC {

	//Count rows
	public static function count($table, $ma){
		$query = "SELECT * FROM `".$table."` WHERE ";
		$value = "VALUES (";

        # create query structure of array
		foreach(array_keys($ma) as $key){
			$query = $query."`".$key."` = '".$ma["$key"]."' AND ";
		}
		$query = mb_eregi_replace("(.*)[^.]{4}$", '\\1', $query);
		$sql = $query;

        # connected connect
		$conn = $GLOBALS['connect'];
		if($result = $conn->query($sql)){
			$rowsCount = $result->num_rows; # ID - constant
			return $rowsCount;
			# return $rowsCount;
			$result->free();
		}

	}

	//Add data to table
	public static function insert($table, $values){
		$ma = $values;
		$query = "INSERT INTO `".$table."`(";
		$value = "VALUES (";
		foreach(array_keys($ma) as $key){
			$query = $query."`".$key."`, ";
		}
		$query = mb_eregi_replace("(.*)[^.]{2}$", '\\1', $query);
		$query = $query.")";
		foreach(array_keys($ma) as $key){
			$value = $value."'".$ma["$key"]."', ";
		}
		$value = mb_eregi_replace("(.*)[^.]{2}$", '\\1', $value);
		$value = $value.")";
		$full = $query." ".$value;
		mysqli_query($GLOBALS['connect'], $full);
		return $full;
	}

	//Update data in table
	public static function update($table, $data, $id){
		$query = "UPDATE `".$table."` SET ";
		foreach(array_keys($data) as $key){
			$query = $query."`".$key."` = '".$data["$key"]."', ";
		}
		$query = mb_eregi_replace("(.*)[^.]{2}$", '\\1', $query);
		$query = $query." WHERE `id` = ".$id;
		mysqli_query($GLOBALS['connect'], $query);
		return $query;
	}

	//Get data from table
	public static function select($table, $index, $index2, $data='a'){

    $connect = $GLOBALS['connect'];  

    if($data != 'a'){
          $querry = mysqli_query($connect, "SELECT * FROM `$table` WHERE `$index` = '$index2'");
          $datas = mysqli_fetch_assoc($querry);
          return $datas["$data"];
    }
    else{  
          $querry = mysqli_query($connect, "SELECT * FROM `$table` WHERE `$index` = '$index2'");
          $datas = mysqli_fetch_assoc($querry);
          return $datas;
    }
  }


  public static function select2($table, $index, $index2, $index3, $index4, $data='a'){

      $connect = $GLOBALS['connect'];  
  
    if($data != 'a'){
          $querry = mysqli_query($connect, "SELECT * FROM `$table` WHERE `$index` = '$index2' AND `$index3` = '$index4'");
          $datas = mysqli_fetch_assoc($querry);
          return $datas["$data"];
    }
    else{  
          $querry = mysqli_query($connect, "SELECT * FROM `$table` WHERE `$index` = '$index2' AND `$index3` = '$index4'");
          $datas = mysqli_fetch_assoc($querry);
          return $datas;
    }
  }

  // Func for getting goods info Used for (/ru/documents/completion.php)
  public static function select3($table, $index, $index2, $index3, $index4, $price){

      $connect = $GLOBALS['connect'];  
    
          $querry = mysqli_query($connect, "SELECT * FROM `$table` WHERE `$index` = '$index2' AND `$index3` = '$index4' AND `price` = '$price'");
          $datas = mysqli_fetch_assoc($querry);
          return $datas;
  }

	//Get data from table
	public static function show($table, $data='a'){

      $connect = $GLOBALS['connect'];  
    
      if($data != 'a'){
        $querry = mysqli_query($connect, "SELECT * FROM `$table` ORDER BY `id` DESC");
        $datas = mysqli_fetch_assoc($querry);
        return $datas["$data"];
      }
      else{  
        $querry = mysqli_query($connect, "SELECT * FROM `$table` ORDER BY `id` DESC");
        return $querry;
      }
	}

  public function ssg_data($link, $data){
      $ch = curl_init("$link?" . http_build_query($data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HEADER, false);
      $return = curl_exec($ch);
      curl_close($ch);
      return $return;
    
  }

	//Delete data
	public static function delete($table ,$index, $index2){
		$connect = $GLOBALS['connect'];  
    	mysqli_query($connect, "DELETE FROM `$table` WHERE `$index` = $index2");
  }

}
class SS{
  public static function is($tab){
      if(!$_SESSION['SSTab'][$tab]){
          return false;
      }
      return true;
  }

  public static function set($tab, $key, $data){
      $_SESSION['SSTab'][$tab]['data'][$key] = $data;
      return true;
  }

  public static function select($tab, $key){
      if(!$_SESSION['SSTab'][$tab]){
          return "Table not found";
      }
      elseif(!$_SESSION['SSTab'][$tab]['data'][$key]){
          return "Key not found";
      }
      return $_SESSION['SSTab'][$tab]['data'][$key];
  }

  public static function delete($tab, $key){
      unset($_SESSION['SSTab'][$tab]['data'][$key]);
      return true;
  }

  public static function destroy($tab){
      unset($_SESSION['SSTab'][$tab]);
      return true;
  }

  public static function rows($tab){
      return count($_SESSION['SSTab'][$tab]['data']);
  }

}
class DTC {
    //Send [GET] data to link
    public static function oget($link, $data){
      $ch = curl_init("$link?" . http_build_query($data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HEADER, false);
      $return = curl_exec($ch);
      curl_close($ch);
      return $return;
    
  }

  //Send [POST] data to link
  public static function opost($link, $data){
      $ch = curl_init("$link");
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HEADER, false);
      $re = curl_exec($ch);
      curl_close($ch);	
      return $re;
    
  }
}
class STR{
  public static function cut($start, $text, $end){
      $t1 = mb_eregi_replace("(.*)[^.]{".$end."}$", '\\1', $text);
      $t2 = mb_eregi_replace("^.{".$start."}(.*)$", '\\1', $t1);
      return $t2;
  }
}
class GEND {

	//Qr Generate example: qr('https://jcodes.space', 'my/adil.png')
	public static function qr($data, $path){
		$qr = new QR_BarCode();
        $qr->info($data);
        $qr->qrCode(500, $path);
	}

	//Generate random symbols
	public static function str($length = 6)
	{
		$arr = array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}
	
	//Generate random numbers
	public static function int($length = 6)
	{
		$arr = array(
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	//Generate random (numbers and symbols)
	public static function mix($length = 6)
	{
		$arr = array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
			'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	//Generate link for whatsapp
	public static function wame($number, $msg = " "){
		$data=urlencode($msg);
		$link="https://api.whatsapp.com/send?phone=$number&text=$data";
		return $link;
	}
}

//Router controller
class Router {
	//Routing for getting view (adding new url)
	public static function get($link, $path){
		global $routes_ectr;
		$routes_ectr["$link"] = "$path";
	}

	//Router VIEW activator 
	public static function on(){
		global $routes_ectr;
		global $routes_ectr_404;

		$url = $_SERVER['REQUEST_URI'];
		$url = explode('?', $url);
		$url = $url[0];
		if (array_key_exists($url, $routes_ectr)) {
			$handler = $routes_ectr[$url];
			include "view/".$handler;
		} else {
			include $routes_ectr_404;
		}
	}

	//Redirecting 
	public static function redirect($url, $sleep = 0){
		header('Refresh: '.$sleep.'; url='.$url);
		exit();
	}

	//Redirecting 
	public static function redirectr($url){
		header('Location: '.$url);
		exit();
	}

	//Get host (may add path)
	public static function host($path = ""){
		if ($path) {
			$link="$path";
		}
		else {
			$link=null;
		}
			$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$link";		
		
		return $actual_link;
	}
	//Get full url
	public static function url(){
		$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return $actual_link;
	}

	//Get info about useragent
	public static function agent(){
		$detect = new Mobile_Detect;

		if($detect->isTablet() ) { $device = 'Tablet'; }
		elseif($detect->isMobile() && !$detect->isTablet() ) { $device = 'Phone'; }
		else{ $device = "PC";}

		if($detect->isiOS() ) { $os = 'IOS'; }
		elseif($detect->isAndroidOS() ) { $os = 'AOS'; }
		elseif($detect->isBlackBerryO() ) { $os = 'BB'; }
		elseif($detect->iswebOS() ) { $os = 'WOS'; }
		else{ $os = 'Windows OS';}


		if($detect->isiPhone() ) { $phone = 'iPhone'; }
		elseif($detect->isSamsung() ) { $phone = 'Samsung'; }
		elseif($detect->isBlackBerry() ) { $phone = 'BlackBery'; }
		elseif($detect->isSony() ) { $phone = 'Sony'; }
		else{ $phone = 'null';}


		if($detect->isChrome() ) { $browser = 'Chrome'; }
		if($detect->isOpera() ) { $browser = 'Opera'; }
		if($detect->isSafari() ) { $browser = 'Safari'; }
		if($detect->isEdge() ) { $browser = 'Edge'; }
		if($detect->isIE() ) { $browser = 'IE'; }
		if($detect->isFirefox() ) { $browser = 'Firefox'; }
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) {
			$browser = 'Chrome';
		}
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'OPR') !== FALSE) {
			$browser = 'Opera';
		}
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'YaBrowser') !== FALSE) {
			$browser = 'Yandex';
		}
		else {
			$browser = 'null';
		}


		$ip = $_SERVER['REMOTE_ADDR']; 
			$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip.'?lang=ru'));
			if($query && $query['status'] == 'success') {
				$country = $query['country'];
				$city = $query['city'];
				} else {
				$country = "null";
				$city = "null";
			}
		$today = date("Y-m-d H:i"); 
		
		$data = array(
			'time' => $today,
			'country' => $country,
			'region' => $city,
			'device' => $device,
			'phone' => $phone,
			'browser' => $browser,
			'os' => $os,
		);

		return $data;

	}
}

//Session controller
class Session {

	public $name;

	//Create new Session
	public function new($id, $key = 'none'){
		$name = $this->name;

		$_SESSION["$name"] = [
            "id" => $id,
            "key" => $key,
        ];
	}

	//Close Session
	public static function close($name){
		unset($_SESSION["$name"]);
	}

	//Route if not session
	public function not($locate){
		$name = $this->name;
		if (!$_SESSION["$name"]) {
    		header('Location: '.$locate);
		}
	}

	//Route if have Session
	public function yes($locate){
		$name = $this->name;
		if ($_SESSION["$name"]) {
    		header('Location: '.$locate);
		}
	}

	//Check session (have = 1 / not = 0)
	public function check(){
		$name = $this->name;
		if (!$_SESSION["$name"]) {
    		return 0;
		}
		else{
			return 1;
		}
	}


}

//Other tools
class Gen {

	//Qr Generate example: qr('https://jcodes.space', 'my/adil.png')
	public static function qr($data, $path){
		$qr = new QR_BarCode();
        $qr->info($data);
        $qr->qrCode(500, $path);
	}

	//Generate random symbols
	public static function str($length = 6)
	{
		$arr = array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}
	
	//Generate random numbers
	public static function int($length = 6)
	{
		$arr = array(
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	//Generate random (numbers and symbols)
	public static function mix($length = 6)
	{
		$arr = array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
			'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
		);
	 
		$res = '';
		for ($i = 0; $i < $length; $i++) {
			$res .= $arr[random_int(0, count($arr) - 1)];
		}
		return $res;
	}

	//Generate link for whatsapp
	public static function wame($number, $msg = " "){
		$data=urlencode($msg);
		$link="https://api.whatsapp.com/send?phone=$number&text=$data";
		return $link;
	}
}

//JSON in ECTR
class JSON{
	public $data;
	public $json;

	//json decoder
	public function decode(){
		$json = json_decode($this->json, true);
		$this->data = $json;
		return $json;
	}

	//json encoder
	public function encode($data){
		$json = json_encode($data);
		// $this->json = $json;
		return $json;
	}

	//add data to JSON
	public function set($data){
		$json = json_decode($this->json, true);
		$new = array_merge($json, $data);
		$json = json_encode($new);
		$this->json = $json;
		return $json;
	}
}

//Tracking function
function track($name, $type, $oid='0'){
	$db = new DBControl;
	$db->connect = $GLOBAL['db'];
	$db->table = 'tracker';
	
	$data = Router::agent();

	$table = array(
		'oid' => $oid,
		'type' => $type,
		'name' => $name,
		'url' => Router::url(),
		'time' => $data['time'],
		'country' => $data['country'],
		'region' => $data['region'],
		'device' => $data['device'],
		'browser' => $data['browser'],
		'os' => $data['os']
	);

	$db->insert($table);
}

?>