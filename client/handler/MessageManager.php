<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("SessionManager.php");
include_once("../chat/extras/Message.php");
if(!isset($_SERVER["REQUEST_METHOD"])){
  redirectPrivate();
  exit;
}
if(checkOldUSer($redirect=false) == false){
	checkOldUSer();
	exit;
}
$userName = $_COOKIE[base64_encode('userId')];
$userName = base64_decode($userName);
function sanitizeInput($result){
  $result = htmlentities($result);
  $result = str_replace(array("\n", "\r"), '<br>', $result);
  return $result;
}
function writeToMe($message, $friendName, $userName){
  $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
  $isStillfriend = $conn->query("SELECT * FROM $friendName WHERE Friends='$userName' LIMIT 1;");
  if(mysqli_num_rows($isStillfriend) > 0){
    $path = "../Users/".$userName."/Messages/".$friendName."/Messages.php";
    $text = PHP_EOL."<div class='Sender Messages'><span class='text'>".$message."</span></div>";
    $file = fopen($path, 'a');
    fwrite($file, $text);
    fclose($file);
    return true;
  }
  else{
    return false;
    echo "not friend";
  }
  $conn->close();
}
function writeToFriend($message, $friendName, $userName){
  $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
  $isStillfriend = $conn->query("SELECT * FROM $friendName WHERE Friends='$userName' LIMIT 1;");
  if(mysqli_num_rows($isStillfriend) > 0){
    $path = "../Users/".$friendName."/Messages/".$userName."/Messages.php";
    $text = PHP_EOL."<div class='Reciver Messages'><span class='text'>".$message."</span></div>";
    $file = fopen($path, 'a');
    fwrite($file, $text);
    fclose($file);
    return true;
  }
  else{
    echo "reload";
    return false;
  }
  $conn->close();
}
function uploadImage($image, $userName, $friendName){
  $formats = ['png', 'gif', 'jpg', 'jpeg'];
  $sub = explode(".", $image['name']);
  $ext = end($sub);
  $ext = strtolower($ext);
  if(in_array($ext, $formats)){
    $conn = new mysqli('localhost','sudip', 'sudip123', 'chatbox');
    $res = $conn->query("SELECT * FROM $userName WHERE Friends='$friendName' LIMIt 1;");
    if(mysqli_num_rows($res) <= 0){
      echo "not friend";
    }
    else if(mysqli_num_rows($res) > 0){
      $name = urlencode((base64_encode($userName.'-'.$friendName.time().rand(0, 999)))).'.'.$ext;
      $path1 = "../Users/".$userName."/Messages/".$friendName."/images/".$name;
      if(move_uploaded_file($_FILES["file"]['tmp_name'], $path1)){
        $path2 = "../Users/".$friendName."/Messages/".$userName."/".$name;
        if(move_uploaded_file($_FILES["file"]['tmp_name'], $path2)){
          echo $image['name'];
        }
      }
      $msg1 = "<img class='msgImg fa fa-image' src='../".$path1."'>image</img>";
      writeToMe($msg1, $friendName, $userName);
      $msg2 = "<img class='msgImg fa fa-image' src='".$path2."'>image</img>";
      writeToFriend($msg2, $friendName, $userName);
      echo $image['name'];
    }
    $conn->close();
  }
  else{
    echo "bad format";
  }
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['message']) && isset($_POST['friendName'])){
    $message = $_POST['message'];
    $message = sanitizeInput($message);
    $friendName = $_POST['friendName'];
    $friendName = trim(preg_replace('/\s+/', ' ', $friendName));
    $sucess = writeToMe($message, $friendName, $userName);
    if($sucess == true){
      $sucess = writeToFriend($message, $friendName, $userName);
      if($sucess == true){
        $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
        $MessageCount = $conn->query("SELECT * FROM $friendName WHERE Friends='$userName' LIMIT 1;");
        $MessageCount = mysqli_fetch_array($MessageCount);
        $MessageCount = $MessageCount['newMessageCount'];
        $conn->query("UPDATE $friendName SET newMessage=1, newMessageCount=$MessageCount+1 WHERE Friends='$userName';");
        $conn->close();
        echo "sucess_send_message_".$message;
      }
      else{
        echo "reload";
      }
    }
    else{
      echo "reload";
    }
  }
  if(isset($_FILES["file"])){
    if(checkOldUSer($redirect=false) == false){
      echo "reload";
      exit;
    }
    $friendName = $_COOKIE['LastTalkWith'];
    $friendName = trim(preg_replace('/\s+/', '', $friendName));
    uploadImage($_FILES['file'], $userName, $friendName);
  }
}
?>