<?php
/**
Plugin Name: PNG Compressor
Plugin URI: https://wordpress.org/plugins/jpeg-png-compressor/
Description: Increase your website's SEO ranking by fasten page loading speed, number of visitors and ultimately your sales by optimizing any JPEG or PNG images on your website.
Author: premiumthemes
Version: 1.1
Author URI: https://www.pngcompressor.com/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define ('pjci_version', '1.1');

/*define global variables*/
define('pjci_url', plugin_dir_url( __FILE__ ));
define('pjci_request_url', "https://api.pngcompressor.com/v1.0/");
define('pjci_siteurl', "https://www.pngcompressor.com/");
$upload_dir = wp_upload_dir(); 
define('pjci_upload_dir_uri', $upload_dir['basedir']);
define('pjci_upload_dir_url', $upload_dir['baseurl']);

/*Add setting and bulk link in plugin option*/ 
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'pjci_action_links' );
function pjci_action_links( $links ) {
	$links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=png-compressor') ) .'">Settings</a>';
	$links[] = '<a href="'. esc_url( get_admin_url(null, 'upload.php?page=pjci-bulk-optimization') ) .'">Bulk Optimization</a>';
	return $links;
}

/*include files*/
require_once plugin_dir_path( __FILE__ ) . '/admin/pjci_admin_page.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/pjci_admin_media.php';

/*enqueue_scripts for admin*/
function pjci_admin_enqueue_script() {
wp_enqueue_script( 'pjci_admin_enqueue_scripts', pjci_url . 'admin/js/admin_media.js', __FILE__, '', true);
wp_enqueue_script( 'pjci_admin_enqueue_pie_chart', pjci_url . 'admin/js/pie-chart.js', __FILE__, '', true);
wp_enqueue_style( 'pjci_admin_enqueue_estyle', pjci_url . 'admin/css/admin_style.css', __FILE__, '', false);
if(isset($_GET['page'])){
	if($_GET["page"]== 'pjci-bulk-optimization' || $_GET["page"] == 'png-compressor'){
wp_enqueue_style( 'pjci_admin_enqueue_googleapis_fonts', 'https://fonts.googleapis.com/css?family=Roboto:400,500,700', '', false);
wp_enqueue_style( 'pjci_admin_enqueue_font_awesome', pjci_url . 'admin/css/font-awesome.min.css', __FILE__, '', false);
wp_enqueue_style( 'pjci_admin_enqueue_style', pjci_url . 'admin/css/style.css', __FILE__, '', false);
wp_enqueue_style( 'pjci_admin_enqueue_responsive', pjci_url . 'admin/css/responsive.css', __FILE__, '', false);
	}
}
}
add_action('admin_enqueue_scripts', 'pjci_admin_enqueue_script');

/*show other optimization plugins that cause incompatiblity*/ 
add_action( 'admin_notices', 'pjci_show_incompatible_notices' );
function pjci_show_incompatible_notices() {
pjci_incompatible_plugins();
}

function pjci_incompatible_plugins() {
$incompatible_plugins = array(
'Compress JPEG & PNG images' => 'tiny-compress-images/tiny-compress-images.php',
'CheetahO Image Optimizer' => 'cheetaho-image-optimizer/cheetaho.php',
'EWWW Image Optimizer' => 'ewww-image-optimizer/ewww-image-optimizer.php',
'Imagify' => 'imagify/imagify.php',
'Kraken Image Optimizer' => 'kraken-image-optimizer/kraken.php',
'ShortPixel Image Optimizer' => 'shortpixel-image-optimiser/wp-shortpixel.php',
'WP Smush' => 'wp-smushit/wp-smush.php',
'WP Smush Pro' => 'wp-smush-pro/wp-smush.php',
);
$incompatible_plugins_serach = array_filter( $incompatible_plugins, 'is_plugin_active' );
if ( count( $incompatible_plugins_serach ) > 0 ) {
$pjci_notice = '<div class="error notice pjci-notice incompatible-plugins">';
$pjci_notice .= '<h3>';
$pjci_notice .= esc_html__( 'PNG JPG Compress Images', 'pjci-compress-images' );
$pjci_notice .= '</h3>';
$pjci_notice .= '<p>';
$pjci_notice .= esc_html__(
'We found You have activated multiple image optimization plugins. This may lead to unhoped results. The following plugins were detected:','pjci-compress-images');
$pjci_notice .= '</p>';
$pjci_notice .= '<table>';
$pjci_notice .= '<tr><td class="bullet">#</td><td class="name">';
$pjci_notice .= esc_html__( 'PNG JPG Compress Images', 'pjci-compress-images' );
$pjci_notice .= '</td><td></td></tr>';
foreach ( $incompatible_plugins_serach as $pjci_name => $pjci_file ) {
$pjci_notice .= '<tr><td class="bullet">#</td><td class="name">';
$pjci_notice .= $pjci_name;
$pjci_notice .= '</td><td>';
$pjci_nonce = wp_create_nonce( 'deactivate-plugin_' . $pjci_file );
$pjci_query_string = 'action=deactivate&plugin=' . $pjci_file . '&_wpnonce=' . $pjci_nonce;
$pjci_url = admin_url( 'plugins.php?' . $pjci_query_string );
$pjci_notice .= '<a class="button button-primary" href="' . $pjci_url . '">';
$pjci_notice .= esc_html__( 'Deactivate' );
$pjci_notice .= '</a></td></tr>';
}
$pjci_notice .= '</table>';
$pjci_notice .= '</div>';
echo $pjci_notice;
}
}

