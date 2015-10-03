<?php

class FMViewFormMakerSQLMapping {
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
  
  public function edit_query($id, $form_id) {
    wp_print_scripts('jquery');
    wp_print_scripts('jquery-ui-tooltip');
    ?>
    <link media="all" type="text/css" href="<?php echo WD_FM_URL . '/css/style.css'; ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo WD_FM_URL . '/css/jquery-ui-1.10.3.custom.css'; ?>">
    <?php
    $label = $this->model->get_labels($form_id);
    $query_obj = $this->model->get_query($id);
    
    $temp		= explode('***wdfcon_typewdf***',$query_obj->details);
    $con_type	= $temp[0];
    $temp		= explode('***wdfcon_methodwdf***',$temp[1]);
    $con_method	= $temp[0];
    $temp		= explode('***wdftablewdf***',$temp[1]);
    $table_cur	= $temp[0];
    $temp		= explode('***wdfhostwdf***',$temp[1]);
    $host		= $temp[0];
    $temp		= explode('***wdfportwdf***',$temp[1]);
    $port		= $temp[0];
    $temp		= explode('***wdfusernamewdf***',$temp[1]);
    $username	= $temp[0];
    $temp		= explode('***wdfpasswordwdf***',$temp[1]);
    $password	= $temp[0];
    $temp		= explode('***wdfdatabasewdf***',$temp[1]);
    $database	= $temp[0];
    
    $details = $temp[1];
    
    $tables = $this->model->get_tables_saved($con_type, $username, $password, $database, $host);
    $table_struct = $this->model->get_table_struct_saved($con_type, $username, $password, $database, $host, $table_cur, $con_method);

    $filter_types=array("type_submit_reset", "type_map", "type_editor", "type_captcha", "type_recaptcha", "type_button", "type_paypal_total", "type_send_copy");
    $label_id= array();
    $label_order= array();
    $label_order_original= array();
    $label_type= array();
  
    ///stexic
    $label_all	= explode('#****#',$label);
    $label_all 	= array_slice($label_all,0, count($label_all)-1);   
  
    foreach($label_all as $key => $label_each) {
      $label_id_each=explode('#**id**#',$label_each);
      $label_oder_each=explode('#**label**#', $label_id_each[1]);
      
      if(in_array($label_oder_each[1],$filter_types))
        continue;
        
      array_push($label_id, $label_id_each[0]);
      array_push($label_order_original, $label_oder_each[0]);
      $ptn = "/[^a-zA-Z0-9_]/";
      $rpltxt = "";
      $label_temp=preg_replace($ptn, $rpltxt, $label_oder_each[0]);
      array_push($label_order, $label_temp);    
      array_push($label_type, $label_oder_each[1]);
    }
    
    $form_fields='';
    foreach($label_id as $key => $lid) {
      $form_fields.='<a onclick="insert_field('.$lid.'); jQuery(\'#fieldlist\').hide();" style="display:block; text-decoration:none;">'.$label_order_original[$key].'</a>';
    }
	$user_fields = array("subid"=>"Submission ID", "ip"=>"Submitter's IP", "userid"=>"User ID", "username"=>"Username", "useremail"=>"User Email");
	foreach($user_fields as $user_key=>$user_field) {
		$form_fields.='<a onclick="insert_field(\''.$user_key.'\'); jQuery(\'#fieldlist\').hide();" style="display:block; text-decoration:none;">'.$user_field.'</a>';
	}
    $cond='<div id="condid"><select id="sel_condid" style="width: 110px">';    
    foreach($table_struct as $col) {
      $cond.='<option>'.$col->Field.'</option>';
    }
    $cond.='</select>';    
    $cond.='<select id="op_condid"><option value="=" selected="selected">=</option><option value="!=">!=</option><option value=">">&gt;</option><option value="<">&lt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option><option value="%..%">Like</option><option value="%..">Starts with</option><option value="..%">Ends with</option></select><input id="val_condid" style="width:120px" type="text" /><select id="andor_condid" style="visibility: hidden;"><option value="AND">AND</option><option value="OR">OR</option></select><img src="' . WD_FM_URL . '/images/delete.png" onclick="delete_cond(&quot;condid&quot;)" style="vertical-align: middle;"></div>';
    ?>
    <script>
      function connect() {
        jQuery("input[type='radio']").attr('disabled','');
        jQuery('#struct').html('<div id="saving"><div id="saving_text">Loading</div><div id="fadingBarsG"><div id="fadingBarsG_1" class="fadingBarsG"></div><div id="fadingBarsG_2" class="fadingBarsG"></div><div id="fadingBarsG_3" class="fadingBarsG"></div><div id="fadingBarsG_4" class="fadingBarsG"></div><div id="fadingBarsG_5" class="fadingBarsG"></div><div id="fadingBarsG_6" class="fadingBarsG"></div><div id="fadingBarsG_7" class="fadingBarsG"></div><div id="fadingBarsG_8" class="fadingBarsG"></div></div></div>');

        jQuery("#struct").load('index.php?option=com_formmaker&task=db_tables&con_type='+jQuery('input[name=con_type]:checked').val()+'&con_method='+jQuery('input[name=con_method]:checked').val()+'&format=row');
      }
      jQuery(document).ready(function() {
        jQuery("#tables").change(function (){
          jQuery('#table_struct').html('<div id="saving"><div id="saving_text">Loading</div><div id="fadingBarsG"><div id="fadingBarsG_1" class="fadingBarsG"></div><div id="fadingBarsG_2" class="fadingBarsG"></div><div id="fadingBarsG_3" class="fadingBarsG"></div><div id="fadingBarsG_4" class="fadingBarsG"></div><div id="fadingBarsG_5" class="fadingBarsG"></div><div id="fadingBarsG_6" class="fadingBarsG"></div><div id="fadingBarsG_7" class="fadingBarsG"></div><div id="fadingBarsG_8" class="fadingBarsG"></div></div></div>');

          jQuery("#table_struct").load('index.php?option=com_formmaker&task=db_table_struct&name='+jQuery(this).val()+'&con_type='+jQuery('input[name=con_type]:checked').val()+'&con_method='+jQuery('input[name=con_method]:checked').val()+'&host='+jQuery('#host_rem').val()+'&port='+jQuery('#port_rem').val()+'&username='+jQuery('#username_rem').val()+'&password='+jQuery('#password_rem').val()+'&database='+jQuery('#database_rem').val()+'&format=row&id='+jQuery("#form_id").val());
        });
        jQuery('html').click(function() {
          if(jQuery("#fieldlist").css('display')=="block") {
            jQuery("#fieldlist").hide();
          }
        });
        jQuery('.cols input[type="text"]').on('click', function() {
          event.stopPropagation();
          jQuery("#fieldlist").css("top",jQuery(this).offset().top+jQuery(this).height()+2);
          jQuery("#fieldlist").css("left",jQuery(this).offset().left);
          jQuery("#fieldlist").slideDown('fast');
          selected_field=this.id;
        });
        jQuery('#query_txt').click(function() {
          event.stopPropagation();
          jQuery("#fieldlist").css("top",jQuery(this).offset().top+jQuery(this).height()+2);
          jQuery("#fieldlist").css("left",jQuery(this).offset().left);
          jQuery("#fieldlist").slideDown('fast');
          selected_field=this.id;
        });
        jQuery('#fieldlist').click(function(event){
          event.stopPropagation();
        });
        jQuery('.add_cond').click( function() {
            jQuery('.cols').append(conds.replace(/condid/g, cond_id++));
            update_vis();
          }
        );
      });
      var selected_field ='';
      var isvisible = 1;
      var cond_id = 1;
      conds='<?php echo $cond ?>';          
      fields=new Array(<?php
        $fields = "";        
        if($table_struct) {
          foreach($table_struct as $col) {
            $fields.=' "'.$col->Field.'",';
          }
          echo  substr($fields, 0, -1);
        }
        ?>);

      function dis(id, x) {
        if(x)
          jQuery('#'+id).removeAttr('disabled');
        else
          jQuery('#'+id).attr('disabled','disabled');        
      }
      function update_vis() {
        previous=0;
        for(i=1; i<cond_id; i++) {
          if(jQuery('#'+i).html()) {
            jQuery('#andor_'+i).css('visibility', 'hidden');            
            if(previous)
              jQuery('#andor_'+previous).css('visibility', 'visible');              
            previous=i;
          }
        }
      }
      function delete_cond(id) {
        jQuery('#'+id).remove();
        update_vis();
      }
      function save_query() {
        con_type	=jQuery('input[name=con_type]:checked').val();
        con_method	=jQuery('input[name=con_method]:checked').val();
        table		=jQuery('#tables').val();
        host		=jQuery('#host_rem').val();
        port		=jQuery('#port_rem').val();
        username	=jQuery('#username_rem').val();
        password	=jQuery('#password_rem').val();
        database	=jQuery('#database_rem').val();
        
        str=con_type+"***wdfcon_typewdf***"+con_method+"***wdfcon_methodwdf***"+table+"***wdftablewdf***"+host+"***wdfhostwdf***"+port+"***wdfportwdf***"+username+"***wdfusernamewdf***"+password+"***wdfpasswordwdf***"+database+"***wdfdatabasewdf***";
        
        if(fields.length) {
          for(i=0; i<fields.length; i++) {
            str+=fields[i]+'***wdfnamewdf***'+jQuery('#'+fields[i]).val()+'***wdfvaluewdf***'+jQuery('#ch_'+fields[i]+":checked" ).length+'***wdffieldwdf***';
          }
        }
        
        for(i=1; i<cond_id; i++) {
          if(jQuery('#'+i).html()) {
            str+=jQuery('#sel_'+i).val()+'***sel***'+jQuery('#op_'+i).val()+'***op***'+jQuery('#val_'+i).val()+'***val***'+jQuery('#andor_'+i).val()+'***where***';
          }
        }        
        if(!jQuery('#query_txt').val()) {
          gen_query();
        }        
        jQuery('#details').val(str);
        var datatxt = jQuery("#query_form").serialize()+'&form_id='+jQuery("#form_id").val(); 
        
        if(jQuery('#query_txt').val()) {
          jQuery('.c1').html('<div id="saving"><div id="saving_text">Saving</div><div id="fadingBarsG"><div id="fadingBarsG_1" class="fadingBarsG"></div><div id="fadingBarsG_2" class="fadingBarsG"></div><div id="fadingBarsG_3" class="fadingBarsG"></div><div id="fadingBarsG_4" class="fadingBarsG"></div><div id="fadingBarsG_5" class="fadingBarsG"></div><div id="fadingBarsG_6" class="fadingBarsG"></div><div id="fadingBarsG_7" class="fadingBarsG"></div><div id="fadingBarsG_8" class="fadingBarsG"></div></div></div>');

          jQuery.ajax({
            type: "POST",
            url: "<?php echo add_query_arg(array('action' => 'FormMakerSQLMapping', 'id' => $id, 'form_id' => $form_id, 'task' => 'update_query', 'width' => '1000', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>",
            data: datatxt,
            success: function(data) {
              window.parent.wd_fm_apply_options();
              window.parent.tb_remove(); 
            }
          });
        }
        else {
          alert('The query is empty.');
        }
        return false;
      }

      function gen_query() {
        if(jQuery('#query_txt').val())
          if(!confirm('Are you sure you want to replace the Query? All the modifications to the Query will be lost.'))
            return;
            
        query="";
        fields=new Array(<?php
        $fields = "";
        if($table_struct) {
          foreach($table_struct as $col) {
            $fields.=' "'.$col->Field.'",';
          }
          echo  substr($fields, 0, -1);
        }
        ?>);

        con_type	=jQuery('input[name=con_type]:checked').val();
        con_method	=jQuery('input[name=con_method]:checked').val();
        table		=jQuery('#tables').val();
        fls='';
        vals='';	
        valsA=new Array();
        flsA=new Array();
        
        if(fields.length) {
          for(i=0; i<fields.length; i++) {
            if(jQuery('#ch_'+fields[i]+":checked" ).length) {
              flsA.push(fields[i]);
              valsA.push(jQuery('#'+fields[i]).val());
            }
          }
        }
        if(con_method=="insert") {
          if(flsA.length) {
            for(i=0; i<flsA.length-1; i++) {
              fls+= '`'+flsA[i]+'`, ';
              vals+= '"'+valsA[i]+'", ';
            }
            fls+= '`'+flsA[i]+'`';
            vals+= '"'+valsA[i]+'"';
          }          
          if(fls)
            query="INSERT INTO "+jQuery('#tables').val()+" (" +fls+") VALUES ("+vals+")";
        }
        
        if(con_method=="update") {
          if(flsA.length) {
            for(i=0; i<flsA.length-1; i++) {
              vals+= '`'+flsA[i]+'`="'+valsA[i]+'", ';
            }
            vals+= '`'+flsA[i]+'`="'+valsA[i]+'"';
          }
          where="";
          previous='';
          
          for(i=1; i<cond_id; i++) {
            if(jQuery('#'+i).html()) {
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
          if(vals)
            query="UPDATE "+jQuery('#tables').val()+" SET " + vals+(where? ' WHERE'+where: '') ;
        }
        if(con_method=="delete") {        
          where="";
          previous='';          
          for(i=1; i<cond_id; i++) {
            if(jQuery('#'+i).html()) {
              if(jQuery('#op_'+i).val()=="%..%")
                op_val=' LIKE "%'+jQuery('#val_'+i).val()+'%"';                
              else if(jQuery('#op_'+i).val()=="%..")
                op_val=' LIKE "%'+jQuery('#val_'+i).val()+'"';                
              else if(jQuery('#op_'+i).val()=="..%")
                op_val=' LIKE "'+jQuery('#val_'+i).val()+'%"';              
              else
                op_val=' '+jQuery('#op_'+i).val()+' "'+jQuery('#val_'+i).val()+'"';                
              where+=previous+' '+jQuery('#sel_'+i).val()+op_val;              
              previous=' '+ jQuery('#andor_'+i).val();
            }            
          }          
          if(where)
            query="DELETE FROM "+jQuery('#tables').val()+ ' WHERE'+where ;
        }        
        jQuery('#query_txt').val(query);
      }

      jQuery(document).ready(function () {
        jQuery(".hasTip").tooltip({
           track: true,
           content: function () {
             return jQuery(this).prop('title');
           }
         });
      });

      function insert_field(myValue) {
        if(!selected_field)
          return;
        myField=document.getElementById(selected_field);        
        if (document.selection) {
          myField.focus();      
          sel = document.selection.createRange();    
          sel.text = myValue;    
        }    
        else {
          if (myField.selectionStart || myField.selectionStart == '0') {
            var startPos = myField.selectionStart;       
            var endPos = myField.selectionEnd;      
            myField.value = myField.value.substring(0, startPos)           
            +  "{"+myValue+"}"        
            + myField.value.substring(endPos, myField.value.length);   
          }
         else {
          myField.value += "{"+myValue+"}";    
         }
        }
      }
    </script>

    <style>
      .main_func {
        font-size: 12px;
        display:inline-block;
        width:480px;
        vertical-align:top;
      }      
      .desc {
        font-size: 12px;
        display:inline-block;
        width:250px;
        position:fixed;
        margin:15px;
        padding-left:55px;
      }      
      .desc button {
        max-width: 200px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      .key label {
        display:inline-block;
        width:150px;
      }
      .cols {
        border: 3px solid red;
        padding: 4px;
      }
      .cols div:nth-child(even) {background: #FFF}
      .cols div:nth-child(odd) {background: #F5F5F5}
      .cols div {
        height: 28px;
        padding: 5px
      }
      .cols label {
        display:inline-block;
        width:200px;
        font-size:15px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        vertical-align: middle;
      }
      .cols input[type="text"] {
        width: 220px;
        line-height: 18px;
        height: 20px;
      }
      .cols input[type="text"]:disabled {
        cursor: not-allowed;
        background-color: #eee;
      }
      .cols input[type="checkbox"] {
        width: 20px;
        line-height: 18px;
        height: 20px;
        vertical-align: middle;
      }
      .cols select {
        line-height: 18px;
        height: 24px;
      }
      #fieldlist {
        position: absolute;
        width:225px;
        background: #fff;
        border: solid 1px #c7c7c7;
        top: 0;
        left: 0;
        z-index: 1000;
      }
      #fieldlist a {
        padding: 5px;
        cursor:pointer;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      #fieldlist a:hover {
        background: #ccc;
      }
      .gen_query, .gen_query:focus {
        width: 200px;
        height: 38px;
        background: #0E73D4;
        color: white;
        cursor: pointer;
        border: 0px;
        font-size: 16px;
        font-weight: bold;
        margin: 20px;
      }
      .gen_query:active {
        background: #ccc;
      }
    </style>
    
    <div class="c1">
      <div class="main_func">
        <table class="admintable">
          <tr valign="top">
            <td  class="key">
              <label title="asf">Connection type: </label>
            </td>
            <td >
              <input type="radio" name="con_type" id="local" value="local" <?php if($con_type=='local') echo 'checked="checked"'?> disabled>
              <label for="local">Local</label>
              <input type="radio" name="con_type" id="remote" value="remote"  <?php if($con_type=='remote') echo 'checked="checked"'?> disabled>
              <label for="remote">Remote</label>
            </td>
          </tr>
          <tr class="remote_info" <?php if($con_type=='local') echo 'style="display:none"'?>>
            <td class="key">Host</td>
            <td>
              <input type="text" name="host" id="host_rem" style="width:180px" value="<?php echo $host; ?>" disabled>
              Port : <input type="text" name="port" id="port_rem" value="<?php echo $port; ?>" style="width:50px" disabled>
            </td>
          </tr>
          <tr class="remote_info" <?php if($con_type=='local') echo 'style="display:none"'?>>
            <td class="key">Username</td>
            <td>
              <input type="text" name="username" id="username_rem"  style="width:272px" value="<?php echo $username; ?>" disabled>
            </td>
          </tr>
          <tr class="remote_info" <?php if($con_type=='local') echo 'style="display:none"'?>>
            <td  class="key">Password</td>
            <td>
              <input type="password" name="password" id="password_rem"  style="width:272px" value="<?php echo $password; ?>" disabled>
            </td>
          </tr>
          <tr class="remote_info" <?php if($con_type=='local') echo 'style="display:none"'?>>
            <td  class="key">Database</td>
            <td>
              <input type="text"name="database" id="database_rem"  style="width:272px" value="<?php echo $database; ?>" disabled>
            </td>
          </tr>			
          <tr valign="top">
            <td  class="key">
              <label>Type: </label>
            </td>
            <td >
              <input type="radio" name="con_method" id="insert" value="insert" <?php if($con_method=='insert') echo 'checked="checked"'?> disabled>
              <label for="insert">Insert</label>
              <input type="radio" name="con_method" id="update" value="update" <?php if($con_method=='update') echo 'checked="checked"'?> disabled>
              <label for="update">Update</label>
              <input type="radio" name="con_method" id="delete" value="delete" <?php if($con_method=='delete') echo 'checked="checked"'?> disabled>
              <label for="delete">Delete</label>
            </td>
          </tr>
          <tr valign="top">
            <td  class="key">
            </td>
            <td >
              <input type="button" value="Connect" onclick="connect()" disabled>
            </td>
          </tr>
        </table>
        <div id="struct">
          <label for="tables" style="display:inline-block;width:157px;font-weight: bold;">Select a table</label>
          <select name="tables" id="tables" disabled> 
            <option value="" ></option>
          <?php
          
          foreach($tables as $table)
            echo '<option value="'.$table.'" '.($table_cur==$table ? 'selected' : '').' >'.$table.'</option>';
          ?>
          </select>
          <br/><br/>
          
          
          <div id="table_struct">

        <?php
        if($table_struct)
        {
        ?>

        <div class="cols">
        <?php

        $temps=explode('***wdffieldwdf***',$details);
        $wheres = $temps[count($temps)-1];   
        $temps 	= array_slice($temps,0, count($temps)-1);   
        $col_names= array();
        $col_vals= array();
        $col_checks= array();

        foreach($temps as $temp)
        {
          $temp=explode('***wdfnamewdf***',$temp);
          array_push($col_names, $temp[0]);
          $temp=explode('***wdfvaluewdf***',$temp[1]);
          array_push($col_vals, $temp[0]);
          array_push($col_checks, $temp[1]);
        }
          if($con_method=='insert' or $con_method=='update')
          {
            echo '<div style="background: none;text-align: center;font-size: 20px;color: rgb(0, 164, 228);font-weight: bold;"> SET </div>';

            foreach($table_struct as $key =>$col)
            {
            
              $title=' '.$col->Field;
              $title.="<ul style='padding-left: 17px;'>";
              $title.="<li>Type - ".$col->Type."</li>";
              $title.="<li>Null - ".$col->Null."</li>";
              $title.="<li>Key - ".$col->Key."</li>";
              $title.="<li>Default - ".$col->Default."</li>";
              $title.="<li>Extra - ".$col->Extra."</li>";
              $title.="</ul>";
              
            ?>
              <div><label title="<?php echo $title; ?>" class="hasTip"><b><?php echo $col->Field; ?></b><img src="<?php echo WD_FM_URL . '/images/info.png'; ?>" style="width:20px; vertical-align:middle;padding-left: 10px;" /></label><input type="text" id="<?php echo $col->Field; ?>" <?php if(!$col_checks[$key]) echo 'disabled="disabled"'?>  value="<?php echo $col_vals[$key]; ?>" /><input id="ch_<?php echo $col->Field; ?>"  type="checkbox" onClick="dis('<?php echo $col->Field; ?>', this.checked)" <?php if($col_checks[$key]) echo 'checked="checked"'?> /></div>
            <?php
            }
          }
            
          if($con_method=='delete' or $con_method=='update')
          {
            echo '<div style="background: none;text-align: center;font-size: 20px;color: rgb(0, 164, 228);font-weight: bold;"> WHERE </div>
            <img src="' . WD_FM_URL . '/images/add_condition.png" title="ADD" class="add_cond"/></br>';
            
            if($wheres)
            {
              echo '<script>';

              $wheres	=explode('***where***',$wheres);
              $wheres = array_slice($wheres,0, count($wheres)-1);   
              foreach($wheres as $where)
              {			
                $temp=explode('***sel***',$where);
                $sel = $temp[0];
                $temp=explode('***op***',$temp[1]);
                $op = $temp[0];
                $temp=explode('***val***',$temp[1]);
                $val = $temp[0];
                $andor = $temp[1];
                echo 'jQuery(".cols").append(conds.replace(/condid/g, cond_id++));	update_vis();
                  jQuery("#sel_"+(cond_id-1)).val("'.$sel.'");
                  jQuery("#op_"+(cond_id-1)).val("'.$op.'");
                  jQuery("#val_"+(cond_id-1)).val("'.$val.'");
                  jQuery("#andor_"+(cond_id-1)).val("'.$andor.'");
                ';

              }
              echo '</script>';

            }			

            
          }
        ?>
        <div style="color:red; background: none">The changes above will not affect the query until you click "Generate query".</div>
        </div>
        <br/>
        <input type="button" value="Generate Query" class="gen_query" onclick="gen_query()">
        <br/>
        <form name="query_form" id="query_form" >
          <label style="vertical-align: top;" for="query_txt" > Query: </label><textarea id="query_txt" name="query" rows=5 style="width:428px" ><?php echo $query_obj->query; ?></textarea>
          <input type="hidden" name="details" id="details">
          <input type="hidden" name="id" value="<?php echo $query_obj->id; ?>">
        </form>
        <input type="button" value="Save" style="float: right;width: 200px;height: 38px;background: #0E73D4;color: white;cursor: pointer;border: 0px;font-size: 16px;font-weight: bold;margin: 20px;" onclick="save_query()">
        
        
        <div id="fieldlist" style="display: none;">
        <?php echo $form_fields ?>
        </div>
        

            <?php
        }
        ?>
          </div>
        </div>
        <input type="hidden" value="<?php echo $form_id ?>" id="form_id">

      </div>

      <div class="desc">
      <?php
      foreach($label_id as $key => $lid)
      {
        echo '<div>{'.$lid.'} - <button onclick="insert_field('.$lid.');">'.$label_order_original[$key].'</button></div>';

      }
      $user_fields = array("subid"=>"Submission ID", "ip"=>"Submitter's IP", "userid"=>"User ID", "username"=>"Username", "useremail"=>"User Email");
	  foreach($user_fields as $user_key=>$user_field) {
	    echo '<div>{'.$user_key.'} - <button onclick="insert_field(\''.$user_key.'\');">'.$user_field.'</button></div>';
	  }
      ?>
      </div>
    </div>
    <?php
    die();
  }

  public function add_query($form_id){
    wp_print_scripts('jquery');
    wp_print_scripts('jquery-ui-tooltip');
    ?>
    <link media="all" type="text/css" href="<?php echo WD_FM_URL . '/css/style.css'; ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo WD_FM_URL . '/css/jquery-ui-1.10.3.custom.css'; ?>">
    <?php
    $label = $this->model->get_labels($form_id);

		$filter_types=array("type_submit_reset", "type_map", "type_editor", "type_captcha", "type_recaptcha", "type_button", "type_paypal_total", "type_send_copy");
		$label_id= array();
		$label_order= array();
		$label_order_original= array();
		$label_type= array();
	
		///stexic
		$label_all	= explode('#****#',$label);
		$label_all 	= array_slice($label_all,0, count($label_all)-1);   
	
		foreach($label_all as $key => $label_each) {
			$label_id_each=explode('#**id**#',$label_each);
			$label_oder_each=explode('#**label**#', $label_id_each[1]);
			
			if(in_array($label_oder_each[1],$filter_types))
				continue;
				
			array_push($label_id, $label_id_each[0]);
		
		
			array_push($label_order_original, $label_oder_each[0]);
		
			$ptn = "/[^a-zA-Z0-9_]/";
			$rpltxt = "";
			$label_temp=preg_replace($ptn, $rpltxt, $label_oder_each[0]);
			array_push($label_order, $label_temp);
		
			array_push($label_type, $label_oder_each[1]);
		}
  ?>
    <script>
    function insert_field(){}
    
    function connect() {
      jQuery("input[type='radio']").attr('disabled','');
      jQuery(".connect").attr('disabled','');
      jQuery('#struct').html('<div id="saving"><div id="saving_text">Loading</div><div id="fadingBarsG"><div id="fadingBarsG_1" class="fadingBarsG"></div><div id="fadingBarsG_2" class="fadingBarsG"></div><div id="fadingBarsG_3" class="fadingBarsG"></div><div id="fadingBarsG_4" class="fadingBarsG"></div><div id="fadingBarsG_5" class="fadingBarsG"></div><div id="fadingBarsG_6" class="fadingBarsG"></div><div id="fadingBarsG_7" class="fadingBarsG"></div><div id="fadingBarsG_8" class="fadingBarsG"></div></div></div>');
      jQuery.ajax({
        type: "POST",
        url: "<?php echo add_query_arg(array('action' => 'FormMakerSQLMapping', 'id' => 0, 'form_id' => $form_id, 'task' => 'db_tables', 'width' => '1000', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>",
        data: 'con_type='+jQuery('input[name=con_type]:checked').val()+'&con_method='+jQuery('input[name=con_method]:checked').val()+'&host='+jQuery('#host_rem').val()+'&port='+jQuery('#port_rem').val()+'&username='+jQuery('#username_rem').val()+'&password='+jQuery('#password_rem').val()+'&database='+jQuery('#database_rem').val()+'&format=row',
        success: function(data) {
          if(data==1) {
            jQuery("#struct").html('<div style="font-size: 22px; text-align: center; padding-top: 15px;">Could not connect to MySQL.</div>')
            jQuery(".connect").removeAttr('disabled');
            jQuery("input[type='radio']").removeAttr('disabled','');
          }
          else {
            jQuery("#struct").html(data);
          }
        }
      });
    }
    
    function shh(x) {
      if(x)
        jQuery(".remote_info").show();
      else
        jQuery(".remote_info").hide();
    }
      
    </script>
    <style>
      .main_func {
        font-size: 12px;
        display:inline-block;
        width:480px;
        vertical-align:top;
      }
      
      .desc {
        font-size: 12px;
        display:inline-block;
        width:250px;
        position:fixed;
        margin:15px;
        margin-left:55px;
      }
      
      .desc button {
        max-width: 200px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      
      .key label {
        display:inline-block;
        width:150px;
      }	
    </style>
    
    <div class="c1">
      <div class="main_func">
        <table class="admintable">
          <tr valign="top">
            <td  class="key">
              <label title="asf">Connection type: </label>
            </td>
            <td >
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
          <tr valign="top">
            <td  class="key">
              <label>Type: </label>
            </td>
            <td >
              <input type="radio" name="con_method" id="insert" value="insert" checked="checked">
              <label for="insert">Insert</label>
              <input type="radio" name="con_method" id="update" value="update">
              <label for="update">Update</label>
              <input type="radio" name="con_method" id="delete" value="delete">
              <label for="delete">Delete</label>
            </td>
          </tr>
          <tr valign="top">
            <td  class="key">
            </td>
            <td >
              <input type="button" value="Connect" onclick="connect()" class="connect">
            </td>
          </tr>
        </table>
        <div id="struct">
        </div>
        <input type="hidden" value="<?php echo $form_id ?>" id="form_id">
      </div>
      <div class="desc">
      <?php
      foreach($label_id as $key => $lid) {
        echo '<div>{'.$lid.'} - <button onclick="insert_field('.$lid.');">'.$label_order_original[$key].'</button></div>';
      }
	  $user_fields = array("subid"=>"Submission ID", "ip"=>"Submitter's IP", "userid"=>"User ID", "username"=>"Username", "useremail"=>"User Email");
	  foreach($user_fields as $user_key=>$user_field) {
	    echo '<div>{'.$user_key.'} - <button onclick="insert_field(\''.$user_key.'\');">'.$user_field.'</button></div>';
	  }
      ?>
      </div>
    </div>	
    <?php
    die();
  }
  
  public function db_tables($form_id){
    $tables = $this->model->get_tables();
    ?>
    <label for="tables" style="display:inline-block;width:157px;font-weight: bold;">Select a table</label>
    <select name="tables" id="tables"> 
      <option value="" ></option>
      <?php
      foreach($tables as $table) {
        echo '<option value="' . $table . '" >' . $table . '</option>';
      }
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
          url: "<?php echo add_query_arg(array('action' => 'FormMakerSQLMapping', 'id' => 0, 'form_id' => $form_id, 'task' => 'db_table_struct', 'width' => '1000', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>",
          data: 'name='+jQuery(this).val()+'&con_type='+jQuery('input[name=con_type]:checked').val()+'&con_method='+jQuery('input[name=con_method]:checked').val()+'&host='+jQuery('#host_rem').val()+'&port='+jQuery('#port_rem').val()+'&username='+jQuery('#username_rem').val()+'&password='+jQuery('#password_rem').val()+'&database='+jQuery('#database_rem').val()+'&format=row',
          success: function(data) {
            jQuery("#table_struct").html(data);
          }
        });
      })
    </script>
    <?php
    die();
  }
  
