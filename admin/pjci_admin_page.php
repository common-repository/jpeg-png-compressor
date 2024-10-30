<?php 
/*Add admin menu links*/
add_action('admin_menu', 'pjci_menu_page');
function pjci_menu_page(){
add_options_page('PNG Compressor', 'PNG Compressor', 'manage_options', 'png-compressor', 'pjci_admin_page');
add_media_page('Bulk Optimization', 'Bulk Optimization', 'manage_options', 'pjci-bulk-optimization', 'pjci_bulk_optimization' );
}

/*Main PJCI setting page*/ 
function pjci_admin_page(){
global $wpdb;
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && isset($_POST['setting_nonce'])){

if(isset($_POST['pjci_compression_timing'])){
$pjci_compression_timing = sanitize_text_field($_POST['pjci_compression_timing']);
}
if(isset($_POST['pjci_sizes'])){
$pjci_sizes =array();	
foreach($_POST['pjci_sizes'] as $key => $value){
$pjci_sizes[$key] = sanitize_text_field($value);
}
}
if(!empty($pjci_compression_timing)){
update_option('pjci_compression_timing', $pjci_compression_timing);
} else {
update_option('pjci_compression_timing', "");
}
if(!empty($pjci_sizes) && isset($pjci_sizes)){
update_option('pjci_sizes', serialize($pjci_sizes));
} else {
update_option('pjci_sizes', "");
}
}
$unserialize_pjci_compression_timing =  esc_attr(get_option('pjci_compression_timing'));
$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
$pjci_total_credits_get =  esc_attr(get_option('pjci_total_credits'));
$pjci_plan_name =  esc_attr(get_option('pjci_plan_name'));
$pjci_quota_total =  esc_attr(get_option('pjci_quota_total'));
$pjci_quota_used =  esc_attr(get_option('pjci_quota_used'));
$pjci_quota_remaining =  esc_attr(get_option('pjci_quota_remaining'));
$pjci_responseCode =  esc_attr(get_option('pjci_responseCode'));
$pjci_response_message =  esc_attr(get_option('pjci_response_message'));
$pjci_response_errorMsg =  esc_attr(get_option('pjci_response_errorMsg'));
echo '<div class="png-main-outer" onbeforeunload="return "Are you really want to perform the action?"">
<form action="" id="pjci-settings" method="POST">
<div class="png-container">
<div class="main-head">
<h1>PNG Compressor</h1>
<p>Optimize your PNG and JPEG images automatically with PNG Compressor and speed up your website. 
</p>
</div>
<div class="small-blocks">
<div class="png-main-block">
<h3 class="block-heading"><span>My Account</span></h3>
<div class="png-inner-block">
<input type="hidden" id="get_pjci_api_key" value="'.$pjci_api_key.'">
<div '.(empty($pjci_api_key) ? 'style="display:none;"' : '').'  class="png-block key_approved">
<div class="status status-success" style="display: none;">
<ul class="accout-options">
<li>Your account status is <span style="color:#57a800;">connected</span>.</li>
<li>Your Plan: <span style="color:#c42d2d;"><strong class="pjci_plan_name">'.$pjci_plan_name.'</strong></span></li>
<li>Total images credit: <span style="color:#57a800;"><strong class="pjci_quota_total">'.$pjci_quota_total.'</strong></span></li>
<li>Total comressed images for this month: <span style="color:#57a800;"><strong class="pjci_quota_used">'.$pjci_quota_used.'</strong></span></li>
<li>Your pending image compressions for this month: <span style="color:#c42d2d;"><strong class="pjci_quota_remaining">'.$pjci_quota_remaining.'</strong></span></li>
</ul>
<a  href="javascript:void(0)" class="change_api_key common-btn green-btn">Change API key</a>
</div>
<div class="not_conncted not_conncted-success" style="display: none;"><p class="not_conncted">'.$pjci_response_errorMsg.'</p><p><a href="javascript:void(0)" class="change_api_key">Change API key</a></p></div>
<div class="retrieve_account" style="display: none;">
<div id="optimization-spinner" class="spinner" style="visibility:visible; float:left;"></div>
<p style="font-weight:bold;">Retrieving account status</p>
</div>
<div class="update" style="display: none;">
<h4>Change your API key</h4>
<p class="introduction">Enter your API key. If you have lost your key, go to your <a href="'.pjci_siteurl.'/my-apis" target="_blank">API dashboard</a> to retrieve it.</p>
<span class="error_replace_api" style="color:#ff0000; margin-bottom:10px;"></span>
<span class="success_replace_api" style="color:#41871d; margin-bottom:10px;"></span>
<div class="register-form">
<div class="form-group">
<span class="fa fa-key"></span>
<input class="form-field" id="pjci_api_key" name="pjci_api_key" size="35" value="'.$pjci_api_key.'" type="text" placeholder="Enter Your API Key">
<input type="hidden" value="'.admin_url('admin-ajax.php').'" id="admin_ajax_url">
</div>
<button type="button" class="common-btn verify_key" data-pjci-action="update-key">Save
<div id="optimization-spinner" class="spinner_button"></div>
</button>
</div>
<p class="message"></p>
<p><a href="javascript:void(0)" class="cancel_cls">Cancel</a></p>
</div>
</div>
<div class="png-block" id="already-account" style="display: none;">
<h3>Already have an account?</h3>
<p>Enter your API key. Go to your <a href="'.pjci_siteurl.'/my-apis">API dashboard</a> to retrieve it.</p>
<span class="error_replace_api" style="color:#ff0000; margin-bottom:10px;"></span>
<span class="success_replace_api" style="color:#41871d; margin-bottom:10px;"></span>
<div class="register-form">
<div class="form-group">
<span class="fa fa-key"></span>
<input id="pjci_api_key_first" name="pjci_api_key" class="form-field" type="text" value="" placeholder="Enter Your API Key">
<input type="hidden" value="'.admin_url('admin-ajax.php').'" id="admin_ajax_url">
</div>
<span class="message"></span>
<button type="button" class="common-btn save_verify_key" data-pjci-action="update-key">
Save <div id="optimization-spinner" class="spinner_button"></div></button>';
echo "<a class='account-link no-account'>Don't have an account?</a>";
echo '</div>
</div>
<div '.(!empty($pjci_api_key) ? 'id="pjci-register-account"' : '').' class="png-block register-block wide">
<h3>Register new account</h3>
<p>Provide your name and email address to start optimizing images.</p>
<div class="register-form">
<span class="error_replace" style="color:#ff0000; margin-bottom:10px;"></span>
<span class="success_replace" style="color:#41871d; margin-bottom:10px;"></span>
<span id="head"></span>
<div class="form-group">
<span class="fa fa-user"></span>
<input class="form-field" id="pjci_api_key_name" name="pjci_api_key_name" placeholder="User name" value="" type="text">
</div>
<span id="p1"></span>
<div class="form-group">
<span class="fa fa-envelope"></span>
<input class="form-field" id="pjci_api_key_email" name="pjci_api_key_email" placeholder="Email Address" value="" type="text">
<input type="hidden" value="'.admin_url('admin-ajax.php').'" id="admin_ajax_url">
</div>
<span id="p3"></span>
<span class="message"></span>
<button type="button" class="common-btn green-btn create_account"><div id="optimization-spinner" class="spinner_button"></div>Register  Account</button>
<a class="account-link already-account">Already have an account?</a>
</div>
</div>
</div>                
</div>
<div class="png-main-block">
<h3 class="block-heading"><span>How PNG Compressor should work?</span></h3>
<div class="png-inner-block">
<div class="compress-options">
<label for="rb1" class="compress-label '.($unserialize_pjci_compression_timing == 'background' ? 'active' : '').' ">
<div class="label-btn">
<span>
<input autocomplete="off" id="rb1" name="pjci_compression_timing" value="background" type="radio" '.($unserialize_pjci_compression_timing == 'background' ? 'checked' : '').'>
<i></i>
</span>
</div>
<div class="label-content">
<h4>Compress new images silently <span style="color:#57a800;">(Recommended)</span></h4>
<h6>This is the fastest way, but it can make some issues with other image related plugins.</h6>
</div>
</label>
<label for="rb2" class="compress-label '.($unserialize_pjci_compression_timing == 'auto' ? 'active' : '').' ">
<div class="label-btn">
<span>
<input autocomplete="off" id="rb2" name="pjci_compression_timing" value="auto" type="radio" '.($unserialize_pjci_compression_timing == 'auto' ? 'checked' : '').'>
<i></i>
</span>
</div>
<div class="label-content">
<h4>Compress new images while uploading</h4>
<h6>Image upload will take time, but it will provides best compatibility with other plugins.</h6>
</div>
</label>
<label for="rb3" class="compress-label '.($unserialize_pjci_compression_timing == 'manual' ? 'active' : '').' ">
<div class="label-btn">
<span>
<input autocomplete="off" id="rb3" name="pjci_compression_timing" value="manual" type="radio" '.($unserialize_pjci_compression_timing == 'manual' ? 'checked' : '').'>
<i></i>
</span>
</div>
<div class="label-content">
<h4>Do not optimize new images automatically</h4>
<h6>You can manually select the images you would like to compress in the media library.</h6>
</div>
</label>
</div>
</div>                
</div>
</div>
<div class="png-main-block">
<h3 class="block-heading"><span>Avaliable sizes for compression</span></h3>
<div class="png-inner-block">
<h4>Select your website desired image sizes to be compressed</h4>
<h6>Wordpress generates resized versions of every image. Choose which sizes you would like to compress.</h6>
<div class="select-size-block">
<div class="select-size">
<ul class="clearfix">
<li>
<label class="checkbox-label">
<span class="select-chkbox">
<input class="original_image" id="pjci_sizes_0" name="pjci_sizes[original]" value="on" type="checkbox" '.(!empty($unserialize_pjci_sizes['original']) && $unserialize_pjci_sizes['original'] == 'on' ? 'checked' : '').'>
<i></i>
</span>
Original image (overwritten by compressed image)
</label>
</li>';
$size = get_intermediate_image_sizes();
foreach ($size as $key => $value) {
echo '<li>
<label class="checkbox-label">
<span class="select-chkbox">
<input id="pjci_sizes_'.$value.'" name="pjci_sizes['.$value.']" value="on" type="checkbox" '.(!empty($unserialize_pjci_sizes[$value]) && $unserialize_pjci_sizes[$value] == 'on' ? 'checked' : '').'>
<i></i></span>';
echo ''.$value.' - '.''; 
$sizes = pjci_get_image_sizes($value);
if($value == "medium_large"){
echo '768x?';
} else {
echo $sizes['width'].'x'.$sizes['height']; 
}
echo '</label>
</li>';
}
echo '</ul>
</div>
</div>
</div>                
</div>
<input type="hidden" value="'.wp_create_nonce('setting_nonce').'" name="setting_nonce"/>
<input name="submit" id="submit" class="pjci-button-primary common-btn" value="Save Changes" type="submit">
</form>
</div>
</div>';
}

