<?php
$data = $_POST;
$defaults = (object) array(
	'mod_on'                    => 'no',
	'mod_email'                 => __("You've successfully registered for %%blogname%% but before your account can be used you must activate it. To activate use the link below %%link%%. ",'simplr-registration-form'),
	'mod_email_subj'            => __("Please activate your %%blogname%% account",'simplr-registration-form'),
	'mod_activation'            => "auto",
	'mod_email_activated'       => __("Your account was activated. Your username is %%username%%.",'simplr-registration-form'),
	'mod_email_activated_subj'  => __("Your %%blogname%% account was activated.",'simplr-registration-form'),
	'mod_roles'                 => array('administrator'),
);
$simplr_reg = get_option('simplr_reg_options');

if (!is_object($simplr_reg)) $simplr_reg = new stdClass;
//setup defaults
foreach($defaults as $k => $v ) {
	$simplr_reg->$k = isset($simplr_reg->$k) ? $simplr_reg->$k : $defaults->$k;
}

if(isset($data['mod-submit'])) {
	if(!wp_verify_nonce(-1, $data['reg-mod']) && !current_user_can('manage_options')){ wp_die('Death to hackers!');}
	foreach($data as $k => $v) {
		$simplr_reg->$k = $v ? $v : $defaults->$k;
	}
	update_option('simplr_reg_options', $simplr_reg);
	echo '<div id="message" class="updated notice is-dismissible alert message"><p>'.__("Settings saved",'simplr-registration-form').'</p></div>';
}
?>
<form id="add-field" action="" method="post">
<h3><?php _e('Moderation','simplr-registration-form'); ?></h3>
<p><?php _e('These settings allow you to enable and control moderation','simplr-registration-form'); ?><p>
<?php SREG_Form::select(array(
	'name'		=> 'mod_on',
	'label'		=> __('Enable Moderation?','simplr-registration-form'),
	'required'	=> false
	),
	$simplr_reg->mod_on, 'wide chzn',
		array( 'yes'=>__('Yes', 'simplr-registration-form'), 'no'=>__('No', 'simplr-registration-form') )
	);
?>

<div class="mod-choices" <?php if($simplr_reg->mod_on == 'no' ) { echo 'style="display:none;"'; } ?>>
	<?php SREG_Form::select(array(
		'name'	=> 'mod_activation',
		'label'	=> __('Approval Mode','simplr-registration-form'),
		'required'	=> false,
		'default'	=> 'auto',
		'comment'	=> __("In *automatic* mode, the user is activated/approved as soon as the activation link in the moderation email is clicked. In *manual* mode the user is only approved after a site admin has approved that account.",'simplr-registration-form')), $simplr_reg->mod_activation, 'select chzn wide alignleft',
			array('auto'=> __('Automatic','simplr-registration-form'),'manually'=>__('Manual','simplr-registration-form'))
		);
	?>

	<?php SREG_Form::text(array(
		'name' => 'mod_email_subj',
		'label'=> __('Email Subject Line','simplr-registration-form'),
		'required'=>false,
		'default'	=> __("Welcome to %%blogname%%",'simplr-registration-form'),
		), $simplr_reg->mod_email_subj, 'text input');
	?>
	<?php SREG_Form::textarea(array(
		'name'	=> 'mod_email',
		'label'	=> __('Moderation Email','simplr-registration-form'),
		'required'	=> false,
		'comment'	=> __("You can use user submitted values in this field by wrapping them in %%. For instance to use the value of the field 'first_name' you would type 'Welcome, %%first_name%% '. Use %%link%% for the activation link. ",'simplr-registration-form'),
		'default'	=> __("hello",'simplr-registration-form'),
		),
		$simplr_reg->mod_email, 'textarea wide',
		array( '500px', '150px')
	); ?>
	<?php $roles = new WP_Roles(); ?>
	<?php SREG_Form::select(array(
		'name'	=> 'mod_roles[]',
		'label'	=> __('Roles','simplr-registration-form'),
		'multiple'	=> true,
		'comment'	=> __("Which user roles can moderate new users.",'simplr-registration-form'),
		'required'	=> true), $simplr_reg->mod_roles, 'wide chzn alignleft', $roles->get_names()
		);
	?>
	<?php SREG_Form::text(array(
		'name' => 'mod_email_activated_subj',
		'label'=> __('Account Activated Email Subject Line','simplr-registration-form'),
		'required'=>false,
		), $simplr_reg->mod_email_activated_subj, 'text input');
	?>
	<?php SREG_Form::textarea(array(
		'name'  => 'mod_email_activated',
		'label' => __('Account Activated Email','simplr-registration-form'),
		'required' => false,
		'comment'  => __("This email is sent to alert the user their account was activated.", 'simplr-registration-form') ,
		),
		$simplr_reg->mod_email_activated, 'textarea wide',
		array( '500px', '150px')
		); ?>
</div>

<p class="submit">
	<?php wp_nonce_field('reg-mod',-1); ?>
	<input type="submit" name="mod-submit" class="button button-primary" value="<?php _e('Submit','simplr-registration-form'); ?>" />
</p>

<script>
jQuery.noConflict();
jQuery(document).ready(function($) {
	$('select[name="mod_on"]').change(function() {
		var val =$(this).find('option:selected').val();
		if(val == 'yes') { $('.mod-choices').slideDown(); } else { $('.mod-choices').slideUp(); }
	});
});
</script>
</form>
