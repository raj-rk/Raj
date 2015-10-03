<?php
$current = $this->database->getById($_GET['id']);
?>

Are you sure you wish to delete the entry "<?php echo $current->ref;?>"?<br><br>
<a class="actionbtn" href="<?php echo site_url();?>/wp-admin/admin.php?page=cwd-management&view=delete_proc&del=y&id=<?php echo $_GET['id'];?>&_del_rec=<?php echo wp_create_nonce( 'del_rec-' .  $_GET['id']);?>">Yes</a> <a class="actionbtn" href="<?php echo site_url();?>/wp-admin/admin.php?page=cwd-management">No</a>