/*PJCI Bulk Optimization Page*/ 
function pjci_bulk_optimization() {
$query_img_args = array(
'post_type' => 'attachment',
'post_mime_type' =>array(
'jpg|jpeg|jpe' => 'image/jpeg',
'gif' => 'image/gif',
'png' => 'image/png',
),
'post_status' => 'inherit',
'posts_per_page' => -1,
);
$query_img = new WP_Query( $query_img_args );
$sum = 0;
if(empty(get_option('pjci_sizes'))){
$unserialize_pjci_sizes = array();	
} else {
$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
}
$attachment_count = array();
$get_attachment_ID_first = "";
foreach ($query_img->posts as $key => $value) {
$get_attachment_ID = $value->ID;
$attachment_count[] = $value->ID;
$get_attachment_ID_first .= $value->ID.',';
}
$save_option_fs = array();
$set = "";
$sum_count=$sum_count_db=$save_option_db_count=0;
foreach ($attachment_count as $key => $value) {
$post_id = $value;
$get_db_data = unserialize(get_post_meta( $post_id, 'pjci_compress_images', true ));
if(empty($get_db_data)){
} else {
$pjci_size_count = count($unserialize_pjci_sizes);
$db_count = count($get_db_data);
if($db_count >= $pjci_size_count){
$save_option_db_count += count($unserialize_pjci_sizes);
} else {
$save_option_db_count += count($get_db_data);
}
}
}
$save_option_set = array();
foreach ($attachment_count as $key => $value) {
$post_id = $value;
$local_ftp_path = get_attached_file($post_id);
$live_web_path = wp_get_attachment_url($post_id);
$featured_img_fpath = wp_get_attachment_metadata($post_id);
$img_base_name = basename(get_attached_file( $post_id));

if(!empty($featured_img_fpath)){
$save_option_fs = array();
foreach ($unserialize_pjci_sizes as $key => $value) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
if(array_key_exists($key, $featured_img_fpath['sizes'])){
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
}
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$filesize = round(filesize($full_ftp_imgName), 1);
$save_option_fs[$key] = $filesize;
}
$save_option_fs_count = count(array_unique($save_option_fs));
$save_option_set[$post_id] = $save_option_fs_count;
$sum_count+= $save_option_fs_count;
}
}
$compressed_img_total = $sum_count;
$total_mb =  round($sum / 1e+6, 2);
$total_image_count = $query_img->post_count;
if(empty(get_option('pjci_sizes'))){
$unserialize_pjci_sizes = array();	
} else {
$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
}
$send_attachment_ID=$send_attachment_ID1="";
$attachment_count = array();
foreach ($save_option_set as $key => $value) {
$get_db_data = unserialize(get_post_meta( $key, 'pjci_compress_images', true ));
if(!empty($get_db_data)){
$total_db_fs = array();
foreach ($get_db_data as $key_data => $get_db_fatch) {
if(in_array($get_db_fatch["image_compress_option"], array_keys($unserialize_pjci_sizes))) {
$total_db_fs[] = $get_db_fatch;
}
}
$total_db_count = count($total_db_fs);
if($value != $total_db_count){
$send_attachment_ID .= $key.',';
$send_attachment_ID1 .= $key.',';
$attachment_count[] = $key;
}
}
}

