<?php 
    $pageTitle = "My Bets - Bet Tracking";
    include("includes/header.php"); ?>

    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/progress_bar.css">

<?php
  $wins = GetResultNumber("Win");
  $losses = GetResultNumber("Loss");
  $upcomings = GetResultNumber("Upcoming");
  $pushes = GetResultNumber("Push");
  $total = $wins + $losses + $upcomings + $pushes;
        ?>


  <script>
  $(document)
    .ready(function() {

      $('.upcomingPopup').popup({
        position : 'top center',
        html : '<i class="horizontally flipped reply icon"></i><?php echo $upcomings; ?> Upcoming'
      });

      $('.pushPopup').popup({
        position : 'top center',
        html  : '<i class="exchange icon"></i><?php echo $pushes; ?> Push'
      });

    });
  </script>
</head>
<body class="pushable">
<?php require("includes/incl_nav.php"); ?>

    <div class="ui text container">
    </div>

  </div>

<div class="ui custom flowing popup transition hidden" style="min-width:170px;">
  <div class="ui middle aligned animated list">
    <div class="item">
      <div class="content">
        <div class="header"><a href="my_account"><i class="setting icon"></i>&nbsp;My Account</a></div>
      </div>
    </div>
    <div class="item">
      <div class="content">
        <div class="header"><a href="bet_action.php?logout=true"><i class="sign out icon"></i>&nbsp;Logout</a></div>
      </div>
    </div>
  </div>
</div>



<script>
$(document).ready(function(){
    $('.menu .item').tab({
    	onFirstLoad : function(tabName) {
            GetBodavaData(tabName);
    		$('.ph').removeClass("ph");
          }
    });

});
</script>

<style>
/*	.bottom.attached.tab.segment{
		min-height: 400px;
	}
	.ui.container.tabs{
		min-height: 400px;
	}*/
	td{
		padding: .5em;
	}
	.tab.segment, .ph{
		min-height: 500px;
	}
</style>

	
 <div class="ui container tabs" style="padding: 20px 0">
	<h1 class="ui header" style="font-size:2em;">Bovada Game Lines</h1>

	<div class="ui top attached tabular menu">
	  <a class="item" data-tab="NFL">
	  	<img class="leagueIcon" src="http://content.sportslogos.net/logos/7/1007/full/dwuw5lojnwsj12vfe0hfa6z47.gif" alt="NFL" />
	  </a>
	  <a class="item" data-tab="NHL">
	  	<img class="leagueIcon" src="http://content.sportslogos.net/logos/1/491/full/wkue6hfkzqs2bnl0efw8sihf7.png" alt="NHL" />
	  </a>
	  <a class="item" data-tab="MLB">
	  	<img class="leagueIcon" src="https://upload.wikimedia.org/wikipedia/en/thumb/2/2a/Major_League_Baseball.svg/1280px-Major_League_Baseball.svg.png" alt="MLB" />
	  </a>
	  <a class="item" data-tab="NBA">
	  	<img src="http://content.sportslogos.net/logos/6/982/full/2971.gif" alt="NBA" />
	  </a>
	  <a class="item" data-tab="NCF">
	  	<img src="http://sportsandentertainmentnashville.com/wp-content/uploads/2014/10/NCAA_Football_Logo.png" alt="NCF" />
	  </a>
	  <a class="item" data-tab="NCB">
	  	<img src="http://elitesportsadvisor.com/wp-content/uploads/2014/11/NCAA-Basketball-Logo-300x294.png" alt="NCB" />
	  </a>
	</div>
	
	<div class="ui bottom attached tab segment" data-tab="NFL">

	</div>
	<div class="ui bottom attached tab segment" data-tab="NHL">
	  
	</div>
	<div class="ui bottom attached tab segment" data-tab="MLB">
	  
	</div>
	<div class="ui bottom attached tab segment" data-tab="NBA">
	  
	</div>
	<div class="ui bottom attached tab segment" data-tab="NCF">
	  
	</div>
	<div class="ui bottom attached tab segment" data-tab="NCB">
	  
	</div>
	     				      			
</div>

<div id="placeholder" class="ph">

</div>



<script>

