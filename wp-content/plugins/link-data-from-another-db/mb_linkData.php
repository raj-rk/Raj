<?php
/*
Plugin Name: Linked Data From Another DB
Plugin URI: http://www.montanabanana.com
Description: Link data from another database is a plugin that allows your WP users to display user selectable content from a MySQL database. The Admin will set up the SQL query and the User can select results from the query. This plugin was originally created for a real estate website to allow Users to display their properties within their WP author page.
Version: 0.1.0.6
Author: Eric @ Montana Banana
Author URI: http://montanab.com/blog/wordpress-link-data-from-another-database/

Copyright (c) 2013 Montana Banana

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

*/ 



//////////////////////////////////////////////////////

///////////////
/////ADMIN/////
///////////////

require_once ABSPATH . WPINC . '/pluggable.php';

//admin menu
function MBlinkdata_admin_actions() {
    global $user_ID;
    if($user_ID && $user_ID>0){
        if(current_user_can('level_10')==1){
            add_users_page("Link Data From DB", "Link Data From DB", 1, "Link_Data_From_DB", "MBlinkdata_admin");
        }
    }    
}
add_action('admin_menu', 'MBlinkdata_admin_actions');

//admin page
function MBlinkdata_admin() {
	include('mb_linkData_admin.php');
}
 
        
function load_mb_admin_files() {
    wp_register_style( 'mb_admin_css', plugin_dir_url('').'link-data-from-another-db/css/jquery.dataTables.css', false, '1.0.0' );
    wp_register_style( 'mb_linkdata_styles', plugin_dir_url('').'link-data-from-another-db/css/MBlinkdata_styles.css', false, '1.0.0' );
    wp_register_style( 'MBlinkData-admin-ui-css', plugin_dir_url('').'link-data-from-another-db/css/jquery-ui.css', false, '1.0.0' );
    wp_register_script( 'mb_jquery', plugin_dir_url('').'link-data-from-another-db/js/jquery-1.9.1.js',false, '1.0.0');
    wp_register_script( 'mb_jquery_ui', plugin_dir_url('').'link-data-from-another-db/js/jquery-ui-1.10.3.custom.min.js',false, '1.0.0');
    wp_register_script( 'mb_datatables', plugin_dir_url('').'link-data-from-another-db/js/jquery.dataTables.min.js',false, '1.0.0');
    wp_register_script( 'mb_datatables_hidden', plugin_dir_url('').'link-data-from-another-db/js/dataTables.fnGetHiddenNodes.js', array('mb_datatables'), '1.0.0');
    
    wp_enqueue_style( 'mb_admin_css' );
    wp_enqueue_style( 'mb_linkdata_styles' );
    wp_enqueue_style( 'MBlinkData-admin-ui-css' );
    wp_enqueue_script( 'mb_datatables' );        
    wp_enqueue_script( 'mb_datatables_hidden' );
    wp_enqueue_script( 'mb_jquery' );        
    wp_enqueue_script( 'mb_jquery_ui' );

}
add_action( 'admin_enqueue_scripts', 'load_mb_admin_files' );




//deactivating? :(
if ( function_exists('register_uninstall_hook') ){    
    function deactivate_MBlinkdata(){
        delete_option('MBlinkdata_limit_list');
        delete_option('MBlinkdata_profile_title');
        delete_option('MBlinkdata_user_description');
        delete_option('MBlinkdata_user_title');
        delete_option('MBlinkdata_dbcolumnidmanual');
        delete_option('MBlinkdata_roles');
        delete_option('MBlinkdata_dbcolumns_profile');
        delete_option('MBlinkdata_dbcolumns_edit');
        delete_option('MBlinkdata_method');
        delete_option('MBlinkdata_custom_profile');
        delete_option('MBlinkdata_custom');
        delete_option('MBlinkdata_dbcolumnwherevalue');
        delete_option('MBlinkdata_dbcolumnwhereid');
        delete_option('MBlinkdata_dbcolumnid');
        delete_option('MBlinkdata_dbtable');
        delete_option('MBlinkdata_dbpwd');
        delete_option('MBlinkdata_dbuser');
        delete_option('MBlinkdata_dbname');
        delete_option('MBlinkdata_dbhost');
    }
    register_uninstall_hook( __FILE__, 'deactivate_MBlinkdata' );    
}

