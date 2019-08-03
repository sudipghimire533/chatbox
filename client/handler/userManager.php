<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("SessionManager.php");

function searchUser($name){
  $userName = base64_decode($_COOKIE[base64_encode('userId')]);
  $name = trim(preg_replace('/\s+/', '', $name));
  $name = implode("%%" ,str_split($name));
  $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
  $res = $conn->query("SELECT * FROM main WHERE 
                        UserName LIKE '%".$name."%' 
                        OR Email LIKE '%".$name."%' 
                        OR ID LIKE '%".$name."%' 
                        OR FirstName LIKE '%".$name."%' 
                        OR LastName LIKE '%".$name."%'
                        OR SecEmail LIKE '%".$name."%'
                        OR DisplayName LIKE '%".$name."%';
                      ");
  while ($row = mysqli_fetch_array($res)){
    $result = $row['UserName'];
    $displayName = $row['DisplayName'];
    $isfriend = $conn->query("SELECT * FROM $userName WHERE Friends='$result' LIMIT 1;");
    $isPending = $conn->query("SELECT * FROM Request WHERE Sender='$result' AND Reciver='$userName' LIMIT 1;");
    $hasSent = $conn->query("SELECT * FROM Request WHERE Reciver='$result' AND Sender='$userName' LIMIT 1;");
    if($result == $userName){
      echo "<div class='searchRes' title='View my profile'>$result<i class='fas fa-user'></i></div>";
    }
    else if(mysqli_num_rows($isfriend) > 0){
      echo "
        <div id='_unfriend_this_user_$result'title='Visit Profile of this user'class='searchRes'>$displayName
          <i title='Start Conversation with this user'class='fas fa-user-friends'onclick='showMessage(this.parentElement);refreshSearch();'></i>
          <i title='Unfriend This User'class='fas fa-minus-circle'onclick='unfriend(this.parentElement.id);refreshSearch();'></i>
        </div>
      ";
    }
    else if(mysqli_num_rows($isPending) > 0){
      echo "<script>delete prevClass,nextClass;var prevClass='fa-times';var nextClass='fa-user-plus';var nextClass2='fas-user-friends';</script>";
      echo "
            <div id='_delete_this_request__accept_this_request_$result'class='searchRes' title='Visit Profile of this user'>$displayName
              <i title='Delete this Request'class='fas fa-minus-circle'onclick='deleteReq(this.parentElement.id);refreshSearch();'></i>
              <i title='Accept this Request'class='fas fa-user-plus'onclick='acceptReq(this.parentElement);refreshSearch();'></i>
            </div>
      ";
    }
    else if(mysqli_num_rows($hasSent) > 0){
      echo "<script>delete prevClass,nextClass;var prevClass='fa-times';var nextClass='fa-user-plus';</script>";
      echo "
          <div id='_cancel_this_request_$result'title='Visit Profile of this user'class='searchRes'>$displayName
            <i title='Cancel this Request'class='fas fa-times'onclick='cancelReq(this.parentElement.id);refreshSearch();'></i>
          </div>
      ";
    }
    else{
      echo "<script>delete prevClass,nextClass;var prevClass='fa-user-plus';var nextClass='fa-times';</script>";
      echo "
            <div id='_send_request_to_this_user_$result'title='Send Request to this user'class='searchRes'>$displayName
              <i title='Send Request'class='fas fa-user-plus'onclick='addFrnd(this.parentElement.id);refreshSearch();'></i>
            </div>
          ";
    }
    
  }
	$conn->close();
}
function changePassword($old, $new,$userName){
  $GLOBALS['error'] = "";
  function checkPassword($Password){
		if(strlen($Password) < 8){
      $GLOBALS['error'] =  "Too short password..";
			return false;
		}
		else if(!preg_match('/[0-9]/',$Password)){
      $GLOBALS['error'] =  "Password must contain a number..";
			return false;
		}
		else if(!preg_match("/[a-z]/i", $Password)){
      $GLOBALS['error'] =  "Password must contain alphabets..";
			return false;
		}
		else if(preg_match('/\s/',$Password)){
      $GLOBALS['error'] =  "Whitespace are invalid in Password..";
			return false;
		}
		else{
			return true;
		}
	}
  if(checkPassword($old) == false){
    echo "<script>showLog('Enter Current Password Corectly..', 'error', 'oldPass');</script>";
  }
  else if(checkPassword($new) == false){
    echo "<script>showLog('".$GLOBALS['error']."', 'error', 'newPass');</script>";
  }
  else if(checkPassword($new) && checkPassword($new)){
    $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
    $res = $conn->query("SELECT * FROM main WHERE UserName='$userName';");
    if($res != false){
      $row = mysqli_fetch_array($res);
      if(password_verify($old, $row['Password'])){
        $newHash = password_hash($new, PASSWORD_BCRYPT);
        $res = $conn->query("UPDATE main SET Password='$newHash' WHERE UserName='$userName';");
        if($res){
          $GLOBALS['newHash'] = $newHash;
          $res = $conn->query("SELECT * FROM main WHERE UserName='$userName' LIMIT 1;");
          $GLOBALS['timeTrigger'] = mysqli_fetch_array($res)['lastChange'];
          echo "<script>showLog('Password Updated...', 'sucess', null);</script>";
          return true;
        }
      }
      else{
        echo "<script>showLog('Please enter old Password..', 'error', 'oldPass');</script>";
        return false;
      }
    }
    else if($res == false){
      echo "<script>showLog('Unknown error occured Please try later..', 'error', 'newPass');</script>";
      return false;
    }
    $conn->close();
  }
}
function returnCostum($dbname='chatbox', $tbname, $column, $field='UserName', $value){
  $conn = new mysqli('localhost', 'sudip', 'sudip123', $dbname);
  $res = $conn->query("SELECT * FROM $tbname WHERE $field='$value';");
  $row = mysqli_fetch_array($res);
  $result = $row[$column];
  $conn->close();
  return $result;
}
function changeSecEmail($userName, $SecEmail){
  $error = "Invalid Email..";
  function checkEmail($Email){
		$sub = explode("@", $Email);
		if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$Email)){
			return false;
		}
		else if(preg_match('/[\'^£$%&*()}{@#~.`?><>,"!|=_+¬-]/', $sub[0])){
			return false;
		}
		else{
      $error = "";
			return true;
		}
  }
  if(checkEmail($SecEmail) == true){
    $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
    $conn->query("UPDATE main SET SecEmail='$SecEmail' WHERE UserName='$userName';");
    $timeTrigger = returnCostum('chatbox', 'main', 'lastChange', 'UserName', base64_decode($_COOKIE[base64_encode('userId')]));
    setcookie(base64_encode("userIdentification"), base64_encode($timeTrigger), time() + (86400*5),'/');
    echo "<script>showLog('Email added sucessfully..', 'sucess', 'secEmail');</script>";
    $conn->close();
  }
  else{
    echo "<script>showLog('Looks like that an invalid Email..', 'error', 'secEmail');</script>";
  }
}
if(isset($_SERVER['REQUEST_METHOD'])){
if ($_SERVER["REQUEST_METHOD"] == "GET"){
	if(isset($_GET['action'])){
    if($_GET['action'] == 'searchUser'){
      if(isset($_GET['query'])){
        $name = $_GET['query'];
        $name = trim(preg_replace('/\s+/', '', $name));
        echo "
          <strong style='width: 100%;'>Search reasult for:<i style='text-decoration:none'><u>"
          .$name."</u></i></strong><hr>
          ";
        if(strlen($name) > 0){
          searchUser($name);
        };
      }
    }
	}
}
else if ($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST['action'])){
    if($_POST['action'] == 'changePassword'){
      if(checkOldUser($redirect=false) == false){
        echo "<script>window.location.reload();</script>";
      }
      if(isset($_POST['oldPassword']) && isset($_POST['newPassword']) && isset($_POST['confirmPassword'])){
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPasword = $_POST['confirmPassword'];
        $uName = base64_decode($_COOKIE[base64_encode('userId')]);
        if($newPassword == $confirmPasword){
          $changed = changePassword($old = $oldPassword, $new = $newPassword, $userName = $uName);
          if($changed == true){
            $hash = gzcompress(base64_encode($GLOBALS['newHash']), 6);
            $timeTrigger = base64_encode($GLOBALS['timeTrigger']);
            setcookie(base64_encode("userIdentification"), $timeTrigger, time() + (86400*5),'/');
            setcookie(base64_encode("myHash"), $hash, time() + (86400*5),'/');
            setcookie('LastTalkWith', 'ChatBox', time() + (86400*1),'/');
          }
        }
        else{
          echo "<script>showLog('Password Did not matched...', 'error', 'confirmPass');</script>";
        }
      }
      else{
        echo "<script>showLog('Incomplete Data...', 'error', 'oldPass');</script>";
      }
    }
    else if($_POST['action'] == 'changeSecEmail'){
      if(checkOldUser($redirect=false) == false){
        echo "<script>window.location.reload();</script>";
        exit;
      }
      if(isset($_POST['secEmail'])){
        $secEmail  = $_POST['secEmail'];
        $uName = base64_decode($_COOKIE[base64_encode('userId')]);
        changeSecEmail($uName, $secEmail);
      }
    }
  }
}
}
?>