  public function db_table_struct ($form_id) {
    $table_struct = $this->model->get_table_struct();
    $label = $this->model->get_labels($form_id);
    $con_method = isset($_POST['con_method']) ? $_POST['con_method'] : '';
		$filter_types=array("type_submit_reset", "type_map", "type_editor", "type_captcha", "type_recaptcha", "type_button", "type_paypal_total", "type_send_copy");
		$label_id= array();
		$label_order= array();
		$label_order_original= array();
		$label_type= array();
	
		///stexic
		$label_all	= explode('#****#',$label);
		$label_all 	= array_slice($label_all,0, count($label_all)-1);   
	
		foreach($label_all as $key => $label_each) {
			$label_id_each=explode('#**id**#',$label_each);
			$label_oder_each=explode('#**label**#', $label_id_each[1]);
			
			if(in_array($label_oder_each[1],$filter_types)) {
				continue;
      }
			array_push($label_id, $label_id_each[0]);
			array_push($label_order_original, $label_oder_each[0]);
			$ptn = "/[^a-zA-Z0-9_]/";
			$rpltxt = "";
			$label_temp=preg_replace($ptn, $rpltxt, $label_oder_each[0]);
			array_push($label_order, $label_temp);
			array_push($label_type, $label_oder_each[1]);
		}
		$form_fields='';
		foreach($label_id as $key => $id) {
			$form_fields.='<a onclick="insert_field('.$id.'); jQuery(\'#fieldlist\').hide();" style="display:block; text-decoration:none;">'.$label_order_original[$key].'</a>';
		}
		$user_fields = array("subid"=>"Submission ID", "ip"=>"Submitter's IP", "userid"=>"User ID", "username"=>"Username", "useremail"=>"User Email");
		foreach($user_fields as $user_key=>$user_field) {
			$form_fields.='<a onclick="insert_field(\''.$user_key.'\'); jQuery(\'#fieldlist\').hide();" style="display:block; text-decoration:none;">'.$user_field.'</a>';
		}
		$cond='<div id="condid"><select id="sel_condid" style="width: 110px">';		
		foreach($table_struct as $col) {
			$cond.='<option>'.$col->Field.'</option>';
		}
		$cond.='</select>';
		
		$cond.='<select id="op_condid"><option value="=" selected="selected">=</option><option value="!=">!=</option><option value=">">&gt;</option><option value="<">&lt;</option><option value=">=">&gt;=</option><option value="<=">&lt;=</option><option value="%..%">Like</option><option value="%..">Starts with</option><option value="..%">Ends with</option></select><input id="val_condid" style="width:120px" type="text" /><select id="andor_condid" style="visibility: hidden;"><option value="AND">AND</option><option value="OR">OR</option></select><img src="' . WD_FM_URL . '/images/delete.png" onclick="delete_cond(&quot;condid&quot;)" style="vertical-align: middle;"></div>';
    ?>
    <script>
      var selected_field ='';
      var isvisible = 1;
      var cond_id = 1;
       //onclick="gen_query()"
      conds='<?php echo $cond ?>';
      fields=new Array(<?php
        $fields = "";
        if($table_struct) {
          foreach($table_struct as $col) {
            $fields.=' "'.$col->Field.'",';
          }
          echo  substr($fields, 0, -1);
        }
        ?>);
      function dis(id, x) {
        if(x)
          jQuery('#'+id).removeAttr('disabled');
        else
          jQuery('#'+id).attr('disabled','disabled');
      }

      function update_vis() {
        previous=0;
        for(i=1; i<cond_id; i++) {
          if(jQuery('#'+i).html()) {
            jQuery('#andor_'+i).css('visibility', 'hidden');            
            if(previous) {
              jQuery('#andor_'+previous).css('visibility', 'visible');
            }
            previous=i;
          }
        }
      }

      function delete_cond(id) {
        jQuery('#'+id).remove();
        update_vis();
      }

      jQuery('.add_cond').click( function() {
          jQuery('.cols').append(conds.replace(/condid/g, cond_id++));
          update_vis();
        }
      );

      jQuery('html').click(function() {
        if(jQuery("#fieldlist").css('display')=="block") {
          jQuery("#fieldlist").hide();
        }
      });

      jQuery('.cols input[type="text"]').on('click', function() {
        event.stopPropagation();
        jQuery("#fieldlist").css("top",jQuery(this).offset().top+jQuery(this).height()+2);
        jQuery("#fieldlist").css("left",jQuery(this).offset().left);
        jQuery("#fieldlist").slideDown('fast');
        selected_field=this.id;
      });

      jQuery('#query_txt').click(function() {
        event.stopPropagation();
        jQuery("#fieldlist").css("top",jQuery(this).offset().top+jQuery(this).height()+2);
        jQuery("#fieldlist").css("left",jQuery(this).offset().left);
        jQuery("#fieldlist").slideDown('fast');
        selected_field=this.id;
      });

      jQuery('#fieldlist').click(function(event){
          event.stopPropagation();
      });

      function save_query() {
        con_type	=jQuery('input[name=con_type]:checked').val();
        con_method	=jQuery('input[name=con_method]:checked').val();
        table		=jQuery('#tables').val();
        table		=jQuery('#tables').val();
        host		=jQuery('#host_rem').val();
        port		=jQuery('#port_rem').val();
        username	=jQuery('#username_rem').val();
        password	=jQuery('#password_rem').val();
        database	=jQuery('#database_rem').val();
          
        str=con_type+"***wdfcon_typewdf***"+con_method+"***wdfcon_methodwdf***"+table+"***wdftablewdf***"+host+"***wdfhostwdf***"+port+"***wdfportwdf***"+username+"***wdfusernamewdf***"+password+"***wdfpasswordwdf***"+database+"***wdfdatabasewdf***";
          
        if(fields.length) {
          for(i=0; i<fields.length; i++) {
            str+=fields[i]+'***wdfnamewdf***'+jQuery('#'+fields[i]).val()+'***wdfvaluewdf***'+jQuery('#ch_'+fields[i]+":checked" ).length+'***wdffieldwdf***';
          }
        }
        
        for(i=1; i<cond_id; i++) {
          if(jQuery('#'+i).html()) {
            str+=jQuery('#sel_'+i).val()+'***sel***'+jQuery('#op_'+i).val()+'***op***'+jQuery('#val_'+i).val()+'***val***'+jQuery('#andor_'+i).val()+'***where***';
          }
        }
        
        if(!jQuery('#query_txt').val()) {
          gen_query();
        }

        jQuery('#details').val(str);

        var datatxt = jQuery("#query_form").serialize()+'&form_id='+jQuery("#form_id").val(); 
        if(jQuery('#query_txt').val()) {
          jQuery('.c1').html('<div id="saving"><div id="saving_text">Saving</div><div id="fadingBarsG"><div id="fadingBarsG_1" class="fadingBarsG"></div><div id="fadingBarsG_2" class="fadingBarsG"></div><div id="fadingBarsG_3" class="fadingBarsG"></div><div id="fadingBarsG_4" class="fadingBarsG"></div><div id="fadingBarsG_5" class="fadingBarsG"></div><div id="fadingBarsG_6" class="fadingBarsG"></div><div id="fadingBarsG_7" class="fadingBarsG"></div><div id="fadingBarsG_8" class="fadingBarsG"></div></div></div>');
          jQuery.ajax({
            type: "POST",
            url: "<?php echo add_query_arg(array('action' => 'FormMakerSQLMapping', 'id' => 0, 'form_id' => $form_id, 'task' => 'save_query', 'width' => '1000', 'height' => '500', 'TB_iframe' => '1'), admin_url('admin-ajax.php')); ?>",
            data: datatxt,
            success: function(data) {
              window.parent.wd_fm_apply_options();
              window.parent.tb_remove(); 
            }
          });
        }
        else {
          alert('The query is empty.');
        }
        return false;
      }

      function gen_query() {
        if(jQuery('#query_txt').val()) {
          if(!confirm('Are you sure you want to replace the Query? All the modifications to the Query will be lost.')) {
            return;
          }
        }
        query="";
        fields=new Array(<?php
          $fields = "";
          if($table_struct) {
            foreach($table_struct as $col) {
              $fields.=' "'.$col->Field.'",';
            }
            echo  substr($fields, 0, -1);
          }
          ?>);

        con_type =jQuery('input[name=con_type]:checked').val();
        con_method =jQuery('input[name=con_method]:checked').val();
        table =jQuery('#tables').val();
        fls='';
        vals='';	
        valsA=new Array();
        flsA=new Array();
        
        if(fields.length) {
          for(i=0; i<fields.length; i++) {
            if(jQuery('#ch_'+fields[i]+":checked" ).length) {
              flsA.push(fields[i]);
              valsA.push(jQuery('#'+fields[i]).val());
            }
          }
        }

        if(con_method=="insert") {
          if(flsA.length) {
            for(i=0; i<flsA.length-1; i++) {
                fls+= '`'+flsA[i]+'`, ';
                vals+= '"'+valsA[i]+'", ';
            }
            fls+= '`'+flsA[i]+'`';
            vals+= '"'+valsA[i]+'"';
          }          
          if(fls) {
            query="INSERT INTO "+jQuery('#tables').val()+" (" +fls+") VALUES ("+vals+")";
          }
        }
        
        if(con_method=="update") {
          if(flsA.length) {
            for(i=0; i<flsA.length-1; i++) {
              vals+= '`'+flsA[i]+'`="'+valsA[i]+'", ';
            }
            vals+= '`'+flsA[i]+'`="'+valsA[i]+'"';
          }
          where="";
          previous='';
          for(i=1; i<cond_id; i++) {
            if(jQuery('#'+i).html()) {
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
          if(vals) {
            query="UPDATE "+jQuery('#tables').val()+" SET " + vals+(where? ' WHERE'+where: '') ;
          }
        }

        if(con_method=="delete") {        
          where="";
          previous='';          
          for(i=1; i<cond_id; i++) {
            if(jQuery('#'+i).html()) {
              if(jQuery('#op_'+i).val()=="%..%")
                op_val=' LIKE "%'+jQuery('#val_'+i).val()+'%"';
                
              else if(jQuery('#op_'+i).val()=="%..")
                op_val=' LIKE "%'+jQuery('#val_'+i).val()+'"';
                
              else if(jQuery('#op_'+i).val()=="..%")
                op_val=' LIKE "'+jQuery('#val_'+i).val()+'%"';
              
              else
                op_val=' '+jQuery('#op_'+i).val()+' "'+jQuery('#val_'+i).val()+'"';                
              where+=previous+' '+jQuery('#sel_'+i).val()+op_val;              
              previous=' '+ jQuery('#andor_'+i).val();
            }
          }
          if(where) {
            query="DELETE FROM "+jQuery('#tables').val()+ ' WHERE'+where ;
          }
        }
        jQuery('#query_txt').val(query);
      }
      
      jQuery(document).ready(function () {
        jQuery(".hasTip").tooltip({
           track: true,
           content: function () {
             return jQuery(this).prop('title');
           }
         });
      });

      function insert_field(myValue) {
        if(!selected_field)
          return;        
        myField=document.getElementById(selected_field);        
        if (document.selection) {
          myField.focus();      
          sel = document.selection.createRange();    
          sel.text = myValue;    
        }
        else {
          if (myField.selectionStart || myField.selectionStart == '0') {
             var startPos = myField.selectionStart;       
             var endPos = myField.selectionEnd;      
             myField.value = myField.value.substring(0, startPos)           
             +  "{"+myValue+"}"        
             + myField.value.substring(endPos, myField.value.length);   
          } 
          else {
            myField.value += "{"+myValue+"}";    
          }
        }
      }
    </script>
    <style>
      .cols div:nth-child(even) {background: #FFF;}
      .cols div:nth-child(odd) {background: #F5F5F5;}
      .cols div {
        height: 28px;
        padding: 5px;
      }
      .cols label {
        display:inline-block;
        width:200px;
        font-size:15px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        vertical-align: middle;
      }
      .cols input[type="text"] {
        width: 220px;
        line-height: 18px;
        height: 20px;
      }
      .cols input[type="text"]:disabled {
        cursor: not-allowed;
        background-color: #eee;
      }
      .cols input[type="checkbox"] {
        width: 20px;
        line-height: 18px;
        height: 20px;
        vertical-align: middle;
      }
      .cols select {
        line-height: 18px;
        height: 24px;
      }
      #fieldlist {
        position: absolute;
        width:225px;
        background: #fff;
        border: solid 1px #c7c7c7;
        top: 0;
        left: 0;
        z-index: 1000;
      }
      #fieldlist a {
        padding: 5px;
        cursor:pointer;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
      }
      #fieldlist a:hover {
        background: #ccc;
      }
      .gen_query, .gen_query:focus {
        width: 200px;
        height: 38px;
        background: #0E73D4;
        color: white;
        cursor: pointer;
        border: 0px;
        font-size: 16px;
        font-weight: bold;
        margin: 20px;
      }
      .gen_query:active {
        background: #ccc;
      }
    </style>
    <?php
    if($table_struct) {
      ?>

      <div class="cols">
        <?php	
        if($con_method=='insert' or $con_method=='update') {
          echo '<div style="background: none;text-align: center;font-size: 20px;color: rgb(0, 164, 228);font-weight: bold;"> SET </div>';
          foreach($table_struct as $col) {
            $title=' '.$col->Field;
            $title.="<ul style='padding-left: 17px;'>";
            $title.="<li>Type - ".$col->Type."</li>";
            $title.="<li>Null - ".$col->Null."</li>";
            $title.="<li>Key - ".$col->Key."</li>";
            $title.="<li>Default - ".$col->Default."</li>";
            $title.="<li>Extra - ".$col->Extra."</li>";
            $title.="</ul>";
            ?>
              <div><label title="<?php echo $title; ?>" class="hasTip"><b><?php echo $col->Field; ?></b><img src="<?php echo WD_FM_URL . '/images/info.png'; ?>" style="width:20px; vertical-align:middle;padding-left: 10px;" /></label><input type="text" id="<?php echo $col->Field; ?>" disabled="disabled"/><input id="ch_<?php echo $col->Field; ?>"  type="checkbox" onClick="dis('<?php echo $col->Field; ?>', this.checked)"/></div>
            <?php
          }
        }
        if($con_method=='delete' or $con_method=='update') {
          echo '<div style="background: none;text-align: center;font-size: 20px;color: rgb(0, 164, 228);font-weight: bold;"> WHERE </div>

          <img src="' . WD_FM_URL . '/images/add_condition.png" title="ADD" class="add_cond"/></br>';          
        }
        ?>
      </div>
      <br/>
      <input type="button" value="Generate Query" class="gen_query" onclick="gen_query()">
      <br/>
      <form name="query_form" id="query_form" >
        <label style="vertical-align: top;" for="query_txt" > Query: </label><textarea id="query_txt" name="query" rows=5 style="width:428px"></textarea>
        <input type="hidden" name="details" id="details">
      </form>
      <input type="button" value="Save" style="float: right;width: 200px;height: 38px;background: #0E73D4;color: white;cursor: pointer;border: 0px;font-size: 16px;font-weight: bold;margin: 20px;" onclick="save_query()">      
      <div id="fieldlist" style="display: none;">
        <?php echo $form_fields ?>
      </div>
      <?php
    }
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