$query_img_ids = array(
'post_type' => 'attachment',
'post_mime_type' =>array(
'jpg|jpeg|jpe' => 'image/jpeg',
'gif' => 'image/gif',
'png' => 'image/png',
),
'meta_query' => array(
array(
'key' => 'pjci_compress_images',
'compare' => 'NOT EXISTS',
)
),
'post_status' => 'inherit',
'posts_per_page' => -1,
);
$query_img_ids_fetch = new WP_Query( $query_img_ids );
if($query_img_ids_fetch->post_count != 0){
$send_attachment_ID="";
$attachment_count = array();
foreach ($query_img_ids_fetch->posts as $key => $query_img_ids_get) {
$send_attachment_ID .= $query_img_ids_get->ID.',';
$attachment_count[] = $query_img_ids_get->ID;
}
}
$send_attachment_ID = $send_attachment_ID1.''.$send_attachment_ID;
$send_attachment_ID = implode(',',array_unique(explode(',', $send_attachment_ID)));
$total_image_count_uncompress = $query_img_ids_fetch->post_count;
$get_backend_size_count = count($unserialize_pjci_sizes);
$save_option_fs = array();
$set = "";
$sum_count = 0;
foreach ($attachment_count as $key => $value) {
$post_id = $value;
$local_ftp_path = get_attached_file($post_id);
$live_web_path = wp_get_attachment_url($post_id);
$featured_img_fpath = wp_get_attachment_metadata($post_id);
$img_base_name = basename(get_attached_file( $post_id));
if(!empty($featured_img_fpath)){
$save_option_fs = array();
foreach ($unserialize_pjci_sizes as $key => $value) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
if(array_key_exists($key, $featured_img_fpath['sizes'])){
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
}
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$filesize = round(filesize($full_ftp_imgName), 1);
$save_option_fs[$key] = $filesize;
}
$save_option_fs_count = count(array_unique($save_option_fs));
$save_option_set[$post_id] = $save_option_fs_count;
$sum_count+= $save_option_fs_count;
}
}
if(empty($save_option_db_count)){
$uncompressed_img = $sum_count;
} else {
$uncompressed_img = $compressed_img_total-$save_option_db_count;	
}
$uncompressed_img_compress = $compressed_img_total-$uncompressed_img;
global $wpdb;
$metas = $wpdb->get_results( 
$wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta where meta_key = %s", 'pjci_compress_images')
);
if(empty($metas)){
$metas = array();
} else {
$metas = $metas;
} 
$sum_init=$sum_compress=0;
foreach ($metas as $key => $meta) {
$sum_init1=$sum_compress1=0;
if(empty(unserialize(unserialize($meta->meta_value)))){
$unserialize_metaset = array();
} else {
$unserialize_metaset = unserialize(unserialize($meta->meta_value));
} 
foreach ($unserialize_metaset as $key => $value) {
$originalSizeInKB = str_replace(" bytes","",$value['originalSizeInByte']);
$compressSizeInKB = str_replace(" bytes","",$value['compressSizeInByte']);
$sum_init1 += $originalSizeInKB;
$sum_compress1 += $compressSizeInKB;
}
$sum_init += $sum_init1;
$sum_compress += $sum_compress1;
}
if(!empty($sum_init)){
$initial_size_ar = pjci_formatSizeUnits($sum_init);
}
if(!empty($sum_compress)){
$sum_compress_ar = pjci_formatSizeUnits($sum_compress);
}
if(!empty($sum_init) && !empty($sum_compress)){
$remain_percentage = $sum_init-$sum_compress;
$remain_percentage_balance = round($remain_percentage*100/$sum_init, 2);
} else {
$remain_percentage_balance = 0;	
}
echo '<div class="png-main-outer" onbeforeunload="return "Are you really want to perform the action?"">
<div class="png-container">
<div class="main-head">
<h1>PNG Compressor Bulk Optimization</h1>
<p>Optimize your PNG and JPEG images automatically with PNG Compressor and speed up your website.</p>
</div>
<div class="png-main-block">
<div class="png-inner-block small-blocks">
<div class="png-block">
<h3>Available Images</h3>
<p>You can start optimising your entire website image library. Please press the green button to start improving your website speed.</p>
<div class="uploadImg-block">
<div class="upload-box">
<h5>Uploaded images</h5>
<h1>'.$total_image_count.'</h1>
</div>
<div class="upload-box uncommpress">
<h5>Uncompressed images</h5>';
if ($uncompressed_img < 0){
$uncompressed_img = '0';
} else {
$uncompressed_img = $uncompressed_img;
}
echo '<h1>'.$uncompressed_img.'</h1>
</div>
</div>
</div>
<div class="png-block">
<h3>Total Savings</h3>
<p>Analysis report based upon available JPEG and PNG images in your media library.</p>
<div class="saving-block clearfix">
<div class="saving-chart">  
<div id="pjci-optimization-chart" class="chart" data-percent="'.$remain_percentage_balance.'">
<div class="value">
<div class="percentage" id="savings-percentage"><span  class="pie-value"></span></div>
<div class="label">savings</div>
</div>
</div>
</div>
<div class="saving-detail">
<p><strong>'.$uncompressed_img_compress.'</strong> image sizes optimized</p>';
if(!empty($initial_size_ar)){
echo '<p><strong>'.$initial_size_ar.'</strong> initial size</p>';
} else {
echo '<p><strong>'.$total_mb.'</strong> initial size</p>';
}
if(!empty($sum_compress_ar)){
echo '<p><strong>'.$sum_compress_ar.'</strong> current size</p>';
} else {
echo '<p><strong>'.$total_mb.'</strong> current size</p>';
}
echo '</div>
</div>
</div>
</div>
</div>
<p class="remember-msg"><strong>Please Note:</strong> In order to use Bulk optimisation please stay on this page until the process is not completed. Once its done you can continue working where you were.</p>
<div class="bulk-compress-bar text-center">
<div class="png-block m-b-30">';
if(!empty($uncompressed_img)){
echo '<div class="progressbar" id="Progress_Status">
<div id="myprogressBar" class="progress"></div>
<div class="numbers">
<span id="optimized-so-far">0</span>/<span>'.$uncompressed_img.'</span>
<span id="percentage">(0%)</span>
</div>
</div>';
} else {
echo '<div class="progressbar" id="Progress_Status">';
if($uncompressed_img_compress == 0){
echo '<div id="myprogressBar" class="progress" style="width:0%"></div>';	
} else {
echo '<div id="myprogressBar" class="progress" style="width:100%"></div>';
}
echo '<div class="numbers">
<span id="optimized-so-far">'.$uncompressed_img_compress.'</span>/<span>'.$uncompressed_img_compress.'</span>';
if($uncompressed_img_compress == 0){
echo '<span id="percentage">(0%)</span>';
} else {
echo '<span id="percentage">(100%)</span>';
}
echo '</div>
</div>';
}
echo '</div>';
$quota_remaining =  esc_attr(get_option('pjci_quota_remaining'));
if(!empty($uncompressed_img) && $quota_remaining != "0" && current_user_can( 'upload_files' )){
echo '<input type="hidden" name="bulk_optimisation_nonce" value="'.wp_create_nonce('bulk_optimisation_nonce').'"/><a href="javascript:void(0);" data-total-img="'.$uncompressed_img.'" data-size-count="'.$get_backend_size_count.'" data-send-IDs="'.$send_attachment_ID.'" data-first-IDs="'.$get_attachment_ID_first.'" data-ajax-url="'.admin_url("admin-ajax.php").'" id="id-start" class="common-btn green-btn btn-large">Bulk Optimization</a>';
}
echo '</div>
</div>
</div>';
}
/*Register account with pngcompressor*/ 
add_action( 'wp_ajax_pjci_register_account', 'pjci_register_account' );
function pjci_register_account() {
if(isset($_POST['register_nonce'])){	
$name = sanitize_text_field($_POST['name']);
$email = sanitize_email($_POST['email']);
$encode_data = array('email' => $email, 'your_name' => $name);
$pjci_body = json_encode($encode_data);
$pjci_url = "register";
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$pjci_body,'','');
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = (int)$pjci_request_response_array_set['response_code'];
$response_success = $pjci_request_response->success;
$response_message = $pjci_request_response->message;
$response_errorMsg = $pjci_request_response->errorMsg;
$Key = $pjci_request_response->apikey;
if($response_code == "200"){
update_option("pjci_api_key", $Key);
$results['response_code'] = $response_code;
$results['response_success'] = $response_success;
$results['response_message'] = $response_message;
$results['Key'] = $Key;
$size = get_intermediate_image_sizes();
$update_size_option = array();
foreach ($size as $key => $value) {
$update_size_option[$value] = "on";
}
$admin_original_size = array('original' => 'on');
$final_image_set = $admin_original_size+$update_size_option;
update_option('pjci_compression_timing', 'background');
update_option('pjci_sizes', serialize($final_image_set));
} elseif($response_code == "202"){
$results['response_code'] = $response_code;
$results['response_success'] = $response_success;
$results['response_message'] = $response_message;
$results['response_errorMsg'] = $response_errorMsg;
}
echo json_encode($results);
wp_die();
}
}

