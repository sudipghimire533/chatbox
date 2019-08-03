
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("SessionManager.php");
if(checkOldUser($redirect=false) != true){
  echo "<script>window.location.reload()</script>";
  exit;
}

$userName = $_COOKIE[base64_encode('userId')];
$GLOBALS['userName'] = base64_decode($userName);
$GLOBALS['conn'] = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');

function sentRequestList(){
  $userName = $GLOBALS['userName'];
  $conn = $GLOBALS['conn'];
  $res = $conn->query("SELECT * FROM Request WHERE Sender='$userName';");
  $totalFriendReq = mysqli_num_rows($res);
  for ($i=0; $i < $totalFriendReq ; $i++) { 
    $res = $conn->query("SELECT * FROM Request WHERE Sender='$userName' LIMIT $i,1;");
    $fetchedFriends = mysqli_fetch_array($res);
    $friendName = $fetchedFriends['Reciver'];
    $existFriend = $conn->query("SELECT 1 FROM $friendName;");
    if($existFriend == true){
        $displayName = mysqli_fetch_array($conn->query("SELECT DisplayName FROM main WHERE UserName='$friendName' LIMIT 1;"))['DisplayName'];
        echo "<li id='_cancel_this_request_$friendName' class='SentReq'title='Visit Profile of this user'><span id='Content'> $displayName</span><i title='Cancel Request to this user'class='fas fa-minus-circle'id='rightAction'onclick="."'cancelReq(this.parentElement.id);'"."style='float: right;'></i></li>";
    }
  }
}
function pendingList(){
  $userName = $GLOBALS['userName'];
  $conn = $GLOBALS['conn'];
  $res = $conn->query("SELECT * FROM Request WHERE Reciver='$userName';");
  $totalFriendReq = mysqli_num_rows($res);
  for ($i=0; $i < $totalFriendReq ; $i++) {
    $res = $conn->query("SELECT * FROM Request WHERE Reciver='$userName' LIMIT $i,1;");
    $fetchedFriends = mysqli_fetch_array($res);
    $friendName = $fetchedFriends['Sender'];
    $existFriend = $conn->query("SELECT 1 FROM $friendName;");
    if($existFriend == true){
        $displayName = mysqli_fetch_array($conn->query("SELECT DisplayName FROM main WHERE UserName='$friendName' LIMIT 1;"))['DisplayName'];
        echo "<li id='_delete_this_request_$friendName'title='Visit Profile of this user'class='FriendReq'><span id='Content'><i class='fas fa-user'id='_accept_this_request_$friendName'><i title='Add friend'class='fas fa-user-plus'onclick='acceptReq(this.parentElement);'></i> $displayName</i><i title='Delete this Request'class='fas fa-minus-circle'id='rightAction'onclick='deleteReq(this.parentElement.parentElement.id)'></i></span></li>";
    }
  }
}
function friendList(){
  $userName = $GLOBALS['userName'];
  $conn = $GLOBALS['conn'];
  $res = $conn->query("SELECT * FROM $userName;");
  $totalFriends = mysqli_num_rows($res);
  for ($i=0; $i < $totalFriends ; $i++){
    $frndClass = 'Friend';
    $res = $conn->query("SELECT * FROM $userName LIMIT $i,1");
    $fetchedFriends = mysqli_fetch_array($res);
    $friendName = $fetchedFriends['Friends'];
    $displayName = $fetchedFriends['DisplayName'];
    $rightIcon = "fas fa-comments";
    $hasnewMessage = $fetchedFriends['newMessage'];
    $iconText = "";
    if($hasnewMessage == 1){
      $frndClass = "Friend withMessage";
      $fetched = $conn->query("SELECT * FROM $userName WHERE Friends='$friendName' LIMIT 1;");
      $newMessageCount = mysqli_fetch_array($fetched)['newMessageCount'];
      if($newMessageCount > 0){
        if($newMessageCount < 10){
          $rightIcon = "iconic iconic".$newMessageCount;
        }
        else if($newMessageCount >= 10){
          $rightIcon = "iconic iconicMore";
        }
      }
    }
    $existFriend = $conn->query("SELECT 1 FROM $friendName;");
    if($existFriend == true){
      echo "<li title='Start chatting with this user'class='$frndClass'onclick='showMessage(this);'id='".$friendName."'><span id='Content'><i class='fas fa-user-friends'></i> ".$displayName."</span><i class='$rightIcon'id='rightAction'></i></li>";
    }
    else{
        echo "<li class='Friend'><span id='Content'><i class='fas fa-user'></i>  ChatBoxUser</span></li>";
    }
  }
}
if(isset($_GET['placements'])){
  $placement = $_GET['placements'];
  if($placement == "friendList"){
    friendList();
  }
  else if($placement == "sentList"){
    sentRequestList();
  }
  else if($placement == "PendingList"){
    pendingList();
  }
}
mysqli_close($conn);
?>