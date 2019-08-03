var alertSound;
var loadFriendList = true;
var loadRequestList = true;
var loadSentList = true;
var loadMessage = true;
var totalMessage=10;
var detectLastTalk = true;
var existLeftBar = true;
var existRightBar = true;

function PositionElements(){
  vars = new Array;
  vars['LeftWidth'] = vars['RightWidth'] = 0;
  vars['root'] = $("body").get(0);
  vars['totalWidth'] = document.body.offsetWidth;
  if(existLeftBar == true){
    vars['leftBarContainer'] = document.getElementById('leftBarContainer');
    vars['LeftWidth'] = vars['leftBarContainer'].offsetWidth;
  } else{
    vars['LeftWidth'] = 0;
  }
  if(existRightBar == true){
    vars['rightBarContainer'] = document.getElementById('rightBarContainer');
    vars['RightWidth'] = vars['rightBarContainer'].offsetWidth;
  } else{
    vars['RightWidth'] = 0;
  }
  vars['MessageContainer'] = document.getElementById('MessageBoxContainer');
  vars['totalAvailable'] = document.body.clientWidth;
  vars['MessageContainer'].style.marginLeft = vars['LeftWidth'] - 7;
  vars['MessageContainer'].style.marginRight = vars['RightWidth'];
  vars['MessageContainer'].style.maxWidth = vars['totalWidth'] - vars['LeftWidth'] - vars['RightWidth'] + 12;
  vars['MessageContainer'].style.minWidth = vars['totalWidth'] - vars['LeftWidth'] - vars['RightWidth'] + 12;
  document.getElementById('MessageComposer').style.width = vars['MessageContainer'].offsetWidth - 20;
  document.getElementById('MessageComposer').style.minWidth = this.vars['MessageContainer'].offsetWidth - 20;
  vars['root'].style.setProperty("--messageBoxWidth",vars['MessageContainer'].style.minWidth);
  vars['root'].style.setProperty("--LeftWidth", vars['LeftWidth'] + "px");
  vars['root'].style.setProperty("--RightWidth", vars['RightWidth'] + "px");
  delete vars;
  setTimeout(PositionElements, 2000);
}
function loadSideBars(){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        document.getElementById("SentReqestPending").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "../handler/ListMain.php?placements=sentList", true);
  if(loadFriendList == true)
    { xhttp.send(); }
  else
    {delete xhttp;}
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        document.getElementById("FriendListPending").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "../handler/ListMain.php?placements=PendingList", true);
  if(loadSentList == true)
    { xhttp.send(); }
  else
    {delete xhttp;}
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("FriendList").innerHTML = this.responseText;
      if(detectLastTalk == true){
      //this shold only execute once when loaded for first time
        let oneTime = new Array;
        oneTime['LastTalkWith'] = document.cookie.replace(/(?:(?:^|.*;\s*)LastTalkWith\s*\=\s*([^;]*).*$)|^.*$/, "$1");
          if(oneTime['LastTalkWith'].length < 1){
            window.location.reload();
          }
          if(typeof oneTime['LastTalkWith'] == 'string' && oneTime['LastTalkWith'] != 'ChatBox'){
            oneTime['LastTalkWithElement'] = document.getElementById(oneTime['LastTalkWith']);
            if(oneTime['LastTalkWithElement'] != null){
              if(oneTime['LastTalkWithElement'].classList.contains("Friend")){
                oneTime['LastTalkWithElement'].click();
                detectLastTalk = false;
              }
            }
          }
        delete detectLastTalk,oneTime;
      }
    }
  };
  xhttp.open("GET", "../handler/ListMain.php?placements=friendList", true);
  if(loadRequestList == true)
    { xhttp.send(); }
  else
    {delete xhttp;}
  if(loadRequestList == true || loadFriendList == true || loadSentList == true)
    { setTimeout(loadSideBars, 5000); }
}
//few variables ----
var loop, prevElem;
var currentFriend = document.cookie.replace(/(?:(?:^|.*;\s*)LastTalkWith\s*\=\s*([^;]*).*$)|^.*$/, "$1");
function refreshMessage(elem, next=0){
  if($(".Messages").length <= 0){
    elem.click();
  }
  if(document.getElementById('MessageBoxContainer').scrollTop == 0){
    if($(".Messages").length >= 10){
      totalMessage  = totalMessage+"add"+5;
      $("#loadingIcon").remove();
      $("#Messages").append("<div id='loadingIcon'><div class='upperMessage loadingIcon'></div></div>");
      $('#MessageBoxContainer').animate({
          scrollTop: 30
      }, 100);
    }
  }
  if(typeof loop !== 'undefined'){
    clearTimeout(loop);
  }
  this.vars = new Array;
  this.vars['friendName'] = $(elem).attr('id');
  $("#MessageBoxContainer #WindowsBtn .title#PersonName").get(0).textContent = this.vars['friendName'];
  $.ajax({
    url: "extras/Message.php",
    data: {
      action: 'showMessage',
      friendName: this.vars['friendName'],
      count: totalMessage,
      switched: next
    },
    cache: true,
    type: "GET",
    success: function(response){
      $("#loadingIcon").remove();
      if(response == "reload"){
        window.location.reload(false);
      }
      else if(response === "_We_are_kindly_waiting_for_your_feedback_"){
        $("#loadingIcon").remove();
        response = response.replace("_We_are_kindly_waiting_for_your_feedback_", "");
        totalMessage = $(".Messages").length;
      }
      else if(response.includes("f6950f3e54e16c9921e3b05704b808e881247a5f052af0582a96c40afdef96c3abad4c5ef96b7ae6bb1dab5968d6cd681d30e9dad2999f48c352681af27ec5b8")){
        this.res = response.replace("f6950f3e54e16c9921e3b05704b808e881247a5f052af0582a96c40afdef96c3abad4c5ef96b7ae6bb1dab5968d6cd681d30e9dad2999f48c352681af27ec5b8", "");
        this.res = this.res.replace("_We_are_kindly_waiting_for_your_feedback_", "");
        $("#Messages").append(this.res);
        totalMessage = $(".Messages").length;
      }
      else if(response.includes("fjhdjhjghrhfdsdj1111lksjfkhfuutyruwiyrouw08039rfhhdfkhskhdskghio3urou3otyhfkjuqowueryeifhceuourhfjcrufhcrihigh")){
        this.res = response.replace("fjhdjhjghrhfdsdj1111lksjfkhfuutyruwiyrouw08039rfhhdfkhskhdskghio3urou3otyhfkjuqowueryeifhceuourhfjcrufhcrihigh", "");
        this.res = this.res.replace("_We_are_kindly_waiting_for_your_feedback_", "");
        $("#Messages").prepend(this.res);
        $('#MessageBoxContainer').animate({
          scrollTop: $('#MessageBoxContainer').get(0).scrollHeight
        }, 1000);
        alertSound.play();
        totalMessage = $(".Messages").length;
      }
      else{
        $("#Messages").html(response);
        totalMessage = $(".Messages").length;
      }
      totalMessage = $(".Messages").length;
    },
    error: function(){
      window.location.reload(false);
    }
  });
  if($(".Messages").length <= 0){
    elem.click();
  }
  delete this.vars;
  delete next;
  if(loadMessage == true){
    loop = setTimeout(refreshMessage, 500, elem);
  }
}
function showMessage(elem){
  this.shouldRefresh = true;
  loadMessage = true;
  totalMessage = 10;
  $('#MessageBoxContainer').animate({
    scrollTop: $('#MessageBoxContainer').get(0).scrollHeight
  }, 1000);
  prevElem = elem;
  $("button#send").attr("disabled", false);
  PositionElements();
  loadSideBars();
  currentFriend = $(elem).attr('id');
  document.getElementById("Message").focus();
  if(loadMessage == true && this.shouldRefresh == true){
    delete this.shouldRefresh;
    refreshMessage(elem, 1);
  }
}
function sendMessage(){
  if($("#MessageComposer #filePrivew").find('i.fa.fa-image').length){
    //alert(fileSelector.children()[0].files.length);
    $("#MessageComposer #filePrivew").get(0).innerHTML = "";
    container = $("#MessagesParent #showCasePrev");
    let i = 0;
    images.forEach(element => {
      let file_data = fileSelector.children()[0].files[i];
      let form_data = new FormData();
      imgName = fileSelector.children()[0].files[i].name;
      extension = imgName.split('.').pop().toLowerCase();
      if(jQuery.inArray(extension, ['png', 'jpg', 'jpeg', 'gif']) == -1){
        cansend = false;
        alert('invalid format');
      }
      i += 1;
      form_data.append("file", file_data);
      if(cansend = true){
        container.get(0).innerHTML += '<div class="Priview shown"><div class="Container"><i class="fa fa-spinner"></i></div></div>';
        $.ajax({
          url: '../handler/MessageManager.php',
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,
          type: 'POST',
          success: function (response) {
              if(response == imgName){
                alert("sucess");
                clearPriview();
              }
              else if(response == 'not friend'){
                $("button#send").attr("disabled", true);
                document.getElementById("Messages").html += "<br>Can't Send that Message..";
              }
              else if(response == 'reload'){
                window.location.reload();
              }
              else{
                alert(response);
              }
          },
          sucess: function(response){
            alert(response);
          },
          error: function (response) {
              alert(response);
          }
        });
        if(loadMessage == true){
          refreshMessage(prevElem);
        }
      }
      if(i == images.length){
        images = new Array;
      }
    });
  }
  let messageField = document.getElementById("Message");
  message = messageField.value;
  if(message.trim().length>0){
    this.msg = "<div class='Sender Messages unsend'><span class='text'>"+message+"</span></div>";
    $("#Messages").get(0).innerHTML = this.msg + $("#Messages").get(0).innerHTML;
    $.ajax({
      url: "../handler/MessageManager.php",
      data: {
        action: 'sendMessage',
        friendName: currentFriend,
        message: message
      },
      cache: true,
      type: "POST"
    }).done(
      function(e){
        totalMessage += 1;
        if(e == "not friend"){
          $("button#send").attr("disabled", true);
          document.getElementById("Messages").html += "<br>Can't Send that Message..";
        }
        else if(e == 'reload'){
          window.location.reload();
        }
        else if(e.includes('sucess_send_message_')){
          this.msg = e.replace("sucess_send_message_","");
          this.msg = "<div class='Sender Messages'><span class='text'>"+this.msg+"</span></div>";
          $(".Messages.Sender.unsend").remove();
          $("#Messages").get(0).innerHTML = this.msg + $("#Messages").get(0).innerHTML;
        }
      }
    );
    messageField.focus();
    document.getElementById("Message").value = "";
    $('#MessageBoxContainer').animate({
      scrollTop: $('#MessageBoxContainer').get(0).scrollHeight
    }, 1000);
  }
}

