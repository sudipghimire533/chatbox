<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(file_exists("../../handler/SessionManager.php")){
  include_once("../../handler/SessionManager.php");
}
if(checkOldUser($redirect=false) != true){
	echo "reload";
	exit;
}
$GLOBALS['friendName'] = $_COOKIE['LastTalkWith'];
$GLOBALS['friendName'] = trim(preg_replace('/\s+/', '', $GLOBALS['friendName']));
$cookieName = base64_encode('userId');
$username = $_COOKIE[$cookieName];
$GLOBALS['userName'] = base64_decode($username);

function loadDeafultMessage($userName){
  if(checkOldUser($redirect=false) == false){
    echo "reload";
  }
}
function loadMessage($friendName, $userName){
  if(isset($conn)){
    unset($conn);
  }
  $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
  $res = $conn->query("SELECT * FROM $userName WHERE Friends='$friendName' LIMIT 1;");
  if($res != false && mysqli_num_rows($res) > 0){
    $path = "../../Users/".$userName."/"."Messages/".$friendName."/Messages.php";
    if(file_exists($path)){
      setcookie('LastTalkWith', $friendName, time() + (86400*5), '/');
		  $content = file($path);
		  return $content;
    }
    else{
      return false;
    }
  }
  else{
    return false;
  }
  $conn->close();
}
function showMessage($friendName, $userName){
  $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
  $result = loadMessage($friendName, $userName);
  $conn->query("UPDATE $userName SET newMessage=0, newMessageCount=0 WHERE Friends='$friendName';");
  if($result != false){
    $totalMsg = count($result);
    $readLine = 10;
    if(isset($_GET['count']) && gettype($_GET['count']) == 'integer'){
      $readLine = $_GET['count'];
    }
    if($readLine > count($result)){
      $readLine = count($result);
    }
    $container = Array();
    for($i = 1; $i <= $readLine ; $i++){ 
      if($totalMsg-$i >= 0){
        $lines = $result[$totalMsg - $i];
        if($ondone = 'echo'){
          array_push($container, $lines);
          if($i == $readLine){
            foreach ($container as $key => $value) {
              echo $value;
            }
          }
        }
      }
      else{
        break;
      }
    }
  }
  else if($result == false){
    loadDeafultMessage($userName);
  }
}
if(isset($_SERVER["REQUEST_METHOD"])){
  $echoedSame = false;
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		if($_GET['action'] == 'showMessage' && isset($_GET['friendName'])){
      $friendName = $_GET['friendName'];
      $friendName = trim(preg_replace('/\s+/', '', $friendName));
      $GLOBALS['friendName'] = $friendName;
      $friendName = $GLOBALS['friendName'];
      setcookie('LastTalkWith', $friendName, time() + (86400*5), '/');
      $userName = $GLOBALS['userName'];
      if($_GET['switched'] == 1){
        showMessage($friendName, $userName);
      }
      else{
        $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
        $res = $conn->query("SELECT * FROM $userName WHERE Friends='$friendName' LIMIT 1;");
        if(mysqli_num_rows($res) <= 0){
          loadDeafultMessage($userName);
        }
        else if(mysqli_num_rows($res) > 0){
          $res = $conn->query("SELECT * FROM $userName WHERE newMessage=1;");
          if(mysqli_num_rows($res) > 0){
            $count = mysqli_num_rows($res);
            for ($i=0; $i < $count ; $i++) { 
              $res = $conn->query("SELECT * FROM $userName WHERE newMessage=1 LIMIT $i,1;");
              $row = mysqli_fetch_array($res);
              $frnd = $row['Friends'];
              if($frnd == $friendName){
                $result = loadMessage($friendName, $userName);
                if($result != false){
                  $totalMsg = count($result);
                  $readLine = $row['newMessageCount'];
                  $container = ['fjhdjhjghrhfdsdj1111lksjfkhfuutyruwiyrouw08039rfhhdfkhskhdskghio3urou3otyhfkjuqowueryeifhceuourhfjcrufhcrihigh'];
                  for($i = 1; $i <= $readLine ; $i++){ 
                    if($totalMsg-$i >= 0){
                      $lines = $result[$totalMsg - $i];
                      array_push($container, $lines);
                      if($i == $readLine){
                        $x = 0;
                        foreach ($container as $key => $value) {
                          echo $value;
                          $conn->query("UPDATE $userName SET newMessageCount=$readLine-$x WHERE Friends='$friendName';");
                          if($readLine == $x){
                            $conn->query("UPDATE $userName SET newMessage=0 WHERE Friends='$friendName';");
                          }
                          $x++;
                        }
                      }
                    }
                    else{
                      break;
                    }
                  }
                }
                else if($result == false){
                  loadDeafultMessage($userName);
                }
              }
            }
          }
          if(isset($_GET['count']) && (strpos($_GET['count'], 'add') !== false)){
            $prevMsgCount = explode("add", $_GET['count'])[0];
            $nextMsgCount = explode("add", $_GET['count'])[1];
            $result = loadMessage($friendName, $userName);
            if($result != false && isset($prevMsgCount) && isset($nextMsgCount)){
              $totalMsg = count($result);
              if($nextMsgCount > $totalMsg-$prevMsgCount){
                $nextMsgCount = $totalMsg-$prevMsgCount;
              }
              if($nextMsgCount < 0){
                $nextMsgCount = 0;
              }
              $readLine = $nextMsgCount;
              $container =['f6950f3e54e16c9921e3b05704b808e881247a5f052af0582a96c40afdef96c3abad4c5ef96b7ae6bb1dab5968d6cd681d30e9dad2999f48c352681af27ec5b8'];
              if($readLine > 0){
                for($i = 1; $i <= $readLine ; $i++){
                  if($totalMsg - ($i+$prevMsgCount) >= 0 && isset($result[$totalMsg - ($i+$prevMsgCount)])){
                    $lines = $result[$totalMsg - ($i+$prevMsgCount)];
                    array_push($container, $lines);
                    if($i == $readLine){
                      foreach($container as $key => $value){
                        echo $value;
                      }
                    }
                  }
                  else{
                    break;
                  }
                }
              }
              else{
                if($echoedSame == false){
                  echo "_We_are_kindly_waiting_for_your_feedback_";
                  $echoedSame = true;
                }
              }
            }
            else if($result == false){
              loadDeafultMessage($userName);
            }
          }
          else{
            if($echoedSame == false){
              echo "_We_are_kindly_waiting_for_your_feedback_";
              $echoedSame = true;
            }
          }
        }
      }
    }
	}
}
for($i = 0;$i < 0; $i++){
  if(isset($conn)){
    $conn->close();
  }
  else{
    break;
  }
}
?>