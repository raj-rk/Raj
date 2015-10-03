<?php
$current = $this->database->getById($_GET['id']);

$data = stripslashes($current->data);
if ($this->utility->isJson($data) && !$this->utility->isMulti($data, true))
{
    $processed = '';
    $data_arr = json_decode($data);
    $loop = 1;
    $arr_count = count($data_arr);
    foreach ($data_arr as $key => $value)
    {
        $processed .= $key . '=' . $value;
        if(end($data_arr) != $value) $processed .= PHP_EOL;
        $loop++;
    }
    $data = $processed;
}
elseif($this->utility->isJson($data) && $this->utility->isMulti($data, true))
{
    $data = json_encode(json_decode($data), JSON_PRETTY_PRINT);
}

?>
<h3>Editing record "<?php echo $current->ref ?>"</h3>
<form name="input" action="<?php echo site_url();?>/wp-admin/admin.php?page=cwd-management&view=edit_proc" method="post">
<textarea type="text" name="data" placeholder="Data" required><?php echo $data ?></textarea><br>
<input type="hidden" name="cwdaction" value="edit">
<input type="hidden" name="edit" value="y">
<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
<input type="hidden" name="_edit_rec" value="<?php echo wp_create_nonce( 'edit_rec-' .  $_GET['id']);?>">
<a class="button button-alert" href="<?php echo CWD_URL?>">Cancel</a>
<input class="button button-primary right" type="submit" value="Edit Record">
</form>