/*Get all wordpress define image size*/ 
function pjci_get_image_sizes( $size = '' ) {
global $_wp_additional_image_sizes;
$sizes = array();
$get_intermediate_image_sizes = get_intermediate_image_sizes();
foreach( $get_intermediate_image_sizes as $_size ) {
if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
$sizes[ $_size ]['width'] = esc_attr(get_option( $_size . '_size_w' ));
$sizes[ $_size ]['height'] = esc_attr(get_option( $_size . '_size_h' ));
$sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
$sizes[ $_size ] = array( 
'width' => $_wp_additional_image_sizes[ $_size ]['width'],
'height' => $_wp_additional_image_sizes[ $_size ]['height'],
'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
);
}
}
if ( $size ) {
if( isset( $sizes[ $size ] ) ) {
return $sizes[ $size ];
} else {
return false;
}
}
return $sizes;
}

/*CURL POST Request*/ 
function pjci_requests_post($pjci_url,$pjci_body,$pjci_header,$boundary){
	if($pjci_url == "upload"){
		$url = pjci_request_url.$pjci_url;
		$headers = array('Content-Type'=>'multipart/form-data; boundary='.$boundary,'Authorization'=>$pjci_header,'Expect'=> '100-continue');	
	}else{
		$url = pjci_request_url.$pjci_url;
		if(!empty($pjci_header)){
			$headers = array('Content-Type' => 'application/json; charset=utf-8','Authorization'=>$pjci_header);
		}else{
			$headers = array('Content-Type'=>'application/json');	
		}
	}
$post_remote = wp_remote_post( $url,
    array(
      'method' => 'POST',
    'timeout' => 200,
    'redirection' => 5,
    'httpversion' => '1.0',
    'headers' => $headers,
    'blocking' => true,
    'sslverify'=> true,
        'body' => $pjci_body,
    )
  );
  
  if ( is_wp_error( $post_remote ) ) {
  $result['error_msg'] =$post_remote->get_error_message();
  $post_remote->get_error_message();
  }else{
  $result['response_body'] = wp_remote_retrieve_body($post_remote);
  $result['response_code'] = wp_remote_retrieve_response_code($post_remote);
  }
  return $result;
}

/*CURL GET REQUEST */ 
function pjci_requests_get($pjci_url,$pjci_header){
$url = pjci_request_url.$pjci_url;	
$get_remote = wp_remote_get($url,
 array(
  'timeout' => 200,
  'httpversion' => '1.0',
  'redirection' => 5,
  'blocking'    => true,
  'headers'     => array('Authorization'=> $pjci_header),
  'sslverify'   => true,
  )
  );
  if ( is_wp_error( $get_remote ) ) {
  $result['error_msg'] =$get_remote->get_error_message();
  }else{
   $result['response_body'] = wp_remote_retrieve_body($get_remote);
  $result['response_code'] = wp_remote_retrieve_response_code($get_remote);
  }
return $result;
}


/*CURL GET REQUEST */ 
function pjci_requests_get_img_data($pjci_url){
$url = $pjci_url;  
$get_remote = wp_remote_get($url,
 array(
  'timeout' => 200,
  'httpversion' => '1.0',
  'redirection' => 5,
  'blocking'    => true,
  'sslverify'   => true,
  )
  );
  if ( is_wp_error( $get_remote ) ) {
  $result['error_msg'] =$get_remote->get_error_message();
  }else{
   $result['response_body'] = wp_remote_retrieve_body($get_remote);
  $result['response_code'] = wp_remote_retrieve_response_code($get_remote);
  }
return $result;
}

/*Get compressed time difference*/
function pjci_time_ago($time){
$out = '';
$now = time();
$diff = $now - $time;
if($diff < 60)
return 'now';
elseif($diff < 3600)
return str_replace('{num}', ($out = round($diff / 60)), $out == 1 ? '{num} minute ago' : '{num} minutes ago');
elseif($diff < 3600 * 24)
return str_replace('{num}', ($out = round($diff / 3600)), $out == 1 ? '{num} hour ago' : '{num} hours ago');
elseif($diff < 3600 * 24 * 2)
return 'yesterday';
else
return strftime(date('Y', $time) == date('Y') ? '%e %b': '%e %b, %Y', $time);
}

/*Image formated size*/
function pjci_formatSizeUnits($bytes){
if ($bytes >= 1073741824){
$bytes = number_format($bytes / 1073741824, 2) . ' GB';
} elseif ($bytes >= 1048576){
$bytes = number_format($bytes / 1048576, 2) . ' MB';
} elseif ($bytes >= 1024){
$bytes = number_format($bytes / 1024, 2) . ' KB';
} elseif ($bytes > 1){
$bytes = $bytes . ' bytes';
} elseif ($bytes == 1){
$bytes = $bytes . ' byte';
} else {
$bytes = '0 bytes';
}
return $bytes;
}