function GetBodavaData(league){

	 $('.segment[data-tab=' + league + ']').html('<div class="ui segment"><div class="ui active dimmer"><div class="ui text loader">Loading ' + league + ' Games</div></div><br><br><br></div>');

	$.get("http://sportsfeeds.bovada.lv/basic/" + league + ".xml", function(d){
		$('#show_table').html("");
		$('#show_table').append('<h2 class="page-header">Current NFL Games</h2>');
		
		var str = "";

		 $(d).find('Schedule').each(function(){
		 e = $(this);

		 var last_published = $(this).attr("PUBLISH_DATE") + ' @ ' + $(this).attr("PUBLISH_TIME");
		//console.log(last_published);

		$(e.contents("EventType").contents("Date")).each(function(){
			f = $(this);

			var game_date = f.attr("DTEXT");
			var bDate = new Date(f.attr("DTEXT"))
			var now = new Date();
			var diff = new Date(bDate - now);
			var days = Math.round(diff/1000/60/60/24);
			//console.log(days);
			if (days >= -1) {
				
				// console.log(game_date);

				$(f.contents("Event")).each(function(){
					var eventArray = {};

					eventArray["game_date"] = game_date;

					h = $(this);
					var teams = h.attr("NAME");
					eventArray["teams"] = teams;
					// console.log(teams)

					$(h.contents("Competitor")).each(function(){
						j = $(this);

						
						var arr = j.attr("ID").split("-");
						var bodava_id = arr[1];
						//console.log(bodava_id);

						var name = j.attr("NAME");
						//console.log(name);

						var home_away = j.attr("NUM");
						//console.log(home_away);

						if (home_away == "1") {
							var home_team_id = bodava_id;
							var home_team_name = name;
							eventArray["home_team_id"] = home_team_id;
							eventArray["home_team_name"] = home_team_name;
						}else{
							var away_team_id = bodava_id;
							var away_team_name = name;
							eventArray["away_team_id"] = away_team_id;
							eventArray["away_team_name"] = away_team_name;
						};




						$(j.contents("Line")).each(function(){
							k = $(this);

							if (k.attr("TYPE") == "Moneyline") {
								if (home_away == 1) {
									var home_moneyline = k.contents("Choice").attr("VALUE");
									eventArray["home_moneyline"] = home_moneyline;
								}else{
									var away_moneyline = k.contents("Choice").attr("VALUE");
									eventArray["away_moneyline"] = away_moneyline;

								};
								// console.log(moneyline);
								//console.log("Moneyline - " + k.contents("Choice").attr("VALUE"))
							}else if (k.attr("TYPE") == "Pointspread") {
								if (home_away == 1) {
									var home_pointspread = k.contents("Choice").attr("VALUE");
									eventArray["home_pointspread"] = home_pointspread;
								}else{
									var away_pointspread = k.contents("Choice").attr("VALUE");
									eventArray["away_pointspread"] = away_pointspread;
								};
								// console.log("Pointspread - " + k.contents("Choice").attr("VALUE"))
							};
						})


					})

					var over = "";
					var under = "";
					var x = 1;

					$(h.contents("Line").contents("Choice")).each(function(){
						m = $(this);

						if (x == 1) {
							 over = m.attr("VALUE");
						}else{
							 under = m.attr("VALUE");
						};

						x += 1;

					})

					//console.log(over + under)
					eventArray["over"] = over;
					eventArray["under"] = under;

					var game_time = h.contents("Time").attr("TTEXT");
					eventArray["game_time"] = game_time;
					//console.log(h.contents("Time").attr("TTEXT"))

					str += eventArray["game_date"] + "$$";
					str += eventArray["teams"] + "$$";
					str += eventArray["home_team_id"] + "$$";
					str += eventArray["home_team_name"] + "$$";
					str += eventArray["home_pointspread"] + "$$";
					str += eventArray["home_moneyline"] + "$$";
					str += eventArray["away_team_id"] + "$$";
					str += eventArray["away_team_name"] + "$$";
					str += eventArray["away_pointspread"] + "$$";
					str += eventArray["away_moneyline"] + "$$";
					str += eventArray["over"] + "$$";
					str += eventArray["under"] + "$$";
					str += eventArray["game_time"] + "###";

				})


			};


			})

			arrtoData(str.slice(0,-3), league, last_published);
		})

	})
}; //end function


function arrtoData(str, league, last_published){
	// $('#show_table').html("<div class='text-center'><img style='margin:auto;' src='/bet_tracking/images/loading_hex.gif' alt='Loading...' /></div>");

	$.ajax({
        url: ajaxPage,
        type: 'POST',
        data: {_GetBodavaData: str, sport: league, last_published: last_published},
        async: false,
        cache: false,
        timeout: 30000,
        error: function(){
        },
        success: function(result){ 
        	if (result != "") {
				$('.segment[data-tab=' + league + ']').html(result);
        	}else{
            	$('.segment[data-tab=' + league + ']').html("<div class='ui massive red message'><i class='calendar icon'></i>No Odds Availible</div>");
            }	
        }
    });


}


	
</script>




<?php
require('includes/footer.php');
?>