/*Verify Key with pngcompressor*/
add_action( 'wp_ajax_pjci_api_key_verify', 'pjci_api_key_verify' );
function pjci_api_key_verify() {
if(isset($_POST['verify_nonce'])){	
$pjci_header = sanitize_text_field($_POST['api_key']);
$pjci_url = "user-status";
$pjci_request_response_array_set = pjci_requests_get($pjci_url,$pjci_header);
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = (int)$pjci_request_response_array_set['response_code'];
if($response_code == "200"){
$response_success = $pjci_request_response->success;
$response_key_active = $pjci_request_response->key_active;
$response_plan_name = $pjci_request_response->plan_name;
$response_quota_total = $pjci_request_response->quota_total;
$response_quota_used = $pjci_request_response->quota_used;
$response_quota_remaining = $pjci_request_response->quota_remaining;
$response_errorMsg = $pjci_request_response->errorMsg;
$results['responseCode'] = $response_code;
$results['response_plan_name'] = $response_plan_name;
$results['response_quota_total'] = $response_quota_total;
$results['response_quota_used'] = $response_quota_used;
$results['response_quota_remaining'] = $response_quota_remaining;
$results['Key'] = $pjci_header;
update_option('pjci_responseCode', $response_code);
update_option('pjci_total_credits', $response_total_credits);
update_option('pjci_plan_name', $response_plan_name);
update_option('pjci_quota_total', $response_quota_total);
update_option('pjci_quota_used', $response_quota_used);
update_option('pjci_quota_remaining', $response_quota_remaining);
update_option("pjci_api_key", $pjci_header);
} elseif($response_code == "401"){
$error_msg = $pjci_request_response_array_set['error_msg'];
$response_success = $pjci_request_response->success;
$response_message = $pjci_request_response->message;
$response_errorMsg = $pjci_request_response->errorMsg;
$response_error_msg = $error_msg;
$results['responseCode'] = $response_code;
$results['response_response_message'] = $response_message;
$results['response_response_errorMsg'] = $response_errorMsg;
$results['response_error_msg'] = $response_error_msg;
$results['Key'] = $pjci_header;
update_option("pjci_api_key", $pjci_header);
update_option('pjci_responseCode', $response_code);
update_option('pjci_response_message', $response_message);
update_option('pjci_response_errorMsg', $response_errorMsg);
}
echo json_encode($results);
wp_die();
}
}

/*Compress Single Image from pngcompressor*/ 
add_action( 'wp_ajax_pjci_single_img_compress', 'pjci_single_img_compress' );
function pjci_single_img_compress() {
if (!current_user_can( 'upload_files' )){
$message = esc_html__("You don't have permission to upload files.");
echo json_encode($message);
wp_die();
}	
$post_id = (int)$_POST['post-id'];
$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
$local_ftp_path = get_attached_file($post_id);
$live_web_path = wp_get_attachment_url($post_id);
$featured_img_fpath = wp_get_attachment_metadata($post_id);
$img_base_name = basename(get_attached_file( $post_id));
if(!empty($featured_img_fpath)){
$value_set = array();
foreach ($unserialize_pjci_sizes as $key => $value) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
if(array_key_exists($key, $featured_img_fpath['sizes'])){
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
}
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$filesize = round(filesize($full_ftp_imgName), 1);
$value_set[$key] = $filesize;
}
}
$final_image_set = array_unique($value_set);
$pjci_combine_data = array();
$result=array();	
update_post_meta( $post_id, 'pjci_single_images_check', "yes");
$pjci_single_images_check = 0;
$pjci_len = count($final_image_set);
foreach ($final_image_set as $key => $image_all) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
$last_web_img_name = basename($live_web_path);
$links = str_replace($last_web_img_name, $image_last_name, $live_web_path);
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$img_url = array(
'imageurl' => $links
);
$pjci_url = "url";
$str = json_encode($img_url,  JSON_UNESCAPED_SLASHES);
$pjci_header = esc_attr(get_option('pjci_api_key'));
$get_db_data = unserialize(get_post_meta( $post_id, 'pjci_compress_images', true ));
if(empty($get_db_data)){
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$str,$pjci_header,'');
} else {
if (!in_array($image_last_name, array_column($get_db_data, 'file_name'))){
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$str,$pjci_header,'');
}
}
if(!empty($pjci_request_response_array_set)){
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = (int)$pjci_request_response_array_set['response_code'];
if($response_code == "401" || $response_code == "412" || $response_code == "415" || $response_code == "406" || $response_code == "413"){
if(empty($get_db_data)){	
update_post_meta( $post_id, 'pjci_compress_images', serialize($result));
} else {
update_post_meta( $post_id, 'pjci_compress_images', serialize($get_db_data));	
}
wp_die();
} else {
if($response_code == "422" || $response_code == "404" || $response_code == "408"){
$payload=$boundary='';	
$boundary = wp_generate_password( 24,false,false );
 $payload .= '--'.$boundary;
 $payload .= "\r\n";
 $payload .= 'Content-Disposition: form-data; name="' . 'image' .
'"; filename="' . basename( $full_ftp_imgName ) . '"' . "\r\n";
 $payload .= 'Content-Type: image/jpeg' . "\r\n";
 $payload .= "\r\n";
 $payload .= file_get_contents( $full_ftp_imgName );
 $payload .= "\r\n";
 $payload .= '--' . $boundary . '--';
$pjci_url = 'upload'; 
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$payload,$pjci_header,$boundary);
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = $pjci_request_response_array_set['response_code'];
$payload=$boundary='';	
	}
}
if(!empty($pjci_request_response) && $response_code == "200"){
$success = $pjci_request_response->success;
$file_name = $pjci_request_response->file_name;
$original_size = (int)$pjci_request_response->original_size;
$compress_size = (int)$pjci_request_response->compress_size;
$compress_percentage = $pjci_request_response->compress_percentage;
$compress_path = $pjci_request_response->compress_path;
$created = $pjci_request_response->created;
//$get_image_data = file_get_contents($compress_path);
$pjci_request_response_img = pjci_requests_get_img_data($compress_path);
$get_image_data = $pjci_request_response_img['response_body'];
$upload_image=file_put_contents($full_ftp_imgName, $get_image_data);
if(!empty($upload_image)){
$db_request = array(
"response_code" => $response_code,
"file_name" => $file_name,
"originalSizeInByte" => $original_size,
"compressSizeInByte" => $compress_size,
"percentage" => $compress_percentage,
"image_compress_option" => $key,
"time" => $created
);
if(empty($get_db_data)){
if(empty($result)){
$result[] = array_merge($pjci_combine_data, $db_request);
} else {
if (!in_array($file_name, array_column($result, 'file_name'))){
$result[] = array_merge($pjci_combine_data, $db_request);
}
}
if ($key  === @end(array_keys($final_image_set))) {
update_post_meta( $post_id, 'pjci_compress_images', serialize($result));
}
} else {
$save_db_data = array($db_request);
if (in_array($file_name, array_column($get_db_data, 'file_name'))){} else {
$save_db_data = array_merge_recursive($get_db_data, $save_db_data);
update_post_meta( $post_id, 'pjci_compress_images', serialize($save_db_data));
}
}
}
}
}

