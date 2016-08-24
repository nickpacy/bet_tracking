<?php 
    $pageTitle = "My Account - Bet Tracking";
    include("includes/header.php"); ?>

    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/progress_bar.css">
</head>
<body class="pushable">
<?php require("includes/incl_nav.php"); ?>
    <div class="ui text container">
    </div>

  </div>


  <div class="ui container grid" style="margin:20px 0px;">
    <div class="row">
      <div class="eight wide column">
        <div class="ui form">
        <div class="ui icon input">
          <input type="text" placeholder="Search users..." onkeyup="CheckFriendTable($(this).val());">
          <i class="inverted circular grey add user link icon" id="addFriendbtn" onclick="return AddFriend()"></i>
          <input type="hidden" id="addFriendID" />
        </div>
          <!-- <input type="text" class="form-control" id="addFriend" placeholder="Add Friend" onkeyup="CheckFriendTable($(this).val());;" />
          <input type="hidden" id="addFriendID" />
          <button 
           class="ui button small" onclick="AddFriend();" id="addFriendbtn" disabled><i class="add user icon"></i></button> -->
        </div>
      </div>
    </div>
    <div class="row">
      <div class="five wide column">
      <h2 class="ui header">Friend List</h2>
      <?php
        $qry = "SELECT friend_id, user_name, user_email, count(Bet.user_id) AS 'NumBets' FROM `Friends` ";
        $qry = $qry . " INNER JOIN UserTBL ON UserTBL.user_id = Friends.friend_id ";
        $qry = $qry . " LEFT JOIN Bet ON Friends.friend_id = Bet.user_id";
        $qry = $qry . " WHERE Friends.user_id = $user_id";
        $qry = $qry . " GROUP BY friend_id, user_name, user_email";
        $qry = $qry . " ORDER BY count(Bet.user_id) DESC;";
        $q = mysqli_query($conn, $qry);
        $rowCount = $q->num_rows; ?>
        <?php if ($rowCount != 0) { ?>
        <div id="friendTbl">
          <table class="ui single line table">
            <thead>
              <tr>
                <th>Friend</th>
                <th>Number of Bets</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <? while($rs = mysqli_fetch_array($q)) { 
              ?>
              <tr>
                <td><?php print $rs["user_name"]; ?></td>
                <td><?php print $rs["NumBets"]; ?></td>
                <td><a href="#" onclick="GetFriendResults('<?php print $rs["friend_id"]; ?>'); return false;">View Bets&nbsp;<i class="book icon"></i></a></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
        <? } ?>
      </div>
      <div class="eleven wide column">
        <div id="friend_info"></div>
      </div>
    </div>
  </div>


<script>
	function CheckFriendTable(str){

	      if (str.length > 1) {
	          
	          $.getJSON(ajaxPage,
	             {_CheckFriendList: str},
	             function(result){
	              
	             	if (result.check == "No User") {
	             		$("#addFriendbtn").removeClass('greem');
                  $("#addFriendbtn").addClass("grey");
                }else{
                  var friend_id = result.check;
                  $("#addFriendbtn").removeClass('grey');
                  $("#addFriendbtn").addClass("green");
	             	};

	             });
	      }

	}

	function AddFriendToList(){

  

		//if (($("#addFriendbtn").hasClass(".green")) && ($("#addFriendID").val() != "")) {
			// var fid = $("#addFriendID").val();
			//  $.post(ajaxPage,
			//         {_AddFriend: fid, user_id: <?php echo $user_id ?>},
			//           function(result){
			//       $("#friendTbl").html(result);
			//   });
		//};

    console.log("test");

	}


	function GetFriendResults(friend_id){

		$.get(ajaxPage,
	             {_GetFriendResults: friend_id},
	             function(result){
	             	$("#friend_info").html(result);
	     });

	}

</script>

<?php
require('includes/footer.php');
?>