////////////////
///SHORT CODE///
////////////////
function MBlinkData(){
    $data = get_the_author_meta('MBlinkdata_my_links');
    $data = explode(",",$data);
    $dd = "";
    foreach($data AS $d){
        $dd.="'$d',";
    } 
    $dd = rtrim($dd, ",");
    $query  = stripslashes(get_option('MBlinkdata_custom_profile'));
    $query = preg_replace("/__IDS__/",$dd,$query);
    /*
    echo get_option('MBlinkdata_dbuser').'<br />';
    echo get_option('MBlinkdata_dbpwd').'<br />';
    echo get_option('MBlinkdata_dbname').'<br />';
    echo get_option('MBlinkdata_dbhost').'<br />';
    */
    $linked_content = new wpdb(get_option('MBlinkdata_dbuser'), get_option('MBlinkdata_dbpwd'), get_option('MBlinkdata_dbname'), get_option('MBlinkdata_dbhost'));
    $linked_content->show_errors = true;
    $linked_results = $linked_content->get_results($query); 
    /*
    if ( false === $linked_content->get_results($query)) {
        echo $query.'<br />'; 
        return new WP_Error( 'db_query_error', 
		__( 'Could not execute query' ), $wpdb);
    }
    */
	return $linked_results;
}
//add_shortcode( 'MBlinkData', 'MBlinkData' );

///////////////
///EDIT PAGE///
///////////////
 
////////////////////////////////////////////////////

if(!function_exists('word_limiter')){
    function word_limiter($str, $limit = 35, $end_char = '&#8230;'){
        if (trim($str) == ''){
            return $str;
        }
        preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

        if (strlen($str) == strlen($matches[0])){
            $end_char = '';
        }
        return rtrim($matches[0]).$end_char;
    }
}


if(!function_exists('character_limiter')){
    function character_limiter($str, $n = 35, $end_char = '&#8230;'){
        if (strlen($str) < $n){
            return $str;
        }

        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

        if (strlen($str) <= $n){
            return $str;
        }

        $out = "";
        foreach (explode(' ', trim($str)) as $val){
            $out .= $val.' ';

            if (strlen($out) >= $n){
                $out = trim($out);
                return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
            }       
        }
    }
}

$this_user = wp_get_current_user();

$allowed_roles = explode(',',get_option('MBlinkdata_roles'));
$user_roles = $this_user->roles;
$allowed = FALSE;
foreach($user_roles AS $v=>$ur){
    $ur = ucwords($ur);
    if(in_array($ur,$allowed_roles)){
        $allowed = TRUE;
    }
}

