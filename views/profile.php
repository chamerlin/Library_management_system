<?php 
$title = "Profile";
function get_content(){
require_once '../controllers/connection.php';

$id = $_SESSION['user_data']['id'];
$query = "SELECT reader.*, category1.genre AS genre, category2.language AS language FROM reader 
JOIN category1 ON reader.genre = category1.id 
JOIN category2 ON reader.language = category2.id WHERE reader.id = $id";
$user = mysqli_fetch_assoc(mysqli_query($cn, $query));

$query2 = "SELECT * FROM category1";
$genres = mysqli_fetch_all(mysqli_query($cn, $query2), MYSQLI_ASSOC);

$query3 = "SELECT * FROM category2";
$languages = mysqli_fetch_all(mysqli_query($cn, $query3), MYSQLI_ASSOC);
?>

<div class="container margin">
    <?php if(isset($_SESSION['message'])):?>
        <div class="card-panel white-text center-align <?php echo $_SESSION['class'];?>">
            <?php echo $_SESSION['message'];?>
        </div>
    <?php endif;?>
    <div class="row ">
        <h4><?php echo $user['username'];?>'s Profile</h4>
            <div class="card horizontal blue lighten-5 col l10 m12 s12 offset-l1">
                <div class="card-content ">
                    <div class="col l8 m9 s10"  style="background-image: url(../assets/img/library.jpg); margin-bottom:20px; ">
                        <div class="col l12 m12 s12 offset-s3 offset-m5 offset-l7" style="background-image: url(../assets/img/library.jpg);">
                            <h4 class="white-text">Library Card</h4>
                        </div>
                    </div>
                    
                    <div class="col l8 m8 s12">
                        <h5>Username: <?php echo $user['username'];?></h5>
                        <h5>Full Name: <?php echo$user['fullname'];?></h5>
                        <h5>Contact Number: <?php echo $user['contactno'];?></h5>
                        <h5>Email: <?php  echo $user['email'];?></h5>
                        
                    </div>

                    <div class="col l4 m4 s12">
                        <h5 class="valign-wrapper"><i class="material-icons">favorite</i><?php echo $user['genre'];?></h5>
                        <h5 class="valign-wrapper"><i class="material-icons">favorite</i><?php echo $user['language'];?></h5>
                        <div class="card-action valign-wrapper">
                            <i class="material-icons medium">account_box</i>
                            <button data-target="modal1" class="btn blue darken-1 modal-trigger">Update</button>
                                
                            <div class="modal" id="modal1">
                                    <div class="modal-content">
                                        <form action="../web.php" method="POST">
                                        <input type="hidden" name="action" value="update_profile">
                                        <input type="hidden" name="id" value="<?php echo $user['id'];?>">
                                        

                                        <div class="input-field col l6 m12 s12">
                                            <label for="fullname">Fullname</label>
                                            <input type="text" name="fullname" id="fullname" value="<?php echo $user['fullname'];?>">
                                            <span class="err_msg"></span>
                                        </div>
                                        <div class="input-field col l6 m12 s12">
                                            <label for="username">Username</label>
                                            <input type="text" name="username" id="username" class="validate" value="<?php echo $user['username'];?>">
                                            <span class="err_msg"></span>
                                        </div>
                                        <div class="input-field col l12 m12 s12">
                                            <input type="email" name="email" id="email" class="validate" value="<?php echo $user['email'];?>">
                                            <label for="email">Email</label>
                                            <span class="helper-text" data-error="format wrong" data-success=""></span>
                                        </div>
                                        <div class="input-field col l12 m12 s12">
                                            <label for="contactno">Contact Number</label>
                                            <input type="tel" name="contactno" id="contactno" pattern="[0-9]{3}[-][0-9]{7}" placeholder="xxx-xxxxxxx" value="<?php echo $user['contactno'];?>" required/>
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
                                        <button class="btn">
                                            Update
                                            <i class="material-icons right">create</i>
                                        </button>
        
                                        </form>
                                    </div>
                            </div>
                        </div>
                    </div>
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

    document.addEventListener('DOMContentLoaded', function(){
        var elems = document.querySelectorAll('.modal');
        var instances = M.Modal.init(elems, {});
    });

    let elems = document.querySelectorAll('select');
    let instances = M.FormSelect.init(elems, {});

    document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.collapsible');
    var instances = M.Collapsible.init(elems, {});
    });  
    
    let fullname = document.getElementById('fullname');
    let username = document.getElementById('username');

    fullname.addEventListener('keyup', function(e) {
        if(fullname.value.length < 1){
            fullname.nextElementSibling.innerHTML = "Please input your name";
        }
        else{
            fullname.nextElementSibling.innerHTML = ""
        }
    });

    username.addEventListener('keyup', function(e) {
        if(username.value.length < 8){
            username.nextElementSibling.innerHTML = "Username should be at least 8 chars";
        }
        else{
            username.nextElementSibling.innerHTML = ""
        }
    });
</script>