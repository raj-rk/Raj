<?php
 
// Unregister 3 Periodic Footer Widget Sidebars
function twelve14_remove_sidebar() {
      unregister_sidebar( 'sidebar-1' ); 
}
add_action( 'widgets_init', 'twelve14_remove_sidebar', 11 );


?>
