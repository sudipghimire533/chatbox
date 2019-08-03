<?php
function redirectPrivate(){
	header("Location: http://".$_SERVER['HTTP_HOST']."/Web/client/chat/chat.php");
	setcookie('as03had29of', base64_encode('Verified'), time()+100, '/');
}
function deleteOlderInTemp(){
	$connTemp = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
	$res = $connTemp->query("DELETE FROM temp WHERE TimeCalc < NOW() - INTERVAL 24 HOUR;");
	mysqli_close($connTemp);
	return $res;
}
function deleteOldLogin(){
	$cookie1 = base64_encode('userId');
	$cookie2 = base64_encode("userIdentification");
	setcookie($cookie1, "expired", time() - 10, '/');
	setcookie($cookie2, "expired", time() - 10, '/');
  setcookie('LastTalkWith', "expired", time() - 10, '/');
  setcookie(base64_encode("myHash"), "expired", time() - 10, '/');
}
function checkOldUser($redirect = true){
	if(isset($_COOKIE[base64_encode('userId')]) && isset($_COOKIE[base64_encode('userIdentification')])){
		$myUname = $_COOKIE[base64_encode('userId')];
		$myUpdate = $_COOKIE[base64_encode('userIdentification')];
		$myUname = base64_decode($myUname);
    $myUpdate = base64_decode($myUpdate);
    $myPass = gzuncompress($_COOKIE[base64_encode("myHash")]);
    $myPass = base64_decode($myPass);
		$conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
		if(!$conn){ echo 'error..'; }
		$res = $conn->query("SELECT * FROM main WHERE UserName = '$myUname' LIMIT 1;");
		if(mysqli_num_rows($res) > 0){
			$row = mysqli_fetch_array($res);
      $userPass = $row['lastChange'];
      $userKey = $row['Password'];
			if($userPass == $myUpdate && $myPass == $userKey){
				if($redirect == true){
					redirectPrivate();
				}
				return true;
			}
			else{
        deleteOldLogin();
				return false;
			}
		}
		else{
			echo "<script>invalid();document.getElementById('ErrorLogger').textContent = 'We noticed a change in your account..';</script>";
			deleteOldLogin();
			return false;
		}
	}
	else{
		//echo '<br>i am new na<br>';
		deleteOlderInTemp();
		return false;
	}
}
function LogMeOut(){
	deleteOldLogin();
}
function LogOtherOut(){
  $isValid = checkOldUser($redirect = false);
  if($isValid == true){
    $userName = base64_decode($_COOKIE[base64_encode('userId')]);
    $timestamp = date("Y-m-d H:i:s");
    $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
    $conn->query("UPDATE main set lastChange='$timestamp'  where UserName='$userName';");
    setcookie(base64_encode("userIdentification"), base64_encode($timestamp), time() + (86400*5),'/');
    $conn->close();
  }
  else{
    checkOldUser($redirect = true);
  }
}
if(isset($_SERVER['REQUEST_METHOD'])){
	if($_SERVER["REQUEST_METHOD"] == "POST") {
	 	if(isset($_POST['action'])){   
	   		if($_POST['action'] == 'LogOut'){
          LogMeOut();
        }
			else if($_POST['action'] == "LogOtherOut"){
				LogOtherOut();;
			}
		}
	}
}
else{
	redirectPrivate();
	exit;
}
?>
