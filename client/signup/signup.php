<?php
include_once("../handler/SessionManager.php");
checkOldUser();
?>
<!DOCTYPE HTML>
<html lang="en_us">
<head>
	<!--include these lines in every index pages-->
	<meta charset="UTF-8">
	<meta name="author"content="Sudip Ghimire">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="../images/favicon.png" type="image/png">
	<link rel="stylesheet" type="text/css" href="../menu/menu.css" />
	<!-----change these lines per index page---->
	<link rel="stylesheet" type="text/css" href="signup.css">
	<title>Sudip Signup</title>
</head>
<body>
	<div id="reserveMenu">
<?php
	$menuFile=file_get_contents("../menu/menu.php");
	echo $menuFile;
?>
	</div>
	<form id="FormArea"class="signup-Sec"method="POST"action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<div id="Greet">
			<div id="firstLine" class="lines">
				Create a Chat ID
			</div>
			<div id="secondLine" class="lines">
				If you already have a Chat ID, please <a href="../signin/signin.php">sign in here</a>.
			</div>
		</div>
		<div id="Input">
			<div id="ErrorLogger"name="ErrorLogger">Fill up the Field Below</div>

			<span id="EmailFld" class="fields">
				<input type="text" name="Email" id="Email"onchange="Makesmall(this)" />
			</span>
			<span id="PasswordFld" class="fields">
				<input type="Password" name="Password" id="Password" />
			</span>
			<br />
			<span id="UserNameFld" class="fields">
				<input type="text" name="UserName"maxlength="17" id="UserName"onchange="Makesmall(this)" />
			</span>
			<span id="FullNameFld" class="fields">
				<input type="text" name="FullName" id="FullName"onchange="Makesmall(this)" />
			</span>
		</div>
		<div id="Agree">
			<div id="terms">
				<span class="label">
					I agree to the Your <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>
				</span>
			</div>
			<div id="subscription">
				<span class="label">
					I understand that by signing up, I am agreeing to receive promotional materials from Author.
				</span>
			</div>
		</div>
		<div id="btnContainer">
			<input type="submit" name="submit" id="createBtn" class="btns" value="Create Chat ID">
			<br />
			<a id="already" href="#">Already have a chat ID?</a>
		</div>
	</form>
	<center id="seperator">
		<span id="label">Try to Get</span>
	</center>
	<center id="DesktopApp">
		<button id="downloadBtn">Get Desktop App</button>
	</center>
	<!------------------------------------------->
	<div id="Toast">Some text Message Here..</div>
	<!-------------------------------------------->

</body>
<script type="text/javascript"src="../menu/menu.js"></script>
<script type="text/javascript" src="signup.js"></script>
<script src="../../plugins/jquery.min.js"></script>
</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Kathmandu");


$Email = $Password = $UserName = $FullName = $conn = "";

