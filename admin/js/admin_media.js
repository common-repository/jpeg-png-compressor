(function($) { 
$(document).ready(function () {
var get_pjci_api_key = $("#get_pjci_api_key").val();
$("#id-start").on("click", function() {
$("#id-start").css("display", "none");
$("#myprogressBar").addClass('active');
var get_id = $(this).attr("data-send-IDs");
var ajaxurl = $(this).attr("data-ajax-url");
var bulk_optimisation_nonce = $('input[name="bulk_optimisation_nonce"]').val();
var get_ids = get_id.substr(0, get_id.length-1);
var valNew= get_ids.split(',');
var id_lenth = valNew.length;
$.each(valNew, function (index, value) {
if (index == 0) {
$.ajax({
url: ajaxurl,
data: {'action': 'pjci_bulk_img_compress', "post-id": value,"bulk_optimisation_nonce": bulk_optimisation_nonce},
type: 'POST',
async: true,
success: function(get_fields){
if(get_fields == "401"){
$(".bulk_limit_ext").css("display", "block");
}
}
});
} else {
setTimeout(function(){
$.ajax({
url: ajaxurl,
data: {'action': 'pjci_bulk_img_compress', "post-id": value,"bulk_optimisation_nonce": bulk_optimisation_nonce},
type: 'POST',
async: true,
success: function(get_fields){
if(get_fields == "401"){
$(".bulk_limit_ext").css("display", "block");
}
}
});
}, 10000 * (index + 1));
}
});
});

//Custom Button Compress
$(".custom-compress").on("click", function() {
$(this).siblings(".spinner").css("visibility", "visible");
$(this).off('click');
$(this).css('display', 'none');
var ajaxurl = $(this).attr("data-ajax-url");
var post_id = $(this).attr("data-post-id");
$(".compress_progrss_"+post_id).css('display', 'block');
var success_msg_class="success_msg_"+post_id;
$("#pjci_detail_"+post_id).css("display", "none");
$(this).before("<span style='margin: 3px 0px 0 0;color: green;' class='"+success_msg_class+"'></span>");
$(this).addClass("success_delete");
$.ajax({
url: ajaxurl,
data: {'action': 'pjci_single_img_compress', "post-id": post_id},
type: 'POST',
async: true,
success: function(get_fields){
$(".message_"+post_id).css("display", "block");
$(".remain_"+post_id).css("display", "none");
var json = $.parseJSON(get_fields);
if(typeof json.total_size_count === "undefined"){
$(".success_msg_"+post_id).html(json);	
$(".compress_progrss_"+post_id).css('display', 'none');
}else{
$(".pjci-compress-image span.spinner").css("visibility", "hidden");
$(".success_delete").css("display", "none");
$(".success_msg_"+post_id).text("Image Compressed Successfully");
$(".compress_progrss_"+post_id).css('display', 'none');
$(".total_saving_"+post_id).html("Saved "+json.response_total_percentage+"%");
$(".message_"+post_id).html("<strong>"+json.total_size_count+"</strong> sizes compressed");
}
}
});
});

//Register Account
$(".create_account").on("click", function(e) {
$(this).find(".spinner_button").css("visibility", "visible");
var ajaxurl = $("#admin_ajax_url").val();
var firstname = $("#pjci_api_key_name").val();
var email = $("#pjci_api_key_email").val();
var register_nonce = $("input[name='setting_nonce']").val();
var name_regex = /^[a-zA-Z]+$/;
var email_regex = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
if (firstname.length == 0) {
$('#head').text("* All fields are mandatory *");
$("#pjci_api_key_name").focus();
$("#pjci_api_key_name").addClass("error_class");
$("#pjci_api_key_email").addClass("error_class");
$(this).find(".spinner_button").css("visibility", "hidden");
return false;
}
// Validating Name Field.
else if (!firstname.match(name_regex) || firstname.length == 0) {
$('#p1').text("* For your name please use alphabets only *");
$("#pjci_api_key_name").focus();
$("#pjci_api_key_name").addClass("error_class");
$(this).find(".spinner_button").css("visibility", "hidden");
return false;
}
// Validating Email Field.
else if (!email_regex.test(email) || email.length == 0) {
$('#p3').text("* Please enter a valid email address *"); // This Segment Displays The Validation Rule For Email
$("#pjci_api_key_email").focus();
$("#pjci_api_key_email").addClass("error_class");
$(this).find(".spinner_button").css("visibility", "hidden");
return false;
}
else {
$.ajax({
url: ajaxurl,
data: {'action': 'pjci_register_account', "name": firstname, "email": email,"register_nonce":register_nonce},
type: 'POST',
async: true,
success: function(results){
var json = $.parseJSON(results);
$(".spinner_button").css("visibility", "hidden");
if(json.response_code == "202"){
$('.error_replace').text(json.response_errorMsg);
setTimeout(function() {
$(".error_replace").empty();
},6000);
} else if(json.response_code == "200"){
$("#pjci_api_key").val(json.Key);
$('.success_replace').text(json.response_message);
setTimeout(function() {
$(".success_replace").empty();
$(".key_approved, .not_conncted").css("display", "block");
$('.not_conncted').text("Please verify your email address");
$(".wide, .retrieve_account, .update").css("display", "none");
},3000);
} else {
}
}
});
return true;
}
});

$(".save_verify_key").on("click", function() {
$(this).find(".spinner_button").css({"visibility": "visible", "float": "none"});
var ajaxurl = $("#admin_ajax_url").val();
var api_key =  $("#pjci_api_key_first").val();
var verify_nonce = $("input[name='setting_nonce']").val();
$.ajax({
url: ajaxurl,
data: {'action': 'pjci_api_key_verify', "api_key": api_key,"verify_nonce":verify_nonce},
type: 'POST',
async: true,
success: function(results){
$(".spinner_button").css({"visibility": "hidden"});
var json = $.parseJSON(results);
if(json.responseCode == "401"){
$(".key_approved, .pjci_disable_api").css("display", "none");
$('.error_replace_api').text(json.response_response_errorMsg);
//$(".wide, .update").show();
} else if(json.responseCode == "200"){
$("#pjci_api_key").val(json.Key);
$('.pjci_plan_name').html(json.response_plan_name);
$('.pjci_quota_total').html(json.response_quota_total);
$('.pjci_quota_used').html(json.response_quota_used);
$('.pjci_quota_remaining').html(json.response_quota_remaining);
$(".not_conncted-success").css("display", "none");
$(".key_approved").css("display", "block");
$(".wide").css("display", "none");
$(".status-success").css("display", "block");
$(".retrieve_account, .update, #already-account, .pjci_disable_api").css("display", "none");
}
}
});
});

//verify key
$(".verify_key").on("click", function() {
$(this).find(".spinner_button").css({"visibility": "visible", "float": "none"});
var ajaxurl = $("#admin_ajax_url").val();
var api_key = $("#pjci_api_key").val();
var verify_nonce = $("input[name='setting_nonce']").val();
$.ajax({
url: ajaxurl,
data: {'action': 'pjci_api_key_verify', "api_key": api_key,"verify_nonce":verify_nonce},
type: 'POST',
async: true,
success: function(results){
$(".spinner_button").css({"visibility": "hidden"});
var json = $.parseJSON(results);
if(json.responseCode == "401"){
$("#pjci_api_key_first").val(json.Key);
$(".key_approved").css("display", "none");
$(".register-block").css("display", "none");
$("#already-account").css("display", "block");
$(".update").show();
} else if(json.responseCode == "200"){
$("#pjci_api_key").val(json.Key);
$('.pjci_plan_name').html(json.response_plan_name);
$('.pjci_quota_total').html(json.response_quota_total);
$('.pjci_quota_used').html(json.response_quota_used);
$('.pjci_quota_remaining').html(json.response_quota_remaining);
$(".not_conncted-success").css("display", "none");
$(".key_approved").css("display", "block");
$(".wide").css("display", "none");
$(".status-success").css("display", "block");
$(".retrieve_account, .update").css("display", "none");
}
}
});
});

$(document).on("click", ".change_api_key", function() {
$(".status-success").css("display", "none");
$(".update").show();
});

$(".cancel_cls").on("click", function() {
var not_conncted_text = $(".not_conncted").text();
if(not_conncted_text == "API key has been disabledChange API key"){
$(".status-success").css("display", "none");
} else {
$(".status-success").css("display", "block");
}
$(".update").css("display", "none");
});

if(get_pjci_api_key != ""){
$(".verify_key").trigger('click'); 
$(".status-success").css("display", "none");
$(".retrieve_account").css("display", "block");
}
});

$(window).on('load', function(ev) {
$('#pjci-optimization-chart').pieChart({
barColor: '#57a800',
trackColor: '#CDF5D0',
lineCap: '',
lineWidth: 20,
onStep: function (from, to, percent) {
$(this.element).find('.pie-value').text(Math.round(percent)+'%');
}
});
});
}(jQuery)); 

