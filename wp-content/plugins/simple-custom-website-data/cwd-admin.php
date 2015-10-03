<?php global $cwd; ?>
<?php require 'views/header.php';?>
<?php
$cwd->showView($_GET['view']);
?>
<?php require 'views/footer.php';?>
