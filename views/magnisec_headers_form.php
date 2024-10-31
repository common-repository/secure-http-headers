<?php 
wp_register_style('msecshh_style',  plugins_url('../assets/magnisec_headers_style.css' , __FILE__ ));
	wp_enqueue_style('msecshh_style',  plugins_url('../assets/magnisec_headers_style.css' , __FILE__ ));
?>
<?php	
$headers = [];
$cookies = [];
$nginx = 0;
$prepare = $wpdb->prepare("SELECT header,isactive FROM magnisec_headers_security_headers");
$query = $wpdb->get_results($prepare, ARRAY_A);
$prepare_directives = $wpdb->prepare("SELECT directive,isactive FROM magnisec_headers_permissions_directives");
$query_directives = $wpdb->get_results($prepare_directives, ARRAY_A);
$prepare_server = $wpdb->prepare("SELECT server,isactive FROM magnisec_headers_website_servers");
$query_server = $wpdb->get_results($prepare_server, ARRAY_A);
foreach ($query_server as $s)
{
	if ($s["server"] === "Nginx" && $s["isactive"] == 1)
	{
		$nginx = 1;
	}
}
if (!empty($query))
{
	foreach($query as &$sub_array) {
		if($sub_array['header'] == 'Cookie Secure flag') {
			$cookies[] = $sub_array;
			$sub_array = [];
		}
		if($sub_array['header'] == 'Cookie Samesite Lax flag') {
			$cookies[] = $sub_array;
			$sub_array = [];
		}
		if($sub_array['header'] == 'Cookie HttpOnly flag') {
			$cookies[] = $sub_array;
			$sub_array = [];
		}
	}
	$headers = $query;
	$headers = array_map('array_filter', $headers);
	$headers = array_filter($headers);

$link = "";
				if( is_multisite() && is_network_admin() )
				{
					$link = network_admin_url( 'admin.php?page=magnisec-headers-confirm' );
				}
				else if (!is_multisite())
				{
					$link = admin_url( 'options-general.php?page=magnisec-headers-confirm' );
				}

?>
<div class="m_sec_header">
<h5 class="m_sec_title">Secure HTTP Headers - Choose Configuration</h5>
</div>
<br>

<form method="post" class="form sec_div" id="m_sec_form"> 
	
<?php
	wp_nonce_field('magnisec-headers-setting', 'MSecSecurityHeaders-configuration'); 
?>
	<div class="m_sec_row">
		<div class="m_sec_col-md-6">
			
			<div class="m_sec_header">
				<h5 class="m_sec_subtitle">Choose headers you want to secure:</h5>
				<button type="button" class="m_sec_recommended m_sec_link">Recommended configuration</button> 
				<button type="button" class="m_sec_unselect m_sec_selectall m_sec_link"> </button> 
			</div>
			<?php foreach ($headers as $k=>$h)
			{ $permission_checked = false;
				if($headers[$k]["header"] === "Permissions-Policy") { ?>
			<div class="sec_checkbox">
				<label class="sec_custom_checkbox_label">
					<?php echo esc_html($headers[$k]["header"]); ?>
					<input name="header[]" value="<?php echo esc_html($headers[$k]["header"]); ?>" type="checkbox" class="m_sec_permission_check sec_custom_checkbox_input sec_custom_checkbox_input_header"
					<?php if($headers[$k]["isactive"] == 1) {$permission_checked = true; echo "checked";}  ?>/>
					<span class="sec_custom_checkbox_check"></span>
				</label>
			</div>
			<div class="m_sec_permissions_div">
			<h5 class="m_sec_subtitle">Choose directives you want to allow:</h5>
			<?php foreach($query_directives as $directive) { ?>
			<div class="sec_checkbox">
				<label class="sec_custom_checkbox_label">
					<?php echo esc_html($directive["directive"]); ?>
					<input name="directive[]" value="<?php echo esc_html($directive["directive"]); ?>" type="checkbox" class="sec_custom_checkbox_input sec_custom_checkbox_input_permission"
					<?php if($permission_checked === false){echo "disabled";} if($directive["isactive"] == 1 && $permission_checked === true) {echo "checked";}  ?>/>
					<span class="sec_custom_checkbox_check"></span>
				</label>
			</div>
			<?php } ?>
			</div>
			<?php	} else {
			?>
			<div class="sec_checkbox">
				<label class="sec_custom_checkbox_label">
					<?php echo esc_html($headers[$k]["header"]); ?>
					<input name="header[]" value="<?php echo esc_html($headers[$k]["header"]); ?>" type="checkbox" class="sec_custom_checkbox_input sec_custom_checkbox_input_header"
					<?php if($headers[$k]["isactive"] == 1) {echo "checked";}  ?>/>
					<span class="sec_custom_checkbox_check"></span>
				</label>
			</div>
				<?php } } ?>
		</div>
		
		<div class="m_sec_col-md-6">	
			<div class="m_sec_header">
				<h5 class="m_sec_subtitle">Choose cookie flags you want to add:</h5>
				<button type="button" class="m_sec_recommended_cookie m_sec_link">Recommended configuration</button> 
				<button type="button" class="m_sec_unselect_cookie m_sec_selectall_cookie m_sec_link"> </button> 
			</div>
			<?php foreach ($cookies as $k=>$h)
			{ ?>
			<div class="sec_checkbox">
				<label class="sec_custom_checkbox_label">
					<?php echo esc_html($cookies[$k]["header"]); ?>
					<input name="cookie[]" value="<?php echo esc_html($cookies[$k]["header"]); ?>" type="checkbox" class="sec_custom_checkbox_input sec_custom_checkbox_input_cookie"
					<?php if($cookies[$k]["isactive"] == 1) {echo "checked";}  ?>/>
					<span class="sec_custom_checkbox_check"></span>
				</label>
			</div>
			<?php } ?>
		</div>
		
	</div>
	<br>
	<button type="submit" name="save_sec_headers" id="save_sec_headers" class="sec_button-primary">Save</button>
		
</form>	
<?php
	wp_register_script('msecshh_script',  plugins_url('../assets/magnisec_headers_script.js' , __FILE__ ), array('jquery'));
	wp_enqueue_script('msecshh_script');
?>
<?php } else { ?>


<h5 class="m_sec_subtitle">Something went wrong, please contact us on admin@magnisec.com!</h5>

<?php } ?>