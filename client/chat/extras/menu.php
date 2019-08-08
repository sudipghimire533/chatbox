
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
    <div id="commonControl">
      <span id='friendRequest'class='commonControl'>
        <i class='fas fa-user-friends'onclick='showFriendRequestBinder();'></i>
      </span>
      <span id='Message'class='commonControl'>
        <i class='fas fa-comments'></i>
      </span>
    </div>
    <div id="DropDown"class="tooltip">
      <i class="fas fa-caret-down" onclick="toggleDropDown();"></i>
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
  <div id="bindFriendRequest"class='binder fas fa-angle-up'>
    <div id='Container'>
      <div id='Top'class='section'>
        <span id='left'>
          <strong class='bindTitle'><!--Request--></strong>
        </span>
        <span id='right'>
          <span id='info1'><a href='#'class='sub-label'>Find Friends</a></span>
        </span>
      </div>
      <ul id='main'class='section'>
        <div class='loadingIcon'></div>
      </ul>
      <div id='bottom'class='section'>
        <span id='info1'class="infos">
          <a href='#'>See All</a>
        </span>
        <span id='info2'class="infos">
          <a href='#'>Manage</a>
        </span>
      </div>
    </div>
  </div>
</div>