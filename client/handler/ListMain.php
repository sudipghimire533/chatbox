<?php
gc_enable();
gc_collect_cycles();
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
  if($totalFriendReq <= 0){
    echo "<span id='info'>No Sent Request Yet...</span>";
  }
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
  $totalFriendReq = mysqli_num_rows($conn->query("SELECT ID FROM Request WHERE Reciver='$userName';"));
  if($totalFriendReq <= 0){
    echo "<span id='info'>No Request Yet...</span>";
  }
  for ($i=0; $i < $totalFriendReq ; $i++){
    $friendName = mysqli_fetch_array($conn->query("SELECT Sender FROM Request WHERE Reciver='$userName' LIMIT $i,1;"))['Sender'];
    if($conn->query("SELECT 1 FROM $friendName;") == true){
        $displayName = mysqli_fetch_array($conn->query("SELECT DisplayName FROM main WHERE UserName='$friendName' LIMIT 1;"))['DisplayName'];
        echo "<li id='_accept_this_request_$friendName'title='Visit Profile of this user'class='FriendReq'><span id='Content'><i class='fas fa-user'><i title='Add friend'class='fas fa-user-plus'onclick='acceptReq(this.parentElement);'></i> $displayName</i><i title='Delete this Request'class='fas fa-minus-circle'id='rightAction'onclick='acceptReq(this.parentElement.parentElement.id)'></i></span></li>";
    }
  }
  $conn->close();
  unset($conn, $userName, $res, $totalFriendReq);
}
function friendList($force = 1){
  if($force == 0){
    echo "samamamefriendndndjhjrtgrghjhgyrityirytieyrinmdnmbgmfdmbfmbmmm22121gdhfgdhsf";
  }
  else if($force == 1){
    $userName = $GLOBALS['userName'];
    $conn = $GLOBALS['conn'];
    $totalFriends = mysqli_num_rows($conn->query("SELECT ID FROM $userName;"));
    if($totalFriends <= 0){
      echo "<span id='info'>No Friend Request Yet...</span>";
    }
    for ($i=0; $i < $totalFriends ; $i++){
      $frndClass = 'Friend';
      $res = $conn->query("SELECT Friends,newMessage FROM $userName LIMIT $i,1;");
      $fetchedFriends = mysqli_fetch_array($res);
      $friendName = $fetchedFriends['Friends'];
      $displayName = mysqli_fetch_array($conn->query("SELECT DisplayName From main WHERE UserName='$friendName' LIMIT 1;"))['DisplayName'];
      $rightIcon = "fas fa-circle";
      if($fetchedFriends['newMessage'] == 1){
        $frndClass = "Friend withMessage";
        $fetched = $conn->query("SELECT newMessageCount FROM $userName WHERE Friends='$friendName' LIMIT 1;");
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
      if($conn->query("SELECT 1 FROM $friendName;") == true){
        $profileSrc = '../Users/'.$friendName.'/data/images/profile.png';
        if(!file_exists($profileSrc)){
          $profileSrc = '../Users/ChatBox/Data/Images/profile.png';
        }
        echo "<li title='Start chatting with this user' class='Friend' onclick='showMessage(this);' id='$friendName'><span id='Content'><img class='profile'src='$profileSrc'/><span class='text'>$displayName</span><i class='$rightIcon' id='rightAction'></i></span></li>";
      }
      else{
          echo "<li class='Friend'><span id='Content'><i class='fas fa-user'></i>  ChatBoxUser</span></li>";
      }
    }
    $conn->close();
    unset($fetchedFriends, $rightIcon, $displayName, $friendName, $res, $frndClass, $conn, $userName);
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
  unset($_GET);
}
gc_enable();
gc_collect_cycles();
?>