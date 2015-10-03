<?php
if(defined('WP_UNINSTALL_PLUGIN') ){
  global $wpdb;
  $table_name = $wpdb->prefix . 'custom_website_data';
  delete_option('cwd_newmsg');
  $wpdb->query("DROP TABLE IF EXISTS $table_name");
}