if ($pjci_single_images_check == $pjci_len - 1) {
delete_post_meta($post_id, 'pjci_single_images_check');
}

$pjci_single_images_check++;
}

/*Popup Set for single image compress*/ 
$get_db_data = unserialize(get_post_meta( $post_id, 'pjci_compress_images', true ));
$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
if(empty($get_db_data)){
$total_size_count = "0";	
} else {
$total_db_fs = array();
foreach ($get_db_data as $key => $get_db_fatch) {
if(in_array($get_db_fatch["image_compress_option"], array_keys($unserialize_pjci_sizes))) {
$total_db_fs[] = $get_db_fatch;
}
}
$total_size_count = count($total_db_fs);
}
$sum=0;
$percentage = array();
foreach ($get_db_data as $key => $get_db_fatch) {
$percentage[] = $get_db_fatch["percentage"];
}
$total_img = count($percentage);
foreach ($percentage as $key => $percentage_val) {
$percentage_expload = explode('%', $percentage_val);
$sum+= $percentage_expload[0];
}
$results['response_total_percentage'] = round($sum/$total_img, 2);
$results['total_size_count'] = $total_size_count;
echo json_encode($results);
wp_die();
}

/*When image compress action is auto OR background*/ 
if(esc_attr(get_option('pjci_compression_timing')) == "auto" || esc_attr(get_option('pjci_compression_timing')) == "background" ){
add_filter( 'wp_generate_attachment_metadata', 'pjci_on_upload_image', 10, 2 );
}

function pjci_on_upload_image($metadata, $attachment_id) {
global $pagenow;
if(($pagenow == 'async-upload.php' && esc_attr(get_option('pjci_compression_timing')) == "auto") || ($pagenow == 'admin-ajax.php' && esc_attr(get_option('pjci_compression_timing')) == "auto")){
$allowed_file_types = array("jpg", "jpeg", "png", "gif");
$file_url = wp_get_attachment_url( $attachment_id );
$filetype = wp_check_filetype( $file_url );
$uploaded_file_type = $filetype['ext'];
if(in_array($uploaded_file_type, $allowed_file_types)) {
$post_id = $attachment_id;
$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
$local_ftp_path = get_attached_file($post_id);
$live_web_path = wp_get_attachment_url($post_id);
$featured_img_fpath = wp_get_attachment_metadata($post_id);
$img_base_name = basename(get_attached_file( $post_id));
if(!empty($featured_img_fpath)){
$value_set = array();
foreach ($unserialize_pjci_sizes as $key => $value) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
if(array_key_exists($key, $featured_img_fpath['sizes'])){
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
}
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$filesize = round(filesize($full_ftp_imgName), 1);
$value_set[$key] = $filesize;
}
}
$final_image_set = array_unique($value_set);
$pjci_combine_data = array();
$result=array();
foreach ($final_image_set as $key => $image_all) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
$last_web_img_name = basename($live_web_path);
$links = str_replace($last_web_img_name, $image_last_name, $live_web_path);
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$img_url = array(
'imageurl' => $links
);
$pjci_url = "url";
$str = json_encode($img_url,  JSON_UNESCAPED_SLASHES);
$pjci_header = esc_attr(get_option('pjci_api_key'));
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$str,$pjci_header,'');
if(!empty($pjci_request_response_array_set)){
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = (int)$pjci_request_response_array_set['response_code'];
if($response_code == "401" || $response_code == "412" || $response_code == "415" || $response_code == "406" || $response_code == "413"){
update_post_meta( $post_id, 'pjci_compress_images', serialize($result));
return $metadata;
wp_die();
} else {
if($response_code == "422" || $response_code == "404" || $response_code == "408" ){
$payload=$boundary='';	
$boundary = wp_generate_password( 24,false,false );
 $payload .= '--'.$boundary;
 $payload .= "\r\n";
 $payload .= 'Content-Disposition: form-data; name="' . 'image' .
'"; filename="' . basename( $full_ftp_imgName ) . '"' . "\r\n";
 $payload .= 'Content-Type: image/jpeg' . "\r\n";
 $payload .= "\r\n";
 $payload .= file_get_contents( $full_ftp_imgName );
 $payload .= "\r\n";
 $payload .= '--' . $boundary . '--';
$pjci_url = 'upload'; 
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$payload,$pjci_header,$boundary);
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = $pjci_request_response_array_set['response_code'];
$payload=$boundary='';	
	}
}
if(!empty($pjci_request_response) && $response_code == "200"){
$success = $pjci_request_response->success;
$file_name = $pjci_request_response->file_name;
$original_size = (int)$pjci_request_response->original_size;
$compress_size = (int)$pjci_request_response->compress_size;
$compress_percentage = $pjci_request_response->compress_percentage;
$compress_path = $pjci_request_response->compress_path;
$created = $pjci_request_response->created;
$pjci_request_response_img = pjci_requests_get_img_data($compress_path);
$get_image_data = $pjci_request_response_img['response_body'];
$upload_image=file_put_contents($full_ftp_imgName, $get_image_data);
if(!empty($upload_image)){
$db_request = array(
"response_code" => $response_code,
"file_name" => $file_name,
"originalSizeInByte" => $original_size,
"compressSizeInByte" => $compress_size,
"percentage" => $compress_percentage,
"image_compress_option" => $key,
"time" => $created
);
if(empty($result)){
$result[] = array_merge($pjci_combine_data, $db_request);
} else {
if (!in_array($file_name, array_column($result, 'file_name'))){
$result[] = array_merge($pjci_combine_data, $db_request);
}
}
}
if ($key  === @end(array_keys($final_image_set))) {
update_post_meta( $post_id, 'pjci_compress_images', serialize($result));
}
}
}
}
return $metadata;
}
} elseif(($pagenow == 'async-upload.php' && esc_attr(get_option('pjci_compression_timing')) == "background") || ($pagenow == 'admin-ajax.php' && esc_attr(get_option('pjci_compression_timing')) == "background")){
pjci_action_add_background($metadata, $attachment_id);
return $metadata;
}
}

