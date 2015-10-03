<?php
$data = $_POST;
$simplr_reg = get_option('simplr_reg_options');
if(isset($data['main-submit'])) {
	if(!wp_verify_nonce(-1, $data['reg-api']) && !current_user_can('manage_options')){ wp_die('Death to hackers!');}
		$simplr_reg->recap_public = $data['recap_public'];
		$simplr_reg->recap_private = $data['recap_private'];
		$simplr_reg->recap_on = $data['recap_on'];
		update_option('simplr_reg_options',$simplr_reg);
} elseif(isset($data['fb-submit'])) {
	if(!wp_verify_nonce(-1, @$data['reg-fb']) && !current_user_can('manage_options')){ wp_die('Death to hackers!');}
		$simplr_reg->fb_connect_on = $data['fb_connect_on'];
		$simplr_reg->fb_app_id = @$data['fb_app_id'];
		$simplr_reg->fb_app_key = @$data['fb_app_key'];
		$simplr_reg->fb_app_secret = @$data['fb_app_secret'];
		$simplr_reg->fb_login_allow = @$data['fb_login_allow'];
		$simplr_reg->fb_login_redirect = @$data['fb_login_redirect'];
		$simplr_reg->fb_request_perms = @$data['fb_request_perms'];
		$simplr_reg->fb_auto_register = @$data['fb_auto_register'];
		update_option('simplr_reg_options',$simplr_reg);
}
// Fix Notices and set some defaults.
if (!is_object($simplr_reg)) $simplr_reg = new stdClass;
if ( !isset($simplr_reg->recap_public) )     { $simplr_reg->recap_public = ''; }
if ( !isset($simplr_reg->recap_private) )    { $simplr_reg->recap_private = ''; }
if ( !isset($simplr_reg->recap_on) )         { $simplr_reg->recap_on = 'no'; }
if ( !isset($simplr_reg->fb_connect_on) )    { $simplr_reg->fb_connect_on = 'no'; }
if ( !isset($simplr_reg->fb_app_id) )        { $simplr_reg->fb_app_id = ''; }
if ( !isset($simplr_reg->fb_app_secret) )    { $simplr_reg->fb_app_secret = ''; }
if ( !isset($simplr_reg->fb_request_perms) ) { $simplr_reg->fb_request_perms = ''; }
if ( !isset($simplr_reg->fb_login_allow) )   { $simplr_reg->fb_login_allow = 'no'; }
if ( !isset($simplr_reg->fb_auto_register) ) { $simplr_reg->fb_auto_register = 0; }
if ( !isset($simplr_reg->fb_login_redirect) ){ $simplr_reg->fb_login_redirect = ''; }
?>
<form action="?page=simplr_reg_set&regview=api" method="post" id="add-field">
	<h3><?php _e('reCAPTCHA','simplr-registration-form'); ?></h3>
	<script>
	jQuery.noConflict();
	jQuery(document).ready(function() {
		jQuery('select[name="recap_on"]').change(function() {
			val = jQuery(this).find('option:selected').attr('value');
			if( val == 'yes' ) {
			jQuery('#recap-hidden').show();
			} else {
			jQuery('#recap-hidden').hide();
			}
		});

		jQuery('select[name="fb_connect_on"]').change(function() {
			val = jQuery(this).find('option:selected').attr('value');
			if( val == 'yes' ) {
			jQuery('#fb-hidden').show();
			} else {
			jQuery('#fb-hidden').hide();
			}
		});

	});
	</script>
	<p><?php _e('If you would like to use reCAPTCHA for blocking spam registrations, enter you API keys below:','simplr-registration-form'); ?></p>
	<?php
	SREG_Form::select(array(
	'name'=>'recap_on',
	'label'=>__('Enable reCAPTCHA?','simplr-registration-form'),
	'comment'=>__('In order to use reCAPTCHA anti-spam protection you first need to set you an API account here: http://www.google.com/recaptcha','simplr-registration-form'),
	'required'=>false
	),
	$simplr_reg->recap_on, 'wide chzn',
	array('yes'=>__('Yes', 'simplr-registration-form'), 'no'=>__('No', 'simplr-registration-form'))
	);
	?>

	<div id="recap-hidden" <?php if($simplr_reg->recap_on != 'yes') { ?>style="display:none;"<?php } ?>>
		<?php
		SREG_Form::text(array(
			'name'=>'recap_public',
			'label'=>__('reCAPTCHA Public Key','simplr-registration-form'),
			'required'=>false
		),
		$simplr_reg->recap_public, 'wide'
			);
			SREG_Form::text(array(
				'name'=>'recap_private',
				'label'=>__('reCAPTCHA Private Key','simplr-registration-form'),
				'required'=>false
			),
			$simplr_reg->recap_private, 'wide '
		);
		?>
	</div>
	<?php echo wp_nonce_field(-1,"reg-api"); ?>
	<p class="submit">
		<input type="submit" class="button-primary" name="recaptcha-submit" value="<?php _e('Save Changes','simplr-registration-form') ?>" />
	</p>
