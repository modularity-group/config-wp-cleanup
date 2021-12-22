<?php
remove_action('welcome_panel', 'wp_welcome_panel');

add_action('wp_dashboard_setup', 'wp_cleanup_dashboard', 999);
function wp_cleanup_dashboard() {
  global $wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
  // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
  if (!current_user_can('administrator') || !current_user_can('developer')) {
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);
  }
}

add_action('dashboard_glance_items', 'wp_cleanup_dashboard_improve');
function wp_cleanup_dashboard_improve(){
	$glances = array();
	$args = array('public' => true, '_builtin' => false );
	$post_types = get_post_types($args, 'object', 'and');

	foreach ($post_types as $post_type){
		$num_posts = wp_count_posts($post_type->name);
		$num   = number_format_i18n($num_posts->publish);
		$text  = _n($post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));

		if (current_user_can('edit_posts')){
			$glance = '<a class="'.$post_type->name.'-count" href="'.admin_url('edit.php?post_type='.$post_type->name).'">'.$num.' '.$text.'</a>';
		} else {
			$glance = '<span class="'.$post_type->name.'-count">'.$num.' '.$text.'</span>';
		}
		$glances[] = $glance;
	}
	return $glances;
}

add_action('admin_head', 'wp_cleanup_admin_messages');
add_action('login_head', 'wp_cleanup_admin_messages');
function wp_cleanup_admin_messages() {
  if (!current_user_can( 'administrator' ) || !current_user_can( 'developer' )) {
    echo '<style>.update-nag, .updated, .error, .is-dismissible { display: none; }</style>';
  }
}

add_action('admin_head', 'wp_cleanup_customclasses');
function wp_cleanup_customclasses() { ?>
  <style>#editor .block-editor-block-inspector .html-anchor-control + .components-base-control { display: none !important; }</style>
  <?php
}

add_action('wp_head', 'wp_cleanup_adminbar');
function wp_cleanup_adminbar() { ?>
  <style>
  #wpadminbar {
    width: 33px;
    overflow: hidden;
    min-width: unset;
  }
  #wpadminbar:hover {
    width: 100%;
    overflow: unset;
    min-width: 600px;
  }
  </style>
  <?php
}

add_action('get_header', 'wp_cleanup_remove_admin_bar_css');
function wp_cleanup_remove_admin_bar_css() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}