function pjci_action_add_background($metadata, $attachment_id) {
$context     = 'wp';
$action      = 'pjci_async_optimize_upload_new_image';
$_ajax_nonce = wp_create_nonce( 'new_media-' . $attachment_id );
$body = compact( 'action', '_ajax_nonce', 'metadata', 'attachment_id', 'context' );

$args = array(
'timeout'   => 0.01,
'blocking'  => false,
'body'      => $body,
'cookies'   => isset( $_COOKIE ) && is_array( $_COOKIE ) ? $_COOKIE : array(),
'sslverify' => apply_filters( 'https_local_ssl_verify', false ),
);	
if ( defined( 'XMLRPC_REQUEST' ) && get_current_user_id() ) {
$rpc_hash = md5( maybe_serialize( $body ) );
set_transient( 'pjci_rpc_' . $rpc_hash, get_current_user_id(), 10 );
}
if ( getenv( 'WORDPRESS_HOST' ) !== false ) {
wp_remote_post( getenv( 'WORDPRESS_HOST' ) . '/wp-admin/admin-ajax.php', $args );
} else {
$responsess= wp_remote_post( admin_url( 'admin-ajax.php' ), $args );
}
}

add_action('admin_init', 'pjci_admin_init');
function pjci_admin_init() {
add_filter( 'wp_ajax_pjci_async_optimize_upload_new_image','pjci_async_optimize_upload_new_image');	

}

function pjci_async_optimize_upload_new_image() {
$attachment_id = (int)$_POST['attachment_id'];
$metadata = $_POST['metadata'];
$allowed_file_types = array("jpg", "jpeg", "png", "gif");
$file_url = wp_get_attachment_url( $attachment_id );
$filetype = wp_check_filetype( $file_url );
$uploaded_file_type = $filetype['ext'];
if(in_array($uploaded_file_type, $allowed_file_types)) {
$post_id = $attachment_id;
update_post_meta( $post_id, 'pjci_check_bgcompress', "yes");
$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));

$local_ftp_path = get_attached_file($post_id);
$live_web_path = wp_get_attachment_url($post_id);
$featured_img_fpath = wp_get_attachment_metadata($post_id);
$img_base_name = basename(get_attached_file( $post_id));
if(!empty($featured_img_fpath)){
$value_set = array();
foreach ($unserialize_pjci_sizes as $key => $value) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
if(array_key_exists($key, $featured_img_fpath['sizes'])){
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
}
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$filesize = round(filesize($full_ftp_imgName), 1);
$value_set[$key] = $filesize;
}
}

$final_image_set = array_unique($value_set);
$pjci_combine_data = array();
$result=array();
foreach ($final_image_set as $key => $image_all) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
$last_web_img_name = basename($live_web_path);
$links = str_replace($last_web_img_name, $image_last_name, $live_web_path);
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$img_url = array(
'imageurl' => $links
);
$pjci_url = "url";
$str = json_encode($img_url,  JSON_UNESCAPED_SLASHES);
$pjci_header = esc_attr(get_option('pjci_api_key'));
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$str,$pjci_header,'');
if(!empty($pjci_request_response_array_set)){
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = (int)$pjci_request_response_array_set['response_code'];
if($response_code == "401" || $response_code == "412" || $response_code == "415" || $response_code == "406" || $response_code == "413"){
wp_die();
} else {
if($response_code == "422" || $response_code == "404" || $response_code == "408" ){
/*$message = esc_html__("Unable to compress the image.1");
echo json_encode($message);	*/
$payload=$boundary='';	
$boundary = wp_generate_password( 24,false,false );
 $payload .= '--'.$boundary;
 $payload .= "\r\n";
 $payload .= 'Content-Disposition: form-data; name="' . 'image' .
'"; filename="' . basename( $full_ftp_imgName ) . '"' . "\r\n";
 $payload .= 'Content-Type: image/jpeg' . "\r\n";
 $payload .= "\r\n";
 $payload .= file_get_contents( $full_ftp_imgName );
 $payload .= "\r\n";
 $payload .= '--' . $boundary . '--';
$pjci_url = 'upload'; 
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$payload,$pjci_header,$boundary);
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = $pjci_request_response_array_set['response_code'];
$payload=$boundary='';	
	}
}
if(!empty($pjci_request_response) && $response_code == "200"){
$success = $pjci_request_response->success;
$file_name = $pjci_request_response->file_name;
$original_size = (int)$pjci_request_response->original_size;
$compress_size = (int)$pjci_request_response->compress_size;
$compress_percentage = $pjci_request_response->compress_percentage;
$compress_path = $pjci_request_response->compress_path;
$created = $pjci_request_response->created;
$pjci_request_response_img = pjci_requests_get_img_data($compress_path);
$get_image_data = $pjci_request_response_img['response_body'];
$upload_image=file_put_contents($full_ftp_imgName, $get_image_data);
if(!empty($upload_image)){
$db_request = array(
"response_code" => $response_code,
"file_name" => $file_name,
"originalSizeInByte" => $original_size,
"compressSizeInByte" => $compress_size,
"percentage" => $compress_percentage,
"image_compress_option" => $key,
"time" => $created
);
$result[] = array_merge($pjci_combine_data, $db_request);
}
if ($key  === @end(array_keys($final_image_set))) {
update_post_meta( $post_id, 'pjci_compress_images', serialize($result));
}
}
}
}
}
}

