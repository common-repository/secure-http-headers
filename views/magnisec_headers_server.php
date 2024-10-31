<?php 
wp_register_style('msecshh_style', plugins_url('../assets/magnisec_headers_style.css', __FILE__ ));
	wp_enqueue_style('msecshh_style', plugins_url('../assets/magnisec_headers_style.css', __FILE__ ));
?>

<?php
$prepare = $wpdb->prepare("SELECT server,isactive FROM magnisec_headers_website_servers");
$squery = $wpdb->get_results($prepare, ARRAY_A);
print_R($error);
?>
<?php if (!empty($squery))
{ ?>
<div class="m_sec_header">
<h5 class="m_sec_title">Secure HTTP Headers - Choose Server</h5>
</div>
<br>

<?php
	$link = "";
				if( is_multisite() && is_network_admin() )
				{
					$link = network_admin_url( 'admin.php?page=magnisec-headers-setting' );
				}
				else if (!is_multisite())
				{
					$link = admin_url( 'options-general.php?page=magnisec-headers-setting' );
				}

?>

<form action="<?php echo esc_url($link); ?>" method="post" class="form sec_div" id="m_sec_form_server"> 
	<?php
		wp_nonce_field('magnisec-headers-setting', 'MSecSecurityHeaders-server'); 
	?>
	<div class="m_sec_row">
		<div class="m_sec_col-md-6">
			<div class="m_sec_header">
				<h5 class="m_sec_subtitle">Please select your server:</h5>
				
				<?php foreach($squery as $server) { ?>
				<div class="sec_radio">
					<label class="sec_custom_checkbox_label">
						
						<input name="server" value="<?php echo esc_html($server["server"]); ?>" type="radio" class="sec_custom_checkbox_input" <?php if ($server["isactive"] == 1) { echo "checked"; } ?> />
						<span class="sec_custom_checkbox_check"></span>
						<?php echo esc_html($server["server"]); ?>
					</label>
				</div>
				
				<?php } ?>
			</div>
		</div>
	</div>		
	<br>
	
		<button type="submit" name="save_sec_headers" id="save_sec_server" class="sec_button-primary">Next</button>
	
</form>
<?php } else { ?>

<h5 class="m_sec_subtitle">Something went wrong, please contact us on admin@magnisec.com!</h5>

<?php } ?>