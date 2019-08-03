<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("SessionManager.php");

function addFriendReq($newFriend){
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
	if($isValiduser == true && $isValidFriend == true && $userName != $newFriend && $isPending1 <= 0 && $isPending2 <= 0){
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
		$theirDisplayName = mysqli_fetch_array($conn->query("SELECT DisplayName FROM main WHERE UserName='$newFriend' LIMIT 1;"))['DisplayName'];
		$myDisplayName = mysqli_fetch_array($conn->query("SELECT DisplayName FROM main WHERE UserName='$userName' LIMIT 1;"))['DisplayName'];
		$prevFrnd = $conn->query("SELECT * FROM $userName WHERE Friends='$newFriend' LIMIT 1;");
		if(mysqli_num_rows($prevFrnd) < 1){
			$conn->query("INSERT INTO $userName (Friends, DisplayName) VALUES ('$newFriend', '$theirDisplayName');");
		}
		$conn->query("INSERT INTO $newFriend (Friends, DisplayName) VALUES ('$userName', '$myDisplayName');");
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
		fwrite($messageFile, "<div class='Reciver Messages'><span class='text'style='color: blue !important;'>Hello from chatbox</span></div>");
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
		fwrite($messageFile, "<div class='Reciver Messages'><span class='text'style='color: blue !important;'>Hello from chatbox</span></div>");
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
	$path = "../Users/".$user."/Messages/".$userName."/Messages.php";
	if(file_exists($path)){
		$file = fopen($path, 'a');
		$text = PHP_EOL."<div class='Reciver Messages'><span class='text'style='color: red !important;'>You are no longer friend with this user...</span></div>";
		fwrite($file, $text);
	}
	$lastTalkWith = trim(preg_replace('/\s+/', '', $_COOKIE['LastTalkWith']));
	if($lastTalkWith == $user){
		setcookie('LastTalkWith', 'ChatBox', time() + (86400*1),'/');
		echo "reload";
	}
	$conn->close();
}
if($_POST['action'] == 'sentRequest'){
	$conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
	$userName = $_COOKIE[base64_encode('userId')];
	$userName = base64_decode($userName);
	$FriendName = $_POST['UserName'];
	$FriendName = trim(preg_replace('/\s+/', ' ', $FriendName));
	$res = $conn->query("SELECT * FROM $userName WHERE Friends='$FriendName' LIMIT 1;");
	$existFriend = mysqli_num_rows($res);
	if($existFriend > 0){
		mysqli_close($conn);
		echo "<script>invalid('You are already Frriend with $FriendName');</script>";
	}
	else{
		addFriendReq($FriendName);
	}
}
else if($_POST['action'] == 'acceptRequest'){
	$FriendName = $_POST['UserName'];
	$FriendName = trim(preg_replace('/\s+/', ' ', $FriendName));
	addFriend($FriendName);
}
else if($_POST['action'] == 'deleteRequest'){
	$FriendName = $_POST['UserName'];
	$FriendName = trim(preg_replace('/\s+/', ' ', $FriendName));
	deleteReq($FriendName);
}
else if($_POST['action'] == 'cancelReq'){
	$FriendName = $_POST['UserName'];
	$FriendName = trim(preg_replace('/\s+/', ' ', $FriendName));
	cancelReq($FriendName);
}
else if($_POST['action'] == 'unfriend'){
	$FriendName = $_POST['UserName'];
	$FriendName = trim(preg_replace('/\s+/', ' ', $FriendName));
	unfriend($FriendName);
}
?>