/*Bulk Optimazition process for images*/ 
add_action( 'wp_ajax_pjci_bulk_img_compress', 'pjci_bulk_img_compress' );
function pjci_bulk_img_compress() {
if(isset($_POST['bulk_optimisation_nonce'])&& current_user_can('upload_files')){	
$get_post_id = (int)$_POST['post-id'];
if($get_post_id == "compresstion_false"){
delete_post_meta_by_key('pjci_inc_no');
wp_die();
}
$get_post_id_exp = explode(',', $get_post_id);
$img_url="";
$pjci_set_number = 1;
foreach ($get_post_id_exp as $key => $value) {
$post_id = $value;
$allowed_file_types = array("jpg", "jpeg", "png", "gif");
$file_url = wp_get_attachment_url( $post_id );
$filetype = wp_check_filetype( $file_url );
$uploaded_file_type = $filetype['ext'];
if(in_array($uploaded_file_type, $allowed_file_types)) {
$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
$local_ftp_path = get_attached_file($post_id);
$live_web_path = wp_get_attachment_url($post_id);
$featured_img_fpath = wp_get_attachment_metadata($post_id);
$img_base_name = basename(get_attached_file( $post_id));

if(!empty($featured_img_fpath)){
$value_set = array();
foreach ($unserialize_pjci_sizes as $key => $value) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
if(array_key_exists($key, $featured_img_fpath['sizes'])){
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
}
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$filesize = round(filesize($full_ftp_imgName), 1);
$value_set[$key] = $filesize;
}
}
$final_image_set = array_unique($value_set);
$pjci_combine_data = array();
$result=array();
$pjci_inc_no = 1;
foreach ($final_image_set as $key => $image_all) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
$last_web_img_name = basename($live_web_path);
$links = str_replace($last_web_img_name, $image_last_name, $live_web_path);
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$img_url = array(
'imageurl' => $links
);
$pjci_url = "url";
$str = json_encode($img_url,  JSON_UNESCAPED_SLASHES);
$pjci_header = esc_attr(get_option('pjci_api_key'));
$get_db_data = unserialize(get_post_meta( $post_id, 'pjci_compress_images', true ));
if(empty($get_db_data)){
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$str,$pjci_header,'');
} else {
if (!in_array($image_last_name, array_column($get_db_data, 'file_name'))){
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$str,$pjci_header,'');
}
}
if(!empty($pjci_request_response_array_set)){
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = (int)$pjci_request_response_array_set['response_code'];
if($response_code == "401" || $response_code == "412" || $response_code == "415" || $response_code == "406"|| $response_code == "413"){
if(empty($get_db_data)){	
update_post_meta( $post_id, 'pjci_compress_images', serialize($result));
} else {
update_post_meta( $post_id, 'pjci_compress_images', serialize($get_db_data));	
}
echo $response_code;
wp_die();
} else {
if($response_code == "422" || $response_code == "404" || $response_code == "408" ){
/*$message = esc_html__("Unable to compress the image.1");
echo json_encode($message);	*/
$payload=$boundary='';	
$boundary = wp_generate_password( 24,false,false );
 $payload .= '--'.$boundary;
 $payload .= "\r\n";
 $payload .= 'Content-Disposition: form-data; name="' . 'image' .
'"; filename="' . basename( $full_ftp_imgName ) . '"' . "\r\n";
 $payload .= 'Content-Type: image/jpeg' . "\r\n";
 $payload .= "\r\n";
 $payload .= file_get_contents( $full_ftp_imgName );
 $payload .= "\r\n";
 $payload .= '--' . $boundary . '--';
$pjci_url = 'upload'; 
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$payload,$pjci_header,$boundary);
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = $pjci_request_response_array_set['response_code'];
$payload=$boundary='';	
	}
}
if(!empty($pjci_request_response) && $response_code == "200"){
$success = $pjci_request_response->success;
$file_name = $pjci_request_response->file_name;
$original_size = (int)$pjci_request_response->original_size;
$compress_size = (int)$pjci_request_response->compress_size;
$compress_percentage = $pjci_request_response->compress_percentage;
$compress_path = $pjci_request_response->compress_path;
$created = $pjci_request_response->created;
$pjci_request_response_img = pjci_requests_get_img_data($compress_path);
$get_image_data = $pjci_request_response_img['response_body'];
$upload_image=file_put_contents($full_ftp_imgName, $get_image_data);
if(!empty($upload_image)){
$db_request = array(
"response_code" => $response_code,
"file_name" => $file_name,
"originalSizeInByte" => $original_size,
"compressSizeInByte" => $compress_size,
"percentage" => $compress_percentage,
"image_compress_option" => $key,
"time" => $created
);
if(empty($get_db_data)){
if(empty($result)){
$result[] = array_merge($pjci_combine_data, $db_request);
} else {
if (!in_array($file_name, array_column($result, 'file_name'))){
$result[] = array_merge($pjci_combine_data, $db_request);
}
}
if ($key  === @end(array_keys($final_image_set))) {
update_post_meta( $post_id, 'pjci_compress_images', serialize($result));
}
} else {
$save_db_data = array($db_request);
if (in_array($file_name, array_column($get_db_data, 'file_name'))){} else {
$save_db_data = array_merge_recursive($get_db_data, $save_db_data);
update_post_meta( $post_id, 'pjci_compress_images', serialize($save_db_data));
}
}
}
$pjci_inc_no_get = get_post_meta($post_id, 'pjci_inc_no', true );
if(empty($pjci_inc_no_get)){
update_post_meta( $post_id, 'pjci_inc_no', $pjci_inc_no);
} else {
$pjci_inc_no = $pjci_inc_no_get+1;
update_post_meta( $post_id, 'pjci_inc_no', $pjci_inc_no);
}
$pjci_inc_no++;
}
}
}
}
$pjci_set_number++;
}	
wp_die();
}
}

/*Get inital size of uploaded images*/
add_action( 'wp_ajax_pjci_get_fields', 'pjci_get_fields' );
function pjci_get_fields() {
$total_img = $_POST['total_img'];
$get_post_id = $_POST['post-id'];
$sum_count = 0;
$get_post_id_exp = explode(',', $get_post_id);
foreach ($get_post_id_exp as $key => $value) {
$post_id = $value;
$pjci_inc_no = get_post_meta($post_id, 'pjci_inc_no', true );
$sum_count+= $pjci_inc_no;
}
$get_number_set = $sum_count;
if(empty($get_number_set)){
$get_number_set = 0;
}
$percentage_per_img = number_format(100/$total_img, 2);
$total_percentage = ceil($percentage_per_img*$get_number_set);
if($total_percentage >= 100){
$total_percentage = 100;	
delete_post_meta_by_key('pjci_inc_no');
}
$results['get_number_set'] = $get_number_set;
$results['total_percentage'] = $total_percentage;
echo json_encode($results);
wp_die();
}

/*Bulk Action compression form media listing*/ 
add_action('admin_footer', 'pjci_compress_image_bulk');
function pjci_compress_image_bulk() {
global $pagenow;
$poststatus  = get_post_status();
if($pagenow == 'upload.php') { ?>
<script type="text/javascript">
jQuery(document).ready(function() {
jQuery('<option>').val('pjci_compress_images').text('<?php _e( 'PJCI Compress Image', 'pjci' ); ?>').appendTo("select[name='action']");
jQuery('<option>').val('pjci_compress_images').text('<?php _e('PJCI Compress Image', 'pjci' )?>').appendTo("select[name='action2']");
});
</script>
<?php
}
}

