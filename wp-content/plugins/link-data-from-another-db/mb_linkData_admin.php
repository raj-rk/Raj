<?php 
global $wp_roles;
$all_roles = $wp_roles->roles;


	if($_POST['MBlinkdata_hidden'] == 'Y') {
		update_option('MBlinkdata_dbhost', $_POST['MBlinkdata_dbhost']);
		update_option('MBlinkdata_dbname', $_POST['MBlinkdata_dbname']);
		update_option('MBlinkdata_dbuser', $_POST['MBlinkdata_dbuser']);
		update_option('MBlinkdata_dbpwd', $_POST['MBlinkdata_dbpwd']);
		update_option('MBlinkdata_dbtable', $_POST['MBlinkdata_dbtable']);
		update_option('MBlinkdata_dbcolumnid', $_POST['MBlinkdata_dbcolumnid']);
        update_option('MBlinkdata_dbcolumnwhereid', $_POST['MBlinkdata_dbcolumnwhereid']);
        update_option('MBlinkdata_dbcolumnwherevalue', $_POST['MBlinkdata_dbcolumnwherevalue']);
        update_option('MBlinkdata_custom', $_POST['MBlinkdata_custom']);
        update_option('MBlinkdata_custom_profile', $_POST['MBlinkdata_custom_profile']);
        update_option('MBlinkdata_method', $_POST['MBlinkdata_method']);
        
		$dbcolumnsedit = $_POST['MBlinkdata_dbcolumns_edit'];
        if(is_array($dbcolumnsedit)){$dbcolumnsedit = implode(",",$dbcolumnsedit);}
		update_option('MBlinkdata_dbcolumns_edit', $dbcolumnsedit);
        
		$dbcolumnsprofile = $_POST['MBlinkdata_dbcolumns_profile'];
        if(is_array($dbcolumnsprofile)){$dbcolumnsprofile = implode(",",$dbcolumnsprofile);}
		update_option('MBlinkdata_dbcolumns_profile', $dbcolumnsprofile);
        
		$dbroles = $_POST['MBlinkdata_roles'];
        if(is_array($dbroles)){$dbroles = implode(",",$dbroles);}
		update_option('MBlinkdata_roles', $dbroles);
        
        $dbcolumnsmanual = $_POST['MBlinkdata_dbcolumnidmanual'];
        if(is_array($dbcolumnsmanual)){$dbcolumnsmanual = implode(",",$dbcolumnsmanual);}
		update_option('MBlinkdata_dbcolumnidmanual', $dbcolumnsmanual);
                
		update_option('MBlinkdata_user_title', $_POST['MBlinkdata_user_title']);
		update_option('MBlinkdata_user_description', $_POST['MBlinkdata_user_description']);
		update_option('MBlinkdata_profile_title', $_POST['MBlinkdata_profile_title']);
		update_option('MBlinkdata_limit_list', (int)$_POST['MBlinkdata_limit_list']);
        ?>
        <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
        <?php
	}
?>

<script type="text/javascript">
$t=jQuery.noConflict();
$t(document).ready(function(){
    $t('#tabs').tabs();
});
</script>

<style>
.tab_content{
    padding:10px;
}
</style>

