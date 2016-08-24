
  <script>
  $(document)
    .ready(function() {
      // fix menu when passed
      $('.masthead')
        .visibility({
          once: false,
          onBottomPassed: function() {
            $('.fixed.menu').transition('fade in');
          },
          onBottomPassedReverse: function() {
            $('.fixed.menu').transition('fade out');
          }
        });

      // create sidebar and attach to menu open
      $('.ui.sidebar').sidebar('attach events', '.toc.item');

      $('.userMenu').popup({
          position: 'bottom center',
          delay: {
            show: 100,
            hide: 2000
          }
        });

    });
  </script>


<!-- Following Menu -->
<div class="ui large top fixed menu transition hidden">
  <div class="ui container">
    <a class="item <?php if ($page == 'index'){ echo "active"; } ?>" href="index">Home</a>
    <a class="item <?php if ($page == 'my_bets'){ echo "active"; } ?>" href="my_bets">My Bets</a>
    <a class="item <?php if ($page == 'game_list'){ echo "active"; } ?>" href="game_list">Game List</a>
    <a class="item <?php if ($page == 'stats'){ echo "active"; } ?>" href="stats">Stats</a>
    <div class="right menu">
      <div class="item userMenu" data-html='
<div class="ui list">
  <div class="item">
    <i class="settings icon"></i>
    <div class="content">
      <a href="my_account">My Account</a>
    </div>
  </div>
  <div class="item">
    <i class="sign out icon"></i>
    <div class="content">
      <a href="bet_action.php?logout=true">Logout</a>
    </div>
  </div>
</div>'>
        <p><?php print $_SESSION['user_name']; ?></p>
      </div>
      <div class="item">
        <a class="ui primary button" onclick="$('.ui.long.modal').modal('show');">Add Bet</a>
      </div>
    </div>
  </div>
</div>

<!-- Sidebar Menu -->
<div class="ui vertical inverted sidebar menu left">
  <a class="item <?php if ($page == 'index'){ echo "active"; } ?>" href="index">Home</a>
  <a class="item <?php if ($page == 'my_bets'){ echo "active"; } ?>" href="my_bets">My Bets</a>
  <a class="item <?php if ($page == 'game_list'){ echo "active"; } ?>" href="game_list">Game List</a>
  <a class="item <?php if ($page == 'stats'){ echo "active"; } ?>" href="stats">Stats</a>
  <a class="item" ><?php print $_SESSION['user_name']; ?></a>
</div>




<!-- Page Contents -->
<div class="pusher">
  <div class="ui inverted vertical masthead center aligned segment">

    <div class="ui container">
      <div class="ui large secondary inverted pointing menu">
        <a class="toc item">
          <i class="sidebar icon"></i>
        </a>
        <a class="item <?php if ($page == 'index'){ echo "active"; } ?>" href="index">Home</a>
        <a class="item <?php if ($page == 'my_bets'){ echo "active"; } ?>" href="my_bets">My Bets</a>
        <a class="item <?php if ($page == 'game_list'){ echo "active"; } ?>" href="game_list">Game List</a>
        <a class="item <?php if ($page == 'stats'){ echo "active"; } ?>" href="stats">Stats</a>
        <div class="right item">
          <h4 class="ui inverted header userMenu" data-html='
<div class="ui list">
  <div class="item">
    <i class="settings icon"></i>
    <div class="content">
      <a href="my_account">My Account</a>
    </div>
  </div>
  <div class="item">
    <i class="sign out icon"></i>
    <div class="content">
      <a href="bet_action.php?logout=true">Logout</a>
    </div>
  </div>
</div>'><i class="inverted user icon" ></i><?php print $_SESSION['user_name']; ?></h4>
        </div>
      </div>
    </div>
