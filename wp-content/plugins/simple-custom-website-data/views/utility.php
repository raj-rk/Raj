<h3 class="page-title">Utility</h3>
<ul>
    <li>
        <strong>Backup</strong> all records by exporting them -
        <a href="<?php echo site_url();?>/wp-admin/admin.php?page=cwd-management&export=json">    Export JSON
        </a> |
        <a href="<?php echo site_url();?>/wp-admin/admin.php?page=cwd-management&export=true">
            Export CSV <em>(Deprecated)</em>
        </a>
    </li>
    <li>
        <strong>Import</strong>
        <form action="<?php echo site_url();?>/wp-admin/admin.php?page=cwd-management&import=true&_import_rec=<?php echo wp_create_nonce( 'import_secure');?>" method="post" enctype="multipart/form-data">
            <label for="file">Select import file (.json/.csv):</label><br>
            <input type="file" name="file" id="file" /><br>
            <input type="submit" class="button button-primary" name="submit" value="Import" />
        </form>
    </li>
</ul>
<br>
<br>
<hr>
<br>
<a href="<?php echo site_url();?>/wp-admin/admin.php?page=cwd-management">Dashboard</a>