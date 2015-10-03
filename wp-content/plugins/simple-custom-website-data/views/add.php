<h3 class="title">Add New Record</h3>
<form name="input" action="<?php echo CWD_URL?>&view=proc_add" method="post">
    <input type="text" name="ref" placeholder="Reference" required><br>
    <textarea type="text" name="data" placeholder="Data" required></textarea><br>
    <?php wp_nonce_field('cwd_add_action', 'cwd_add_name') ?>
    <input type="hidden" name="cwdaction" value="add">
    <a class="button button-alert" href="<?php echo CWD_URL?>">Cancel</a>
    <input class="button button-primary right" type="submit" value="Add Record">
</form>
