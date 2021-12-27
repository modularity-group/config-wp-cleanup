<?php defined("ABSPATH") or die;

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

add_action("login_enqueue_scripts", function(){
  wp_enqueue_style(
    'theme-style',
    get_stylesheet_directory_uri() . '/style.css',
    array(),
    filemtime( get_stylesheet_directory() . '/style.css' ),
    'all'
  );
});

add_action("get_header", function() {
  $themeAuthorUri = wp_get_theme()->get('AuthorURI');
  $themeCredits = "~ Web development by $themeAuthorUri ~";
  $themeCreditsLine = "";
  $themeCreditsLine = str_pad($themeCreditsLine, mb_strlen($themeCredits), "~");
  echo "<!-- \n\n$themeCreditsLine\n$themeCredits\n$themeCreditsLine\n\n -->";
});

add_action( 'wp_before_admin_bar_render', function() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('wp-logo');
}, 7 );

add_action( 'admin_bar_menu', function($wp_admin_bar) {
  $wp_admin_bar->remove_menu( 'customize' );
}, 999 );

add_action('wp_dashboard_setup', function() {
  wp_add_dashboard_widget(
    'modularity_dashboard_docu', get_bloginfo( 'name' ) . ' - Dokumentation', function() {
      $docu = get_page_by_path( 'doc' );
      echo $docu ? $docu->post_content : "";
      echo "<br><small><em>Edit this content in the <b>Doc</b> page.</em></small>";
    }
  );
});

add_filter('admin_footer_text', function() {
  echo '<span><strong>Administrator: <a href="mailto:'.get_bloginfo('admin_email').'">'.get_bloginfo('admin_email').'</a></strong></span>';
});

add_filter('update_footer', function() {
  if(current_user_can( 'administrator' ) OR current_user_can( 'developer' )){
    return 'WordPress '.get_bloginfo( 'version' ).' / '.wp_get_theme()->get( 'Name' ).' '.wp_get_theme()->get( 'Version' );
  }
}, 999);

add_action('login_head', 'wp_cleanup_branding_login');
function wp_cleanup_branding_login(){ ?>
  <style>
  body {
    --login-font-size: var(--base-font-size, 12px);
    --login-color-background: var(--base-color-background, #fff);
    --login-color-text: var(--base-color-text, #333);
    --login-color-accent: var( --base-color-accent, #000);
    background: none;
  }
  body.login {
    display: flex;
    align-items: center;
    background: #fff;
    background: var(--login-color-background);
    color: var(--login-color-text);
  }
  body.login .message {
    background: var(--login-color-background);
  }
  h1 {
    display:none;
  }
  #login {
    padding: 0;
  }
  .login form {
    padding: 0;
    background: unset;
    box-shadow: none;
    margin-top: 0;
    border: 0;
  }
  .login form:before {
    content: '<?php echo bloginfo("name"); ?>';
    color: var(--login-color-text);
    width: 100%;
    text-align: center;
    display: block;
    font-size: 30px;
    line-height: 40px;
    padding: 30px 0;
    font-weight: bold;
    box-sizing: border-box;
  }
  p label[for='user_login'],
  p label[for='user_pass'],
  p label[for='user_email'] {
    text-align: center !important;
    display: block;
    color: var(--login-color-text);
  }
  input[type='text'],
  input[type='password'],
  input[type='email'] {
    text-align: center;
    padding: 10px !important;
    border: 1px solid var(--login-color-text);
  }
  input[type='text']:focus,
  input[type='checkbox']:focus,
  input[type='password']:focus,
  input[type='email']:focus {
    outline: none !important;
    box-shadow: none !important;
    border: 1px solid black;
  }
  input[type='submit'] {
    background: var(--login-color-accent) !important;
    box-shadow: none !important;
    text-shadow: none !important;
    border-radius: 0 !important;
    border-color: var(--login-color-accent) !important;
    font-size: var(--login-font-size) !important;
    color: var(--login-color-background) !important;
  }
  input[name='rememberme']{
    vertical-align: text-bottom;
  }
  p label[for='rememberme'] {
    line-height: 34px !important;
  }
  .privacy-policy-page-link {
    margin:0 !important;
  }
  a.privacy-policy-link {
    color: black;
  }
  a.privacy-policy-link:hover {
    color: grey;
  }
  p#nav,
  p#backtoblog {
    box-sizing: border-box;
    padding: 0 !important;
    text-align: center;
  }
  p#nav a:hover,
  p#backtoblog a:hover {
    color: grey !important;
  }
  </style>
  <?php
}

add_action('admin_head','wp_cleanup_branding_block_editor');
function wp_cleanup_branding_block_editor(){
  ?>
  <style>
  .edit-post-header .edit-post-fullscreen-mode-close {
    background-repeat: no-repeat;
    background-position: center center;
    background-size: 25px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='37.152' height='31.347' viewBox='0 0 37.152 31.347'%3E%3Cdefs%3E%3Cstyle%3E.a%7Bfill:%23fff;%7D%3C/style%3E%3C/defs%3E%3Cpath class='a' d='M22.576,13.764V7.822a2.323,2.323,0,0,0-3.964-1.644L4,20.593,18.612,35.005a2.322,2.322,0,0,0,3.964-1.642V27.584c6.385.158,13.363,1.314,18.576,9.262V34.525A20.892,20.892,0,0,0,22.576,13.764Z' transform='translate(-4 -5.5)'/%3E%3C/svg%3E");
  }
  .edit-post-header .edit-post-fullscreen-mode-close svg {
    display: none;
  }
  </style>
  <?php
}

add_action('init','wp_cleanup_head');
function wp_cleanup_head(){
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'feed_links_extra', 3);
  remove_action('wp_head', 'rest_output_link_wp_head', 10);
  remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
  remove_action('wp_head', 'wp_oembed_add_host_js');
  remove_action('wp_head', 'wp_resource_hints', 2);
  remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}

add_action('init','wp_cleanup_emojies');
function wp_cleanup_emojies(){
  remove_action('wp_head', 'print_emoji_detection_script', 7 );
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
  add_filter('emoji_svg_url', '__return_false');
}

add_action('init','wp_cleanup_oembed');
function wp_cleanup_oembed(){
  //remove_action('rest_api_init', 'wp_oembed_register_route'); // makes embedding in block-editor fail
  remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
}

add_action('wp_footer', 'wp_cleanup_wpembed');
function wp_cleanup_wpembed(){
  wp_deregister_script('wp-embed');
}