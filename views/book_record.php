<?php 
$title = "Book Records";
function get_content() {
    require_once '../controllers/connection.php';
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $query = "SELECT DISTINCT record.*,reader.username, books.title,my_books.reader_id,my_books.book_id, my_books.status FROM record
    JOIN my_books ON my_books.id = record.mybooks_id
    JOIN reader ON reader.id = my_books.reader_id
    JOIN books ON books.id = my_books.book_id
    WHERE record.mybooks_id = my_books.id ORDER BY id DESC";
    $records = mysqli_fetch_all(mysqli_query($cn, $query), MYSQLI_ASSOC);
    $date = date('Y-m-d');
?>

<div class="container margin">
    <?php if(isset($_SESSION['message'])):?>
        <div class="card-panel white-text center-align <?php echo $_SESSION['class'];?>">
            <?php echo $_SESSION['message'];?>
        </div>
    <?php endif;?>
    <h4 class="center-align">Book Records</h4>
    <table class="highlight centered" style="border:1px black solid; box-shadow:5px 6px grey">
        <thead>
            <tr>
                <th>Username</th>
                <th>Book Title</th>
                <th>Book Status</th>
                <th>Issued Date</th>
                <th>Return Date</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($records as $record):?>
                <?php if($record['status'] != 4):?>
            <tr>
                <td><?php echo $record['username'];?></td>
                <td><?php echo $record['title'];?></td>
                    
                <?php if($record['status'] == 1):?>
                    <td>
                        <span class="pending">Pending</span>
                    </td>
                <?php elseif($record['status'] == 2 || $record['status'] == 3):?>
                    <td>
                        <span class="lent">Lent</span>
                    </td>
                <?php elseif(strtotime($record['return_date']) < strtotime($date)):?>
                    <td>
                        <span class="expired">Expired</span>
                    </td>

                <?php endif;?>
                
                <td><?php echo $record['lend_date'];?></td>
                <td><?php echo $record['return_date'];?></td>


                <?php if($record['status'] != 1): ?>
                    <td></td>

                <?php else:?>
                    <td>
                        <button class="btn  modal-trigger" data-target="modal1">Approve</button>
                            <div class="modal" id="modal1">
                                <div class="modal-content left-align">
                                    <h4>Date for lent</h4>
                                    <form action="../web.php" method="POST">
                                        <input type="hidden" name="action" value="approve">
                                        <input type="hidden" name="id" value="<?php echo $record['id']?>">
                                        <input type="hidden" name="reader_id" value="<?php echo $record['reader_id']?>">
                                        <input type="hidden" name="book_id" value="<?php echo $record['book_id'];?>">
                                        <label>Lent date:</label>
								        <input type="date" name="lend" min="<?php echo date('Y-m-d');?>">
								        <label>Return date:</label>
								        <input type="date" name="return" min="<?php echo date('Y-m-d');?>">
                                        <button class="btn waves-green waves-effect btn-flat">Confirm</button>
                                    </form>
                                </div>
                            </div>
                    </td>
                <?php endif;?>

            </tr>
            <?php endif;?>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

<?php 
}
require_once 'layout.php';
?>

<script>
    let modals = document.querySelectorAll('.modal');
    let modalInstances = M.Modal.init(modals, {});

    document.addEventListener('DOMContentLoaded', ()=>{
        let card = document.querySelector('.card-panel')
        setTimeout(() => {
            <?php unset($_SESSION['message']);?>
            <?php unset($_SESSION['class']);?>
            card.classList.toggle('hide');
        }, 3000);
    })
</script>