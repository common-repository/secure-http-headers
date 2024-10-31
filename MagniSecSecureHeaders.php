<?php
/**
* Plugin Name: Secure HTTP Headers
* Plugin URI: https://magnisec.com
* Description: Secure HTTP headers - Essential, and easy.
* Author: MagniSec
* Network: true
* Version: 1.0
* Requires at least: 5.3
* License: GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Secure HTTP Headers is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Secure HTTP Headers is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Secure HTTP Headers. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/
namespace MagniSecSecureHeaders;

    class SecureHTTPHeaders
    {
		
		static $instance = false;

	private function __construct() {
		$selected_headers = [];
		$selected_directives = [];
		$all_directives = [];
		$servers = [];
		define( 'MSECSHH', plugin_dir_path( __FILE__ ) ); 
		if (is_multisite())
		{
			add_action('network_admin_menu', array( $this,'magnisec_headers_add_custom_options_page'));
		}
		else
		{
			add_action('admin_menu', array( $this,'magnisec_headers_add_custom_options_page'));
		}
		register_activation_hook(__FILE__, array( $this,'magnisec_headers_activate_server'));
	
		add_action('admin_init', array( $this,'magnisec_headers_redirect_server'));
		add_filter('plugin_action_links_'.plugin_basename(__FILE__), array( $this,'magnisec_headers_add_settings_link'));
		register_activation_hook( __FILE__, array( $this,'magnisec_headers_init' ));
		register_deactivation_hook( __FILE__, array( $this,'magnisec_headers_deactivate' ));


	}
		public static function getMagniSecHeadersInstance() {
			if ( !self::$instance )
				self::$instance = new self;
			return self::$instance;
		}
	
		public function magnisec_headers_add_custom_options_page() {
			if( is_multisite() && is_network_admin() )
			{
				add_menu_page('Secure HTTP Headers', 'Secure HTTP Headers', 'manage_options', 'magnisec-headers-server', array( $this,'magnisec_headers_add_custom_options_server'));
				add_submenu_page(null, "Secure HTTP Headers Setting", "Secure HTTP Headers Setting", 'manage_options', 'magnisec-headers-setting', array( $this,'magnisec_headers_add_custom_options_form'));
				add_submenu_page(null, "Secure HTTP Headers Confirm", "Secure HTTP Headers Confirm", 'manage_options', 'magnisec-headers-confirm', array( $this,'magnisec_headers_add_custom_options_confirm'));
			}
			else
			{
				add_options_page('Secure HTTP Headers', 'Secure HTTP Headers', 'manage_options', 'magnisec-headers-server', array( $this,'magnisec_headers_add_custom_options_server'));
				add_submenu_page(null, "Secure HTTP Headers Setting", "Secure HTTP Headers Setting", 'manage_options', 'magnisec-headers-setting', array( $this,'magnisec_headers_add_custom_options_form'));
				add_submenu_page(null, "Secure HTTP Headers Confirm", "Secure HTTP Headers Confirm", 'manage_options', 'magnisec-headers-confirm', array( $this,'magnisec_headers_add_custom_options_confirm'));
			}
		}
		
	
	
	public function magnisec_headers_add_custom_options_confirm() { 
		global $wpdb;
		include( MSECSHH . '/views/magnisec_headers_confirm.php');
	}
	
	public function magnisec_headers_add_custom_options_server() { 
		global $wpdb;
		include( MSECSHH . '/views/magnisec_headers_server.php');
	}

	public function magnisec_headers_add_custom_options_form() { 
			global $wpdb;
			global $selected_headers;
			$selected_headers = [];
			global $selected_directives;
			global $all_directives;
			global $servers;
			$servers = [];
			$all_directives = [];
			$selected_directives = [];
			$table_name = "magnisec_headers_security_headers";
			$table_name_directives = "magnisec_headers_permissions_directives";
			$table_name_servers = "magnisec_headers_website_servers";
			
			$servers = [["server" => "Apache", "isactive" => 0], ["server" => "Nginx", "isactive" => 0]];
			$headers = [["header" => "Strict-Transport-Security", "isactive" => 0], ["header" => "Expect-CT", "isactive" => 0], ["header" => "Cross-Origin-Resource-Policy", "isactive" => 0], ["header" => "Referrer-Policy", "isactive" => 0], ["header" => "Cross-Origin-Embedder-Policy", "isactive" => 0], ["header" => "Cross-Origin-Opener-Policy", "isactive" => 0], ["header" => "Clear-Site-Data", "isactive" => 0], ["header" => "X-Download-Options", "isactive" => 0], ["header" => "X-Frame-Options", "isactive" => 0], ["header" => "X-Content-Type-Options", "isactive" => 0], ["header" => "Access-Control-Allow-Origin", "isactive" => 0], ["header" => "Permissions-Policy", "isactive" => 0], ["header" => "X-Permitted-Cross-Domain-Policies", "isactive" => 0], ["header" => "Cookie Secure flag", "isactive" => 0], ["header" => "Cookie HttpOnly flag", "isactive" => 0], ["header" => "Cookie Samesite Lax flag", "isactive" => 0]];
			$directives = [["directive" => "autoplay", "isactive" => 0], ["directive" => "camera", "isactive" => 0], ["directive" => "document-domain", "isactive" => 0], ["directive" => "encrypted-media", "isactive" => 0], ["directive" => "fullscreen", "isactive" => 0], ["directive" => "geolocation", "isactive" => 0], ["directive" => "microphone", "isactive" => 0], ["directive" => "midi", "isactive" => 0], ["directive" => "payment", "isactive" => 0], ["directive" => "publickey-credentials-get", "isactive" => 0], ["directive" => "usb", "isactive" => 0], ["directive" => "xr-spatial-tracking", "isactive" => 0]];
			if (!empty($_POST)) {
				if (isset($_POST["server"]) && !empty($_POST["server"])) {
					if (!isset( $_POST['MSecSecurityHeaders-server']) || !wp_verify_nonce( $_POST['MSecSecurityHeaders-server'], 'magnisec-headers-setting' )) 
					{
						wp_nonce_ays( '' );
					} 
					$is_active = 0;
					$server_string = sanitize_text_field($_POST["server"]);
					foreach ($servers as $server_key=>$server)
					{
						if ($servers[$server_key]["server"] === $server_string)
						{	
							$is_active = 1;
							$servers[$server_key]["isactive"] = 1;
						}
					}
					if ($is_active === 0)
					{
						$link_server = "";
						if( is_multisite() && is_network_admin() )
						{
							$link_server = network_admin_url( 'admin.php?page=magnisec-headers-server' );
						}
						else if (!is_multisite())
						{
							$link_server = admin_url( 'options-general.php?page=magnisec-headers-server' );
						}
						?>
						<script type="text/javascript">
							document.location.href="<?php echo $link_server; ?>";
						</script>
						<?php
						exit;
					}
					foreach ($servers as $server_key=>$server)
					{
						$wpdb->update($table_name_servers, ["server" => $servers[$server_key]["server"], "isactive" => $servers[$server_key]["isactive"]], ["server" => $servers[$server_key]["server"]]);
					}
				}
				else {
					if (!isset( $_POST['MSecSecurityHeaders-configuration']) || !wp_verify_nonce( $_POST['MSecSecurityHeaders-configuration'], 'magnisec-headers-setting' )) 
					{
						wp_nonce_ays( '' );
					} 
					
					if (isset($_POST["header"]) && !empty($_POST["header"])) {
						$selected_headers_arr = [];
						foreach ($_POST["header"] as $header_str)
						{
							$selected_headers_arr[] = sanitize_text_field($header_str);
						}
						$selected_headers = array_merge($selected_headers, $selected_headers_arr);
					}
					if (isset($_POST["cookie"]) && !empty($_POST["cookie"])) {
						$selected_cookies_arr = [];
						foreach($_POST["cookie"] as $cookie_str)
						{
							$selected_cookies_arr[] = sanitize_text_field($cookie_str);
						}
						$selected_headers = array_merge($selected_headers, $selected_cookies_arr);
					}
					if (isset($_POST["directive"]) && !empty($_POST['directive'])) {
						$selected_directives_arr = [];
						foreach($_POST["directive"] as $directive_str)
						{
							$selected_directives_arr[] = sanitize_text_field($directive_str);
						}
						$selected_directives = array_merge($selected_directives, $selected_directives_arr);
					}
					$is_active = 0;
					$is_active_directive = 0;
					foreach ($headers as $k=>$v)
					{
						if (in_array("Permissions-Policy", $selected_headers))
						{
							$is_active_directive = 1;
						}
						if (in_array($headers[$k]["header"], $selected_headers))
						{
							$is_active = 1;
							$headers[$k]["isactive"] = 1;
						}
					}

					foreach ($headers as $k=>$v)
					{
						$header = $headers[$k]["header"];
						$isactive = $headers[$k]["isactive"];
						$wpdb->update($table_name, ["header" => $header, "isactive" => $isactive], ["header" => $header]);
						
					}
					foreach ($directives as $pk=>$pv)
					{
						if (in_array($directives[$pk]["directive"], $selected_directives))
						{
							$directives[$pk]["isactive"] = 1;
						}
					}
					if ($is_active_directive === 1)
					{
						foreach ($directives as $dk=>$dv)
						{
							$directive = $directives[$dk]["directive"];
							$disactive = $directives[$dk]["isactive"];
							$wpdb->update($table_name_directives, ["directive" => $directive, "isactive" => $disactive], ["directive" => $directive]);
						}
					}
					$all_directives = $directives;
					$nginx = 0;
					$prepare_server = $wpdb->prepare("SELECT server,isactive FROM magnisec_headers_website_servers");
					$query_server = $wpdb->get_results($prepare_server, ARRAY_A);
					foreach ($query_server as $s)
					{
						if ($s["server"] === "Nginx" && $s["isactive"] == 1)
						{
							$nginx = 1;
						}
					}
					if ($nginx === 1)
					{
						if($is_active === 1)
						{
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
							
							<script type="text/javascript">
								document.location.href="<?php echo $link; ?>";
							</script>
							<?php
						}

					}
					else
					{
						$this->magnisec_headers_notice();
						$this->magnisec_headers_alter_htaccess();
					}
				}
			}
		
			include( MSECSHH . '/views/magnisec_headers_form.php');
		}

		public function magnisec_headers_activate_server() {
			add_option('magnisec_headers_redirect_option_server', true);
		}
		
	
	
		public function magnisec_headers_redirect_server() {
			if (get_option('magnisec_headers_redirect_option_server', false)) {
				$link = "";
				if( is_multisite() && is_network_admin() )
				{
					$link = network_admin_url( 'admin.php?page=magnisec-headers-server' );
				}
				else if (!is_multisite())
				{
					$link = admin_url( 'options-general.php?page=magnisec-headers-server' );
				}
				delete_option('magnisec_headers_redirect_option_server');
				wp_redirect($link);
				exit;
			}
		}
	
		public function magnisec_headers_notice(){
			global $pagenow;
			if ( $pagenow == 'options-general.php' ) {
				 echo '<div class="notice notice-success is-dismissible">
					 <p>Your settings are saved!</p>
				 </div>';
			}
		}

		
		public function magnisec_headers_init() {
			global $wpdb;
			global $selected_headers;
			global $selected_directives;
			global $all_directives;
			global $servers;
			$all_directives = [];
			$servers = [];
			$table_name = 'magnisec_headers_security_headers';
			$selected_headers = [];
			$selected_directives = [];
			$sql = "DROP TABLE IF EXISTS $table_name;
			CREATE TABLE IF NOT EXISTS $table_name (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  header varchar(50) NOT NULL,
			  isactive tinyint(4) NOT NULL,
			  PRIMARY KEY  (id),
			  UNIQUE (header)
			);";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			$table_name_directives = 'magnisec_headers_permissions_directives';
			$sql_directives = "DROP TABLE IF EXISTS $table_name_directives;
			CREATE TABLE IF NOT EXISTS $table_name_directives (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  directive varchar(50) NOT NULL,
			  isactive tinyint(4) NOT NULL,
			  PRIMARY KEY  (id),
			  UNIQUE (directive)
			);";

			dbDelta($sql_directives);
			$table_name_servers = 'magnisec_headers_website_servers';
			$sql_servers = "DROP TABLE IF EXISTS $table_name_servers;
			CREATE TABLE IF NOT EXISTS $table_name_servers (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  server varchar(50) NOT NULL,
			  isactive tinyint(4) NOT NULL,
			  PRIMARY KEY  (id),
			  UNIQUE (server)
			);";
			dbDelta($sql_servers);
			$xheaders = [["header" => "Strict-Transport-Security", "isactive" => 1], ["header" => "Expect-CT", "isactive" => 1], ["header" => "Cross-Origin-Resource-Policy", "isactive" => 1], ["header" => "Referrer-Policy", "isactive" => 1], ["header" => "Cross-Origin-Embedder-Policy", "isactive" => 1], ["header" => "Cross-Origin-Opener-Policy", "isactive" => 1], ["header" => "Clear-Site-Data", "isactive" => 1], ["header" => "X-Download-Options", "isactive" => 1], ["header" => "X-Frame-Options", "isactive" => 1], ["header" => "X-Content-Type-Options", "isactive" => 1], ["header" => "Access-Control-Allow-Origin", "isactive" => 1], ["header" => "Permissions-Policy", "isactive" => 1], ["header" => "X-Permitted-Cross-Domain-Policies", "isactive" => 1], ["header" => "Cookie Secure flag", "isactive" => 1], ["header" => "Cookie HttpOnly flag", "isactive" => 1], ["header" => "Cookie Samesite Lax flag", "isactive" => 1]];
			$directives = [["directive" => "autoplay", "isactive" => 1], ["directive" => "camera", "isactive" => 1], ["directive" => "document-domain", "isactive" => 1], ["directive" => "encrypted-media", "isactive" => 1], ["directive" => "fullscreen", "isactive" => 1], ["directive" => "geolocation", "isactive" => 1], ["directive" => "microphone", "isactive" => 1], ["directive" => "midi", "isactive" => 1], ["directive" => "payment", "isactive" => 1], ["directive" => "publickey-credentials-get", "isactive" => 1], ["directive" => "usb", "isactive" => 1], ["directive" => "xr-spatial-tracking", "isactive" => 1]];
			$servers = [["server" => "Apache", "isactive" => 1], ["server" => "Nginx", "isactive" => 0]];
			foreach ($xheaders as $k=>$v)
			{
				$header = $xheaders[$k]["header"];
				$isactive = $xheaders[$k]["isactive"];
				$wpdb->insert($table_name, ["header" => $header, "isactive" => $isactive]);
				array_push($selected_headers, $header);
			}
			foreach ($directives as $dk=>$dv)
			{
				$directive = $directives[$dk]["directive"];
				$isactive = $directives[$dk]["isactive"];
				$wpdb->insert($table_name_directives, ["directive" => $directive, "isactive" => $isactive]);
			}
			$all_directives = $directives;
			foreach ($servers as $sk=>$sv)
			{
				$server = $servers[$sk]["server"];
				$isactive = $servers[$sk]["isactive"];
				$wpdb->insert($table_name_servers, ["server" => $server, "isactive" => $isactive]);
			}
		}

		public function magnisec_headers_add_settings_link( $links ) {
			$link = "";
			if( is_multisite() && is_network_admin() )
			{
				$link = network_admin_url( 'admin.php?page=magnisec-headers-server' );
				$links[] = '<a href="' . $link . '">' . __('Settings') . '</a>';
				return $links;
			}
			else if (!is_multisite())
			{
				$link = admin_url( 'options-general.php?page=magnisec-headers-server' );
				$links[] = '<a href="' . $link . '">' . __('Settings') . '</a>';
				return $links;
			}
			return $links;
		}
		
		


		public function magnisec_headers_alter_htaccess() {
			$htaccess = get_home_path().".htaccess";
			$home_url = home_url();
			global $selected_headers;
			global $selected_directives;
			global $all_directives;
			$lines = array();
			$wplines = array();
			$lines[] = "<IfModule mod_headers.c>";
			if (in_array("Referrer-Policy", $selected_headers))
			{
				$lines[] = "Header always set Referrer-Policy \"strict-origin-when-cross-origin\"";
			}
			if (in_array("Strict-Transport-Security", $selected_headers))
			{
				$lines[] = "Header always set Strict-Transport-Security \"max-age=31536000; includeSubDomains; preload\"";
			}
			if (in_array("Expect-CT", $selected_headers))
			{
				$lines[] = "Header always set Expect-CT \"max-age=604800, report-uri=\\\"". $home_url . "/report\\\"\"";
			}
			if (in_array("Cross-Origin-Resource-Policy", $selected_headers))
			{
				$lines[] = "Header always set Cross-Origin-Resource-Policy \"same-site\"";
			}
			if (in_array("Cross-Origin-Embedder-Policy", $selected_headers))
			{
				$lines[] = "Header always set Cross-Origin-Embedder-Policy \"require-corp; report-to=\\\"default\\\"\"";
			}
			if (in_array("Cross-Origin-Opener-Policy", $selected_headers))
			{
				$lines[] = "Header always set Cross-Origin-Opener-Policy \"same-origin; report-to=\\\"default\\\"\"";
			}
			if (in_array("Clear-Site-Data", $selected_headers))
			{
				$lines[] = "Header always set Clear-Site-Data \"\\\"cache\\\"\"";
			}
			if (in_array("X-Download-Options", $selected_headers))
			{
				$lines[] = "Header always set X-Download-Options \"noopen\"";
			}
			if (in_array("X-Frame-Options", $selected_headers))
			{
				$lines[] = "Header always set X-Frame-Options \"SAMEORIGIN\"";
			}
			if (in_array("X-Content-Type-Options", $selected_headers))
			{
				$lines[] = "Header always set X-Content-Type-Options \"nosniff\"";
			}
			if (in_array("Access-Control-Allow-Origin", $selected_headers))
			{
				$lines[] = "Header always set Access-Control-Allow-Origin \"". $home_url . "\"";
			}
			if (in_array("Permissions-Policy", $selected_headers))
			{
				$lines_directives = [];
				foreach ($all_directives as $dck=>$dcv)
				{
					if ($all_directives[$dck]["isactive"] === 0)
					{
						$lines_directives[] = $all_directives[$dck]["directive"] ."=()";
					}
					else
					{
						$lines_directives[] = $all_directives[$dck]["directive"] ."=(self)";
					}
				}
				$lines_directives_for_insert = implode(",", $lines_directives);
				$lines[] = "Header always set Permissions-Policy \"" . $lines_directives_for_insert . "\"";
			}
			if (in_array("X-Permitted-Cross-Domain-Policies", $selected_headers))
			{
				$lines[] = "Header always set X-Permitted-Cross-Domain-Policies \"none\"";
			}
			if (in_array("Cookie Secure flag", $selected_headers))
			{
				$lines[] = "Header edit Set-Cookie \"(?i)^((?:(?!;\s?Secure).)+)$\" \"$1; Secure\"";
			}
			if (in_array("Cookie HttpOnly flag", $selected_headers))
			{
				$lines[] = "Header edit Set-Cookie \"(?i)^((?:(?!;\s?HttpOnly).)+)$\" \"$1; HttpOnly\"";
			}
			if (in_array("Cookie Samesite Lax flag", $selected_headers))
			{
				$lines[] = "Header edit Set-Cookie \"(?i)^((?:(?!;\s?Samesite=Lax).)+)$\" \"$1; Samesite=Lax\"";
			}
			$lines[] = "</IfModule>";
			if (in_array("Cookie Secure flag", $selected_headers))
			{
				$lines[] = "php_flag session.cookie_secure on"; 
			}
			if (in_array("Cookie HttpOnly flag", $selected_headers))
			{
				$lines[] = "php_flag session.cookie_httponly on"; 
			}
			if (in_array("Cookie Samesite Lax flag", $selected_headers))
			{
				$lines[] = "php_value session.cookie_samesite Lax"; 
			}
			insert_with_markers($htaccess, "SecureHTTPHeadersPlugin", $lines);
		}

		public function magnisec_headers_remove_marker($contents, $marker) {
			$posa = strpos($contents, '# BEGIN '.$marker);
			$posb = strpos($contents, '# END '.$marker) + strlen('# END '.$marker);
			$newcontent = substr($contents, 0, $posa);
			$newcontent .= substr($contents, $posb, strlen($contents));
			return $newcontent;
		}
		
	
		public function magnisec_headers_deactivate(){
			global $wpdb;
			$htaccess = get_home_path().".htaccess";
			$table_name = 'magnisec_headers_security_headers';
			$sql = "DROP TABLE IF EXISTS $table_name;";
			$wpdb->query($sql);
			$table_name_directives = 'magnisec_headers_permissions_directives';
			$sql_directives = "DROP TABLE IF EXISTS $table_name_directives;";
			$wpdb->query($sql_directives);
			$table_name_servers = 'magnisec_headers_website_servers';
			$sql_servers = "DROP TABLE IF EXISTS $table_name_servers;";
			$wpdb->query($sql_servers);
			if (file_exists($htaccess))
			{
				insert_with_markers($htaccess, "SecureHTTPHeadersPlugin", "");
				$oldstr = file_get_contents($htaccess);
				if (strpos($oldstr, "# BEGIN SecureHTTPHeadersPlugin") !== false)
				{
					$marker = 'SecureHTTPHeadersPlugin';
					$newcontents = $this->magnisec_headers_remove_marker($oldstr, $marker);
					file_put_contents($htaccess, $newcontents);
				}
			}

			
		}



	}

 




$SecureHTTPHeaders = SecureHTTPHeaders::getMagniSecHeadersInstance();



?>