</form>

<form action="?page=simplr_reg_set&regview=api" method="post" id="add-field">
	<h3><?php _e('Facebook','simplr-registration-form'); ?></h3>
	<p><?php _e('If you intend to use Facebook for Authentication complete form below:','simplr-registration-form'); ?></p>
	<?php
	SREG_Form::select(array(
		'name'=>'fb_connect_on',
		'label'=>__('Enable Facebook Connect?','simplr-registration-form'),
		'required'=>false,
		'comment'=>__('In order to user Facebook Connect you will need to have set up an application at http://www.facebook.com/developer','simplr-registration-form')
	),
	$simplr_reg->fb_connect_on, 'wide chzn',
		array('yes'=>__('Yes', 'simplr-registration-form'), 'no'=>__('No', 'simplr-registration-form'))
	);
	?>

	<div id="fb-hidden" <?php if($simplr_reg->fb_connect_on != 'yes') { ?>style="display:none;"<?php } ?>>
	<?php
	SREG_Form::text(array(
		'name'=>'fb_app_id',
		'label'=>__('Facebook App ID','simplr-registration-form'),
		'required'=>false
		),
		$simplr_reg->fb_app_id, 'wide'
	);

	/*SREG_Form::text(array(
	'name'=>'fb_app_key',
	'label'=>'Facebook Application Key',
	'required'=>false
	),
	$simplr_reg->fb_app_key, 'wide'
	);*/

	SREG_Form::text(array(
		'name'=>'fb_app_secret',
		'label'=>__('Facebook Application Secret','simplr-registration-form'),
		'required'=>false
		),
		$simplr_reg->fb_app_secret, 'wide'
	);

	SREG_Form::checkbox_group(array(
		'name' => 'fb_request_perms',
		'label'=>__('Permissions to Request','simplr-registration-form'),
		'required'=>true,
		'helper'=>'perms'),
		$simplr_reg->fb_request_perms,'checkgroup',
		''
	);

	SREG_Form::select(array(
		'name'=>'fb_login_allow',
		'label'=>__('Allow users to login using their Facebook account?','simplr-registration-form'),
		'required'=>false,
		'default'=>'no',
		'comment'=>''
		),
		$simplr_reg->fb_login_allow,
		'wide',
		array('yes'=>'Yes','no'=>'No')
	);

	SREG_Form::text(array(
		'name'=>'fb_login_redirect',
		'label'=>__('Facebook Login Redirect','simplr-registration-form'),
		'required'=>false,
		'comment' => __('Where should the user be redirected after logging in with Facebook.','simplr-registration-form')
		),
		esc_attr($simplr_reg->fb_login_redirect), 'wide'
	);

	SREG_Form::checkbox(array(
		'name'=>'fb_auto_register',
		'label'=>__("Auto-Register",'simplr-registration-form'),
		'required'=>false,
		'comment'=>__("Enabling this option will automatically register and login the user after agreeing to connect your application to his/her profile.",'simplr-registration-form')
	),$simplr_reg->fb_auto_register,'checkbox');
	?>
	</div>
	<?php echo wp_nonce_field(-1,"fb-api"); ?>
	<p class="submit">
		<input type="submit" class="button-primary" name="fb-submit" value="<?php _e('Save Changes','simplr-registration-form') ?>" />
	</p>
</form>
