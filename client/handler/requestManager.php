<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("SessionManager.php");

function addFriendReq($newFriend){
	$GLOBALS['conn'] = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
	$conn = $GLOBALS['conn'];
	$userName = base64_decode($_COOKIE[base64_encode('userId')]);
	if(checkOldUser($redirect=false) == false){
		deleteOldLogin();
		exit;
	}
	$res1 = $conn->query("SELECT ID FROM Request WHERE Sender='$userName' AND Reciver='$newFriend' LIMIT 1;");
	$res2 = $conn->query("SELECT ID FROM Request WHERE Sender='$newFriend' AND Reciver='$userName' LIMIT 1;");
	$isPending1 = mysqli_num_rows($res1);
	$isPending2 = mysqli_num_rows($res2);
	if($userName != $newFriend && $isPending1 <= 0 && $isPending2 <= 0){
		$conn->query("INSERT INTO Request (Sender, Reciver) VALUES ('$userName','$newFriend');");
	}
	$conn->close();
}
function addFriend($newFriend){
	$GLOBALS['conn'] = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
	$conn = $GLOBALS['conn'];
	$userName = $_COOKIE[base64_encode('userId')];
	$userName = base64_decode($userName);
	$isValiduser = checkOldUser($redirect = false);
	$isValidFriend = $conn->query("SELECT 1 FROM $newFriend;");
	$res1 = $conn->query("SELECT * FROM Request WHERE Sender='$userName' AND Reciver='$newFriend' LIMIT 1;");
	$res2 = $conn->query("SELECT * FROM Request WHERE Sender='$newFriend' AND Reciver='$userName' LIMIT 1;");
	$isPending1 = mysqli_num_rows($res1);
	$isPending2 = mysqli_num_rows($res2);
	if($isValiduser == true && $isValidFriend == true && ($isPending1 > 0 || $isPending2 > 0)){
		$prevFrnd = $conn->query("SELECT * FROM $userName WHERE Friends='$newFriend' LIMIT 1;");
		if(mysqli_num_rows($prevFrnd) < 1){
			$conn->query("INSERT INTO $userName (Friends) VALUES ('$newFriend');");
		}
		$conn->query("INSERT INTO $newFriend (Friends) VALUES ('$userName');");
		$conn->query("DELETE FROM Request WHERE Sender = '$userName' AND Reciver='$newFriend';");
		$conn->query("DELETE FROM Request WHERE Sender = '$newFriend' AND Reciver='$userName';");
		$pathOf = "../Users/".$userName."/Messages/".$newFriend;
		mkdir($pathOf, 0777, true);
		$messageFile = "";
		if(!file_exists($pathOf."/Messages.php")){
			$messageFile = fopen($pathOf."/Messages.php", "w");
		}
		else{
			$messageFile = fopen($pathOf."/Messages.php", "a");
		}
		fwrite($messageFile, "<div class='Notification Messages'><span class='text Vlarge'>You and $newFriend are now Connected</span></div>");
		$pathOf = "../Users/".$newFriend."/Messages/".$userName;
		mkdir($pathOf, 0777, true);
		if(!file_exists($pathOf)){
			$messageFile = fopen($pathOf."/Messages.php", "w");
		}
		else{
			$messageFile = fopen($pathOf."/Messages.php", "w");
		}
		$pathOf = "../Users/".$userName."/Messages/".$newFriend."/images";
		mkdir($pathOf, 0777, true);
		$pathOf = "../Users/".$newFriend."/Messages/".$userName."/images";
		mkdir($pathOf, 0777, true);
		fwrite($messageFile, "<div class='Notification Messages'><span class='text Vlarge'>You and $userName are now Connected</span></div>");
		fclose($messageFile);
		$newCount = mysqli_fetch_array($conn->query("SELECT * FROM $userName WHERE Friends='$newFriend' LIMI1 ;"))['newMessageCount'] + 1;
		$conn->query("UPDATE $userName SET newMessage=1, newMessageCount=$newCount WHERE Friends='$newFriend' LIMIT 1;");
		$newCount = mysqli_fetch_array($conn->query("SELECT * FROM $newFriends WHERE Friends='$userName' LIMI1 ;"))['newMessageCount'] + 1;
		$conn->query("UPDATE $newFriends SET newMessage=1, newMessageCount=$newCount WHERE Friends='$userName' LIMIT 1;");
		unset($newCount);
	}
	$conn->close();
}
function cancelReq($reciver){
	$userName = $_COOKIE[base64_encode('userId')];
	$userName = base64_decode($userName);
	$conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
	$conn->query("DELETE FROM Request WHERE Sender='$userName' AND Reciver='$reciver';");
	$conn->close();
}
function deleteReq($sender){
	$userName = $_COOKIE[base64_encode('userId')];
	$userName = base64_decode($userName);
	$conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
	$conn->query("DELETE FROM Request WHERE Sender='$sender' AND Reciver='$userName';");
	$conn->close();
}
function unfriend($user){
	$userName = $_COOKIE[base64_encode('userId')];
	$userName = base64_decode($userName);
	$conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
	$conn->query("DELETE FROM $userName WHERE Friends='$user';");
  $path = "../Users/".$userName."/Messages/".$user;
  rmdir_recursive($path);
	$path = "../Users/".$user."/Messages/".$userName."/Messages.php";
	if(file_exists($path)){
		$file = fopen($path, 'a');
		$text = PHP_EOL."<div class='Notification Messages'><span class='text Vlarge danger'>You are no longer friend with this user...</span></div>";
		fwrite($file, $text);
		$newCount = mysqli_fetch_array($conn->query("SELECT newMessageCount FROM $user WHERE Friends='$userName' LIMIT 1;"))['newMessageCount'] + 1;
		$conn->query("UPDATE $user SET newMessage=1, newMessageCount=$newCount WHERE Friends='$userName';");
		unset($newCount);
	}
	$lastTalkWith = trim(preg_replace('/\s+/', '', $_COOKIE['LastTalkWith']));
	if($lastTalkWith == $user){
		setcookie('LastTalkWith', 'ChatBox', time() + (86400*1),'/');
		echo "reload";
	}
	$conn->close();
	fclose($file);
}
if($_POST['action'] == 'sentRequest'){
	$conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
	$FriendName = trim(preg_replace('/\s+/', ' ', $_POST['UserName']));
	$conn->close();
	if(mysqli_num_rows($conn->query("SELECT ID FROM $userName WHERE Friends='$FriendName' LIMIT 1;")) > 0){
		echo "<script>invalid('You are already Frriend with $FriendName');</script>";
	}
	else{
		addFriendReq($FriendName);
	}
}
else if($_POST['action'] == 'acceptRequest'){
	$FriendName = trim(preg_replace('/\s+/', ' ', $_POST['UserName']));
	addFriend($FriendName);
}
else if($_POST['action'] == 'deleteRequest'){
	$FriendName = trim(preg_replace('/\s+/', ' ', $_POST['UserName']));
	deleteReq($FriendName);
}
else if($_POST['action'] == 'cancelReq'){
	$FriendName = trim(preg_replace('/\s+/', ' ', $_POST['UserName']));
	cancelReq($FriendName);
}
else if($_POST['action'] == 'unfriend'){
	$FriendName = trim(preg_replace('/\s+/', ' ', $_POST['UserName']));
	unfriend($FriendName);
}
?>