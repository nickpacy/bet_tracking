
<?php 

  $dv = "SELECT default_bet_amount FROM UserTBL WHERE user_id = $user_id;";
  $d = mysqli_query($conn, $dv);
  $dba = $d->num_rows;
  if ($dba > 0) {
    $drs = mysqli_fetch_array($d);
    $default_bet_amount = $drs["default_bet_amount"];
  }else{
    $default_bet_amount = "";
  }

?>

<div class="ui long modal">
  <i class="close icon"></i>
  <div class="header">
    Add New Bet <i class="add square icon"></i>
  </div>
  <div class="content">

    <form class="ui form" method="post" action="bet_action.php" id="add_bet_form">

      <div class="field">
        <label>Sport:</label>
        <select class="ui dropdown allw" id="sport" name="sport" onchange="GetTeams($(this).val());">
          <option value="">-- Select --</option>
          <?php $qry = "SELECT * FROM Sport GROUP BY League ORDER BY League";
                    $q = mysqli_query($conn, $qry); 
             while ($rs = mysqli_fetch_array($q)) { ?>
              <option value="<?php echo $rs["ID"] ?>"><?php echo $rs["League"] ?></option>
            <?php } ?>
        </select>
      </div>

      <div id="bet_info" style=" display:none;">

        <div id="teams_ajax">
                  
        </div>

        <div class="fields">
          <div class="seven wide field"> 
            <label class="betLbl" for="bet_choice">Pick:</label>
            <select class="dropdown" name="bet_choice" id="bet_choice">
              <option value="">-- Select --</option>
              <option value="2">Away</option>
              <option value="1">Home</option>
              <option value="3">Over</option>
              <option value="4">Under</option>
            </select>
          </div>
          <div class="four wide field">
            <label class="betLbl" for="start_time">Game Date:</label>
            <input type="date" name="start_time" id="start_time"  value="<?php echo date("Y-m-j"); ?>" />
          </div>
          <div class="four wide field">
            <label class="betLbl" for="result">Result:</label>
            <select class="dropdown" name="result" id="result">
              <option value="3">Upcoming</option>
              <option value="1">Win</option>
              <option value="2">Loss</option>
              <option value="4">Push</option>
            </select>
          </div>
        </div>


        <div class="fields">
          <div class="three wide field">
            <label class="betLbl" for="bet_spread">Spread:</label>
            <input type="text" name="bet_spread" id="bet_spread" placeholder="ex. -3.5" >
          </div>
          <div class="three wide field">
            <label>Bet Amount:</label>
            <div class="ui left labeled input">
              <div class="ui label">$</div>
              <input type="text" class="form-control" id="bet" name="bet" placeholder="Bet Amount" />
            </div>
          </div>
          <div class="three wide field">
            <label>Odds:</label>
            <input type="text" id="us-odds" name="us_odds" onblur="if($('#bet').val() != ''){oddsConverter(this)};" placeholder="ex. -110" />
          </div>
          <div class="three wide field">
            <label>To Win:</label>
            <div class="ui left labeled transparent input">
              <div class="ui label">$</div>
              <input type="text" class="disabled" id="payout" name="payout" tabindex="-1"/>
            </div>
          </div>
          <div class="three wide field">
            <label>Percent</label>
            <div class="ui transparent input">
              <input type="text" id="implied-probability" name="implied_probability" tabindex="-1" />
            </div>
          </div>
          <input type="hidden" id="decimal-odds" name="decimal_odds" onblur="oddsConverter(this);">
          <input type="hidden" id="fractional-odds" name="fractional_odds" onblur="oddsConverter(this);">
        </div>

        <div class="field">
          <label class="betLbl">Game Notes:</label>
          <textarea name="notes" id="notes" placeholder="Any Game/Bet Notes" rows="3"></textarea>
        </div>
        <input type="hidden" name="_addbet" value="1" />
      </div>
    </form>
  </div>

  <div class="actions">
    <button class="ui button reset" id="reset_btn" onclick="ResetBetModal();">Cancel</button>
    <button class="ui button primary" onclick="$('#add_bet_form').submit();">Add Bet</button>
  </div>
</div>



<script>

$(document)
    .ready(function() {

      //Allow modal
      $('.ui.long.modal').modal({
        blurring: true,
        observeChanges: true,
        onShow    : function(){
          $("body").addClass("modal-open");
          $("#bet").val("<?php echo $default_bet_amount; ?>");
        },
        onHide : function() {
          $("body").removeClass("modal-open")
        }
      });


});

  function ChangeHomeAway(side, team_ID, team){
    //$( "#" + side + "_team value:Home" ).val(team);
    $("#bet_choice option:contains('" + side +"')").text(side + " - " + team);


    GetTeamThumbnail(side, team_ID);

  }

  function ResetBetModal(){
    $('.ui.long.modal').modal('hide');
    $(this).closest('form').find("input[type=text], select").val("");
    $("#bet_info").hide();
  };

</script>





