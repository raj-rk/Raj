<?php $profile_fields = get_option('simplr_profile_fields'); ?>
<div class="wrap">

	<div  class="icon32">
	<img src="<?php echo SIMPLR_URL; ?>/assets/images/sign-up-32.png" ><br></div>

	<?php
	if ( isset($_GET['regview'] ) ) {
		$regview = $_GET['regview'];
	} else {
		$regview = 'main';
	} ?>

	<h2 class="nav-tab-wrapper">
		<a href="?page=simplr_reg_set&regview=main" class="nav-tab <?php if($regview == 'main') { echo 'nav-tab-active'; } ?>" ><?php _e('Settings','simplr-registration-form'); ?></a>
		<a href="?page=simplr_reg_set&regview=fields&orderby=name&order=desc" class="nav-tab <?php if($regview == 'fields') { echo 'nav-tab-active'; } ?>" ><?php _e('Fields','simplr-registration-form'); ?></a>
		<a href="?page=simplr_reg_set&regview=moderation" class="nav-tab <?php if($regview == 'moderation') { echo 'nav-tab-active'; } ?>" ><?php _e('Moderation','simplr-registration-form'); ?></a>
		<a href="?page=simplr_reg_set&regview=api" class="nav-tab <?php if($regview == 'api') { echo 'nav-tab-active'; } ?>" ><?php _e('API', 'simplr-registration-form'); ?></a>
		<a href="?page=simplr_reg_set&regview=instructions" class="nav-tab <?php if($regview == 'instructions') { echo 'nav-tab-active'; } ?>" ><?php _e('Instructions','simplr-registration-form'); ?></a>
	</h2>

	<?php
	//options page logic
	if(get_option('users_can_register') == 1) {
		$slug = (isset($_GET['regview'])) ? $_GET['regview'] : 'main';
		$regview = SIMPLR_DIR . '/views/'.$slug .'.php';
		include($regview);
	} else {
		?>
		<div id="message" class="error errors">
			<p style="color:#333;"><?php _e("Your site is not configured to allow user registrations. To turn on user registration change the membership setting on the <strong>Settings >> General</strong> menu.", 'simplr-registration-form'); ?></p>
		</div>
		<?php
	}
	?>

</div>
