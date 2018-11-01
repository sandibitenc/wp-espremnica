<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Espremnica_Export
 * @subpackage Espremnica_Export/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Espremnica_Export
 * @subpackage Espremnica_Export/includes
 * @author     Your Name <email@example.com>
 */
class Espremnica_Export_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix.'espremnica_country';
		$country_number = 'country_number';
		$country_code = 'country_code';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			//table not in database. Create new table
			$charset_collate = $wpdb->get_charset_collate();
		
			$sql = "CREATE TABLE $table_name (
				id mediumint(9) NOT NULL AUTO_INCREMENT, "
				. $country_code . " varchar(2) NOT NULL, "
				. $country_number . " varchar(3) NOT NULL,
				UNIQUE KEY id (id)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		
			if($csvFile = file(plugin_dir_url( __FILE__ ) . '../admin/assets/country_codes.csv')) {
				$rows = array_map('str_getcsv',$csvFile);
			} else {
				return 'error';
			}
			
			$query = 'INSERT INTO ' . $table_name . ' (' . $country_number . ', ' . $country_code . ') VALUES ';
			foreach($rows as $row) {
				$query_data[] = '("' . $row[0] . '", "' . $row[1] . '") ';
			}
			$query .= implode(',',$query_data);
			$wpdb->query($query);	
		
		}
		else{
		}	
	}

}
