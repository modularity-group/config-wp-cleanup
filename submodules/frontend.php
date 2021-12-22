<?php
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
