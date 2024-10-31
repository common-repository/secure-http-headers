<?php 
	wp_register_style('msecshh_style',  plugins_url('../assets/magnisec_headers_style.css' , __FILE__ ));
	wp_enqueue_style('msecshh_style',  plugins_url('../assets/magnisec_headers_style.css' , __FILE__ ));
?>
<?php	
$headers = [];
$cookies = [];
$nginx = 0;
$prepare = $wpdb->prepare("SELECT header FROM magnisec_headers_security_headers WHERE isactive=1");
$squery = $wpdb->get_results($prepare, ARRAY_A);
$prepare_directives = $wpdb->prepare("SELECT directive,isactive FROM magnisec_headers_permissions_directives");
$all_directivesx2 = $wpdb->get_results($prepare_directives, ARRAY_A);
$prepare_server = $wpdb->prepare("SELECT server,isactive FROM magnisec_headers_website_servers");
$squery_server = $wpdb->get_results($prepare_server, ARRAY_A);
foreach ($squery_server as $s)
{
	if ($s["server"] === "Nginx" && $s["isactive"] == 1)
	{
		$nginx = 1;
	}
}
$selected_headersx = array_column($squery, "header");
if (!empty($squery))
{
	$home_url = home_url();
	$file_lines2 = [];
	if (in_array("Referrer-Policy", $selected_headersx))
	{
		$file_lines[] = "add_header Referrer-Policy \"strict-origin-when-cross-origin\" always;";
	}
	if (in_array("Strict-Transport-Security", $selected_headersx))
	{
		$file_lines[] = "add_header Strict-Transport-Security \"max-age=31536000; includeSubDomains; preload\" always;";
	}
	if (in_array("Expect-CT", $selected_headersx))
	{
		$file_lines[] = "add_header Expect-CT \"max-age=604800, report-uri=\\\"". $home_url . "/report\\\"\" always;";
	}
	if (in_array("Cross-Origin-Resource-Policy", $selected_headersx))
	{
		$file_lines[] = "add_header Cross-Origin-Resource-Policy \"same-site\" always;";
	}
	if (in_array("Cross-Origin-Embedder-Policy", $selected_headersx))
	{
		$file_lines[] = "add_header Cross-Origin-Embedder-Policy \"require-corp; report-to=\\\"default\\\"\" always;";
	}
	if (in_array("Cross-Origin-Opener-Policy", $selected_headersx))
	{
		$file_lines[] = "add_header Cross-Origin-Opener-Policy \"same-origin; report-to=\\\"default\\\"\" always;";
	}
	if (in_array("Clear-Site-Data", $selected_headersx))
	{
		$file_lines[] = "add_header Clear-Site-Data \"\\\"cache\\\"\" always;";
	}
	if (in_array("X-Download-Options", $selected_headersx))
	{
		$file_lines[] = "add_header X-Download-Options \"noopen\" always;";
	}
	if (in_array("X-Frame-Options", $selected_headersx))
	{
		$file_lines[] = "add_header X-Frame-Options \"SAMEORIGIN\" always;";
	}
	if (in_array("X-Content-Type-Options", $selected_headersx))
	{
		$file_lines[] = "add_header X-Content-Type-Options \"nosniff\" always;";
	}
	if (in_array("Access-Control-Allow-Origin", $selected_headersx))
	{
		$file_lines[] = "add_header Access-Control-Allow-Origin \"". $home_url . "\" always;";
	}
	if (in_array("Permissions-Policy", $selected_headersx))
	{
		$file_lines_directives = [];
		foreach ($all_directivesx2 as $dck=>$dcv)
		{
			if ($all_directivesx2[$dck]["isactive"] == 0)
			{
				$file_lines_directives[] = $all_directivesx2[$dck]["directive"] ."=()";
			}
			else
			{
				$file_lines_directives[] = $all_directivesx2[$dck]["directive"] ."=(self)";
			}
		}
		$file_lines_directives_for_insert = implode(",", $file_lines_directives);
		$file_lines[] = "add_header Permissions-Policy \"" . $file_lines_directives_for_insert . "\" always;";
	}
	if (in_array("X-Permitted-Cross-Domain-Policies", $selected_headersx))
	{
		$file_lines[] = "add_header X-Permitted-Cross-Domain-Policies \"none\" always;";
	}

	if (in_array("Cookie Secure flag", $selected_headersx))
	{
		$file_lines2[] = "secure"; 
	}
	if (in_array("Cookie HttpOnly flag", $selected_headersx))
	{
		$file_lines2[] = "HttpOnly"; 
	}
	if (in_array("Cookie Samesite Lax flag", $selected_headersx))
	{
		$file_lines2[] = "SameSite=Lax"; 
	}
?>


	<div class="m_sec_header">
		<h5 class="m_sec_title">Secure HTTP Headers - Secure your website</h5>
	</div>
	<br>
	<div class="m_sec_header_padding">
	<div class="m_sec_row">
		
		<div class="m_sec_col-md-7">	
			<?php if(!empty($file_lines)) { ?>
			<div class="m_sec_header">
				<h5 class="m_sec_subtitle">Copy/Paste bellow code to your configuration file:</h5>
			</div>
			<code id="code">
				<?php echo implode("<br>", $file_lines); ?>
			</code>
			<br>
			<button type="button" class="copy_btn sec_button-primary">Copy to clipboard</button>
			<p class="m_sec_clipboard_note">Copied to clipboard!</p>
			<br><br>
			<?php } ?>
			<?php if(!empty($file_lines2)) { ?>
			<div class="m_sec_header">
				<h5 class="m_sec_subtitle2">Please make sure that nginx_cookie_flag_module is installed on nginx server. 
<br>
Be careful! Applying security code without nginx_cookie_flag_module can crush nginx and make it stop working!
Applying  security code - copy paste  the code below into your configuration file.</h5>
			</div>
			<code id="code2">
				<?php echo "set_cookie_flag * " . implode(" ", $file_lines2) . ";"; ?>
			</code>
			<br>
			<button type="button" class="copy_btn2 sec_button-primary">Copy to clipboard</button>
			<p class="m_sec_clipboard_note2">Copied to clipboard!</p>
			<?php } ?>
			
		</div>
	</div>
</div>
	
<?php
	wp_register_script('msecshh_script',  plugins_url('../assets/magnisec_headers_script.js' , __FILE__ ), array('jquery'));
	wp_enqueue_script('msecshh_script');
?>
<?php } else { ?>

<h5 class="m_sec_subtitle">Something went wrong, please contact us on admin@magnisec.com!</h5>

<?php } ?>