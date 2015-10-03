<form action="<?php echo site_url();?>/wp-admin/admin.php?page=cwd-management&view=import_proc&_import_rec=<?php echo wp_create_nonce( 'import_secure');?>" method="post" enctype="multipart/form-data">
    <label for="file">Select CSV file:</label><br>
    <input type="file" name="file" id="file" /><br>
    <input type="submit" class="button button-primary" name="submit" value="Submit" />
</form>