<?php 
$title = "Readers' Info";
function get_content() {
    require_once '../controllers/connection.php';
    $query = "SELECT reader.*, category1.genre AS genre, category2.language AS language FROM reader 
                JOIN category1 ON reader.genre = category1.id 
                JOIN category2 ON reader.language = category2.id";
    $readers = mysqli_fetch_all(mysqli_query($cn, $query), MYSQLI_ASSOC);
?>

<div class="container margin">
    <h4>Readers' Info</h4>

    <form method="GET">
        <div class="input-field valign-wrapper">
            <label class="label-icon" for="search"><i class="material-icons">search</i></label>
            <input name="search" type="search" placeholder="search reader username" class="black-text center-align" >
            <i class="material-icons">close</i>
        </div>
    </form>

    <?php 
        if(isset($_GET['search'])) {
            $keyword = $_GET['search'];
            $search_query = "SELECT reader.*, category1.genre AS genre, category2.language AS language FROM reader 
                JOIN category1 ON reader.genre = category1.id 
                JOIN category2 ON reader.language = category2.id WHERE reader.username LIKE '%$keyword%'";
                $readers = mysqli_fetch_all(mysqli_query($cn, $search_query), MYSQLI_ASSOC);
        }
    ?>

    <?php if($readers == NULL):?>
        <h4>No such reader found</h4>
    
    <?php else:?>
    <table class="highlight centered" style="border:1px black solid; box-shadow:5px 6px grey">
        <thead class="lime lighten-3">
            <tr>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Genre</th>
                <th>Language</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($readers as $reader):?>
                <tr>
                    <td><?php echo $reader['username'];?></td>
                    <td><?php echo $reader['fullname'];?></td>
                    <td><?php echo $reader['email'];?></td>
                    <td><?php echo $reader['contactno'];?></td>
                    <td><?php echo $reader['genre'];?></td>
                    <td><?php echo $reader['language'];?></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php endif;?>
</div>

<?php 
}
require_once 'layout.php';
?>