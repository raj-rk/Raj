<?php if(!isset($_GET['action']) || $_GET['action'] == 'delete') { ?>
	<div id="simplr-sub-nav">
		<div class="add-field-button">
			<a href="?page=simplr_reg_set&regview=fields&action=add" class="button"><?php _e('Add Field','simplr-registration-form'); ?></a>
		</div>
	</div>
	<?php $table = new SREG_Fields_Table(); ?>
	<form id="mass-edit" action="?page=simplr_reg_set&regview=fields" method="post">
		<?php
		echo $table->header();
		echo $table->rows();
		echo $table->footer();
		?>
		<p>
			<div class="ajaxloading" ><?php _e('Saving sort','simplr-registration-form'); ?> <img class="waiting" src="<?php echo admin_url('/images/wpspin_light.gif',__FILE__); ?>" alt=""></div>
			<?php wp_nonce_field(-1,'_mass_edit'); ?>
			<input type="submit" class="button" name="mass-submit" value="<?php _e('Delete Selected','simplr-registration-form'); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete all the selected fields?','simplr-registration-form'); ?>')">
		</p>
	</form>
	<script type="text/javascript">
	jQuery(document).ready(function() {

		function update_field_sort(event,ui) {
			var sort = {};
			jQuery('table#fields-table tbody#the-list tr').each(function(i) {
				sort[i] = jQuery(this).find('.key').text();
			});
			jQuery.post(ajaxurl,{action:'simplr-save-sort',sort:sort},function(response){
				//console.log('response: ' + response);
			}
			);
			//console.log(sort);
			jQuery('.ajaxloading').toggle();
		}

		jQuery('table#fields-table tbody#the-list').sortable({stop:function() {
			jQuery('.ajaxloading').toggle();
			update_field_sort(); }
		});

	});
	</script>
	<?php
} else {
	?>
	<div id="simplr-sub-nav">
		<div class="add-field-button">
			<a href="?page=simplr_reg_set&regview=fields" class="button"><?php _e('Back to Field List','simplr-registration-form'); ?></a>
		</div>
	</div>
	<p><?php _e('Use the form below to add a registration field. These fields can then be selected on any registration form on the site.','simplr-registration-form'); ?></p>
	<?php
}

if(@$_GET['action'] == 'edit' OR @$_GET['action'] == 'add') {
	if($_GET['action'] == 'edit') {
		$field = new SREG_Fields();
		$field = (object) $field->custom_fields->{$_GET['key']};
	} else if($_GET['action'] == 'add') {
		$field = new SREG_Fields();
	}
	?>
	<script>
	jQuery.noConflict();
	jQuery(document).ready(function() {
		jQuery('#choices').find('.form-comment').each(function() {
			jQuery(this).hide();
			jQuery('input[name="options_array"]').after('<div class="info"><a id="show-info"><?php _e('What are my options?','simplr-registration-form'); ?></a></div>');
		});

		jQuery('#show-info').live('click',function(e) {
			e.preventDefault();
			jQuery('#choices').find('.form-comment').toggle();
		});
	});
	</script>
	<div class="inner">
		<form action="<?php echo esc_url(add_query_arg(array('action'=>'add'))); ?>" method="post" id="add-field">
			<?php SREG_Form::text(array('name'=>'label','label'=>__('Field Label','simplr-registration-form'),'required'=>true,'comment'=>__('Human readable name for display to users','simplr-registration-form')),esc_attr(@$field->label),'wide'); ?>
			<?php SREG_Form::text(array('name'=>'key','label'=>__('Field Key','simplr-registration-form'),'required'=>true,'comment'=>__('Machine readable name to represent this field in the Database','simplr-registration-form')),esc_attr(@$field->key),'wide'); ?>

			<?php SREG_Form::radio( array('name'=>'custom_column','label'=>__('Show this field on user admin screens?','simplr-registration-form'), 'default'=>'no') , @esc_attr($field->custom_column), 'wide', array('yes'=>__('Yes', 'simplr-registration-form'), 'no'=>__('No', 'simplr-registration-form')) ); ?>

			<?php SREG_Form::radio(array('name'=>'required','label'=>__('Is this field required?','simplr-registration-form'),'default'=>'yes'),esc_attr(@$field->required),'wide',array('yes'=>__('Yes', 'simplr-registration-form'),'no'=>__('No', 'simplr-registration-form'))); ?>
			<?php SREG_Form::radio(array('name'=>'show_in_profile','label'=>__('Show this field in user profile?','simplr-registration-form'),'default'=>'yes'),esc_attr(@$field->show_in_profile),'wide',array('yes'=>__('Yes', 'simplr-registration-form'), 'no'=>__('No', 'simplr-registration-form'))); ?>
			<?php SREG_Form::select(array('name'=>'type','label'=>__('Type','simplr-registration-form'),'default'=>'text'),esc_attr($field->type?$field->type:'text'),'wide',array('text'=>__('Text Field','simplr-registration-form'),'textarea'=>__('Textarea','simplr-registration-form'),'select'=>__('Multiple Choice: Select Box','simplr-registration-form'), 'radio'=>__('Multiple Choice: Radio Buttons','simplr-registration-form'),'date'=>__('Date Field','simplr-registration-form'),'checkbox'=>__('Checkbox','simplr-registration-form'),'hidden'=>__('Hidden Field','simplr-registration-form'),'callback'=>__('Callback Function','simplr-registration-form')),'type-select'); ?>

			<div id="choices">
				<?php $comment = __('<strong>Checkbox: </strong> Option not used.','simplr-registration-form') . '<br />' .
					__('<strong>Text Field:</strong> Option is not used.','simplr-registration-form') . '<br />' .
					__('<strong>Multiple Choice:</strong> Separate multiple options with a comma (i.e. yes,no).','simplr-registration-form') . '<br />' .
					__('<strong>Date:</strong> Option is used to determine range of available dates. Enter two years separated by commas. i.e. 2000,2015.','simplr-registration-form') . '<br />' .
					__('<strong>Textarea:</strong> Option is used to determine height and width of text box. Enter dimensions width them height, separated by a comma (i.e. <em>300px,100px</em> would generate a box that is 300 pixels wide and 100 pixels tall).','simplr-registration-form') . '<br />' .
					__('<strong>Hidden Field:</strong> Option determines the value that will be passed to the hidden field.','simplr-registration-form'); ?>
				<?php $values = (isset($field->options_array)) ? implode(',',@$field->options_array): null; ?>
				<?php SREG_Form::text(array('name'=>'options_array','label'=>__('Options','simplr-registration-form'),'comment'=>$comment), $values,''); ?>
				<br class="clear" />
			</div>


			<?php echo wp_nonce_field(-1,"reg-field"); ?>
			<p>
				<?php $submit_value = ($_GET['action'] == 'edit') ? __('Save Changes','simplr-registration-form') : __('Add Field','simplr-registration-form'); ?>
				<input type="submit" name="submit-field" value="<?php echo $submit_value; ?>" class="button-primary"/>
			</p>
		</form>
	</div>
<?php } ?>
