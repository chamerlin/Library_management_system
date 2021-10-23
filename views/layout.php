<?php session_start();?>
<!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <title>Library | <?php echo $title;?></title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
      
    </head>

    <body>

        <?php require_once 'template/nav.php';?>
        
        <main>
            <?php get_content();?>
        </main>

        <?php require_once 'template/footer.php'; ?>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    </body>
  </html>