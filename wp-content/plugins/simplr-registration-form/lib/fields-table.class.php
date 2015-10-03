<?php
class SREG_Fields_Table extends SREG_Fields {
	public $cols;

	function __construct() {
		parent::__construct();
		$this->cols = $this->cols();
	}

	function cols() {
		$cols = array(
			array('name'=>'label','label'=>__('Field Name','simplr-registration-form')),
			array('name'=>'key','label'=>__('Field Key','simplr-registration-form')),
			array('name'=>'required','label'=>__('Required','simplr-registration-form')),
			array('name'=>'type','label'=>__('Field Type','simplr-registration-form')),
			array('name'=>'show_in_profile','label'=>__('Profile?','simplr-registration-form'))
		);
		return $cols;
	}

	function header() {
		?>
		<script>
		jQuery.noConflict();
		jQuery(document).ready(function() {
			jQuery('#delete-all').change(function(){
				check = jQuery(this).attr('checked');
				if(check == false) {
					jQuery('table.wp-list-table').find('#delete-field').attr('checked',false);
				} else {
					jQuery('table.wp-list-table').find('#delete-field').attr('checked',true);
				}

			});
		});
		</script>
		<table id="fields-table" class="wp-list-table widefat" cellspacing="0">
			<thead>
				<tr>
					<th scope='col' id='cb' class='manage-column column-cb check-column'>
						<input type="checkbox" id="delete-all" />
					</th>
					<?php
					$sort = 'desc';
					if(isset($_GET['order'])):
						$sort = ($_GET['order'] == 'asc') ? 'desc' : 'asc';
					endif;
					foreach($this->cols as $col): $col = (object) $col; ?>
						<th scope='col' id='<?php echo $col->name; ?>' class='manage-column column-title sortable <?php echo $sort; ?>'>
							<?php echo $col->label; ?>
						</th>
					<?php endforeach; ?>
					<th scope="col" id="action" class="action-column" style=""><?php _e('Action','simplr-registration-form'); ?></th>
				</tr>
			</thead>
		<?php
	}

	function footer() {
		?>
		</table>
		<?php
	}

	function rows($type = null) { ?>
		<?php echo '<tbody id="the-list">'; ?>
		<?php if($this->get_custom()): ?>
			<?php foreach($this->get_custom() as $field): ?>
				<tr scope="row" class="format-default iedit <?php echo $field['key']; ?>">
					<td>
						<input type="checkbox" name="field_to_delete[]" value="<?php echo $field['key']; ?>" id="delete-field" />
					</td>
					<?php foreach($this->cols as $col): $col = (object) $col; ?>
						<td class="<?php echo $col->name; ?>"><?php echo @$field[$col->name]; ?></td>
					<?php endforeach; ?>
					<td>
					<?php $dnonce = wp_create_nonce('delete'); ?>
						<a href="?page=simplr_reg_set&regview=fields&action=edit&key=<?php echo $field['key']; ?>" class="button"><?php _e('Edit','simplr-registration-form'); ?></a>
						<a href="?page=simplr_reg_set&regview=fields&action=delete&_wpnonce=<?php echo $dnonce; ?>&key=<?php echo $field['key']; ?>" onclick="return confirm('<?php _e('Are you sure you want to do this?','simplr-registration-form'); ?>')" class="button"><?php _e('Delete','simplr-registration-form'); ?></a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
	<?php }
}
?>