//add_action('edit_user_profile', 'select_linked_data');
if($allowed){
    add_action('show_user_profile', 'select_linked_data');
}
add_action('show_user_profile', 'select_linked_data');
function select_linked_data($user) {
    /*
    MBlinkdata_user_title
    MBlinkdata_user_description
    MBlinkdata_profile_title
    MBlinkdata_roles
    MBlinkdata_dbcolumnid
    MBlinkdata_dbcolumnidmanual
    MBlinkdata_dbtable
    MBlinkdata_dbcolumns_edit
    MBlinkdata_dbcolumns_profile    
    MBlinkdata_dbcolumnwhereid    
    MBlinkdata_dbcolumnwherevalue
    MBlinkdata_dbpwd
    MBlinkdata_dbuser
    MBlinkdata_dbname
    MBlinkdata_dbhost
    MBlinkdata_limit_list
    MBlinkdata_my_links
    MBlinkdata_custom
    MBlinkdata_method
    */
    ?>

    
    <script>
    $j=jQuery.noConflict();
    $j(document).ready(function(){
        $j('#data_results').dataTable();
        var table = $j('table.dataTable').dataTable();
        $j('#submit').click(function() {
            var hiddenRows = table.fnGetHiddenNodes();
            $j('table.dataTable tbody').append(hiddenRows);
        });
        
        $j('#button').click( function () {
            var oTable = $j('table.dataTable').dataTable();
            var nHidden = oTable.fnGetHiddenNodes();
        });
    });
    </script>
    
    <br /><br />&nbsp;   
    <div id="MBlinkdata"> 
        <h3><?php echo get_option('MBlinkdata_user_title');?></h3>
        <p><?php echo get_option('MBlinkdata_user_description');?></p>
        <div id="select_your_data">
            <?php
            $columns = explode(",",get_option('MBlinkdata_dbcolumns_edit'));
            $columns_manual = explode(",",get_option('MBlinkdata_dbcolumnidmanual'));
            
            $mbuser = get_option('MBlinkdata_dbuser');
            $mbpwd = get_option('MBlinkdata_dbpwd');
            $mbdb = get_option('MBlinkdata_dbname');
            $mbhost = get_option('MBlinkdata_dbhost');
            
            $linked_db = new wpdb($mbuser, $mbpwd, $mbdb, $mbhost);
            if($linked_db->error){
                echo 'There was an error connecting with the database. Please contact the Administrator';
            }
            else{
                if(stripslashes(get_option('MBlinkdata_custom'))!=""){
                    $custom_query = stripslashes(get_option('MBlinkdata_custom'));
                    $db_results = $linked_db->get_results($custom_query);
                }
                else{
                    if(get_option('MBlinkdata_dbcolumnwhereid')!="" && stripslashes(get_option('MBlinkdata_dbcolumnwherevalue'))!=""){
                        $where= "WHERE ".get_option('MBlinkdata_dbcolumnwhereid')." ".stripslashes(get_option('MBlinkdata_dbcolumnwherevalue'));
                    }
                    $db_results = $linked_db->get_results("SELECT ".get_option('MBlinkdata_dbcolumns_edit')." FROM `".get_option('MBlinkdata_dbtable')."` $where");
                }
                ?>
                <table id="data_results" class="dataTable form-table">
                    <thead>
                        <tr style="font-weight: bold;">
                            <td style="width:50px">Select</td>
                        <?php
                            if(get_option('MBlinkdata_method')=="manual"){
                                $columns_pull = $columns_manual;
                            }
                            else{
                                $columns_pull = $columns;
                            } 
                            foreach($columns_pull AS $column){
                                $nice_column = preg_replace("/_/"," ",$column);
                                $nice_column = ucwords($nice_column);
                                echo '<td>'.$nice_column.'</td>';
                            }
                        ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=0;
                            if(get_option('MBlinkdata_method')=="generator"){
                                $column_id = get_option('MBlinkdata_dbcolumnid');
                            } 
                            else{$column_id='property_id';}                  
                            $mine = get_user_option( 'MBlinkdata_my_links', $_GET['user_id'] );                            
                            $valued = explode(",",$mine);
                            foreach($db_results AS $result){
                                $columns_array = (array)$columns_pull;       
                                echo '
                                <tr>
                                    <td style="width:50px"><input type="checkbox" name="MBlinkdata_selected[]" value="'.$result->$column_id.'"'; if(in_array($result->$column_id,$valued)){echo ' checked';} echo ' /></td>
                                    ';
                                    foreach($columns_array AS $c){
                                        echo '<td>'.character_limiter($result->$c,'50').'</td>'."\r\n";
                                    }
                                    echo '     
                                 </tr>
                                 ';
                                 ++$i;
                            }
                            
                            if($i>0){echo '</tr>';}
                        ?>
                    </tbody>
                </table>
                <?
            }
            ?>
        </div>
    </div>     
    <div style="clear: both;"></div>
    <br /><br />&nbsp;   
    <?
}
add_action('personal_options_update', 'update_mb_linkdata_user_profile' );
add_action('edit_user_profile_update', 'update_mb_linkdata_user_profile' );
function update_mb_linkdata_user_profile($user_id) {
    $MBlinkdata_selected = implode(',',$_POST['MBlinkdata_selected']);
    update_user_meta($user_id, 'MBlinkdata_my_links', $MBlinkdata_selected);
}
 
?>