<?php

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
exit();
delete_option('pjci_compression_timing');
delete_option('pjci_sizes');
delete_option('pjci_api_key');
delete_option('pjci_responseCode');
delete_option('pjci_total_credits');
delete_option('pjci_plan_name');
delete_option('pjci_quota_total');
delete_option('pjci_quota_used');
delete_option('pjci_quota_remaining');
delete_option('pjci_api_responsecode');
delete_option('pjci_response_message');
delete_option('pjci_response_errorMsg');
delete_post_meta_by_key('pjci_inc_no');
delete_post_meta_by_key('pjci_compress_images');
delete_post_meta_by_key('pjci_check_bgcompress');