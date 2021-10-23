<?php 
$title = "Login";
function get_content(){
?>

<div class="container margin">
    <?php if(isset($_SESSION['message'])):?>
        <div class="card-panel white-text center-align <?php echo $_SESSION['class'];?>">
            <?php echo $_SESSION['message'];?>
        </div>
    <?php endif;?>
    <div class="col s12 m7">
        <div class="card horizontal z-depth-4">
            <div class="card-image" >
                <img src="/assets/img/library.jpg" width="40%">
            </div>
             <div class="card-stacked">
                <div class="card-content ">
                    <h5 class="center-align">Login</h5>
                    <form action="/web.php" method="POST" >
                        <input type="hidden" name="action" value="login">
                        <div class="input-field">
                            <input type="text" name="username" id="username">
                            <label for="username">Username</label>
                        </div>
                        <div class="input-field">
                            <input type="password" name="password" id="password">
                            <label for="password">Password</label>
                        </div>
                        <button class="btn">Login</button>
                    </form>
                </div>
                <div class="card-action">
                    <h6>Don't have an account yet? <a href="register.php">Register Now!</a></h6>

                </div>
            </div>
        </div>
    </div>
</div>

<?php 
}
require_once 'layout.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', ()=>{
        let card = document.querySelector('.card-panel')
        setTimeout(() => {
            <?php unset($_SESSION['message']);?>
            <?php unset($_SESSION['class']);?>
            card.classList.toggle('hide');
        }, 3000);
    })
</script>