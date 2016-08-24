
const regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
const ajaxPage = "includes/pacy_ajax.php";


$(function () {
  $('select.dropdown').dropdown();

});

  function ForgotPassword(alumni_id){

    $.getJSON(ajaxPage,
        {_ForgotPassword: alumni_id},
          function(result){
      $("#alumni_id").val(result.user_id)
      $("#email_password").val(result.temp_pass)
    //$("#RSVPCompletebox").html(result);
    //$("#RSVPCompletebox").show('slow');
  
  });
 };

function CheckEmail(user_email){

      if (regex.test(user_email)) {
            
            $.getJSON(ajaxPage,
               {_CheckEmail: user_email},
               function(result){
                $("#emailAvail").closest( "div" ).removeClass(result.ralertClass)
                $("#emailAvail").closest( "div" ).addClass(result.alertClass)
                $("#emailAvail").html(result.check);
                });

      } else {
          $("#emailAvail").closest( "div" ).removeClass('has-success')
          $("#emailAvail").closest( "div" ).addClass('has-error')
          $("#emailAvail").html('Invalid Email');
      }
}

function CheckLogin(user_name){
      
      if (user_name.length > 1) {
          
          $.getJSON(ajaxPage,
             {_CheckLogin: user_name},
             function(result){
              $("#loginAvail").html(result.check);
              });
      }
}


function CheckLoginForm(){
    var proceed = true;

    $("label").each(function(){
      $(this).removeClass('errorAlert') //<-- Should return all input elements in that specific form.
    })

  if (($('#user_email').val() == "") || ((!regex.test($('#user_email').val())))) {
    $( "label[for='user_email']" ).addClass("errorAlert").html('Email Address*:');
    proceed = false;
  }
  if ($('#user_name').val() == "") {
    $( "label[for='user_name']" ).addClass("errorAlert").html('Username*:');
    proceed = false;
  }
  if ($('#password').val() == "") {
    $( "label[for='password']" ).addClass("errorAlert").html('Password*:');
    proceed = false;
  }
  if ($('#password_confirm').val() == "") {
    $( "label[for='password_confirm']" ).addClass("errorAlert").html('Confirm Password*:');
    proceed = false;
  }
  if (($('#password').val()) != ($('#password_confirm').val())) {
    $( "label[for='password_confirm']" ).addClass("errorAlert").html('Confirm Password*:');
    alert('Passwords do not match');
    proceed = false;
  }
  if (proceed) {
    return true;
  }else{
    return false;
  }
}

function GetTeams(Sport_ID){

    $.get(ajaxPage,
        {_GetTeams: Sport_ID},
          function(result){
      $("#teams_ajax").html(result);
      $("#bet_info").show('slow');

      setTimeout(function() {$('.ui.long.modal').modal('refresh');}, 500);

  });
 };

 function GetTeamThumbnail(side, team){

    $.get(ajaxPage,
        {_GetThumbnail: team},
          function(result){
      $("#" + side + "_thumbnail").html('<img src="' + result + '" height="50px;" style="padding:0 10px" />');
  });
 };