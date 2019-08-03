
<div id="Menu">
  <div id="LeftMenu">
    <ul>
      <li id="ImgContainer">
        <img src="../images/favicon.png" />
      </li>
      <li id="searchBox">
        <form action="#" method="GET" id="SearchBoxForm">
          <!--input type="text" placeholder="placegolder" onkeydown="searchUser();" onkeyup="searchUser();"onfocusout="searchResFocusOut();"onfocus="startSearchUser()" /-->
          <?php
            $conn = new mysqli('localhost', 'sudip', 'sudip123', 'chatbox');
            $temp_name = base64_decode($_COOKIE[base64_encode('userId')]);
            $temp_name = mysqli_fetch_array($conn->query('SELECT DisplayName from main LIMIT 1;'))['DisplayName'];
            echo "<input type='text'placeholder='[Search] $temp_name'onkeydown='searchUser();'onkeyup='searchUser();'onfocusout='searchResFocusOut();'onfocus='startSearchUser' />";
            $conn->close();
          ?>
        </form>
      </li>
      <li id="Searchreasult">
        <div id="Container">
        </div>
      </li>
    </ul>
  </div>
  <div id="RightMenu">
    <div id="DropDown">
      <i class="fas fa-caret-down" onclick="toggleDropDown()"> More</i>
    </div>
  </div>
  <div id="DropDownMenu" class="">
      <div border="0"class="DropDownItem">
        <div class="item-label">Home</div>
        <div class="item-label">About</div>
        <div class="item-label">Product</div>
        <hr>
        <div class="item-label" onclick="showSettingWindow();">Setting</div>
        <div class="item-label" onclick="LogOut();">Logout</div>
      </div>
    </div>
</div>