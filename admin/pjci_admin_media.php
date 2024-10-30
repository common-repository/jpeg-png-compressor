<?php 
/*Add compress heading in media column*/
function pjci_modify_media_table( $column ) {
	$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
	$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
	$pjci_responseCode =  esc_attr(get_option('pjci_responseCode'));
	if(!empty($pjci_responseCode) && $pjci_responseCode != "401" && !empty($pjci_api_key) && !empty(esc_attr(get_option('pjci_compression_timing'))) && !empty($unserialize_pjci_sizes)){
	$column['pjci-compress-image'] = 'Compress';
	}
	return $column;
}
add_filter( 'manage_media_columns', 'pjci_modify_media_table');

/*Add compress button in media column section*/ 
function pjci_display_posts_status( $column, $post_id ) {
	if ($column == "pjci-compress-image"){
		add_thickbox();
		$allowed_file_types = array("jpg", "jpeg", "png", "gif");
		$file_url = wp_get_attachment_url( $post_id );
		$filetype = wp_check_filetype( $file_url );
		$uploaded_file_type = $filetype['ext'];
		$local_ftp_path = get_attached_file($post_id);
		$live_web_path = wp_get_attachment_url($post_id);
		$featured_img_fpath = wp_get_attachment_metadata($post_id);
		$img_base_name = basename(get_attached_file( $post_id));
		if(in_array($uploaded_file_type, $allowed_file_types)) {
			$get_db_data = unserialize(get_post_meta( $post_id, 'pjci_compress_images', true ));
			$pjci_check_bgcompress = get_post_meta( $post_id, 'pjci_check_bgcompress', true );
			$pjci_single_images_check = get_post_meta( $post_id, 'pjci_single_images_check', true );
			$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
				if(empty($get_db_data)){
					$total_db_count = "0";	
					} else {
						$total_db_fs = array();
						foreach ($get_db_data as $key => $get_db_fatch) {
						if(in_array($get_db_fatch["image_compress_option"], array_keys($unserialize_pjci_sizes)) || $get_db_fatch["response_code"] == 200 ) {
						$total_db_fs[] = $get_db_fatch;
					}
					}
					$total_db_count = count($total_db_fs);
				}
			if(empty($get_db_data)){
				$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
				$save_option_fs = array();
				$featured_img_fpath = wp_get_attachment_metadata($post_id);
				if(empty($featured_img_fpath) && esc_attr(get_option('pjci_compression_timing')) != "manual"){
				echo '<div class="compress_progrss"><img src="'.plugin_dir_url( __FILE__ ) .'image/pjci_loader.svg" style="vertical-align: bottom;" width="20px" height="20px"> Compression in progress</div>';
				} elseif(esc_attr(get_option('pjci_compression_timing')) == "background" && $pjci_check_bgcompress == "yes"){
				echo '<div class="compress_progrss"><img src="'.plugin_dir_url( __FILE__ ) .'image/pjci_loader.svg" style="vertical-align: bottom;" width="20px" height="20px"> Compression in progress</div>';
				} elseif(esc_attr(get_option('pjci_compression_timing')) == "manual" && $pjci_single_images_check == "yes"){
				echo '<div class="compress_progrss"><img src="'.plugin_dir_url( __FILE__ ) .'image/pjci_loader.svg" style="vertical-align: bottom;" width="20px" height="20px"> Compression in progress</div>';
				} else {
				$save_option_fs = array();
				foreach ($unserialize_pjci_sizes as $key => $serialize_pjci_sizes) {
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
				$total_size_count = count(array_unique($save_option_fs));
				$pjci_header = sanitize_text_field($pjci_api_key);
				$quota_remaining =  esc_attr(get_option('pjci_quota_remaining'));
				$pjci_responseCode =  esc_attr(get_option('pjci_responseCode'));
				if(!empty($pjci_responseCode) && $pjci_responseCode != "401" && !empty($pjci_api_key)){
				$attachment_title = get_the_title($post_id);
				echo '<span class="message_'.$post_id.'">'.$total_size_count.' sizes to be compressed</span>';
				echo '<span class="total_saving_'.$post_id.'"></span>';
				echo '<a id="pjci_detail_'.$post_id.'" href="#TB_inline?width=980&inlineId=pjci-popup_'.$post_id.'" class="thickbox" title="Compression details">Details</a>';
				if($total_size_count != "0" && $quota_remaining != "0" && current_user_can('upload_files') && $pjci_single_images_check != 'yes'){
				echo '<a data-post-id='.$post_id.' data-ajax-url="'.admin_url("admin-ajax.php").'" class="custom-compress compress-btn" href="javascript:void(0)">Compress</a>';
				echo '<div style="display:none;" class="compress_progrss_'.$post_id.'"><img src="'.plugin_dir_url( __FILE__ ) .'image/pjci_loader.svg" style="vertical-align: bottom;" width="20px" height="20px"> Compression in progress</div>';
				}
				echo '<div style="display:none;" id="pjci-popup_'.$post_id.'" class="white-popup mfp-hide">
				<div class="tiny-compression-details">
				<h3>'.$attachment_title.'</h3>
				<table>
				<tbody>
				<tr>
				<th>Size</th>
				<th>Configure</th>
				<th>Initial Size</th>
				<th>Compressed Size</th>
				<th>Same File As</th>
				<th>Date</th>
				</tr>';
				$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
				$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
				$featured_img_fpath = wp_get_attachment_metadata($post_id);
				$featured_img_url = get_intermediate_image_sizes();
				$admin_original_size = array('original');
				$final_image_set = array_merge($admin_original_size, $featured_img_url);
				$i=1;
				$values=$value=$same_size=array();
				foreach ($final_image_set as $key => $image_all) {
				$array_all_img_path = wp_get_attachment_image_src($post_id, $image_all);
				$image_last_name = basename($array_all_img_path[0]);
				
				$local_ftp_path = get_attached_file($post_id);
				$last_ftp_img_name = basename($local_ftp_path);
				$image_loacl_url = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
				$filesize = round(filesize($image_loacl_url), 1);
				echo '<tr '.(($i%2 != 0) ? 'class="even"' : '').'  >';
				$value[$image_all] = round(filesize($image_loacl_url), 1);
				if(in_array($image_all, array_keys($unserialize_pjci_sizes))) {
				$array_unique = array_unique($save_option_fs);
				if(in_array($image_all, array_keys($array_unique))){
				echo '<td><span>'.$image_all.'</span> </td>
				<td>Configured</td>
				<td>'.pjci_formatSizeUnits($filesize).'</td>
				<td>--</td>
				<td><em>';
				} else {
				$sm_name = array_search($filesize, $array_unique);
				echo '<td><span>'.$image_all.'</span> </td>
				<td>Configured</td>
				<td>'.pjci_formatSizeUnits($filesize).'</td>
				<td>--</td>
				<td><em>';
				}
				} else {
				$result = array_intersect($value, $save_option_fs);
				if(array_search($filesize, $result)){
				echo '<td><span>'.$image_all.'</span> </td>
				<td>Not Configured</td>
				<td>'.pjci_formatSizeUnits($filesize).'</td>
				<td>--</td>
				<td><em>';
				} else {
				if(!in_array(round(filesize($image_loacl_url), 1), $values)) {
				 $values[$image_all] = round(filesize($image_loacl_url), 1);
				echo '<td><span>'.$image_all.'</span> </td>
				<td>Not Configured</td>
				<td>'.pjci_formatSizeUnits($filesize).'</td>
				<td>--</td>
				<td><em>';
				} else {
				$same_size[$image_all] = round(filesize($image_loacl_url), 1);
				echo '<td><span>'.$image_all.'</span> </td>
				<td>Not Configured</td>
				<td>'.pjci_formatSizeUnits($filesize).'</td>
				<td>--</td>
				<td><em>';
				}
				}
				}
				//same as
				if (in_array($image_all, array_keys($unserialize_pjci_sizes))) {
				$array_unique = array_unique($save_option_fs);
				if(in_array($image_all, array_keys($array_unique))){
				echo '--';
				} else {
				$sm_name = array_search($filesize, $array_unique);
				echo '"'.$sm_name.'"';
				}
				} else {
				$sm_name = array_search($filesize, $save_option_fs);
				if(array_search($filesize, $result)){
				echo '"'.$sm_name.'"';
				} else {
				$diff = array_intersect($value, $same_size);
				if(array_search($filesize, $diff)){
				$sm_name = array_search($filesize, $diff);
				echo '"'.$sm_name.'"';
				} else {
				echo '--';
				}
				}
				}
				echo '</em></td><td></td></tr>';
				$i++;
				}
				echo '</tbody>
				</table>
				<p>Total savings <strong>0%</strong></p>
				</div>
				</div>';
				}
				}
			} else {
			$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
			$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
			$featured_img_fpath = wp_get_attachment_metadata($post_id);
			if(empty($featured_img_fpath) && esc_attr(get_option('pjci_compression_timing')) != "manual"){
			echo '<div class="compress_progrss"><img src="'.plugin_dir_url( __FILE__ ) .'image/pjci_loader.svg" style="vertical-align: bottom;" width="20px" height="20px"> Compression in progress</div><div style="display:none;" class="compress_progrss_message">Compression in progress</div>.
			';
			} else {
			
			$save_option_fs = array();
			foreach ($unserialize_pjci_sizes as $key => $serialize_pjci_sizes) {
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


			$total_size_count = count(array_unique($save_option_fs));
			if(empty($get_db_data)){
			$total_db_count_filter = "0";	
			} else {
			$total_db_fs = array();
			foreach ($get_db_data as $key => $get_db_fatch) {
			$exit_key = $get_db_fatch['image_compress_option'];
			if(array_key_exists($exit_key, $save_option_fs)){
			$total_db_fs[$exit_key] = $get_db_fatch['compressSizeInByte'];	
			} 
			}
			$total_db_count_filter = count($total_db_fs);
			}
			$pjci_responseCode =  esc_attr(get_option('pjci_responseCode'));
			if(!empty($pjci_responseCode) && $pjci_responseCode != "401" && !empty($pjci_api_key)){
			$attachment_title = get_the_title($post_id);
			echo '<span class="message_'.$post_id.'"><strong>'.$total_db_count.'</strong> sizes compressed</span>';
			echo '<span class="total_saving_'.$post_id.'">Saved <span class="change_percantage_'.$post_id.'">';
			foreach ($get_db_data as $key => $get_db_fatch) {
			$percentage[] = $get_db_fatch["percentage"];
			}
			$sum=0;
			$total_img = count($percentage);
			foreach ($percentage as $key => $percentage_val) {
			$percentage_expload = explode('%', $percentage_val);
			$sum+= $percentage_expload[0];
			}
			echo round($sum/$total_img, 2);
			echo '</span>%</span>'; 
			if($total_size_count > $total_db_count_filter){
			$remain_count = $total_size_count-$total_db_count_filter;
			echo '<span class="remain_'.$post_id.'"><strong>'.$remain_count.'</strong> size to be compressed</span>';
			}
			echo '<a id="pjci_detail_'.$post_id.'" href="#TB_inline?width=980&inlineId=pjci-popup_'.$post_id.'" class="thickbox" title="Compression details">Details</a>';
			$pjci_quota_remaining =  esc_attr(get_option('pjci_quota_remaining'));
			if($pjci_quota_remaining != "0" && $total_size_count != "0" && $total_size_count > $total_db_count_filter && current_user_can('upload_files') && $pjci_single_images_check != 'yes'){
			echo '<a data-post-id='.$post_id.' data-ajax-url="'.admin_url("admin-ajax.php").'" class="custom-compress compress-btn" href="javascript:void(0)">Compress</a>';
			echo '<div style="display:none;" class="compress_progrss_'.$post_id.'"><img src="'.plugin_dir_url( __FILE__ ) .'image/pjci_loader.svg" style="vertical-align: bottom;" width="20px" height="20px"> Compression in progress</div>';
			}
			echo '<div style="display:none;" id="pjci-popup_'.$post_id.'" class="white-popup mfp-hide">
			<div class="tiny-compression-details">
			<h3>'.$attachment_title.'</h3>
			<table>
			<tbody>
			<tr>
			<th>Size</th>
			<th>Configure</th>
			<th>Initial Size</th>
			<th>Compressed Size</th>
			<th>Same File As</th>
			<th>Date</th>
			</tr>';
			$pjci_api_key =  esc_attr(get_option('pjci_api_key'));
			$unserialize_pjci_sizes =  unserialize(get_option('pjci_sizes'));
			$featured_img_fpath = wp_get_attachment_metadata($post_id);
			$featured_img_url = get_intermediate_image_sizes();
			$admin_original_size = array('original');
			$final_image_set = array_merge($admin_original_size, $featured_img_url);
			$i=1;
			$values = array();
			$value = array();
			$same_size = array();
			foreach ($final_image_set as $key => $image_all) {
			$array_all_img_path = wp_get_attachment_image_src($post_id, $image_all);
			$image_last_name = basename($array_all_img_path[0]);
			$local_ftp_path = get_attached_file($post_id);
			$last_ftp_img_name = basename($local_ftp_path);
			$image_loacl_url = str_replace($last_ftp_img_name, $image_last_name, $local_ftp_path);
			$filesize = round(filesize($image_loacl_url), 1);
			echo '<tr '.(($i%2 != 0) ? 'class="even"' : '').'  >';
			$value[$image_all] = round(filesize($image_loacl_url), 1);
			if(in_array($image_all, array_keys($unserialize_pjci_sizes))) {
			$array_unique = array_unique($save_option_fs);
			if(in_array($image_all, array_keys($array_unique))){
			echo '<td><span>'.$image_all.'</span> </td>
			<td>Configured</td>
			<td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$originalSizeInByte = str_replace(" bytes","",$get_db_fatch["originalSizeInByte"]);
			echo pjci_formatSizeUnits($originalSizeInByte);
			}
			}
			} else {
			echo pjci_formatSizeUnits($filesize);	
			}
			echo '</td>
			<td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$compressSizeInByte = str_replace(" bytes","",$get_db_fatch["compressSizeInByte"]);
			echo pjci_formatSizeUnits($compressSizeInByte);
			}
			}
			} else {
			echo '--';
			}
			echo '</td>
			<td><em>';
			} else {
			$sm_name = array_search($filesize, $array_unique);
			echo '<td><span>'.$image_all.'</span> </td>
			<td>Configured</td>
			<td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$originalSizeInByte = str_replace(" bytes","",$get_db_fatch["originalSizeInByte"]);
			echo pjci_formatSizeUnits($originalSizeInByte);
			}
			}
			} else {
			echo pjci_formatSizeUnits($filesize);	
			}
			echo '</td>
			<td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$compressSizeInByte = str_replace(" bytes","",$get_db_fatch["compressSizeInByte"]);
			echo pjci_formatSizeUnits($compressSizeInByte);
			}
			}
			} else {
			echo '--';
			}
			echo '</td>
			<td><em>';
			}
			} else {
			$result = array_intersect($value, $save_option_fs);
			if(array_search($filesize, $result)){
			echo '<td><span>'.$image_all.'</span> </td>
			<td>Not Configured</td>
			<td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$originalSizeInByte = str_replace(" bytes","",$get_db_fatch["originalSizeInByte"]);
			echo pjci_formatSizeUnits($originalSizeInByte);
			}
			}
			} else {
			echo pjci_formatSizeUnits($filesize);	
			}
			echo '</td>
			<td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$compressSizeInByte = str_replace(" bytes","",$get_db_fatch["compressSizeInByte"]);
			echo pjci_formatSizeUnits($compressSizeInByte);
			}
			}
			} else {
			echo '--';
			}
			echo '</td>
			<td><em>';
			} else {
			if(!in_array(round(filesize($image_loacl_url), 1), $values)) {
			 $values[$image_all] = round(filesize($image_loacl_url), 1);
			echo '<td><span>'.$image_all.'</span> </td>
			<td>Not Configured</td>
			<td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$originalSizeInByte = str_replace(" bytes","",$get_db_fatch["originalSizeInByte"]);
			echo pjci_formatSizeUnits($originalSizeInByte);
			}
			}
			} else {
			echo pjci_formatSizeUnits($filesize);	
			}
			echo '</td>
			<td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$compressSizeInByte = str_replace(" bytes","",$get_db_fatch["compressSizeInByte"]);
			echo pjci_formatSizeUnits($compressSizeInByte);
			}
			}
			} else {
			echo '--';
			}
			echo '</td>
			<td><em>';
			} else {
			$same_size[$image_all] = round(filesize($image_loacl_url), 1);
			echo '<td><span>'.$image_all.'</span> </td>
			<td>Not Configured</td>
			<td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$originalSizeInByte = str_replace(" bytes","",$get_db_fatch["originalSizeInByte"]);
			echo pjci_formatSizeUnits($originalSizeInByte);
			}
			}
			} else {
			echo pjci_formatSizeUnits($filesize);	
			}
			echo '</td>
			<td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$compressSizeInByte = str_replace(" bytes","",$get_db_fatch["compressSizeInByte"]);
			echo pjci_formatSizeUnits($compressSizeInByte);
			}
			}
			} else {
			echo '--';
			}
			echo '</td>
			<td><em>';
			}
			}
			}
			//same as
			if (in_array($image_all, array_keys($unserialize_pjci_sizes))) {
			$array_unique = array_unique($save_option_fs);
			if(in_array($image_all, array_keys($array_unique))){
			echo '--';
			} else {
			$array_unique = array_unique($save_option_fs);
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$compressSizeInByte = $get_db_fatch["compressSizeInByte"];
			$image_compress_option = $get_db_fatch["image_compress_option"];
			if($compressSizeInByte == $filesize ){
			echo '"'.$image_compress_option.'"';
			}
			}
			}
			} else {
			$result = array_intersect($value, $save_option_fs);
			if(array_search($filesize, $result)){
			$array_unique = array_unique($save_option_fs);
			$sm_name = array_search($filesize, $array_unique);
			echo '"'.$sm_name.'"';
			}
			}
			}
			} else {
			$sm_name = array_search($filesize, $save_option_fs);
			if(array_search($filesize, $result)){
			echo '"'.$sm_name.'"';
			} else {
			$diff = array_intersect($value, $same_size);
			if(array_search($filesize, $diff)){
			$sm_name = array_search($filesize, $diff);
			echo '"'.$sm_name.'"';
			} else {
			echo '--';
			}
			}
			}
			echo '</em></td>';
			if (in_array($image_last_name, array_column($get_db_data, 'file_name'))){
			echo '<td>';
			foreach ($get_db_data as $key => $get_db_fatch) {
			if($get_db_fatch["file_name"] == $image_last_name){
			$time = $get_db_fatch["time"];
			$time_expload = explode(' GMT+', $time);
			$time_remain = strtotime($time_expload[0]);
			echo pjci_time_ago($time_remain);
			}
			}
			echo '</td>';
			} else {
			echo '<td></td>';
			}
			echo '</tr>';
			$i++;
			}
			echo '</tbody>
			</table>
			<p>Total savings <strong>'; 
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
			echo round($sum/$total_img, 2); 
			echo '%</strong></p>
			</div>
			</div>';
			}
			}
			}
		}
	}
}
add_action( 'manage_media_custom_column' , 'pjci_display_posts_status', 10, 2 );