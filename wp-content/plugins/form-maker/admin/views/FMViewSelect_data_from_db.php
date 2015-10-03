<?php

class FMViewSelect_data_from_db {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  
  public function display($id, $field_id, $field_type, $value_disabled, $form_id){

	wp_print_scripts('jquery');
    wp_print_scripts('jquery-ui-tooltip');

?>
    <link media="all" type="text/css" href="<?php echo WD_FM_URL . '/css/style.css'; ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo WD_FM_URL . '/css/jquery-ui-1.10.3.custom.css'; ?>">
	<script>

	function insert_field(){}

	function connect(){
        jQuery("input[type='radio']").attr('disabled','');
		jQuery(".connect").attr('disabled','');
		jQuery('#struct').html('<div id="saving"><div id="saving_text">Loading</div><div id="fadingBarsG"><div id="fadingBarsG_1" class="fadingBarsG"></div><div id="fadingBarsG_2" class="fadingBarsG"></div><div id="fadingBarsG_3" class="fadingBarsG"></div><div id="fadingBarsG_4" class="fadingBarsG"></div><div id="fadingBarsG_5" class="fadingBarsG"></div><div id="fadingBarsG_6" class="fadingBarsG"></div><div id="fadingBarsG_7" class="fadingBarsG"></div><div id="fadingBarsG_8" class="fadingBarsG"></div></div></div>');
		jQuery.ajax({
			    type: "POST",  
				url:"<?php echo add_query_arg(array('action' => 'select_data_from_db', 'form_id' => $form_id, 'field_type' => $field_type, 'task' => 'db_tables', 'width' => '1000', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>",
			    data: 'con_type='+jQuery('input[name=con_type]:checked').val()+'&con_method='+jQuery('input[name=con_method]:checked').val()+'&host='+jQuery('#host_rem').val()+'&port='+jQuery('#port_rem').val()+'&username='+jQuery('#username_rem').val()+'&password='+jQuery('#password_rem').val()+'&database='+jQuery('#database_rem').val()+'&field_type='+jQuery('#field_type').val()+'&format=row',
			     success: function(data)
				 {
					if(data==1)
					{
						jQuery("#struct").html('<div style="font-size: 22px; text-align: center; padding-top: 15px;">Could not connect to MySQL.</div>')
						jQuery(".connect").removeAttr('disabled');
						jQuery("input[type='radio']").removeAttr('disabled','');

					}
				    else
						jQuery("#struct").html(data)

			    } 

			 });
	}

	function shh(x)
	{
        if(x)

			jQuery(".remote_info").show();

		else

			jQuery(".remote_info").hide();

	}

	</script>
	<style>

	label{

		display:inline;
		margin-bottom: 5px;
	}

	.main_func{

		font-size: 12px;
		display:inline-block;
		width:480px;
		vertical-align:top;
	}

	.key label{

		display:inline-block;
		width:150px;
	}

	</style>
	<div class="c1">
	<div class="main_func">
		<table class="admintable">
			<tr valign="top">
			<td  class="key">
					<label style="font-weight:bold;"> <?php echo __( 'Connection type','form_maker' ); ?>: </label>
				</td>
				<td>
					<input type="radio" name="con_type" id="local" value="local" checked="checked" onclick="shh(false)">
					<label for="local">Local</label>
					<input type="radio" name="con_type" id="remote" value="remote" onclick="shh(true)">
					<label for="remote">Remote</label>
				</td>
			</tr>
			<tr class="remote_info" style="display:none">
				<td class="key">Host</td>
				<td>
					<input type="text" name="host" id="host_rem" style="width:180px">
					Port : <input type="text" name="port" id="port_rem" value="3306" style="width:50px">
				</td>
			</tr>
			<tr class="remote_info" style="display:none">
				<td class="key">Username</td>
				<td>
					<input type="text" name="username" id="username_rem"  style="width:272px">
				</td>
			</tr>
			<tr class="remote_info" style="display:none">
				<td  class="key">Password</td>
				<td>
					<input type="password" name="password" id="password_rem"  style="width:272px">
				</td>
			</tr>
			<tr class="remote_info" style="display:none">
				<td  class="key">Database</td>
				<td>
					<input type="text"name="database" id="database_rem"  style="width:272px">
				</td>
			</tr>
			<tr valign="top" style="display:none;">
				<td  class="key">
					<label> <?php echo __( 'Type','form_maker' ); ?>: </label>
				</td>
				<td>
					<input type="radio" name="con_method" id="select" value="select" checked="checked">
					<label for="select">Select</label>			
				</td>	
			</tr>
			<tr valign="top">
				<td  class="key">
				</td>
				<td>
					<input type="button" value="Connect" onclick="connect()"  class="btn connect">
				</td>
			</tr>
		</table>
		<div id="struct" style="margin-top:10px">
		</div>
		<input type="hidden" id="form_id" value="<?php echo $id ?>" >
		<input type="hidden" id="form_field_id" value="<?php echo $field_id ?>" >
		<input type="hidden" id="field_type" value="<?php echo $field_type ?>" >
		<input type="hidden" id="value_disabled" value="<?php echo $value_disabled ?>" >
	    </div>
	</div>

	<?php
  die();
}
  
  
 public function db_table_struct_select($form_id, $field_type){
    $table_struct = $this->model->get_table_struct();
    $label = $this->model->get_labels($form_id);
	$cond='<div id="condid"><select id="sel_condid" style="width: 110px">';
		foreach($table_struct as $col)
		{
			$cond.='<option>'.$col->Field.'</option>';
		}

		$cond.='</select>';
		$cond.='<select id="op_condid" style="width: 150px"><option value="=" selected="selected">=</option><option value="!=">!=</option><option value=">">&gt;</option><option value="<">&lt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option><option value="%..%">Like</option><option value="%..">Starts with</option><option value="..%">Ends with</option></select><input id="val_condid" style="width:120px; margin:0px !important; padding: 4px 6px;" type="text" /><select id="andor_condid" style="visibility: hidden; width:70px;"><option value="AND">AND</option><option value="OR">OR</option></select><img src="' . WD_FM_URL . '/images/delete.png" onclick="delete_cond(&quot;condid&quot;)" style="vertical-align: middle;"></div>';

?>

<script>

    var selected_field ='';
    var isvisible = 1;
    var cond_id = 1;
    conds='<?php echo $cond ?>';

    if(jQuery('#value_disabled').val()=='no')
	jQuery('.ch_rad_value_disabled').hide();


	function dis(id, x){

	    if(x)
		     jQuery('#'+id).removeAttr('disabled');

	    else
		      jQuery('#'+id).attr('disabled','disabled');
}



function update_vis(){

	previous=0;
	for(i=1; i<cond_id; i++){
       if(jQuery('#'+i).html()){	
			jQuery('#andor_'+i).css('visibility', 'hidden');
			if(previous)
				jQuery('#andor_'+previous).css('visibility', 'visible');
		       	previous=i;

		}

	}

}



  function delete_cond(id){

	jQuery('#'+id).remove();
	update_vis();
}

    jQuery('.add_cond').click( function() {
	jQuery('.cols').append(conds.replace(/condid/g, cond_id++));
	update_vis();
});



function save_query()

{	
    str = '';
	plugin_url='<?php echo WD_FM_URL; ?>';
	product_name = jQuery('#product_name').val();	
	product_price = jQuery('#product_price').val();	
	con_type	=jQuery('input[name=con_type]:checked').val();	
	table		=jQuery('#tables').val();	
	host		=jQuery('#host_rem').val();	
	port		=jQuery('#port_rem').val();
	username	=jQuery('#username_rem').val();	
	password	=jQuery('#password_rem').val();	
	database	=jQuery('#database_rem').val();	
	if(con_type=='remote')	
	 str += host+"@@@wdfhostwdf@@@"+port+"@@@wdfportwdf@@@"+username+"@@@wdfusernamewdf@@@"+password+"@@@wdfpasswordwdf@@@"+database+"@@@wdfdatabasewdf@@@";
	 gen_query();

	var where = jQuery('#where').val();
	var order = jQuery('#order').val();
	var value_disabled = jQuery('#value_disabled').val();
	var num = jQuery("#form_field_id").val();
	var field_type = jQuery("#field_type").val();
	if(product_name || product_price){	

		jQuery('.c1').html('<div id="saving"><div id="saving_text">Saving</div><div id="fadingBarsG"><div id="fadingBarsG_1" class="fadingBarsG"></div><div id="fadingBarsG_2" class="fadingBarsG"></div><div id="fadingBarsG_3" class="fadingBarsG"></div><div id="fadingBarsG_4" class="fadingBarsG"></div><div id="fadingBarsG_5" class="fadingBarsG"></div><div id="fadingBarsG_6" class="fadingBarsG"></div><div id="fadingBarsG_7" class="fadingBarsG"></div><div id="fadingBarsG_8" class="fadingBarsG"></div></div></div>');

		var max_value = 0;
		window.parent.jQuery('.change_pos').each(function() {
			var value = jQuery(this)[0].id;
			max_value = (value > max_value) ? value : max_value;
		});	

		max_value = parseInt(max_value) + 1;
		if(field_type =="checkbox" || field_type =="radio"){	
			var choices_td = window.parent.document.getElementById('choices');
			var div = document.createElement('div');
				div.setAttribute("id", max_value);
				div.setAttribute("class", "change_pos");	
			var el_choices = document.createElement('input');
				el_choices.setAttribute("id", "el_choices"+max_value);
				el_choices.setAttribute("type", "text");
				el_choices.setAttribute("value", '['+table+':'+product_name+']');
				el_choices.style.cssText =   "width:100px; margin:1px; padding:3px 0; border-width: 1px";
				el_choices.setAttribute("onKeyUp", "change_label('"+num+"_label_element"+max_value+"', this.value); change_in_value('"+num+"_elementform_id_temp"+max_value+"', this.value)");
				el_choices.setAttribute("disabled", 'disabled');	
			var el_choices_value = document.createElement('input');
				el_choices_value.setAttribute("id", "el_option_value"+max_value);		
				el_choices_value.setAttribute("type", "text");
				if(value_disabled=='no')
				    el_choices_value.setAttribute("value", '['+table+':'+product_name+']');
				else
					el_choices_value.setAttribute("value", '['+table+':'+product_price+']');
					el_choices_value.style.cssText = "width:100px; margin:1px; padding:3px 0; border-width: 1px";
					el_choices_value.setAttribute("onKeyUp", "change_label_value('"+num+"_elementform_id_temp"+max_value+"', this.value)");
					el_choices_value.setAttribute("disabled", 'disabled');
			var el_choices_params = document.createElement('input');
				el_choices_params.setAttribute("id", "el_option_params"+max_value);
				el_choices_params.setAttribute("class", "el_option_params");
				el_choices_params.setAttribute("type", "hidden");
				el_choices_params.setAttribute("value", where+'[where_order_by]'+order + '[db_info]'+'['+str+']');
			var el_choices_remove = document.createElement('img');
				el_choices_remove.setAttribute("id", "el_choices"+max_value+"_remove");
				el_choices_remove.setAttribute("src", plugin_url + '/images/delete.png');
				el_choices_remove.style.cssText =  'cursor:pointer;vertical-align:middle; margin:3px 3px 3px 7px;';
				el_choices_remove.setAttribute("align", 'top');
				el_choices_remove.setAttribute("onClick", "remove_choise('"+max_value+"','"+num+"','"+field_type+"')");
			var el_choices_handle = document.createElement('img');
				el_choices_handle.setAttribute("class", "el_choices_sortable");
				el_choices_handle.setAttribute("src", plugin_url + '/images/move_cursor.png');		
				el_choices_handle.style.cssText = 'cursor:move; vertical-align:middle; margin:3px 3px 3px 9px;';
				el_choices_handle.setAttribute("align", 'top');

			div.appendChild(el_choices);
			div.appendChild(el_choices_value);	
			div.appendChild(el_choices_remove);
			div.appendChild(el_choices_handle);
			div.appendChild(el_choices_params);
			choices_td.appendChild(div);

			window.parent["refresh_rowcol"](num, field_type);
			if(field_type=='checkbox'){	

				window.parent["refresh_id_name"](num, 'type_checkbox');
				window.parent["refresh_attr"](num, 'type_checkbox');
			}

			if(field_type=='radio'){

				window.parent["refresh_id_name"](num, 'type_radio');
				window.parent["refresh_attr"](num, 'type_radio');
			}

	

		}

		if(field_type =="select"){

			var select_ = window.parent.document.getElementById(num+'_elementform_id_temp');
			var option = document.createElement('option');
				option.setAttribute("id", num+"_option"+max_value);
				option.setAttribute("onselect", "set_select('"+num+"_option"+max_value+"')");
				option.setAttribute("where", where);
				option.setAttribute("order_by", order);			
				option.setAttribute("db_info", '['+str+']');	
				option.innerHTML = '['+table+':'+product_name+']';
				if(value_disabled =='no')
				  option.setAttribute("value", '['+table+':'+product_name+']');

				else
				   option.setAttribute("value", '['+table+':'+product_price+']');

			    select_.appendChild(option);

				

			var choices_td= window.parent.document.getElementById('choices');
			var div = document.createElement('div');
				div.setAttribute("id", max_value);
				div.setAttribute("class", "change_pos");	
			var el_choices = document.createElement('input');
				el_choices.setAttribute("id", "el_option"+max_value);
				el_choices.setAttribute("type", "text");
				el_choices.setAttribute("value", '['+table+':'+product_name+']');
				el_choices.style.cssText =   "width:100px; margin:1px; padding:3px 0; border-width: 1px";
				el_choices.setAttribute("onKeyUp", "change_label_name('"+max_value+"', '"+num+"_option"+max_value+"', this.value)");
				el_choices.setAttribute("disabled", 'disabled');
			var el_choices_remove = document.createElement('img');
				el_choices_remove.setAttribute("id", "el_option"+max_value+"_remove");
				el_choices_remove.setAttribute("src", plugin_url + '/images/delete.png');
				el_choices_remove.style.cssText = 'cursor:pointer; vertical-align:middle; margin:3px';
				el_choices_remove.setAttribute("align", 'top');
				el_choices_remove.setAttribute("onClick", "remove_option('"+max_value+"','"+num+"')");	
			var el_choices_dis = document.createElement('input');
				el_choices_dis.setAttribute("type", 'checkbox');
				el_choices_dis.setAttribute("id", "el_option"+max_value+"_dis");
				el_choices_dis.setAttribute("class", "el_option_dis");
				el_choices_dis.setAttribute("onClick", "dis_option('"+num+"_option"+max_value+"', this.checked)");	
				el_choices_dis.style.cssText ="vertical-align: middle; margin-left:24px; margin-right:24px;";
				if(value_disabled == 'yes')
					el_choices_dis.setAttribute("disabled", 'disabled');
			var el_choices_value = document.createElement('input');
				el_choices_value.setAttribute("id", "el_option_value"+max_value);
				el_choices_value.setAttribute("type", "text");
				if(value_disabled =='no')
					el_choices_value.setAttribute("value", '['+table+':'+product_name+']');
				else
					el_choices_value.setAttribute("value", '['+table+':'+product_price+']');
					el_choices_value.style.cssText =   "width:100px; margin:1px; padding:3px 0; border-width: 1px";
					el_choices_value.setAttribute("onKeyUp", "change_label_value('"+num+"_option"+max_value+"', this.value)");
					el_choices_value.setAttribute("disabled", 'disabled');
			var el_choices_params = document.createElement('input');
				el_choices_params.setAttribute("id", "el_option_params"+max_value);
				el_choices_params.setAttribute("class", "el_option_params");
				el_choices_params.setAttribute("type", "hidden");
				el_choices_params.setAttribute("value", where+'[where_order_by]'+order + '[db_info]'+'['+str+']');
			var el_choices_handle = document.createElement('img');
				el_choices_handle.setAttribute("class", "el_choices_sortable");
				el_choices_handle.setAttribute("src", plugin_url + '/images/move_cursor.png');		
				el_choices_handle.style.cssText = 'cursor:move; vertical-align:middle; margin:3px 3px 3px 10px;';
				el_choices_handle.setAttribute("align", 'top');

			div.appendChild(el_choices);
			div.appendChild(el_choices_value);
			div.appendChild(el_choices_dis);
			div.appendChild(el_choices_remove);
			div.appendChild(el_choices_handle);	
			div.appendChild(el_choices_params);
			choices_td.appendChild(div);

		}	

		if(field_type=='paypal_select'){		
			var select_ = window.parent.document.getElementById(num+'_elementform_id_temp');
			var option = document.createElement('option');
				option.setAttribute("id", num+"_option"+max_value);
				option.setAttribute("onselect", "set_select('"+num+"_option"+max_value+"')");
				option.setAttribute("where", where);
				option.setAttribute("order_by", order);				option.setAttribute("db_info", '['+str+']');
				option.innerHTML = '['+table+':'+product_name+']';
				option.setAttribute("value", '['+table+':'+product_price+']');
				select_.appendChild(option);
			var choices_td= window.parent.document.getElementById('choices');
			var div = document.createElement('div');
				div.setAttribute("id", max_value);
				div.setAttribute("class", "change_pos");
			var el_choices = document.createElement('input');
				el_choices.setAttribute("id", "el_option"+max_value);
				el_choices.setAttribute("type", "text");
				el_choices.setAttribute("value", '['+table+':'+product_name+']');
				el_choices.style.cssText =   "width:100px; margin:1px; padding:3px 0; border-width: 1px";
				el_choices.setAttribute("onKeyUp", "change_label_price('"+num+"_option"+max_value+"', this.value)");el_choices.setAttribute("disabled", 'disabled');
			var el_choices_price = document.createElement('input');
				el_choices_price.setAttribute("id", "el_option_price"+max_value);
				el_choices_price.setAttribute("type", "text");
				el_choices_price.setAttribute("value", '['+table+':'+product_price+']');
				el_choices_price.style.cssText =   "width:50px; margin:1px; padding:3px 0; border-width: 1px";
				el_choices_price.setAttribute("onKeyUp", "change_value_price('"+num+"_option"+max_value+"', this.value)");
				el_choices_price.setAttribute("onKeyPress", "return check_isnum_point(event)");
				el_choices_price.setAttribute("disabled", 'disabled');
			var el_choices_params = document.createElement('input');
				el_choices_params.setAttribute("id", "el_option_params"+max_value);
				el_choices_params.setAttribute("class", "el_option_params");
				el_choices_params.setAttribute("type", "hidden");
				el_choices_params.setAttribute("value", where+'[where_order_by]'+order + '[db_info]'+'['+str+']');
			var el_choices_remove = document.createElement('img');
				el_choices_remove.setAttribute("id", "el_option"+max_value+"_remove");
				el_choices_remove.setAttribute("src", plugin_url + '/images/delete.png');
				el_choices_remove.style.cssText = 'cursor:pointer; vertical-align:middle;  margin-left:4px;';
				el_choices_remove.setAttribute("align", 'top');
				el_choices_remove.setAttribute("onClick", "remove_option_price('"+max_value+"','"+num+"')");
			var el_choices_dis = document.createElement('input');
				el_choices_dis.setAttribute("type", 'checkbox');
				el_choices_dis.setAttribute("id", "el_option"+max_value+"_dis");
				el_choices_dis.setAttribute("onClick", "dis_option_price('"+num+"','"+max_value+"', this.checked)");
				el_choices_dis.style.cssText ="vertical-align: middle; margin-right:24px; margin-left:24px;";
			var el_choices_handle = document.createElement('img');
				el_choices_handle.setAttribute("class", "el_choices_sortable");
				el_choices_handle.setAttribute("src", plugin_url + '/images/move_cursor.png');		
				el_choices_handle.style.cssText = 'cursor:move; vertical-align:middle; margin:3px 3px 3px 20px;';
				el_choices_handle.setAttribute("align", 'top');
			
			div.appendChild(el_choices);
			div.appendChild(el_choices_price);
			div.appendChild(el_choices_dis);
			div.appendChild(el_choices_remove);
			div.appendChild(el_choices_handle);
			div.appendChild(el_choices_params);
			choices_td.appendChild(div);	

		}	


		if(field_type=='paypal_radio' || field_type=='paypal_checkbox' || field_type=='paypal_shipping'){
			if(field_type == 'paypal_shipping')
				field_type = 'paypal_radio';
			var c_table = window.parent.document.getElementById(num+'_table_little');
			var tr = document.createElement('div');
				tr.setAttribute("id", num+"_element_tr"+max_value);
				tr.style.display="table-row";
			var td = document.createElement('div');
				td.setAttribute("valign", "top");
				td.setAttribute("id", num+"_td_little"+max_value);
				td.setAttribute("idi", max_value);
				td.style.display="table-cell";
			var adding = document.createElement('input');
				adding.setAttribute("type", field_type.replace('paypal_', ''));
				adding.setAttribute("value", '['+table+':'+product_price+']');
				adding.setAttribute("id", num+"_elementform_id_temp"+max_value);
			if(field_type=='paypal_checkbox'){	
				adding.setAttribute("onClick", "set_checked('"+num+"','"+max_value+"','form_id_temp')");
				adding.setAttribute("name", num+"_elementform_id_temp"+max_value);
			}

			if(field_type=='paypal_radio'){
				adding.setAttribute("onClick", "set_default('"+num+"','"+max_value+"','form_id_temp')");
				adding.setAttribute("name", num+"_elementform_id_temp");
			}

			var label_adding = document.createElement('label');
				label_adding.setAttribute("id", num+"_label_element"+max_value);
				label_adding.setAttribute("class", "ch-rad-label");
				label_adding.setAttribute("for", num+"_elementform_id_temp"+max_value);
				label_adding.innerHTML = '['+table+':'+product_name+']';
				label_adding.setAttribute("where", where);
				label_adding.setAttribute("order_by", order);				label_adding.setAttribute("db_info", '['+str+']');
			var adding_ch_label = document.createElement('input');
				adding_ch_label.setAttribute("type", "hidden");
				adding_ch_label.setAttribute("id", num+"_elementlabel_form_id_temp"+max_value);
				adding_ch_label.setAttribute("name", num+"_elementform_id_temp"+max_value+"_label");
				adding_ch_label.setAttribute("value", '['+table+':'+product_name+']');
				td.appendChild(adding);
				td.appendChild(label_adding);
				td.appendChild(adding_ch_label);
				tr.appendChild(td);
				c_table.appendChild(tr);
			var choices_td= window.parent.document.getElementById('choices');
			var div = document.createElement('div');
				div.setAttribute("id", max_value);
				div.setAttribute("class", "change_pos");
			var el_choices = document.createElement('input');
				el_choices.setAttribute("id", "el_choices"+max_value);
				el_choices.setAttribute("type", "text");
				el_choices.setAttribute("value", '['+table+':'+product_name+']');
				el_choices.style.cssText =   "width:100px; margin:1px; padding:3px 0; border-width: 1px";
				el_choices.setAttribute("onKeyUp", "change_label('"+num+"_label_element"+max_value+"', this.value); change_label_1('"+num+"_elementlabel_form_id_temp"+max_value+"', this.value); ");
				el_choices.setAttribute("disabled", 'disabled');
			var el_choices_remove = document.createElement('img');
				el_choices_remove.setAttribute("id", "el_choices"+max_value+"_remove");
				el_choices_remove.setAttribute("src", plugin_url + '/images/delete.png');
				el_choices_remove.style.cssText =  'cursor:pointer;vertical-align:middle; margin: 3px 3px 3px 8px;';
				el_choices_remove.setAttribute("align", 'top');
				el_choices_remove.setAttribute("onClick", "remove_choise_price('"+max_value+"','"+num+"')");
			var el_choices_price = document.createElement('input');
				el_choices_price.setAttribute("id", "el_option_price"+max_value);
				el_choices_price.setAttribute("type", "text");
				el_choices_price.setAttribute("value", '['+table+':'+product_price+']');
				el_choices_price.style.cssText =   "width:50px; margin:1px; padding:3px 0; border-width: 1px";
				el_choices_price.setAttribute("onKeyUp", "change_value_price('"+num+"_elementform_id_temp"+max_value+"', this.value)");
				el_choices_price.setAttribute("onKeyPress", "return check_isnum_point(event)");
				el_choices_price.setAttribute("disabled", 'disabled');
			var el_choices_params = document.createElement('input');
				el_choices_params.setAttribute("id", "el_option_params"+max_value);
				el_choices_params.setAttribute("class", "el_option_params");
				el_choices_params.setAttribute("type", "hidden");
				el_choices_params.setAttribute("value", where+'[where_order_by]'+order + '[db_info]'+'['+str+']');
			var el_choices_handle = document.createElement('img');
				el_choices_handle.setAttribute("class", "el_choices_sortable");
				el_choices_handle.setAttribute("src", plugin_url + '/images/move_cursor.png');		
				el_choices_handle.style.cssText = 'cursor:move; vertical-align:middle; margin: 3px 3px 3px 15px;';
				el_choices_handle.setAttribute("align", 'top');
				
				
				div.appendChild(el_choices);
				div.appendChild(el_choices_price);
				div.appendChild(el_choices_remove);
				div.appendChild(el_choices_handle);
				div.appendChild(el_choices_params);
				choices_td.appendChild(div);

			if(field_type=='checkbox')
				window.parent["refresh_id_name"](num, 'type_checkbox');

			if(field_type=='radio' || field_type=='shipping')
				window.parent["refresh_id_name"](num, 'type_radio');
				window.parent["refresh_attr"](num, 'type_checkbox');		

		}

		
		 window.parent.tb_remove(); 

	}

	else{
		if(field_type=="checkbox" || field_type=="radio" || field_type=="select")
			alert('Select a option(s).');
		else
			alert('Select a product name or product price.');
	}

	return false; 

}



function gen_query(){

		query="";
		query_price = "";
		where="";
		previous='';

		for(i=1; i<cond_id; i++){
			if(jQuery('#'+i).html()){
				if(jQuery('#op_'+i).val()=="%..%")
					op_val=' LIKE "%'+jQuery('#val_'+i).val()+'%"';
				else if(jQuery('#op_'+i).val()=="%..")
					op_val=' LIKE "%'+jQuery('#val_'+i).val()+'"';
				else if(jQuery('#op_'+i).val()=="..%")
					op_val=' LIKE "'+jQuery('#val_'+i).val()+'%"';
				else
					op_val=' '+jQuery('#op_'+i).val()+' "'+jQuery('#val_'+i).val()+'"';

				where+=previous+' `'+jQuery('#sel_'+i).val()+'`'+op_val;
				previous=' '+ jQuery('#andor_'+i).val();

			}

		
		}


	query = '['+where+']';	
	query_price = '['+(jQuery('#order_by').val() ? '`'+jQuery('#order_by').val()+'`' +' '+jQuery('#order_by_asc').val() : jQuery('#product_name').val() ? '`'+jQuery('#product_name').val()+'`' +' '+jQuery('#order_by_asc').val() : jQuery('#product_price').val() ? '`'+jQuery('#product_price').val()+'`' +' '+jQuery('#order_by_asc').val() : '' )+']';	
	jQuery('#where').val(query);
	jQuery('#order').val(query_price);

}




</script>

<style>

.table_fields{

	margin-bottom:2px;
}

.table_fields select {

	line-height: 18px;
	width: inherit;
	margin: inherit;
}



.table_fields input[type="text"]{

	width: 225px;
	line-height: 18px;
	height: 20px;

}



.gen_query, .gen_query:focus{

	width: 200px !important;
	height: 38px;
	background: #0E73D4;
	color: white;
	cursor: pointer;
	border: 0px;
	font-size: 16px;
	font-weight: bold;
	margin: 20px 20px 20px 0;
}



.gen_query:active{

	background: #ccc;
}



</style>

<?php if($table_struct): ?>
	<div class="cols">
		<div class="table_fields">
			<label for="product_name" style="display:inline-block;width:157px;font-weight: bold; vertical-align: top;"><?php echo  (strpos($field_type, 'paypal_') === false ? 'Select a name' : ($field_type == 'paypal_shipping' ? 'Select a shipping type' : 'Select a product name')); ?></label>
			<select name="product_name" id="product_name"> 
				<option value="" ></option>
				<?php
				foreach($table_struct as $col)
					echo '<option value="'.$col->Field.'" >'.$col->Field.'</option>';
				?>
			</select>
		</div>
		<div class="table_fields ch_rad_value_disabled">
			<label for="product_price" style="display:inline-block;width:157px;font-weight: bold; vertical-align: top;"><?php echo  (strpos($field_type, 'paypal_') === false ? 'Select a value' : 'Select a product price'); ?></label>
			<select name="product_price" id="product_price"> 
				<option value="" ></option>
				<?php
				foreach($table_struct as $col)
					echo '<option value="'.$col->Field.'" >'.$col->Field.'</option>';

				?>
			</select>
		</div>
		<br/>
		<div style="background: none;text-align: center;font-size: 20px;color: rgb(0, 164, 228);font-weight: bold;">WHERE </div>
		<img src="<?php echo WD_FM_URL . '/images/add_condition.png'; ?>" title="ADD" class="add_cond"/>	
	</div>
	</br>
	<div style="background: none;text-align: center;font-size: 20px;color: rgb(0, 164, 228);font-weight: bold; margin:8px 0;">ORDER BY</div>
	<div class="table_fields">
		<label for="order_by" style="display:inline-block;width:157px;font-weight: bold; vertical-align: top;">Select an option</label>
		<select name="order_by" id="order_by"> 
			<option value="" ></option>
			<?php	
			foreach($table_struct as $col)
				echo '<option value="'.$col->Field.'" >'.$col->Field.'</option>';
			?>
		</select>
		<select name="order_by_asc" id="order_by_asc" style="width:70px;">
			<option value="asc">asc</option>
			<option value="desc">desc</option>
		</select>
	</div>
	<br/>
	<input type="button" value="Save" class="gen_query" onclick="save_query()">
	<form name="query_form" id="query_form"  style="display:none;">
		<textarea id="where" name="where"></textarea>
		<textarea id="order" name="order"></textarea>
	</form>
<?php endif; 
  die();
}

public function db_tables($form_id,$field_type){
	/*$document		= JFactory::getDocument();
	$document->addScript(JURI::root(true).'/components/com_formmaker/views/formmaker/tmpl/wdform.js');*/
	$tables = $this->model->get_tables();
?>
	<label for="tables" style="display:inline-block;width:157px;font-weight: bold;">Select a table</label><select name="tables" id="tables"> 
		<option value="" ></option>
	<?php
	
	foreach($tables as $table)
		echo '<option value="'.$table.'" >'.$table.'</option>';
	?>
	</select>
	<br/><br/>
	<div id="table_struct">
	</div>
	
	<script>

	jQuery("#tables").change(function (){
		jQuery('#table_struct').html('<div id="saving"><div id="saving_text">Loading</div><div id="fadingBarsG"><div id="fadingBarsG_1" class="fadingBarsG"></div><div id="fadingBarsG_2" class="fadingBarsG"></div><div id="fadingBarsG_3" class="fadingBarsG"></div><div id="fadingBarsG_4" class="fadingBarsG"></div><div id="fadingBarsG_5" class="fadingBarsG"></div><div id="fadingBarsG_6" class="fadingBarsG"></div><div id="fadingBarsG_7" class="fadingBarsG"></div><div id="fadingBarsG_8" class="fadingBarsG"></div></div></div>');


		jQuery.ajax({
          type: "POST",
          url: "<?php echo add_query_arg(array('action' => 'select_data_from_db', 'form_id' => $form_id, 'field_type' => $field_type, 'task' => 'db_table_struct_select', 'width' => '1000', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>",
          data: 'name='+jQuery(this).val()+'&con_type='+jQuery('input[name=con_type]:checked').val()+'&con_method='+jQuery('input[name=con_method]:checked').val()+'&host='+jQuery('#host_rem').val()+'&port='+jQuery('#port_rem').val()+'&username='+jQuery('#username_rem').val()+'&password='+jQuery('#password_rem').val()+'&database='+jQuery('#database_rem').val()+'&format=row&field_type='+jQuery('#field_type').val(),
          success: function(data) {
            jQuery("#table_struct").html(data);
          }
        });
	})
	</script>

<?php
die();
}



  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}