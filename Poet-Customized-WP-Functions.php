<?php
    /*
    Plugin Name: Poet Customized WP Functions
    Description: Customized Your WP Functions
    Version: 1.0
    Author: BlueNoob
    Author URI: https://www.bluenoob.com
    Plugin URI: https://www.bluenoob.com
    */
/*****************************************************
  移除 WordPress 加载的JS和CSS链接中的版本号
  https://www.wpdaxue.com/remove-js-css-version.html
******************************************************/
function poet_remove_cssjs_version( $src ) {
        if( strpos( $src, 'ver=' ) )
                $src = remove_query_arg( 'ver', $src ); 
        return $src;
}
add_filter( 'style_loader_src', 'poet_remove_cssjs_version', 999 );
add_filter( 'script_loader_src', 'poet_remove_cssjs_version', 999 );

/*****************************************************
  change WordPress default mail_from information
  https://www.wpdaxue.com/change-wordpress-mail-from-info.html
******************************************************/
function new_from_name($email){
    $wp_from_name = get_option('blogname');
    return $wp_from_name;
}

function new_from_email($email) {
    $wp_from_email = get_option('admin_email');
    return $wp_from_email;
}
 
add_filter('wp_mail_from_name', 'new_from_name');
add_filter('wp_mail_from', 'new_from_email');
/*****************************************************
  移除无用信息
******************************************************/
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

function ashuwp_clean_theme_meta(){
  remove_action( 'wp_head', 'print_emoji_detection_script', 7, 1);
  remove_action( 'wp_print_styles', 'print_emoji_styles', 10, 1);
  remove_action( 'wp_head', 'rsd_link', 10, 1);
  remove_action( 'wp_head', 'wp_generator', 10, 1);
  remove_action( 'wp_head', 'feed_links', 2, 1);
  remove_action( 'wp_head', 'feed_links_extra', 3, 1);
  remove_action( 'wp_head', 'index_rel_link', 10, 1);
  remove_action( 'wp_head', 'wlwmanifest_link', 10, 1);
  remove_action( 'wp_head', 'start_post_rel_link', 10, 1);
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0);
  remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0);
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
  remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0);
  remove_action( 'wp_head', 'rest_output_link_wp_head', 10, 0);
  remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10, 1);
  remove_action( 'wp_head', 'rel_canonical', 10, 0);
}
add_action( 'after_setup_theme', 'ashuwp_clean_theme_meta' ); //清除wp_head带入的meta标签
function ashuwp_deregister_embed_script(){
  wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'ashuwp_deregister_embed_script' ); //清除wp_footer带入的embed.min.js

// 同时删除head和feed中的WP版本号
function ludou_remove_wp_version() {
  return '';
}
add_filter('the_generator', 'ludou_remove_wp_version');

/*****************************************************
 函数名称：wp_login_notify v1.0 by DH.huahua.
 函数作用：有登录wp后台就会email通知博主
******************************************************/
function wp_login_notify()
{
    date_default_timezone_set('PRC');
    $admin_email = get_bloginfo ('admin_email');
    $to = $admin_email;
    $subject = '你的博客空间登录提醒';
    $message = '<p>你好！你的博客空间(' . get_option("blogname") . ')有登录！</p>' .
    '<p>请确定是您自己的登录，以防别人攻击！登录信息如下：</p>' .
    '<p>登录名：' . $_POST['log'] . '<p>' .
    '<p>登录密码：' . $_POST['pwd'] .  '<p>' .
    '<p>登录时间：' . date("Y-m-d H:i:s") .  '<p>' .
    '<p>登录IP：' . $_SERVER['REMOTE_ADDR'] . '<p>';
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
}
add_action('wp_login', 'wp_login_notify');

/*****************************************************
 函数名称：wp_login_failed_notify v1.0 by DH.huahua.
 函数作用：有错误登录wp后台就会email通知博主
******************************************************/
function wp_login_failed_notify()
{
    date_default_timezone_set('PRC');
    $admin_email = get_bloginfo ('admin_email');
    $to = $admin_email;
    $subject = '你的博客空间登录错误警告';
    $message = '<p>你好！你的博客空间(' . get_option("blogname") . ')有登录错误！</p>' .
    '<p>请确定是您自己的登录失误，以防别人攻击！登录信息如下：</p>' .
    '<p>登录名：' . $_POST['log'] . '<p>' .
    '<p>登录密码：' . $_POST['pwd'] .  '<p>' .
    '<p>登录时间：' . date("Y-m-d H:i:s") .  '<p>' .
    '<p>登录IP：' . $_SERVER['REMOTE_ADDR'] . '<p>';
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
}
add_action('wp_login_failed', 'wp_login_failed_notify');

/*****************************************************
  函数名称：poet_str_replace v1.0 by BlueNoob
  函数作用：字符串替换
 ******************************************************/
function poet_str_replace($text){
        $replace = array(
                '–' => '--',
                'aaaa' => 'bbbb'
                );
        $text = str_replace(array_keys($replace), $replace, $text);
        return $text;
}
add_filter('the_content', 'poet_str_replace'); //正文
add_filter('the_excerpt', 'poet_str_replace'); //摘要
add_filter('comment_text', 'poet_str_replace'); //评论

/*****************************************************
    Plugin Name: Quotmarks Replacer
    Plugin URI: http://sparanoid.com/work/quotmarks-replacer/
    Description: Quotmarks Replacer disables wptexturize function that keeps all quotation marks and suspension points in half-width form.
    Version: 2.6.18
    Author: Tunghsiao Liu
 ******************************************************/
$qmr_work_tags = array(
  'the_title',             // http://codex.wordpress.org/Function_Reference/the_title
  'the_content',           // http://codex.wordpress.org/Function_Reference/the_content
  'the_excerpt',           // http://codex.wordpress.org/Function_Reference/the_excerpt
  // 'list_cats',          Deprecated. http://codex.wordpress.org/Function_Reference/list_cats
  'single_post_title',     // http://codex.wordpress.org/Function_Reference/single_post_title
  'comment_author',        // http://codex.wordpress.org/Function_Reference/comment_author
  'comment_text',          // http://codex.wordpress.org/Function_Reference/comment_text
  // 'link_name',          Deprecated.
  // 'link_notes',         Deprecated.
  'link_description',      // Deprecated, but still widely used.
  'bloginfo',              // http://codex.wordpress.org/Function_Reference/bloginfo
  'wp_title',              // http://codex.wordpress.org/Function_Reference/wp_title
  'term_description',      // http://codex.wordpress.org/Function_Reference/term_description
  'category_description',  // http://codex.wordpress.org/Function_Reference/category_description
  'widget_title',          // Used by all widgets in themes
  'widget_text'            // Used by all widgets in themes
  );
foreach ( $qmr_work_tags as $qmr_work_tag ) {
  remove_filter ($qmr_work_tag, 'wptexturize');
}

/**
* Disable the emoji's
*/
function disable_emojis() {
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );
/**
* Filter function used to remove the tinymce emoji plugin.
*/
function disable_emojis_tinymce( $plugins ) {
if ( is_array( $plugins ) ) {
return array_diff( $plugins, array( 'wpemoji' ) );
} else {
return array();
}
}
?>
