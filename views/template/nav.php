<nav class="brown" >
    <div class="nav-wrapper valign-wrapper">
        <a href="/" class="brand-logo center hide-on-med-and-down"><img src="../../assets/img/logo.png" width="100" class="valign-wrapper" ></a>
        <a href="#" data-target="slide-out" class="sidenav-trigger" >
            <i class="material-icons">menu</i>
        </a>
        <ul class="left hide-on-med-and-down container" id="nav-mobile1">
            <?php if(!isset($_SESSION['user_data'])): ?>
            <li><a href="/">Home</a></li>
            <li><a href="/views/register.php">Register</a></li>
            <li><a href="/views/login.php">Login</a></li>
            <?php endif;?>

            <?php if(isset($_SESSION['user_data'])):?>
            <li> 
                <div class="valign-wrapper">
                    <i class="material-icons">account_circle</i>
                    <?php if(isset($_SESSION['user_data']) && $_SESSION['user_data']['isAdmin']):?>
                        <a href="/views/profile.php"><?php echo $_SESSION['user_data']['username'] . " (Admin)";?></a>
                    <?php else: ?>
                        <a href="/views/profile.php"><?php echo $_SESSION['user_data']['username']?></a>
                    <?php endif;?>
                </div>
            </li>
            <li><a href="/">Home</a></li>
            <?php if(!$_SESSION['user_data']['isAdmin']):?>
                <li><a href="/views/myBooks.php">MyBooks</a></li>
                <li><a href="/views/history.php">History</a></li>
            <?php endif;?>
            <li><a href="/web.php/logout">Logout</a></li>
            <?php endif;?>
        </ul>

        <form class="right" id="form1" method="POST">
          <div class="input-field" style="max-width: 350pt;">
            <input name="search" type="search" placeholder="search for keywords" class="black-text" >
            <label class="label-icon" for="search"><i class="material-icons">search</i></label>
            <i class="material-icons">close</i>
            <div id="searchResults" ></div>
          </div>
        </form>
        <form>
            <!-- <div class="row">
                <div class="switch text-white"><label class="white-text">Light
                <input type="checkbox" onclick="myFunction()"><span class="lever"></span>Dark</label></div>
            </div> -->
        </form>
    </div>
</nav>

<ul class="sidenav" id="slide-out">

    <li>
        <div class="user-view">
            <div class="background">
                <img src="/assets/img/library.jpg">
            </div>
            <?php if(isset($_SESSION['user_data']) && $_SESSION['user_data']['isAdmin']):?>
            <a href="/views/profile.php">
                <span class="white-text"><?php echo $_SESSION['user_data']['username'] . " (Admin)";?></span>
            </a><br>
            <a href="/views/profile.php">
                <span class="white-text"><?php echo $_SESSION['user_data']['email'];?></span>
            </a>

            <?php elseif(isset($_SESSION['user_data'])):?>
            <a href="/views/profile.php">
                <span class="white-text"><?php echo $_SESSION['user_data']['username'];?></span>
            </a><br>
            <a href="/views/profile.php">
                <span class="white-text"><?php echo $_SESSION['user_data']['email'];?></span>
            </a>


            <?php else:?>
            <a href="/views/login.php">
                <span class="white-text">Please Login</span>
            </a>
            <?php endif;?>
        </div>
    </li>

    <?php if(!isset($_SESSION['user_data'])):?>
        <li><a href="/">Home</a></li>
        <li><a href="/views/register.php">Register</a></li>
        <li><a href="/views/login.php">Login</a></li>
    <?php endif;?>

    <?php if(isset($_SESSION['user_data'])):?>
        <li><a href="/">Home</a></li>
        <?php if(!$_SESSION['user_data']['isAdmin']):?>
        <li><a href="/views/myBooks.php">MyBooks</a></li>
        <li><a href="/views/history.php">History</a></li>
        <?php endif;?>
        <li><a href="/web.php/logout">Logout</a></li>
    <?php endif;?>

</ul>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems, {});
});

// function myFunction() {
//    var element = document.body;
//    element.classList.toggle("dark-mode");
// }
</script>

