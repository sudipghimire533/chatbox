
<?php
$conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
$GLOBALS['username'] = base64_decode($_COOKIE[base64_encode('userId')]);
$temp_name = $GLOBALS['username'];
$GLOBALS['displayName'] = mysqli_fetch_array($conn->query("SELECT DisplayName from main WHERE UserName='$temp_name' LIMIT 1;"))['DisplayName'];
$conn->close();
?>
<div id="ShowSideBarLeft">
    <i title='Show Left Panel'class="fas fa-plus-circle" onclick="showLeftBar()"></i>
</div>
<div id="SideBarLeft"class="sideBars">
    <div id="WindowsBtn"style="margin-bottom:10px;position: fixed;display: inline-block;background-color: var(--color-mid); width: calc(var(--LeftWidth) - 30px);z-index: 1;">
      <i title="remove this panel"class="fas fa-times-circle"style="cursor: pointer;color: red;"onclick="removeLeftPanel();"></i>
      <i title='hide This Panel'class="fas fa-minus-circle"style="cursor: pointer;color: green;"onclick="hideLeftBar()"></i>
      <i title="resize this panel"class="fas fa-plus-circle"style="cursor: pointer;color: yellow;"></i>
      <i class="title">Contacts:</i>
    </div><br>
    <div id="TopBar" class="leftBarSection">
        <span id="MySelf"style="width: 100%;position: relative;">
            <i class="fas fa-user"style='width: 100%;position: relative;'>
                <?php echo $GLOBALS['displayName']; ?>
            </i>
        </span>
    </div>
    <div id="ListShow" class="leftBarSection">
        <div style="margin-top: 20px;"id="Label"class="fa fa-angle-down"onclick="toggleFriendList()">
            <span id="Text">Friends:</span>
        </div>
        <ul id="FriendList" class="FriendList">
            <!--//<?php
            //include_once("../Users/FriendList/list.php");
            //?>-->
        </ul>
    </div>
    <div id="ListShowPending" class="leftBarSection">
        <div style="margin-top: 20px;"id="Label"class="fa fa-angle-down"onclick="toggleRequestList()">
            <span id="Text">Friend Req:</span>
        </div>
        <ul id="FriendListPending" class="FriendList">
          <!-- //<?php
            //include_once("../Users/FriendList/listPending.php");
            //?>-->
        </ul>
    </div>
    <div id="ListShowSent" class="leftBarSection">
        <div style="margin-top: 20px;"id="Label"class="fa fa-angle-down"onclick="toggleSentList()">
            <span id="Text">Send Req:</span>
        </div>
        <ul id="SentReqestPending" class="FriendList">
          <!--//<?php
            //include_once("../Users/FriendList/sentRequest.php");
          //?>-->
        </ul>
    </div>
    <div style="margin-top: 20px;">
        <a href="#" style="color: #22aabc;text-decoration: none;">
            Explore more..
        </a>
    </div style="margin-top: 20px;">
</div>