<?php
function redirect(){
	header("Location: http://".$_SERVER['HTTP_HOST']."/Web/client/signin/signin.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Verifying..</title>
</head>
<body style="background-color: #FFF; margin: 10vh 10vw;">
</body>
</html>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../handler/SessionManager.php");
$GLOBALS['conn'] = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
$GLOBALS['validationcode'] = urlencode(mysqli_real_escape_string($conn, $_GET['validationcode']));

echo "<h1>processing the code:</h1><div style='color:#26c6da;'>".$validationcode.'</div>';

function createfilesFolder($usrname){
	$pathOf = "../Users/".$usrname;
	if(!file_exists($pathOf)){
		mkdir($pathOf, 0777, true);
		$pathOf = "../Users/".$usrname."/Messages";
		mkdir($pathOf, 0777, true);
		return true;
	}
}
function createUserTable(){
	$conn = $GLOBALS['conn'];
	$validationcode = $GLOBALS['validationcode'];
	$res = $conn->query("SELECT * FROM temp WHERE Validation='$validationcode' LIMIT 1;");
	$row = mysqli_fetch_array($res);
	$usrname = $row['UserName'];
	$creator = createfilesFolder($usrname);
	$res = $conn->query("SELECT * FROM $usrname LIMIT 1;");
	$conn->query("
					CREATE table $usrname(
					ID INT NOT NULL AUTO_INCREMENT,
					Friends VARCHAR(100) NOT NULL UNIQUE,
					newMessage INT(1) NOT NULL DEFAULT 0,
					newMessageCount INT(20) NOT NULL DEFAULT 0,
					DisplayName VARCHAR(100) NOT NULL,
					PRIMARY KEY(ID));
				");
	$conn->query("DELETE FROM temp WHERE Validation='$validationcode';");
	echo "<br><b style='color:#55FF55;'>[+] Expiring this validation code..</b><br>";
	mysqli_close($conn);
	redirect();
}
function checkValidation(){
	$conn = $GLOBALS['conn'];
	deleteOlderInTemp($conn);
	$validationcode = $GLOBALS['validationcode'];
	$GLOBALS['res'] = $conn->query("SELECT * FROM temp WHERE Validation='$validationcode';");
	$res = $GLOBALS['res'];
	$myself = mysqli_fetch_array($res)['UserName'];
	if((mysqli_num_rows($res) == 1)){
		$shift = $conn->query("INSERT INTO main (Email, UserName, FirstName, LastName, Password, DisplayName) SELECT Email, UserName, FirstName, LastName, Password, FullName from temp where Validation='$validationcode';");
		if($shift == TRUE){
			echo "<br><b style='color:#55FF55;'>[+] We shifted you to our database..</b><br>";
			createUserTable();
		}
		else{
		}
	}
	else{
		echo "<br><b style='color:red;'>[+] The Validation code doesn't exist anymore...</b><br><center><button style='border:1px solid #9bcb64;background-color:#9bcb64;padding:15px 30px;cursor:pointer;text-decoration:none;border-radius:5px;font-size:1em;color:#fff;font-weight:bold;box-shadow:10px 5px 10px #3214;'><a href='http://localhost/Web/client/signup/signup.php'style='text-decoration:none;color:#fff;'>Continue...</a></button></center><br>";
	}
}
checkValidation();

mysqli_close($conn);
exit;
?>