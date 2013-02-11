<?php
/*
Plugin Name: Last-Modified HTTP Header
Plugin URI: http://www.pcdr.cz/wordpress/last-modified-header-plugin
Description: Add Automaticaly Last-Modified HTTP Header
Version: 1.0
Author: PCDr. Marty
Author URI: http://www.pcdr.cz/wordpress/last-modified-header-plugin
License: GPL2
*/

  add_action('wp', 'last_modified_header' );
  add_action('admin_menu', 'lmh_settings');

  function last_modified_header() { 
    global $post;
      if(isset($post) && is_single()){
        $timestamp = strtotime($post->post_date_gmt); 
        if (get_option('lmh_use_post_creation_time') == 0 && isset($post->post_modified)) $timestamp = strtotime($post->post_modified);
        $date = date("D, d M Y H:i:s", $timestamp);  // format podle RFC 2822
        header("Last-Modified: " . $date . " GMT");
     }
  }

  function lmh_settings() {
    add_options_page(__('Last-Modified Header','menu-test'), __('Last-Modified Header','menu-test'), 'manage_options', 'lmh_settings', 'lmh_settings_page');
  }

  function lmh_settings_page() {
    if (!current_user_can('manage_options')) wp_die( __('You do not have sufficient permissions to access this page.') );
    $opt_val = get_option('lmh_use_post_creation_time' ,1);

    if( isset($_POST['lmh_submit_hidden']) && $_POST['lmh_submit_hidden'] == 'Y' ) {
        $opt_val = $_POST['lmh_use_post_creation_time'];
        update_option( 'lmh_use_post_creation_time', $opt_val );

?>
<div class="updated"><p><strong><?php _e('Settings saved.', 'lmh-menu' ); ?></strong></p></div>
<?php
    }

    echo '<div class="wrap">';
    echo "<h2>" . __( 'Last-Modified HTTP Header', 'lmh-menu' ) . "</h2>";
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="lmh_submit_hidden" value="Y">

<p><?php _e("<p><b>Options:</b></p>", 'lmh-menu' ); ?> 

<input type="radio" name="lmh_use_post_creation_time" id="radio1" value="1" size="20"<?php if ($opt_val == 1) echo " checked" ?>>
<label for="radio1">Use post creation time</label>  <br>

<input type="radio" name="lmh_use_post_creation_time" id="radio2" value="0" size="20"<?php if ($opt_val == 0) echo " checked" ?>>
<label for="radio2">Use post last modified time</label>

</p><hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>

<?php
 
}   // end function lmh_settings_page()

?>