function connect($dbname,$serverName='localhost',$serverAdmin='sudip',$serverPassword='sudip123'){
	$conn = new mysqli($serverName, $serverAdmin, $serverPassword, $dbname);
	if (!$conn){
    	return 'connection failed to server';
	}
	else{ 
		return $conn;
	}
}
function addUser(){
	$conn = $GLOBALS['conn'];
	deleteOlderInTemp();
	$Email = $GLOBALS['Email'];
	$Password = $GLOBALS['Password'];
	$PasswordHash = password_hash($Password, PASSWORD_BCRYPT);
	$UserName = $GLOBALS['UserName'];
	$firstName = $GLOBALS['firstName'];
	$lastName = $GLOBALS['lastName'];
	$FullName = $GLOBALS['FullName'];
	$Consturl = base64_encode($Email.$UserName.date('h:i:sa').$Password.$Email);
	$Consturl = urlencode($Consturl);
	$adduser = $conn->query("INSERT INTO temp (Email, UserName, FirstName, LastName, Password, Validation, FullName) VALUES ('$Email', '$UserName', '$firstName', '$lastName', '$PasswordHash', '$Consturl', '$FullName');");
	if($adduser != TRUE){
		echo "<b>addUser</b>: <br>" .$conn->error;
	}
	else {
		mysqli_close($conn);
		echo "<script>document.getElementById('FormArea').style.display='none';</script>";
		echo "<script>document.getElementById('seperator').style.display='none';</script>";
		echo "<script>document.getElementById('DesktopApp').style.display='none';</script>";
		echo "<br><b style='color:#55FF55;width:100%;'>[+] Please check your Email and continue to </b><br><center style='width:100%;'><button style='border:1px solid #9bcb64;background-color:#9bcb64;padding:15px 30px;cursor:pointer;text-decoration:none;border-radius:5px;font-size:1em;color:#fff;font-weight:bold;box-shadow:10px 5px 10px #3214;'><a href='http://localhost/Web/client/signin/signin.php'style='text-decoration:none;color:#fff;'>Login Page</a></button></center><br>";
	}
}
function checkOlder(){
	$conn = $GLOBALS['conn'];
	$enteredMail = $GLOBALS['Email'];
	$isOldmail = $conn->query("SELECT * FROM main WHERE Email='$enteredMail' LIMIT 1;");
	$isPendingmail = $conn->query("SELECT * FROM temp WHERE Email='$enteredMail' LIMIT 1;");
	$enteredUname = $GLOBALS['UserName'];
	$isOldname = $conn->query("SELECT * FROM main WHERE UserName='$enteredUname' LIMIT 1;");
	$isPendingname = $conn->query("SELECT * FROM temp WHERE UserName='$enteredUname' LIMIT 1;");
	$isReservedName= $conn->query("SELECT 1 FROM $enteredUname LIMIT 1;");
	if($isReservedName){
		echo "<script>showLog('This username is not available..', 'error');</script>";
		echo "<script>focuson('UserName');</script>";
	}
	else if(!$isOldmail || !$isOldname || !$isPendingname || !$isPendingmail){ 
		die('Error: ' . mysqli_error($conn));
		echo "<script>showLog('Server side failure..', 'error');</script>";
	}
	else if(mysqli_num_rows($isOldmail) > 0){
		echo "<script>showLog('Email already exist..', 'error');</script>";
		echo "<script>focuson('Email');</script>";
	}
	else if(mysqli_num_rows($isOldname) > 0){
		echo "<script>showLog('This username is not available..', 'error');</script>";
		echo "<script>focuson('UserName');</script>";
	}
	else if(mysqli_num_rows($isPendingmail) > 0){
		echo "<script>showLog('This Email is pending for verification..', 'error');</script>";
		echo "<script>focuson('Email');</script>";
	}
	else if(mysqli_num_rows($isPendingname) > 0){
		echo "<script>showLog('This username is temporarly unavailable..', 'error');</script>";
		echo "<script>focuson('UserName');</script>";
	}
	
	else{
		addUSer();
	}
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$GLOBALS['conn'] = connect('chatbox');
	$GLOBALS['Email'] = mysqli_real_escape_string($conn, $_POST['Email']);
	$GLOBALS['Password'] = mysqli_real_escape_string($conn, $_POST['Password']);
	$GLOBALS['UserName'] = mysqli_real_escape_string($conn, $_POST['UserName']);
	$GLOBALS['FullName'] = mysqli_real_escape_string($conn, $_POST['FullName']);
	//fill up the previous data

	echo "<script>fillPrev('$Email', '$Password', '$UserName', '$FullName');</script>";

	//the validation process
	function checkEmail($Email){
		$sub = explode("@", $Email);
		if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$Email)){
			echo "<script>showLog('Invalid Email', 'error');</script>";
			echo "<script>focuson('Email');</script>";
			return false;
		}
		else if(preg_match('/[\'^£$%&*()}{@#~.`?><>,"!|=_+¬-]/', $sub[0])){
			echo "<script>showLog('Invalid Email', 'error');</script>";
			echo "<script>focuson('Email');</script>";
			return false;
		}
		else{
			return true;
		}
	}
	function checkPassword($Password){
		if(strlen($Password) < 8){
			echo "<script>showLog('Too short password..', 'error');</script>";
			echo "<script>focuson('Password');</script>";
			return false;
		}
		else if(!preg_match('/[0-9]/',$Password)){
			echo "<script>showLog('Password must contain a number..', 'error');</script>";
			echo "<script>focuson('Password');</script>";
			return false;
		}
		else if(!preg_match("/[a-z]/i", $Password)){
			echo "<script>showLog('Password must contain alphabets..', 'error');</script>";
			echo "<script>focuson('Password');</script>";
			return false;
		}
		else if(preg_match('/\s/',$Password)){
			echo "<script>showLog('Whitespace are invalid in Password..', 'error');</script>";
			echo "<script>focuson('Password');</script>";
			return false;
		}
		else{
			return true;
		}
	}
	function checkUserName($UserName){
		if (preg_match('/[\'^£$%&*()}{@#~.`?><>,"!|=_+¬-]/', $UserName)){
			echo "<script>showLog('Invalid character in user name..', 'error');</script>";
			echo "<script>focuson('UserName');</script>";
			return false;
		}
		else if(preg_match('/\s/',$UserName)){
			echo "<script>showLog('Whitespace are invalid in user name..', 'error');</script>";
			echo "<script>focuson('USerName');</script>";
			return false;
		}
		else if(strlen($UserName) < 5){
			echo "<script>showLog('Too short user name..', 'error');</script>";
			echo "<script>focuson('UserName');</script>";
			return false;
		}
		else if(strlen($UserName) > 17){
			echo "<script>showLog('Too long user name..', 'error');</script>";
			echo "<script>focuson('UserName');</script>";
			return false;
		}
		else{
			return true;
		}
	}
	function checkFullName($FullName){
		$sub = explode(" ", $FullName);
		if(sizeof($sub) < 2){
			echo "<script>showLog('Seperate first & last name with space..', 'error');</script>";
			echo "<script>focuson('FullName');</script>";
			return false;
		}
		else if(sizeof($sub) > 2){
			echo "<script>showLog('Try to enter exact one space in name..', 'error');</script>";
			echo "<script>focuson('FullName');</script>";
			return false;
		}
		else if (preg_match('/[\'^£$%&*()}{@#~.`?><>,"!|=_+¬-]/', $sub[0])
				 || preg_match('/[\'^£$%&*()}{@#~.`?><>,"!|=_+¬-]/', $sub[1])
				 || preg_match('/[0-9]/',$FullName)){
			echo "<script>showLog('Invalid character in name..', 'error');</script>";
			echo "<script>focuson('FullName');</script>";
			return false;	
		}
		else if(strlen($sub[0]) > 15 || strlen($sub[1]) > 15){
			echo "<script>showLog('Too long Name..', 'error');</script>";
			echo "<script>focuson('FullName');</script>";
			return false;
		}
		else if(strlen($sub[0]) < 3 || strlen($sub[1]) < 3){
			echo "<script>showLog('Too short Name..', 'error');</script>";
			echo "<script>focuson('FullName');</script>";
			return false;
		}
		else{
			$GLOBALS['firstName'] = $sub[0];
			$GLOBALS['lastName'] = $sub[1];
			return true;
		}
	}
	$trueEmail = checkEmail($Email);
	$truePassword = $trueUserName = $trueFullName = $trueAgreement = false;
	if($trueEmail == true){
		$truePassword = checkPassword($Password);
	}
	if($truePassword == true){
		$trueUserName = checkUserName($UserName);
	}
	if($trueUserName == true){
		$trueFullName = checkFullName($FullName);
	}
	if($trueFullName == true){
		checkOlder();
	}
}
?>