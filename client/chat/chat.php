<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../handler/SessionManager.php");
$valid = checkOldUser($redirect = false);
if($valid != true){
  header("Location: http://".$_SERVER['HTTP_HOST']."/Web/client/signin/signin.php");
  exit;
}
else{
  if(isset($_COOKIE['as03had29of'])){
    setcookie('as03had29of', base64_encode('Verified'), time()-10, '/');
  }
}
if(!isset($_COOKIE['LastTalkWith'])){
  setcookie('LastTalkWith', 'ChatBox', time() + (86400*1),'/');
}
?>
<html>
  <head>
    <title>ChatBox Sudip</title>
    <meta charset="UTF-8" />
    <meta name="author"content="Sudip Ghimire" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="../images/favicon.png" type="image/png" />
    <!--meta http-equiv="refresh" content="2000" /-->
    <!-------------------------------------------------------------->
    <link rel="stylesheet" type="text/css" href="chat.css" />
    <!-------------------------------------------------------------->
    <link rel="stylesheet" type="text/css" href="../plugins/css/all.css" />
    <!--------------------------------------------------------------->
  </head>
<body onload="ready()">
  <div id="menuContainer">
    <?php
    include_once("extras/menu.php");
    ?>
  </div>
  <div id="leftBarContainer">
    <?php
      include_once('extras/leftBar.php');
    ?>
  </div>
  <div id="MessageBoxContainer"style='margin-right: 0;margin-left: 0;'>
    <div id="WindowsBtn"style="margin-bottom:10px;position: fixed;display: inline-block;background-color: var(--color-mid); width: calc(var(--messageBoxWidth) - 10px);z-index: 1;border: 1px solid var(--super-light);">
      <i class="fas fa-times-circle"style="cursor: pointer;color: red;"></i>
      <i class="fas fa-minus-circle"style="cursor: pointer;color: green;"></i>
      <i class="fas fa-plus-circle"style="cursor: pointer;color: yellow;"onclick="maximizeMessage(this)"></i>
      <strong class="title"id='PersonName'><?php echo $_COOKIE['LastTalkWith']; ?></strong>
    </div>
    <div id="MessagesParent">
      <div id="Messages">
        <?php
        $cookieName = base64_encode('userId');
        $username = $_COOKIE[$cookieName];
        $userName = base64_decode($username);
        $LastTalkWith = $_COOKIE['LastTalkWith'];
        $filePath = "../Users/".$userName."/Messages/".$LastTalkWith."/Messages.php";
        if(file_exists($filePath)){
          $content = file($filePath);
          $i= 1;
          $totalMsg = sizeof($content);
          $readLine = 10;
          for ($i = 1; $i <= $readLine ; $i++) { 
            if($totalMsg-$i >= 0){
              $result = $content[$totalMsg - $i];
              $result = str_replace(" LineBreak; ", "<br>", $result);
              echo $result;
            }
          }
        }
        else{
          $filePath = "../Users/ChatBox/Messages/Messages.php";
          $content = file($filePath);
          echo $content[0];
        }
          ?>
      </div>
        <div id="showCasePrev">
          <!--<div class="Priview"><div class="Container"><i class="fa fa-spinner"></i></div></div><br>-->
        </div>
        <div id="MessageComposer">
          <div id="filePrivew"></div>
          <textarea name="Message" id="Message"autofocus=""placeholder="What's next..."></textarea>
          <span id="btnContainer">
            <span id="MoreDataType"><i class="fa fa-image" onclick="imageSender();"></i></span>
            <span><button id="send"disabled=""onclick="sendMessage()">send</button></span>
          </span>
        </div>
      </div>
  </div>
  <div id="rightBarContainer"class="">
    <?php
      include_once('extras/rightBar.php');
    ?>
  </div>
  <div id="settingWindowContainer"class="">
  </div>
</body>
<script type="text/javascript" src="../plugins/jquery.min.js"></script>
<script type="text/javascript" src="chat.js"></script>
</html>