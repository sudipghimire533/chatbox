<?php
include_once("../../handler/userManager.php");
$userName = base64_decode($_COOKIE[base64_encode('userId')]);
?>
<div id="SettingWin">
  <div id="WindowsBtn"style="margin-bottom:10px;position: fixed;display: inline-block;background-color: var(--color-mid); width: calc(var(--messageBoxWidth) - 10px);z-index: 1;border: 1px solid var(--super-light);">
    <i class="fas fa-times-circle"style="cursor: pointer;color: red;"onclick="exitSettingWindow();"></i>
    <i class="fas fa-minus-circle"style="cursor: pointer;color: green;"onclick="exitSettingWindow();"></i>
    <i class="fas fa-plus-circle"style="cursor: pointer;color: yellow;"onclick="resizeSettingWindow();"></i>
  </div>
  <div id="Content">
    <div id="Logger">
      <span id="text">Costomize your Box</span>
    </div>
    <div id="Control">
      <Apperence>
        <div id="Theme"class="controls">
          <span id="Label">DarkTheme</span>
          <label class="switch"id="controlRight">
              <input type="checkbox"class="slideSwitch"onclick="triggerTheme(this)">
              <span class="slider round"></span>
          </label>
        </div>
        <div id="CostumTheme"class="controls">
            <div id="Label"onclick="toggleAdvancedTheme(this);">
              <i class="fas fa-angle-right"></i>Costum Theme(<code>advanced</code>)
            </div>
            <br>
            <div id="Container">
              <div class="costomize"title="This is espically for foreground">
                <span class="label">--super-light:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This is espically for background">
                <span class="label">--super-dark:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This is applied for common text color">
                <span class="label">--text-normal:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This is espically for psudo elements">
                <span class="label">--color-mid:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This is for the background of sender messages">
                <span class="label">--sender-bg:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This is for background of reciver bg..">
                <span class="label">--reciver-bg:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This is oor the background of common elements..">
                <span class="label">--normal-bg:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This apply only to few elements :hovered">
                <span class="label">--hovered-bg:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This apply only to few elements :hilighted">
                <span class="label">--hilighted-bg:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This apply to the border of corresponding element">
                <span class="label">--color-danger:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This apply only to few elements :hovered">
                <span class="label">--normal-info:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This apply only to few elements :hilighted">
                <span class="label">--color-enabled:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div class="costomize"title="This apply to the border of corresponding element">
                <span class="label">--color-disabled:</span>
                <span class="input"><input type="text"placeholder="Color value..."value="" /></span>
              </div>
              <div id="btnContainer">
                <span id="RestBtn">
                  <button class='btn'title="Reset Changes"onclick="resetThemeValue()">Reset</button>
                </span>
                <span id="RestBtn">
                  <button class='btn'title="Apply Changes"onclick="applyTheme()">Apply</button>
                </span>
              </div>
            </div>
        </div>
      </Apperence>
      <Account>
        <div id="Label"onclick="toggleAccountSetting(this);">
          <i class="fas fa-angle-right"></i>Account Settings
        </div>
        <div class="seperators">
          <i class="fas fa-angle-right"onclick="toggleSubSeperator(this)">Change Password</i>
          <div id="changePass"class="AccControl">
            <div id="firstLine"class="lines">
              <span>
                <input type="password" id="PrevPassword"placeholder="Old Password.." />
                <i class="fas fa-eye"onclick="toggleVisiblePassword(this);"></i>
              </span>
            </div>
            <div id="secondLine"class="lines">
              <span>
                <input type="password" id="PrevPassword"placeholder="New Password" />
                <i class="fas fa-eye"onclick="toggleVisiblePassword(this);"></i>
              </span>
            </div>
            <div id="thirdLine"class="lines">
              <span>
                <input type="password" id="PrevPassword"placeholder="Retype New Password" />
                <i class="fas fa-eye"onclick="toggleVisiblePassword(this);"></i>
              </span>
            </div>
            <div id="btnContainer">
              <div class="btns">
                <button onclick="changePassword()">Change</button>
              </div>
              <div id="LogOutFromOthers"class="btns">
                <button onclick="logOtherOut()">LougOut From Other Device</button>
              </div>
            </div>
          </div>
        </div>
        <div class="seperators">
          <i class="fas fa-angle-right"onclick="toggleSubSeperator(this)">Change Secondary Email</i>
          <div id="ChangeSecEmail"class="AccControl">
            <div id="firstLine" class="lines">
              <span>
                <input type="text"id="SecEmail"placeholder="Add secondary Email.."value="<?php echo returnCostum('chatbox', 'main', 'SecEmail', 'UserName', $userName); ?>">
              </span>
              <div id="btnContainer">
                <span class="btns">
                  <button id="saveBtn"class="btns"onclick="changeSecEmail();">Save</button>
                </span>
              </div>
              </span>
            </div>
          </div>
        </div>
        </Account>
      </Account>
  </div>