function ready() {
  let themeValue = localStorage.getItem("prefferedTheme");
  if(themeValue == null){
    lightTheme();
  }
  else if(themeValue == 'Dark'){
    darkTheme();
  }
  else if(themeValue == 'Light'){
    lightTheme();
  }
  else{
    costumTheme();
  }
  $( "#Message" ).keypress(function(e) {
    if(e.which === 13){
      if(!e.shiftKey){
        e.preventDefault();
        $("button#send").get(0).click();
      }
    }
  });
  alertSound = new Audio('../plugins/alert.wav');
  $("#DropDownMenu").hide(50);
  $("button#send").attr("disabled", true);
  document.getElementById("menuContainer").style.display = "block";
  document.getElementById("leftBarContainer").style.display = "block";
  document.getElementById("rightBarContainer").style.display = "block";
  document.getElementById("MessageBoxContainer").style.display = "block";
  $("#SearchBoxForm input").each(function(){$(this).get(0).value="";});
  $('#MessageBoxContainer').animate({
    scrollTop: $('#MessageBoxContainer').get(0).scrollHeight
  }, 1000);
  loadSideBars();
  PositionElements();
}

images = new Array;
var fileSelector = null;
function preview(input) {
  reader = new FileReader();
  if (input.files && input.files[0]) {
      reader.onload = function (e) {
        if(images.length < 6){
          for (let index = 0; index < input.files.length; index++) {
            img = $("<img src='' />");
            img.attr("src", e.target.result);
            images.push(img);
            $("#MessageComposer #filePrivew").append("<i class='fa fa-image'></i>");
          }
        }
        else{
          alert("max file size reached");
        }
      }
      reader.readAsDataURL(input.files[0]);
  }
}
function clearPriview(){
  let toRemove = $("#MessagesParent #showCasePrev div.Priview.shown:last-child");
  toRemove.remove();
}
function imageSender(){
  if(images.length > 0){
    sendMessage();
  }
  fileSelector = $("<form enctype='multipart/form-data'><input type='file'accept='image/*'multiple=''></form>");
  fileSelector.children()[0].click();
  $(fileSelector.children()[0]).change(function(){
    preview(this);
  });
}
function searchUser(){
  let elem = $("#SearchBoxForm input").get(0);
  text = elem.value;
  document.getElementById("Searchreasult").style.display = "block";
  elem.value = text.toLowerCase().split(' ').join('');
  text = elem.value;
  $.ajax({
    url: "../handler/userManager.php",
    data: {
      action: 'searchUser',
      query: text
    },
    cache: false,
    type: "GET",
    success: function(response){
      $("#Container").html(response);
    }
  });
}
function searchResFocusOut(){
  $("body").bind( "click.hideSearchMenu",function(event) {
    if($(event.target).is("#Menu #LeftMenu *") == false){
      hideSearch();
    }
    $("body").unbind('.hideSearchMenu');
  });
  $("#SearchBoxForm input").get(0).value = "";
}
function startSearchUser(){
  $( "#Menu #LeftMenu ul #Searchreasult" ).scrollLeft(0);
  $("#SearchBoxForm input").get(0).value = "";
  searchUser();
}
function addFrnd(string){
  string = string.replace("_unfriend_this_user_", "");
  string = string.replace("_delete_this_request_", "");
  string = string.replace("_cancel_this_request_", "");
  string = string.replace("_accept_this_request_", "");
  string = string.replace("_send_request_to_this_user_", "");
  $.ajax({
    url: '../handler/requestManager.php',
    data: {action: 'sentRequest', UserName: string},
    type: 'POST',
    cache: false
  });
  hideSearch();
  loadSideBars();
}
function deleteReq(string){
  string = string.replace("_unfriend_this_user_", "");
  string = string.replace("_delete_this_request_", "");
  string = string.replace("_cancel_this_request_", "");
  string = string.replace("_accept_this_request_", "");
  string = string.replace("_send_request_to_this_user_", "");
  $.ajax({
    url: '../handler/requestManager.php',
    data: {action: 'deleteRequest', UserName: string},
    type: 'POST',
    cache: false
  });
  hideSearch();
  loadSideBars();
}
function cancelReq(string){
  string = string.replace("_unfriend_this_user_", "");
  string = string.replace("_delete_this_request_", "");
  string = string.replace("_cancel_this_request_", "");
  string = string.replace("_accept_this_request_", "");
  string = string.replace("_send_request_to_this_user_", "");
  $.ajax({
    url: '../handler/requestManager.php',
    data: {action: 'cancelReq', UserName: string},
    type: 'POST',
    cache: false
  });
  hideSearch();
  loadSideBars();
}
function unfriend(string){
  string = string.replace("_unfriend_this_user_", "");
  string = string.replace("_delete_this_request_", "");
  string = string.replace("_cancel_this_request_", "");
  string = string.replace("_accept_this_request_", "");
  string = string.replace("_send_request_to_this_user_", "");
  $.ajax({
    url: '../handler/requestManager.php',
    data: {action: 'unfriend', UserName: string},
    type: 'POST',
    cache: false
  }).done(
    function(e){
      if(e == "reload"){
        document.location.reload();
      }
    }
  );
  hideSearch();
  loadSideBars();
}
function acceptReq(elem){
  friendName = elem.id;
  friendName = friendName.replace("_unfriend_this_user_", "");
  friendName = friendName.replace("_delete_this_request_", "");
  friendName = friendName.replace("_cancel_this_request_", "");
  friendName = friendName.replace("_accept_this_request_", "");
  friendName = friendName.replace("_send_request_to_this_user_", "");
  $.ajax({ 
     url: '../handler/requestManager.php',
      data: {action: 'acceptRequest', UserName: friendName},
      type: 'POST',
      cache: false
    });
  hideSearch();
  loadSideBars();
}
function refreshSearch(){
  $("#SearchBoxForm input").keydown();
}
function toggleDropDown(){
  $("#DropDownMenu").show(500);
  $("body").bind("mousedown.hideMenuDropDown",function(event){
    if($(event.target).is("#DropDownMenu *") == false){
      $("#DropDownMenu").hide(500);
    }
    $("body").unbind('.hideMenuDropDown');
  });
}
function LogOut(){
  localStorage.removeItem("prefferedTheme");
  $.ajax({
    type: 'POST',
    url: '../handler/SessionManager.php',
    data: {action: "LogOut"},
    success: function(response){
      window.location.reload(true);
    },
    error: function() {
      showLog('Something unusal happened..','#FF5555');
    }
  });
}
function toggleFriendList(){
  if(loadFriendList == true){ loadFriendList = false; }
  else{ loadFriendList = true; }
  $("#FriendList").toggle($("#ListShow >div").get().length * 300);
  $("#ListShow #Label").toggleClass("fa-angle-right");
  loadSideBars();
}
function toggleRequestList(){
  if(loadRequestList == true){ loadRequestList = false; }
  else{ loadRequestList = true; }
  $("#FriendListPending").toggle($("#ListShowPending >div").get().length * 300);
  $("#ListShowPending #Label").toggleClass("fa-angle-right");
  loadSideBars();
}
function toggleSentList(){
  if(loadSentList == true){ loadSentList = false; }
  else{ loadSentList = true; }
  $("#SentReqestPending").toggle($("#ListShowSent >div").get().length * 300);
  $("#ListShowSent #Label").toggleClass("fa-angle-right");
  loadSideBars();
}
function hideSearch(){
  searchResFocusOut();
  $("#Searchreasult").hide();
  $("#SearchBoxForm input").get(0).value = "";
  $("#Container").html("");
}
function disableMessage(){
  $("button#send").attr("disabled", true);
}
function removeLeftPanel(){
  document.getElementById("leftBarContainer").remove();
  loadRequestList = false;
  loadFriendList = false;
  loadSentList = false;
  existLeftBar = false;
  PositionElements();
}
function removeRightPanel(){
  document.getElementById("rightBarContainer").remove();
  existRightBar = false;
  PositionElements();
}
function hideRightBar(){
  resizeRightBar(action = "minimize");
  $("#SideBarRight").hide(300);
  $("#ShowSideBarRight").show();
  PositionElements();
}
function showRightBar(){
  $("#SideBarRight").show(300);
  $("#ShowSideBarRight").hide();
  PositionElements();
}
function resizeRightBar(action = null){
  if(action == "maximize"){
    $("#rightBarContainer").get(0).classList.add("maximized");
  }
  else if(action == "minimize"){
    $("#rightBarContainer").get(0).classList.remove("maximized");
  }
  else{
    $("#rightBarContainer").toggleClass("maximized");
  }
  PositionElements();
}
function hideLeftBar(){
  $("#SideBarLeft").hide(300);
  $("#ShowSideBarLeft").show();
  loadFriendList = false;
  loadRequestList = false;
  loadSentList = false;
  PositionElements();
}
function showLeftBar(){
  $("#SideBarLeft").show(300);
  $("#ShowSideBarLeft").hide();
  loadFriendList = true;
  loadRequestList = true;
  loadSentList = true;
  PositionElements();
}
function maximizeMessage(me){
  if(existLeftBar == true){ hideLeftBar(); }
  if(existRightBar == true){ hideRightBar(); }
  $(me).attr("onclick", "unmaximizeMessage(this)");
  PositionElements();
}
function unmaximizeMessage(me){
  if(existLeftBar == true){ showLeftBar(); }
  if(existRightBar == true){ showRightBar(); }
  $(me).attr("onclick", "maximizeMessage(this)");
  PositionElements()
}
function showSettingWindow(){
  $("#DropDownMenu").hide(50);
  $.ajax({
    type: 'POST',
    url: 'extras/settingWindow.php',
    success: function(response){
      $("#MessageBoxContainer").css("visibility", 'hidden');
      $("#settingWindowContainer").html(response);
      initilizeSettingWin();
    }
  });
}
function resizeSettingWindow(){
  if(existLeftBar == true){
    hideLeftBar();
  }
  if(existRightBar == true){
    hideRightBar();
  }
}
function exitSettingWindow(){
  $("#MessageBoxContainer").css("visibility", 'visible');
  $("#SettingWin").remove();
  $("#settingWindowContainer").html("");
  $("body > *").css("opacity", "1");
}
function triggerTheme(self){
  if(self.checked){
    darkTheme();
  }
  else{
    lightTheme();
  }
  initilizeSettingWin();
}
function initilizeSettingWin(){
  let themeTrigger = $(".switch#controlRight input");
  let Theme = localStorage.getItem("prefferedTheme");
  if(Theme == "Dark"){
    themeTrigger.prop("checked", true);
  }
  else if(Theme == "Light"){
    themeTrigger.prop("checked", false);
  }
  if(typeof fillDeafultValue === 'function'){
    fillDeafultValue();
  }
}
function lightTheme(){
  let root = $("body").get(0);
  root.style.setProperty("--normal-bg", "#EEE");
  root.style.setProperty("--hilighted-bg", "#DDD");
  root.style.setProperty("--hovered-bg", "#DDD5");
  root.style.setProperty("--super-light", "#FFF");
  root.style.setProperty("--super-dark", "#000");
  root.style.setProperty("--normal-border", "#2196F3");
  root.style.setProperty("--color-mid", "#777");
  root.style.setProperty("--color-danger", "#F55");
  root.style.setProperty("--normal-info", "#5F5");
  root.style.setProperty("--text-normal", "#555");
  root.style.setProperty("--sender-bg", "#0099FF");
  root.style.setProperty("--reciver-bg", "#BABDB6");
  root.style.setProperty("--color-disabled", "#B7B5A3");
  root.style.setProperty("--color-enabled", "#9BCB64DD");
  delete root;
  localStorage.setItem("prefferedTheme", "Light");
  initilizeSettingWin();
}
function darkTheme(){
  let root = $("body").get(0);
  root.style.setProperty("--normal-bg", "#111");
  root.style.setProperty("--hilighted-bg", "#222");
  root.style.setProperty("--hovered-bg", "#222A");
  root.style.setProperty("--super-light", "#000");
  root.style.setProperty("--super-dark", "#FFF");
  root.style.setProperty("--normal-border", "#9EADF1");
  root.style.setProperty("--color-mid", "#888");
  root.style.setProperty("--color-danger", "#F55");
  root.style.setProperty("--normal-info", "#5F5");
  root.style.setProperty("--text-normal", "#AAA");
  root.style.setProperty("--sender-bg", "#888");
  root.style.setProperty("--reciver-bg", "#555");
  root.style.setProperty("--color-disabled", "#484A5C");
  root.style.setProperty("--color-enabled", "#9BCB64DD");
  delete root;
  localStorage.setItem("prefferedTheme", "Dark");
  initilizeSettingWin();
}
function costumTheme(){
  value = JSON.parse(localStorage.getItem("prefferedTheme")).toString();
  bits = value.split(",");
  for(i = 0; i < bits.length; i++){
    let sub = bits[i].split(":");
    $("body").get(0).style.setProperty(sub[0], sub[1]);
  }
}