<div class="wrap">
	<?php echo "<h2>" . __( 'Link Data From DB', 'MBlinkdata_mb' ) . "</h2>"; ?>
    
    <div id="mb_left">
        <div id="tabs">
            <ul>
                <li><a href="#tab_tips">Tips</a></li>
                <li><a href="#tab_db_config">1. DB Config</a></li>
                <?php
                //if DB check
                if(get_option('MBlinkdata_dbname')!=""){
                ?>
                <li><a href="#tab_generator">2. Generator</a></li>
                <li><a href="#tab_manual">3. Manual</a></li>
                <li><a href="#tab_options">4. Options</a></li>
                <? } ?>
                <li><a href="#tab_about">About</a></li>
            </ul>
    	<form name="MBlinkdata_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    		<input type="hidden" name="MBlinkdata_hidden" value="Y">
            
            <? // TAB TIPS ;?>
            
            <div id="tab_tips" class="tab_content">
                <?php echo "<h2>" . __( 'Tips and Info', 'MBlinkdata_mb' ) . "</h2>"; ?>
                <div id="mb_tips_content">
                    <span>Host:</span> the host of your database<br />
                    <span>Name:</span> the name of your database<br />
                    <span>User:</span> the username to access your database<br /> 
                    <span>Password:</span> the password to access your database<br /> 
                    <span>Table:</span> the table you want to display data from<br /> 
                    <span>ID Column:</span> the key to be referenced in the table<br /> 
                    <span>Columns:</span> the column data you want to show<br /> 
                    <span>Roles:</span> What User roles can add the data to their profile<br /> 
                </div>
                <p>* select multiple tables while holding CTRL<br />
                * select a range of tables while holding SHIFT
                </p>
            </div>
            
            <? // TAB DB CONFIG ;?>
            
            <div id="tab_db_config" class="tab_content">
                <?php echo "<h2>" . __( 'Database Settings', 'MBlinkdata_mb' ) . "</h2>"; ?>
        		<p><label for="MBlinkdata_dbhost"><?php _e("Database host: "); ?></label><input type="text" id="MBlinkdata_dbhost" name="MBlinkdata_dbhost" value="<?php echo get_option('MBlinkdata_dbhost', ''); ?>" size="20" title="This is your Database Host... typically 'localhost'" /><?php _e(" ex: localhost" ); ?></p>
        		<p><label for="MBlinkdata_dbname"><?php _e("Database name: "); ?></label><input type="text" id="MBlinkdata_dbname" name="MBlinkdata_dbname" value="<?php echo get_option('MBlinkdata_dbname', ''); ?>" size="20"><?php _e(" ex: listings" ); ?></p>
        		<p><label for="MBlinkdata_dbuser"><?php _e("Database user: "); ?></label><input type="text" id="MBlinkdata_dbuser" name="MBlinkdata_dbuser" value="<?php echo get_option('MBlinkdata_dbuser', ''); ?>" size="20"><?php _e(" ex: root" ); ?></p>
        		<p><label for="MBlinkdata_dbpwd"><?php _e("Database password: "); ?></label><input type="password" id="MBlinkdata_dbpwd" name="MBlinkdata_dbpwd" value="<?php echo get_option('MBlinkdata_dbpwd', ''); ?>" size="20"><?php _e(" ex: mypassword" ); ?></p>                
            </div>
            
            
            <?php
            //if DB check
            if(get_option('MBlinkdata_dbname')!=""){
            ?>
                    
            <? // TAB GENERATOR ;?>
            
            <div id="tab_generator" class="tab_content">
                <?php echo "<h2>" . __( 'Generator', 'MBlinkdata_mb' ) . "</h2>"; ?>
                <?php
                    $mbuser = get_option('MBlinkdata_dbuser');
                    $mbpwd = get_option('MBlinkdata_dbpwd');
                    $mbdb = get_option('MBlinkdata_dbname');
                    $mbhost = get_option('MBlinkdata_dbhost');
                    $mbtable = get_option('MBlinkdata_dbtable');
                    $mbcolumnidmanual = get_option('MBlinkdata_dbcolumnidmanual');
                    $mbcolumnid = get_option('MBlinkdata_dbcolumnid');
                    $mbcolumnsedit = explode(',', get_option('MBlinkdata_dbcolumns_edit'));
                    $mbcolumnsprofile = explode(',', get_option('MBlinkdata_dbcolumns_profile'));
                    $mbcolumnwhereid = get_option('MBlinkdata_dbcolumnwhereid');
                    $mbcolumnwherevalue = get_option('MBlinkdata_dbcolumnwherevalue');                
                    $mbroles = explode(',', get_option('MBlinkdata_roles'));
                    $mbusertitle = get_option('MBlinkdata_user_title');
                    $mbuserdescription = get_option('MBlinkdata_user_description');                
                    $mbprofiletitle = get_option('MBlinkdata_profile_title');
                    $mblimitlist = get_option('MBlinkdata_limit_list');
                    $mbcustom = get_option('MBlinkdata_custom');
                    $mbcustomprofile = get_option('MBlinkdata_custom_profile');
                    $mb_method = get_option('MBlinkdata_method');
                    
                    
                    
                    $linked_db = new wpdb($mbuser, $mbpwd, $mbdb, $mbhost);
                    if($linked_db->error){
                        echo "<h3>" . __( 'Error establishing a database connection to '.$mbdb, 'MBlinkdata_mb' ) . "</h3>"; 
                        echo '                        
                            <ul>
                                <li>Is the username and password provided above is incorrect?</li>
                                <li>Is the database down?</li>
                                <li>Is the hostname correct?</li>
                                <li>Is the database running?</li>
                                <li>Does this website/IP and user "'.$mbuser.'" have permission to access the database?</li>
                            </ul>
                        ';
                    }
                    else{
                        //GET DB TABLES
                        $db_tables = $linked_db->get_results("SHOW TABLES FROM $mbdb");
                        ?>
                        
                        <p style="display: inline;">
                        <?php
                        $i=0;
                        $z=count($db_tables);
                        if($z!=0){
                            ?> 
                            <label for="MBlinkdata_dbtable"><?php _e("Database table: "); ?></label>
                            <?php
                            echo '<select onchange="dbtablechanged()" id="MBlinkdata_dbtable" name="MBlinkdata_dbtable">'."\r\n";
                            echo '<option value="">--Select a Value--</option>';
                            foreach($db_tables AS $table){
                                $table_name = 'Tables_in_'.$mbdb;
                                echo '<option value="'.$table->$table_name.'"'; if($mbtable==$table->$table_name){echo ' selected';} echo '>'.$table->$table_name.'</option>'."\r\n";
                            }
                            echo '</select>';    
                        }
                        else{
                            ?>
                            <label for="MBlinkdata_dbtable"><?php _e("Database table: "); ?></label><input type="text" id="MBlinkdata_dbtable" name="MBlinkdata_dbtable" value="<?php echo $mbtable; ?>" size="20"><?php _e(" ex: listings" ); ?>
                            <?php
                        }
                        //END GET DB TABLES
                        ?>
                        
                        
                        <div style="display:inline;" id="table_warning"></div>
                        
                        <?php
                        
                        //GET TABLE COLUMNS
                        if($mbtable!=""){
                            $db_columns = $linked_db->get_results("SHOW COLUMNS FROM $mbtable");
                            $i=0;
                            $z=count($db_columns);
                            if($z!=0){
                                ?>
                                
                                <p><label for="MBlinkdata_dbcolumnid"><?php _e("ID column: "); ?></label>
                                <?php
                                echo '<select id="MBlinkdata_dbcolumnid" name="MBlinkdata_dbcolumnid">'."\r\n";
                                echo '<option value="">--Select a Value--</option>';
                                foreach($db_columns AS $column){
                                    echo '<option value="'.$column->Field.'"'; if($column->Field==$mbcolumnid){echo ' selected';} echo '>'.$column->Field.'</option>'."\r\n";
                                }
                                echo '</select>';
                                ?>    
                                
                                 
                                <p style="display: inline-block; margin-right:20px"><label for="MBlinkdata_dbcolumns_edit"><?php _e("Column(s) to display to User: "); ?></label><br />
                                <?php
                                echo '<select id="MBlinkdata_dbcolumns_edit" name="MBlinkdata_dbcolumns_edit[]" multiple size="10">'."\r\n";
                                foreach($db_columns AS $column){
                                    echo '<option value="'.$column->Field.'"'; if(in_array($column->Field,$mbcolumnsedit)){echo ' selected';} echo '>'.$column->Field.'</option>'."\r\n";
                                }
                                echo '</select></p>';
                                ?>
                                
                                <p style="display: inline-block;"><label for="MBlinkdata_dbcolumns_profile"><?php _e("Column data to send to Profile: "); ?></label><br />
                                <?php
                                echo '<select id="MBlinkdata_dbcolumns_profile" name="MBlinkdata_dbcolumns_profile[]" multiple size="10">'."\r\n";
                                foreach($db_columns AS $column){
                                    echo '<option value="'.$column->Field.'"'; if(in_array($column->Field,$mbcolumnsprofile)){echo ' selected';} echo '>'.$column->Field.'</option>'."\r\n";
                                }
                                echo '</select></p>';
                            }
                            else{
                                ?>
                                <p><label for="MBlinkdata_dbcolumnid"><?php _e("ID column: "); ?></label> <input type="text" id="MBlinkdata_dbcolumnid" name="MBlinkdata_dbcolumnid" value="<?php echo get_option('MBlinkdata_dbcolumnid', ''); ?>" size="20"><?php _e(" ex: id" ); ?></p>
                                
                                
                                <p><label for="MBlinkdata_dbcolumns_edit"><?php _e("Column(s) to display: "); ?></label> <input type="text" id="MBlinkdata_dbcolumns_edit" name="MBlinkdata_dbcolumns_edit" value="<?php echo get_option('MBlinkdata_dbcolumns_edit', ''); ?>" size="20"><?php _e(" ex: id, title, date" ); ?></p>
                                
                                <p><label for="MBlinkdata_dbcolumns"><?php _e("Column(s) to display: "); ?></label> <input type="text" id="MBlinkdata_dbcolumns" name="MBlinkdata_dbcolumns" value="<?php echo get_option('MBlinkdata_dbcolumns', ''); ?>" size="20"><?php _e(" ex: id, title, date" ); ?></p>
                                <?php
                            }
                        }
                        //END GET TABLE COLUMNS                        
                    }                    
                ?>
                
                <p><label for="MBlinkdata_dbcolumnwhereid"><?php _e("WHERE column"); ?></label>
                <?php
                echo '<select id="MBlinkdata_dbcolumnwhereid" name="MBlinkdata_dbcolumnwhereid">'."\r\n";
                echo '<option value="">--Select a Value--</option>';
                foreach($db_columns AS $column){
                    echo '<option value="'.$column->Field.'"'; if($column->Field==$mbcolumnwhereid){echo ' selected';} echo '>'.$column->Field.'</option>'."\r\n";
                }
                echo '</select>'; 
                ?>  <label for="MBlinkdata_dbcolumnwherevalue"><?php _e(" = "); ?></label><input type="text" id="MBlinkdata_dbcolumnwherevalue" name="MBlinkdata_dbcolumnwherevalue" value="<?php echo stripslashes($mbcolumnwherevalue); ?>" size="20">   <?php _e("ex: WHERE table __>='inactive'__" ); ?>
                <p><input type="radio" id="MBlinkdata_method_generator" name="MBlinkdata_method" value="generator" <?php if($mb_method=="generator"){echo 'checked';} if($mb_method==""){echo ' checked';} ?> /> <label for="MBlinkdata_method_generator" style="color:red">Use Generator</label></p>
            </div>
            
            <? // TAB MANUAL ;?>
    		<div id="tab_manual" class="tab_content">
                <p><label for="MBlinkdata_custom"><?php _e("Custom Query for User Selection: "); ?></label><br /><textarea style="width:500px; height: 120px;" id="MBlinkdata_custom" name="MBlinkdata_custom"><?php echo stripslashes($mbcustom); ?></textarea><br /><?php _e(" ex: Your FULL query (SELECT * FROM `table` WHERE id='500')<br />Use the custom query at your own risk!" ); ?></p>
                
                <p><label for="MBlinkdata_custom"><?php _e("Display Columns for User Results: "); ?></label><br /><textarea style="width:500px; height: 120px;" id="MBlinkdata_dbcolumnidmanual" name="MBlinkdata_dbcolumnidmanual"><?php echo stripslashes($mbcolumnidmanual); ?></textarea><br /><?php _e(" ex: CSV of table names of your generated report -  id,name,address,phone,picture,website" ); ?></p>
                
                
                <p><label for="MBlinkdata_custom_profile"><?php _e("Custom Query for Profile Display: "); ?></label><br /><textarea style="width:500px; height: 120px;" id="MBlinkdata_custom_profile" name="MBlinkdata_custom_profile"><?php echo stripslashes($mbcustomprofile); ?></textarea><br /><?php _e(" ex: Your FULL query with User selection<br />Add \"IN(__IDS__)\" to use Users selections  ex:  AND listings.id IN (__IDS__) <br />Use the custom query at your own risk!" ); ?></p>
                <p><input type="radio" id="MBlinkdata_method_manual" name="MBlinkdata_method" value="manual" <?php if($mb_method=="manual"){echo 'checked';} ?> /> <label for="MBlinkdata_method_manual" style="color:red">Use Manual Query</label></p>
            </div>
            
            <? // TAB OPTIONS ;?>
            <div id="tab_options" class="tab_content">
                <?php echo "<h2>" . __( 'Options', 'MBlinkdata_mb' ) . "</h2>"; ?>
                <p><label for="MBlinkdata_roles"><?php _e("Allowed User Role(s): "); ?></label><br />
                <?php
                echo '<select id="MBlinkdata_roles" name="MBlinkdata_roles[]" multiple size="'.count($all_roles).'">'."\r\n";
                foreach($all_roles AS $role){
                    echo '<option value="'.$role['name'].'"'; if(in_array($role['name'],$mbroles)){echo ' selected';} echo '>'.$role['name'].'</option>'."\r\n";
                }
                echo '</select>'; ?>
                </p>
                <p><label for="MBlinkdata_user_title"><?php _e("Title when editing profile page: "); ?></label> <input type="text" id="MBlinkdata_user_title" name="MBlinkdata_user_title" value="<?php echo $mbusertitle; ?>" size="20"><?php _e(" ex: Choose Your Listings" ); ?></p>
            <p><label for="MBlinkdata_user_description"><?php _e("Instructions on profile editing page: "); ?></label><br /><textarea id="MBlinkdata_user_description" name="MBlinkdata_user_description"><?php echo $mbuserdescription; ?></textarea><br /><?php _e(" ex: These listings will be shown on your profile page" ); ?></p>
            <p><label for="MBlinkdata_profile_title"><?php _e("Title when viewing profile: "); ?></label> <input type="text" id="MBlinkdata_profile_title" name="MBlinkdata_profile_title" value="<?php echo $mbprofiletitle; ?>" size="20"><?php _e(" ex: My Listings" ); ?></p>
            <p><label for="MBlinkdata_limit_list"><?php _e("Limit results on profile: "); ?></label> <input type="text" id="MBlinkdata_limit_list" name="MBlinkdata_limit_list" value="<?php echo $mblimitlist ? 0 : 'unlimited'; ?>" size="20"><?php _e(" ex: 25 - Default: unlimited" ); ?></p>                
            </div>
            
            
            <?
            }
            ?>
            
            
            <? // TAB ABOUT ;?>
            <div id="tab_about" class="tab_content">
                <?php echo "<h2>" . __( 'About This Plugin', 'MBlinkdata_mb' ) . "</h2>"; ?>
                <h3>What is this plugin for?</h3>
                <p>This plugin is for those users who need to pull content from another MySQL database and connect it with their Wordpress Users' Profile. Say you have a non-WP website that has car listings for sale and you also have a WP blog which allows each sales person to blog about car reviews and other related information. With this plugin you can list cars being sold by a user on their Users profile page or even on each blog post.</p>
                <h3>How does it work?</h3>
                <p>The Admin will set up the database information, connect the tables (or even write their own SQL) and give permissions on which User Groups can access. Each User with access will be able to then log into their profile and select any data they want to show in their profile/blog page.</p>
                <h3>Will my Users be able to pull anything content they want from the database?</h3>
                <p>No. When you create your SQL query, you will provide specific data that the User will be able to access and select. They will not be able to see for example the entire database user table (unless you give them access to it!), and they will not be able to modify or delete any content they are able to see.</p>
                <h3>Is this secure?</h3>
                <p>Yes</p>
            </div>
            
              
            
            
            
            
            
            
            
        </div>
            
            
    		<p class="submit">
    		<input type="submit" name="Submit" value="<?php _e('Update Options', 'MBlinkdata_mb' ) ?>" />
    		</p>
    	</form>
     </div>
</div>


    

<script>
function dbtablechanged(){
    document.getElementById('table_warning').innerHTML = 'Update to get correct Table Column(s)';
}
</script>