<?php 
$title = "Register";
function get_content(){
    require_once '../controllers/connection.php';
    $query1 = "SELECT * FROM category1";
    $genres = mysqli_fetch_all(mysqli_query($cn, $query1), MYSQLI_ASSOC);
    $query2 = "SELECT * FROM category2";
    $languages = mysqli_fetch_all(mysqli_query($cn, $query2), MYSQLI_ASSOC);
?>

<div class="container margin">
    <?php if(isset($_SESSION['message'])):?>
        <div class="card-panel white-text center-align <?php echo $_SESSION['class'];?>">
            <?php echo $_SESSION['message'];?>
        </div>
    <?php endif;?>

    <h3 class="center-align">Register Now!</h3>
    <div class="row">
        <form method="POST" action="/web.php" class="col m6 offset-m3">
            <input type="hidden" name="action" value="register">
            <div class="input-field">
                <label for="fullname">Fullname</label>
                <input type="text" name="fullname" id="fullname">
                <span class="err_msg"></span>
            </div>
            <div class="input-field">
                <input type="email" name="email" id="email" class="validate">
                <label for="email">Email</label>
                <span class="helper-text" data-error="format wrong" data-success=""></span>
            </div>
            <div class="input-field">
                <label for="contactno">Contact Number</label>
                <input type="tel" name="contactno" id="contactno" pattern="[0-9]{3}[-][0-9]{7}" placeholder="xxx-xxxxxxx" required/>
            </div>
            <div class="input-field">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="validate">
                <span class="err_msg"></span>
            </div>
            <div class="input-field">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
                <span class="err_msg"></span>
            </div>
            <div class="input-field">
                <label for="password2">Confirm Password</label>
                <input type="password" name="password2" id="password2">
                <span class="err_msg"></span>
            </div>

            <div class="input-field col l6 m12">
                <select name="genre_id">
                    <option disabled>Genre</option>
                    <?php foreach($genres as $genre):?>
                        <option value="<?php echo $genre['id'];?>" class="left" style="color:black;">
                            <?php echo $genre['genre'];?>
                        </option>
                    <?php endforeach;?>

                    <label for="genre">Genre Preferred</label>
                </select>
            </div>
            <div class="input-field col l6 m12">
                <select name="language_id">
                    <option disabled>Language</option>
                    <?php foreach($languages as $language):?>
                        <option value="<?php echo $language['id'];?>" class="left" style="color:black;">
                            <?php echo $language['language'];?>
                        </option>
                    <?php endforeach;?>

                    <label for="genre">Language Preferred</label>
                </select>
            </div>
            <button class="btn cyan darken-4" style="margin-top:20px;">
                Register
                <i class="material-icons right">send</i>
            </button>
            </div>
            
            </div>

        </form>
    </div>
</div>

<?php 
}
require_once 'layout.php';
?>

<script>
let elems = document.querySelectorAll('select');
let instances = M.FormSelect.init(elems, {});

let fullname = document.getElementById('fullname');
let username = document.getElementById('username');
let password = document.getElementById('password');
let password2 = document.getElementById('password2');

    fullname.addEventListener('keyup', function(e) {
        if(fullname.value.length < 1){
            fullname.nextElementSibling.innerHTML = "Please input your name";
        }
        else{
            fullname.nextElementSibling.innerHTML = ""
        }
    })

    username.addEventListener('keyup', function(e) {
        if(username.value.length < 8){
            username.nextElementSibling.innerHTML = "Username should be at least 8 chars";
        }
        else{
            username.nextElementSibling.innerHTML = ""
        }
    })

    password.addEventListener('keyup', function(e) {
        if(password.value.length < 8){
            password.nextElementSibling.innerHTML = "Password should be at least 8 chars";
        }
        else{
            password.nextElementSibling.innerHTML = ""
        }
    })

    password2.addEventListener('keyup', function(e) {
        if(password2.value != password.value){
            password2.nextElementSibling.innerHTML = "Password is not the same";
        }
        else{
            password2.nextElementSibling.innerHTML = ""
        }
    })

    
    document.addEventListener('DOMContentLoaded', ()=>{
        let card = document.querySelector('.card-panel')
        setTimeout(() => {
            <?php unset($_SESSION['message']);?>
            <?php unset($_SESSION['class']);?>
            card.classList.toggle('hide');
        }, 3000);
    })
</script>