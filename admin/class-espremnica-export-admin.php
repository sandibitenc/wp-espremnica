<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Espremnica_Export
 * @subpackage Espremnica_Export/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Espremnica_Export
 * @subpackage Espremnica_Export/admin
 * @author     Your Name <email@example.com>
 */
class Espremnica_Export_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $espremnica_export    The ID of this plugin.
	 */
	private $espremnica_export;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $espremnica_export       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $espremnica_export, $version ) {

		$this->espremnica_export = $espremnica_export;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Espremnica_Export_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Espremnica_Export_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->espremnica_export, plugin_dir_url( __FILE__ ) . 'css/espremnica-export-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Espremnica_Export_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Espremnica_Export_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->espremnica_export, plugin_dir_url( __FILE__ ) . 'js/espremnica-export-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function espremnica_admin_menu(){

		$page_title = 'eSpreminca Export label data as CSV';
		$menu_title = 'eSpremnica Export';
		$capability = 'manage_options';
		$menu_slug  = 'espremnica-export';
		$function   = array($this, 'espremnica_admin_page');
		$icon_url   = 'dashicons-media-code';
		$position   = 4;
	  
		add_menu_page( $page_title,
					   $menu_title, 
					   $capability, 
					   $menu_slug, 
					   $function, 
					   $icon_url, 
					   $position );
	}
	public function espremnica_admin_page() {
		include_once( 'partials/espremnica-export-admin-display.php' );
	}

	public function espremnica_get_orders() {
		return $this->espremnica_display_table();
	}

	private function espremnica_display_table() {
		if($orders = $this->espremnica_get_orders_list()) {
			$print = '';
			foreach($orders as $order) {
				$print .= '<tr><td><input type="checkbox" name="' . $order['order_id'] . '" /></td>
							<td>' . $order['order_id'] . '</td>			
							<td>' . $order['shipping_type'] . '</td>
							<td>' . $order['order_weight'] . '</td>
							<td>' . $order['total'] . '</td>
							<td>' . $order['currency'] . '</td>
							<td>' . $order['country'] . '</td>
							<td>' . $order['country_number'] . '</td>
							<td>' . $order['payment_method'] . '</td>
							<td>' . $order['first_name'] . ' ' . $order['last_name'] . '</td>
							<td>' . $order['company'] . '</td>
							<td>' . $order['address'] . '</td>
							<td>' . $order['postcode'] . '</td>
							<td>' . $order['city'] . '</td>
							<td>' . $order['phone'] . '</td>
							<td>' . $order['email'] . '</td></tr>
				';
			}
			return $print;
		} else {
			return '<h2>Trenutno nimate novih naročil.</h2>';
		}
	}

	// returns array of sanitized orders
	private function espremnica_get_orders_list() {
		if($orders = $this->espremnica_get_orders_query()) {
			foreach($orders as $order) {
				$order_weight = $this->espremnica_get_order_weight($order);
				$order_data = $order->get_data();
				$order_id = $order_data['id'];

				//if shipping address is set use shipping else billing addresses
				$address_type = 'billing';
				if(!empty($order_data['shipping']['first_name'])) {
					$address_type = 'shipping';
				}

				//get country number
				$country_number = $this->espremnica_get_country_number($order_data[$address_type]['country']);
				$shipping_type = $this->espremnica_get_shipping_type($order_weight,$country_number);
				$total = str_replace('.', ',', $order_data['total']);

				$order_list[] = array(
					'order_id' => $order_id,
					'first_name' => preg_replace('/[_:~"$%*;<>#´]/', ' ', $order_data[$address_type]['first_name']),
					'last_name' => preg_replace('/[_:~"$%*;<>#´]/', ' ', $order_data[$address_type]['last_name']),
					'company' =>  preg_replace('/[_:~"$%*;<>#´]/', ' ', $order_data[$address_type]['company']),
					'address' =>  preg_replace('/[_:~"$%*;<>#´]/', ' ', $order_data[$address_type]['address_1']) . ' ' .  preg_replace('/[_:~"$%*;<>#´]/', ' ', $order_data[$address_type]['address_2']),
					'city' => preg_replace('/[_:~"$%*;<>#´]/', ' ',  $order_data[$address_type]['city']),
					'postcode' => $order_data[$address_type]['postcode'],
					'city' => preg_replace('/[_:~"$%*;<>#´]/', ' ', $order_data[$address_type]['city']),
					'country' => $order_data[$address_type]['country'],
					'country_number' => $country_number,
					'email' => $order_data['billing']['email'],
					'phone' => $order_data['billing']['phone'],
					'currency' => $order_data['currency'],
					'total' => $order_data['payment_method'] == 'cod' ? $total:'',
					'payment_method' => $order_data['payment_method'] == 'cod' ? 'ODKBN':'',
					'order_weight' => $order_weight,
					'shipping_type' => $shipping_type
				);
			}
			return $order_list;
		}
	}

	//returns an integer with current order weight in grams
	private function espremnica_get_order_weight($order) {
		$order_weight = 0;
		$order_total_qty = 0;
		$items = $order->get_items();
		if( sizeof( $items ) > 0 ) {
			foreach( $items as $item ) {
				$order_total_qty += $item['qty'];
				if ( $product = $order->get_product_from_item( $item ) ) {
					$order_weight += $product->get_weight() * $item['qty'];
				}
			}
		}
		return $order_weight;
	}

	//returns an array of all orders in processing
	private function espremnica_get_orders_query() {
		$query_args = array(
			'post_status'    => 'wc-processing',
			'posts_per_page' => -1,
		);
		$orders = wc_get_orders( $query_args );
		return $orders;
	}

	//returns a string with country number usable in espremnica
	private function espremnica_get_country_number($country_code) {
		global $wpdb;
		$table_name = $wpdb->prefix.'espremnica_country';
		$query = 'SELECT country_number FROM ' . $table_name . ' WHERE country_code = "' . $country_code . '" LIMIT 1;';
		$country_number_arr = $wpdb->get_results($query, ARRAY_N);
		$country_number = $country_number_arr[0][0];
		
		return $country_number;
	}

	//returns a string with shipping type code usable in espremnica
	private function espremnica_get_shipping_type($order_weight,$country_number) {
		$shipping_type = "103";
		$slovenia = array('705');
		$connect = array('040','276','056','203','348','528','616','703');
		if(in_array($country_number,$slovenia) && $order_weight < 390) {
			$shipping_type = '109';
		} else if(in_array($country_number,$slovenia) && $order_weight >= 390) {
			$shipping_type = '101';
		} else if(in_array($country_number,$connect)) {
			$shipping_type = '110';
		}
		return $shipping_type;
	}
	
	//creates CSV export data
	private function espremnica_array2csv() {
		$data = $this->espremnica_get_orders_list();
		if(count($data) != 0) {
			foreach($data as $array_row) {
				$array[] = array(
					'VrstaPosiljke' 	=> $array_row['shipping_type'],
					'CrtnaKoda' 		=> '',
					'Naziv' 			=> $array_row['first_name'] . ' ' . $array_row['last_name'],
					'DodatniNaziv' 		=> $array_row['company'],
					'Naslov' 			=> $array_row['address'],
					'PostnaSt' 			=> $array_row['postcode'],
					'NazivPoste' 		=> $array_row['city'],
					'Drzava' 			=> $array_row['country_number'],
					'TelSt' 			=> $array_row['phone'],
					'EMail' 			=> $array_row['email'],
					'IdNaslovnika' 		=> '',
					'Opomba' 			=> '',
					'Masa' 				=> $array_row['order_weight'],
					'DodatneStoritve' 	=> $array_row['payment_method'],
					'Odkupnina' 		=> $array_row['total'],
					'Vrednost' 			=> '',
					'VrstaVplDok' 		=> '',
					'Ref.X' 			=> '',
					'Model' 			=> '',
					'Sklic' 			=> $array_row['total'] ? $array_row['order_id'] :'',
					'Namen' 			=> '',
					'OdkupninaVValuti' 	=> '',
					'Valuta' 			=> '',
					'Navodilo' 			=> ''
				);
			}
		} else {
			return null;
		}
		return $array;
	}
	private function espremnica_create_file() {
		if($array = $this->espremnica_array2csv()) {
			$file = fopen('php://output', 'w') or die('Napaka. Ne morem odpreti datoteke.');
			
			$arrayImplode = "";
			foreach($array as $row) {
				$arrayImplode .= implode(';',$row) . "<br>";
			}
			fclose($file);
			return $arrayImplode;
			//return $file;
		}
	}

	//sets headers and creates and starts file download
	private function download_send_headers($filename) {
		// disable caching
		
		
		$now = gmdate("D, d M Y H:i:s");
		
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");
	
		// force download  
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
	
		// disposition / encoding on response body
		header('Content-Type: text/csv');
		header('Content-Type: application/csv; charset=ISO-8859-2');
		header('Content-Length: '.strlen($filename));
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");

	}

	public function espremnica_start_download() {
		//$filename = "espremnica_" . date("Y-m-d") . ".csv";
		//$filename = 'espremnica.csv';
			//$this->download_send_headers($filename);
			echo $this->espremnica_create_file();
		
		//exit();
	}

	//upload csv from espremnica
	public function espremnica_csv_upload() {
		
		if (isset($_POST['submit'])) {
			if (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
	
					echo "<h1>" . "File ". $_FILES['csv_file']['name'] ." uploaded successfully." . "</h1>";
			}
	
			$csv = array_map(function($v){return str_getcsv($v, ";");}, file($_FILES['csv_file']['tmp_name']));
			$header = array_shift($csv);

			$this->espremnica_merge_csv_import_data($csv);
		}
	}

	private function espremnica_merge_csv_import_data($csv) {
		$orders = $this->espremnica_get_orders_list();
		foreach($csv as $row) {
			foreach ($orders as $order) {
				if($row[3] == $order['first_name'] . ' ' . $order['last_name'] &&
					$row[4] == $order['address'] &&
					$row[18] == $order['order_weight']) {
						$order_date = date_i18n("d. F, Y", strtotime($row[0]));
						echo $order['order_id'] . ' ' . $row[1] . ' ' . $order_date . '<br>';
						WfTrackingUtil::update_tracking_data($order['order_id'], $row[1], 'posto-slovenije', WF_Tracking_Admin::SHIPMENT_SOURCE_KEY, WF_Tracking_Admin::SHIPMENT_RESULT_KEY, $order_date);
						
						$order_to_update = wc_get_order( $order['order_id'] );
						$order_to_update->update_status( 'completed' );

				}
			}
		}
	}

}