</div>
<style>
  .seperators{
    margin-left: 2vw;
    margin-top: 5px;
    margin-bottom: 5px;
    position: relative;
    display: inline-block;
    width: 95%;
  }
  .seperators i.fas{
    color: var(-super-dark);
    font-weight: bold;
    font-size: 14px;
    cursor: pointer;
  }
  .seperators > div{
    margin-left: 2vw;
  }
  #settingWindowContainer{
    border: 1px solid var(--color-mid);
    background-color: var(--super-light);
    height: calc(100% - 90px);
    min-height: calc(100% - 90px);
    max-height: calc(100% - 90px);
    margin-top: 70px;
    width: auto;
    z-index: 1;
    min-width: var(--messageBoxWidth);
    max-width: var(--messageBoxWidth);
    margin-left: var(--LeftWidth);
    margin-right: var(--rightWidth);
    box-shadow: 2px 2px 7px 7px var(--super-dark);
    overflow-y: scroll;
    overflow-x: hidden;
    background-color: var(--normal-bg);
    position: absolute;
  }
  #settingWindowContainer #Content{
    position: relative;
    display: inline-block;
    padding-top: 25px;
  }
  #settingWindowContainer #Content #Control Account #Label{
    font-size: 14px;
    font-weight: bold;
    font-family: sans-serif;
    color: var(--super-dark);
    cursor: pointer;
    float: left;
    text-align: left;
    text-transform: uppercase;
    text-decoration: none;
    padding: 5px 10px;
  }
  #settingWindowContainer #Content #Control{
    position: relative;
    display: inline-block;
    padding: calc(2vh + 23px) 2vw;
  }
  #settingWindowContainer #SettingWin #Content #Logger{
    width: calc(var(--messageBoxWidth) - 10px );
    background-color: olivedrab;
    padding: 5px 0;
    text-align: center;
    display: inline-block;
    position: fixed;
    z-index: 1;
  }
  #settingWindowContainer #SettingWin #Content #Logger #text{
    position: relative;
    color: var(--super-light);
    font-weight: normal;
    text-shadow: 1px 1px var(--super-dark);
    cursor: pointer;
    
  }
  #settingWindowContainer #Content #Control .controls{
    position: relative;
    display: inline-block;
    box-sizing: border-box;
    padding: 5px 2px;
    width: calc(var(--messageBoxWidth) - 100px);
    min-width: 200px;
    margin: 3px 0;
  }
  #settingWindowContainer #Content #Control .controls #Label{
    font-size: 14px;
    font-weight: bold;
    font-family: sans-serif;
    color: var(--super-dark);
    cursor: pointer;
    float: left;
    text-align: left;
    text-transform: uppercase;
    text-decoration: none;
  }
  #settingWindowContainer #Content #Control .controls #controlRight{
    float: right;
  }
  #settingWindowContainer #Content #Control #CostumTheme{
    position: relative;
    width: 100%;
    padding-left: 10px;
  }
  #settingWindowContainer #Content #Control #CostumTheme #Container{
    margin-left: 3vw;
    position: relative;
    width: 100%;
  }
  #settingWindowContainer #Content #Control #CostumTheme #Container .costomize{
    display: inline-block;
    position: relative;
    width: 95%;
    margin: 5px 0;
  }
  #settingWindowContainer #Content #Control #CostumTheme #Container .costomize .label{
    font-size: 12px;
    float: left;
    box-sizing: border-box;
    color: var(--color-mid);
  }
  #settingWindowContainer #Content #Control #CostumTheme #Container .costomize input{
    float: right;
    text-align: center;
    box-sizing: border-box;
    padding: 0 3px;
    outline: none;
    border: none;
    min-width: 210px;
    max-width: 210px;
    border-bottom: 1px solid var(--color-mid);
    color: var(--super-dark);
    font-weight: bold;
    font-size: 14px;
    transition: border-bottom-color 1s;
  }
  #settingWindowContainer #Content #Control #CostumTheme #Container .costomize input:focus{
    border-bottom-color: var(--normal-border);
  }
  #settingWindowContainer #Content #Control #CostumTheme #Container #btnContainer{
    position: relative;
    width: auto;
    float: right;
    margin-right: 30px;
    margin-top: 20px;
  }
  #settingWindowContainer #Content #Control #CostumTheme #Container #btnContainer button{
    outline: none;
    border:none;
    font-family: mono;
    font-size: 14px;
    padding: 10px 20px;
    cursor: pointer;
    font-weight: bold;
    border-radius: 5px;
  }
  #settingWindowContainer #Content #Control #CostumTheme #Container #btnContainer #RestBtn button{
    background-color: rgb(59, 114, 59);
    color: white;
    border: 1px solid white;
    transition: background 1s; -webkit-transition: background 1s;
    transition: color 1s; -webkit-transition: color 1s;
  }
  #settingWindowContainer #Content #Control #CostumTheme #Container #btnContainer #RestBtn button:hover{
    background-color: white;
    color: rgb(59, 114, 59);
  }
  #settingWindowContainer #Content #Control .AccControl{
    margin-left: 2vw;
  }
  #settingWindowContainer #Content #Control #changePass{
    margin-top: 20px;
    position: relative;
    width: 98%;
  }
  #settingWindowContainer #Content #Control .AccControl .lines{
    position: relative;
    display: inline-block;
    width: 100%;
  }
  #settingWindowContainer #Content #Control .AccControl .lines span input{
    border: none;
    outline: none;
    font-size: 14px;
    color: var(--color-mid);
    font-weight: bold;
    padding: 7px 10px;
    border-left: 3px solid var(--color-mid);
    margin: 5px 0;
    text-align: left;
    min-width: 100px;
    max-width: 90%;
    transition: border-radius .3s; -webkit-transition: border-radius .3s;
    transition: border-color .3s; -webkit-transition: border-color .3s;
  }
  #settingWindowContainer #Content #Control #change .lines span i.fas{
    position: absolute;
    top: 20%;
    color: var(--color-mid);
    cursor: pointer;
  }
  #settingWindowContainer #Content #Control .AccControl .lines span input:focus{
    color: var(--super-dark);
    border-left-color: var(--normal-border);
    min-width: 100px;
    max-width: 90%;
    border-radius: 5px;
  }
  #settingWindowContainer #Content #Control .AccControl #btnContainer{
    position: relative;
    width: 98%;
    margin: 20px 0;
  }
  #settingWindowContainer #Content #Control .AccControl #btnContainer .btns button{
    border: none;
    outline: none;
    color: var(--super-light);
    background-color: var(--color-enabled);
    font-weight: bold;
    font-size: 16px;
    padding: 10px 15px;
    border-radius: 7px;
    cursor: pointer;
    transition: box-shadow .8s; -webkit-transition: box-shadow .8s;
    transition: transform .8s; -webkit-transition: transform .8s;
    margin: 10px 0px;
  }
  #settingWindowContainer #Content #Control .AccControl #btnContainer .btns button:hover{
    transform: scale(0.95);
  }
  #settingWindowContainer #Content #Control .AccControl #btnContainer .btns button:focus{
    transform: scale(0.90);
  }
  #settingWindowContainer #Content #Control #ChangeSecEmail .lines{
    margin-top: 10px;
  }
  #settingWindowContainer #Content #Control #ChangeSecEmail #btnContainer span button{
    margin: 0;
  }
