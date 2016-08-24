<?php 
    $pageTitle = "Homepage - Bet Tracking";
    include("includes/header.php"); ?>

  <style type="text/css">
    body {
      background-image: url("images/login_back.png");
    }
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      max-width: 450px;
    }
  </style>
  <script>
  $(document)
    .ready(function() {
      $('.ui.form')
        .form({
          fields: {
            login_user_name: {
              identifier  : 'login_user_name',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your username or e-mail'
                }
              ]
            },
            login_password: {
              identifier  : 'login_password',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your password'
                }
              ]
            }
          }
        })
      ;
    })
  ;
  </script>

<div class="ui middle aligned center aligned grid" id="loginFrm">
  <div class="column">
    <h2 class="ui blue image header">
      <img src="images/dice.png" class="image">
      <div class="content">
        Log-in to your account
      </div>
    </h2>
    <form class="ui large form" method="post" action="bet_action.php" onsubmit="if($('#login_user_name').val() == '' || ('#login_password').val() == ''){return false;} ">
      
      <div class="ui tall stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" id="login_user_name" name="login_user_name" placeholder="Username/E-mail address">
            <input type="hidden" name="_CheckLogin" value="2" >
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" id="login_password" name="login_password" placeholder="Password">
          </div>
        </div>
        <button type="submit" class="ui fluid large blue submit button">Login</button>
      </div>

      <div class="ui error message"></div>
    </form>

    <div class="ui message">
      New to us? <a href="#" onclick="$('#loginFrm').hide();$('#createFrm').show();">Sign Up</a>
    </div>
  </div>
</div>

<div class="ui middle aligned center aligned grid" id="createFrm" style="display:none;">
  <div class="column">
    <h2 class="ui blue image header">
      <img src="images/dice.png" class="image">
      <div class="content">
        Create An Account With Us
      </div>
    </h2>
    <form class="ui large form" method="post" action="bet_action.php" onsubmit="return CheckLoginForm();">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" id="user_name" name="user_name" placeholder="Username" onkeyup="CheckLogin(this.value);">
          </div>
        </div>
            <span id="loginAvail" class="help-block"></span>
        <div class="field">
          <div class="ui left icon input">
            <i class="mail icon"></i>
            <input type="text" id="user_email" name="user_email" placeholder="Email Address" onblur="CheckEmail(this.value);">
          </div>
        </div>
            <span id="emailAvail" class="help-block"></span>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" id="password" name="password" placeholder="Password">
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirm Password">
          </div>
        </div>
        <input type="hidden" name="_createAccount" value="1" />
        <button type="submit" class="ui fluid large blue submit button">Create Account</button>
      </div>

      <div class="ui error message"></div>
    </form>

    <div class="ui message">
      Already have an account? <a href="#" onclick="$('#createFrm').hide();$('#loginFrm').show();">Login</a>
    </div>
  </div>
</div>




</body>
</html>