add_action('load-upload.php', 'pjci_compress_image_bulk_action');
function pjci_compress_image_bulk_action() {
global $typenow, $pagenow, $bulk_action,$poststatus;
$post_type = $typenow;
if (isset($_REQUEST['action']) && $_REQUEST['action']!=-1) {
$bulk_action = esc_attr($_REQUEST['action']);

}elseif(isset($_REQUEST['action2']) && $_REQUEST['action']==-1) {
$bulk_action = esc_attr($_REQUEST['action2']);
} 
if('pjci_compress_images' == $bulk_action && $post_type == 'attachment') {
if(isset($_REQUEST['media'])) {
$media_ids = array_map('intval', $_REQUEST['media']);
foreach($media_ids as $media_id) { 
$post_id = $media_id;
$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
$orignal_img_path = wp_get_attachment_url($post_id);
$local_ftp_path = get_attached_file($post_id);
$live_web_path = wp_get_attachment_url($post_id);
$featured_img_fpath = wp_get_attachment_metadata($post_id);
$img_base_name = basename(get_attached_file( $post_id));
if(!empty($featured_img_fpath)){
$value_set = array();
foreach ($unserialize_pjci_sizes as $key => $value) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
if(array_key_exists($key, $featured_img_fpath['sizes'])){
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
}
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$filesize = round(filesize($full_ftp_imgName), 1);
$value_set[$key] = $filesize;
}
}
$final_image_set = array_unique($value_set);
$pjci_combine_data = array();
$result=array();
foreach ($final_image_set as $key => $image_all) {
if($key == "original"){
$image_last_name = $img_base_name;
} else {
$image_last_name = $featured_img_fpath['sizes'][$key]['file'];
}
$last_web_img_name = basename($live_web_path);
$links = str_replace($last_web_img_name, $image_last_name, $live_web_path);
$last_ftp_img_name = basename($local_ftp_path);
$full_ftp_imgName = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
$img_url = array(
'imageurl' => $links
);
$pjci_url = "url";
$str = json_encode($img_url,  JSON_UNESCAPED_SLASHES);
$pjci_header = esc_attr(get_option('pjci_api_key'));
$get_db_data = unserialize(get_post_meta( $post_id, 'pjci_compress_images', true ));
if(empty($get_db_data)){
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$str,$pjci_header,'');
} else {
if (!in_array($key, array_column($get_db_data, 'image_compress_option'))){
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$str,$pjci_header,'');
}
}
if(!empty($pjci_request_response_array_set)){
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = (int)$pjci_request_response_array_set['response_code'];
if($response_code == "401" || $response_code == "412" || $response_code == "415" || $response_code == "406"|| $response_code == "413"){
update_post_meta( $post_id, 'pjci_compress_images', serialize($result));
if ($bulk_action == 'pjci_compress_images'){
$sendback = add_query_arg(
array('post_type'=>'attachment',
'paged'=>intval($_REQUEST['paged']),
'success' => 2,
'task' => $bulk_action), $sendback 
);
wp_redirect($sendback);
exit();
}
} else {
if($response_code == "422" || $response_code == "404" || $response_code == "408" ){
/*$message = esc_html__("Unable to compress the image.1");
echo json_encode($message);	*/
$payload=$boundary='';	
$boundary = wp_generate_password( 24,false,false );
 $payload .= '--'.$boundary;
 $payload .= "\r\n";
 $payload .= 'Content-Disposition: form-data; name="' . 'image' .
'"; filename="' . basename( $full_ftp_imgName ) . '"' . "\r\n";
 $payload .= 'Content-Type: image/jpeg' . "\r\n";
 $payload .= "\r\n";
 $payload .= file_get_contents( $full_ftp_imgName );
 $payload .= "\r\n";
 $payload .= '--' . $boundary . '--';
$pjci_url = 'upload'; 
$pjci_request_response_array_set = pjci_requests_post($pjci_url,$payload,$pjci_header,$boundary);
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
$response_code = $pjci_request_response_array_set['response_code'];
$payload=$boundary='';	
	}
}
$success = $pjci_request_response->success;
$file_name = $pjci_request_response->file_name;
$original_size = (int)$pjci_request_response->original_size;
$compress_size = (int)$pjci_request_response->compress_size;
$compress_percentage = $pjci_request_response->compress_percentage;
$compress_path = $pjci_request_response->compress_path;
$created = $pjci_request_response->created;
$pjci_request_response_img = pjci_requests_get_img_data($compress_path);
$get_image_data = $pjci_request_response_img['response_body'];
$upload_image=file_put_contents($full_ftp_imgName, $get_image_data);
if(!empty($upload_image)){
$db_request = array(
"response_code" => $response_code,
"file_name" => $file_name,
"originalSizeInByte" => $original_size,
"compressSizeInByte" => $compress_size,
"percentage" => $compress_percentage,
"image_compress_option" => $key,
"time" => $created
);
if(empty($get_db_data)){
if(empty($result)){
$result[] = array_merge($pjci_combine_data, $db_request);
} else {
if (!in_array($file_name, array_column($result, 'file_name'))){
$result[] = array_merge($pjci_combine_data, $db_request);
}
}
if ($key  === @end(array_keys($final_image_set))) {
update_post_meta( $post_id, 'pjci_compress_images', serialize($result));
}
} else {
$save_db_data = array($db_request);
if (in_array($key, array_column($get_db_data, 'image_compress_option'))){} else {
$save_db_data = array_merge_recursive($get_db_data, $save_db_data);
update_post_meta( $post_id, 'pjci_compress_images', serialize($save_db_data));
}
}
}
}
}
}
if ($bulk_action == 'pjci_compress_images'){
$sendback = add_query_arg(
array('post_type'=>'attachment',
'paged'=>intval($_REQUEST['paged']),
'success' => 1,
'task' => $bulk_action), $sendback 
);
wp_redirect($sendback);
exit();
}
}
}
}

add_action('admin_notices', 'pjci_compress_images_admin_notice');
function pjci_compress_images_admin_notice() {
global $post_type, $pagenow;
if($pagenow == 'upload.php' && isset($_REQUEST['task']) && $_REQUEST['success'] == "1") { ?>
<div class="updated notice is-dismissible">
<p><?php _e( 'Your Seleted images compress successfully.', 'pjci' );	?></p> 
</div>
<?php }
}

add_action( 'admin_notices', 'pjci_error_notice' );
function pjci_error_notice (){
global $pagenow;
if(!empty(esc_attr(get_option('pjci_api_key')))){
$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
$pjci_header = sanitize_text_field($pjci_api_key);
$pjci_url = "user-status";
$pjci_request_response_array_set = pjci_requests_get($pjci_url,$pjci_header);
if(!empty($pjci_request_response_array_set['response_body'])){
$pjci_request_response = json_decode($pjci_request_response_array_set['response_body']);
if(!empty($pjci_request_response->errorMsg)){
echo '<div class="notice notice-error pjci_disable_api"><p>'.$pjci_request_response->errorMsg.'</p></div>';
} else {
$quota_remaining =  $pjci_request_response->quota_remaining;
update_option('pjci_quota_remaining', $quota_remaining);
if($quota_remaining == "0"){
if($pagenow == 'upload.php' || $pagenow == 'media-new.php' || $_GET["page"] == 'png-compressor'){
echo '<div class="notice notice-error"><p>You have exceeded your limit, <a href="'.pjci_siteurl.'/my-apis" target="_blank">Please recharge or upgrad to higher plan.</a></p></div>';
}
}
}
}
}
}

function pjci_action_maybe_delete( $id ) {
 delete_post_meta($id, 'pjci_compress_images');
};
add_action( 'delete_attachment', 'pjci_action_maybe_delete', 10, 1 );