</style>
<script type="text/javascript">
  function showLog(text, type, focuson){
    let bg;
    if(type == 'sucess'){
      bg = 'var(--normal-info)';
    }
    else if(type == 'error'){
      bg = 'var(--color-danger)';
    }
    else{
      bg = type;
    }
    let container = $("#Logger #text");
    container.html(text);
    $("#Logger").css("background-color", bg);
    if(focuson != null){
      if(focuson == 'oldPass'){
        $("#changePass .lines span input").get(0).focus();
      }
      else if(focuson == 'newPass'){
        $("#changePass .lines span input").get(1).focus();
      }
      else if(focuson == 'confirmPass'){
        $("#changePass .lines span input").get(2).focus()
      }
      else if(focuson == 'secEmail'){
        $("#ChangeSecEmail #firstLine input#SecEmail").get(0).focus();
      }
    }
  }
  function resetThemeValue(){
    lightTheme();
  }
  function fillDeafultValue(){
    let i = 0;
    let fields = $("#CostumTheme #Container .costomize");
    for (i=0; i < fields.get().length; i++){
      let block = $(fields.get(i));
      let label = block.children()[0].textContent.replace(":", "");
      let input = $(block.children()[1]).children()[0];
      $(input).on('keyup', function() {
         setThemeValue();
      });
      input.value = window.getComputedStyle(document.body).getPropertyValue(label);
    }
  }
  self = new Array;
  function setThemeValue(){
    self = new Array;
    let i = 0;
    let fields = $("#CostumTheme #Container .costomize");
    for (i=0; i < fields.get().length; i++){
      let block = $(fields.get(i));
      let label = block.children()[0].textContent.replace(":", "");
      let value = $(block.children()[1]).children()[0].value;
      $("body").get(0).style.setProperty(label, value);
      self.push(label+":"+value);
    }
  }
  function applyTheme(){
    if(self.length > 0){
      store = JSON.stringify(self);
      localStorage.setItem("prefferedTheme", store);
    }
  }
  function toggleAdvancedTheme(){
    let container  = $("#CostumTheme #Container");
    container.slideToggle(1000);
    setTimeout(function(){$("#CostumTheme #Label i").toggleClass("fa-angle-right")}, 700);
    setTimeout(function(){$("#CostumTheme #Label i").toggleClass("fa-angle-down")}, 700);
    if($("#CostumTheme #Label i").get(0).classList.contains("fa-angle-down")){
      fillDeafultValue();
    }
    else{
      let container = $("#Logger #text");
      container.html("Costomize your box");
      $("#Logger").css("background-color", "olivedrab");
      $("#Logger #text").css("color", "var(--super-light)");
      $("#Logger #text").html("Costomize your Box");
    }
  }
  function toggleAccountSetting(){
    $("Account .seperators .AccControl").hide();
    let container = $("Account .seperators");
    container.slideToggle(500);
    setTimeout(function(){$("Account #Label i.fas").toggleClass("fa-angle-right")}, 300);
    setTimeout(function(){$("Account #Label i.fas").toggleClass("fa-angle-down")}, 300);
  }
  function logOtherOut(){
    $.ajax({
      type: 'POST',
      url: '../handler/SessionManager.php',
      data: {action: "LogOtherOut"}
    });
  }
  function toggleVisiblePassword(me){
    let input = $(me.parentElement).children()[0];
    if(input.type == "password"){
      input.type = "text";
      me.classList.add("fa-eye-slash");
      me.classList.remove("fa-eye");
    }
    else if(input.type == "text"){
      input.type = "password";
      me.classList.add("fa-eye");
      me.classList.remove("fa-eye-slash");
    }
  }
  function toggleSubSeperator(me){
    let container = me.parentElement;
    container = $(container).children()[1];
    $(container).slideToggle(300);
    $(me).toggleClass("fa-angle-right");
    $(me).toggleClass("fa-angle-down");
    $("#Logger #text").html('Costomize your Box');
    $("#Logger #text").css('color', 'var(--super-light)');
    $("#Logger").css('background-color', 'olivedrab');
  }
  function changePassword(){
    let inputs = $("#changePass .lines span input");
    let oldPass = inputs.get(0);
    let newPass = inputs.get(1);
    let confirmPass = inputs.get(2);
    $.ajax({
      type: 'POST',
      url: '../handler/userManager.php',
      data: {
        action: "changePassword",
        oldPassword: oldPass.value,
        newPassword: newPass.value,
        confirmPassword: confirmPass.value
      }
    }).done(function(response){
      $("#Logger #text").html(response);
      oldPass.value = "";
      newPass.value = "";
      confirmPass.value = "";
    });
  }
  function changeSecEmail(){
    let field = $("#ChangeSecEmail #firstLine input#SecEmail");
    $.ajax({
      type: 'POST',
      url: '../handler/userManager.php',
      data: {
        action: "changeSecEmail",
        secEmail: field.get(0).value
      }
    }).done(function(response){
      $("#Logger #text").html(response);
    });
  }
  $("#CostumTheme #Container").hide();
  $("Account .seperators").hide();
</script>