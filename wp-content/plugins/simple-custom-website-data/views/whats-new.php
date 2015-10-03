<h3 class="page-title">What's new in version <?php echo CWD_VERSION ?></h3>

<?php
global $cwd;
try
{
    $changes = $cwd->readme
                    ->init()
                    ->getSection('Changelog')
                    ->getSubSection(CWD_VERSION)
                    ->md('content');
    $changes = str_replace('<ul>', '<ul class="ul-list">', $changes);
    echo $changes;
}
catch (Exception $e)
{
    echo "<em>Couldn't find any entries in the changelog for this version</em>";
}
?>

<br>
<br>
<hr>
<br>
<a class="actionbtn" href="<?php echo site_url();?>/wp-admin/admin.php?page=cwd-management">Dashboard</a>