(function($) { 
function set_updates() {
var total_img = jQuery("#id-start").attr("data-total-img");
if(jQuery("#id-start").attr("data-send-IDs") == ""){}
var get_id = jQuery("#id-start").attr("data-send-IDs");
var data_first_ids = jQuery("#id-start").attr("data-first-ids");
var size_count = jQuery("#id-start").attr("data-size-count");
var ajaxurl = jQuery("#id-start").attr("data-ajax-url");
var get_ids = get_id.substr(0, get_id.length-1);
var valNew= get_ids.split(',');
var id_lenth = valNew.length;
$.each(valNew, function (index, value) {
if (index == 0) {
$.ajax({
url: ajaxurl,
data: {'action': 'pjci_get_fields', "post-id": get_ids,'total_img':total_img},
type: 'POST',
async: true,
success: function(get_responce){
var json = $.parseJSON(get_responce);
var set_nmber = json.get_number_set;
$("#optimized-so-far").text(set_nmber);
$("#myprogressBar").width(parseFloat(json.total_percentage) + '%');
$("#percentage").text('('+parseFloat(json.total_percentage) + '%)');
if(parseFloat(json.total_percentage) >= 100){
$("#myprogressBar").removeClass('active');
location.reload();
}
}
});
} else {
setTimeout(function(){
$.ajax({
url: ajaxurl,
data: {'action': 'pjci_get_fields', "post-id": get_ids,'total_img':total_img},
type: 'POST',
async: true,
success: function(get_responce){
var json = $.parseJSON(get_responce);
var set_nmber = json.get_number_set;
$("#optimized-so-far").text(set_nmber);
$("#myprogressBar").width(parseFloat(json.total_percentage) + '%');
$("#percentage").text('('+parseFloat(json.total_percentage) + '%)');
if(parseFloat(json.total_percentage) >= 100){
$("#myprogressBar").removeClass('active');
location.reload();
}
}
});
}, 10000 * (index + 1));
}
});
}
$(document).ready(function(e) {
  $("#id-start").on("click", function() {
  setInterval(function () {
    set_updates();
  }, 6000);
});
});

$(".already-account").click(function(){
$(".register-block").hide();
$("#already-account").show();
})
$(".no-account").click(function(){
$("#already-account").hide();
$(".register-block").show();
});
$(".compress-label").click(function(){
$(this).addClass("active").siblings().removeClass("active");
});
var pathname = $(location).attr('href');
var bulk_optimisation_nonce = $('input[name="bulk_optimisation_nonce"]').val();
parts = pathname.split("?"),
last_part = parts[parts.length-1];
if(last_part == "page=pjci-bulk-optimization"){
$.ajax({
url: ajaxurl,
data: {'action': 'pjci_bulk_img_compress', "post-id": "compresstion_false","bulk_optimisation_nonce":bulk_optimisation_nonce},
type: 'POST',
async: true,
success: function(get_fields){}
});
}
}(jQuery));