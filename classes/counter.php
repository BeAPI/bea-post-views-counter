<?php

/**
 * Class for counter object, make abstraction for DB request, allow full PHP implementation with child
 */
class BEA_PVC_Counter {

	protected $_id = 0;
	protected $_data = null;
	protected $_insertion = false;
	protected $_current_time = 0;
	public $increment_sequence = 1;

	public function __construct($post_id = 0, $auto_fill = true) {
		$post_id = (int) $post_id;
		if ($post_id > 0) {
			$this->_current_time = gmdate('Y-m-d H:i:s');
			$this->_id = $post_id;

			if ($auto_fill == true) {
				$this->_fill_data();
			}
		}
	}

	public function exists() {
		return ( $this->_id > 0 ) ? true : false;
	}

	protected function _get_sql_fields() {
		$sql_fields = "";
		$sql_fields .= " (TIMESTAMPDIFF(DAY, day_date, NOW())) AS day_date_diff, "; // Day
		$sql_fields .= " (TIMESTAMPDIFF(WEEK, week_date, NOW())) AS week_date_diff, "; // Week
		$sql_fields .= " (TIMESTAMPDIFF(MONTH, month_date, NOW())) AS month_date_diff, "; // Month
		$sql_fields .= " (TIMESTAMPDIFF(YEAR, year_date, NOW())) AS year_date_diff "; // Year
		return $sql_fields;
	}

	protected function _fill_data() {
		$this->_data = $this->_get_row(sprintf("SELECT *, %s FROM %s WHERE post_id = %d", $this->_get_sql_fields(), $this->_get_table_name(), $this->_id));
		if ($this->_data == false) {
			// Mark flag for insertion request
			$this->_insertion = true;

			// Default values
			$this->_data = array(
				'day_counter' => 0,
				'day_date' => $this->_current_time,
				'day_date_diff' => 0,
				'previous_day_counter' => 0,
				'week_counter' => 0,
				'week_date' => $this->_current_time,
				'week_date_diff' => 0,
				'previous_week_counter' => 0,
				'month_counter' => 0,
				'month_date' => $this->_current_time,
				'month_date_diff' => 0,
				'previous_month_counter' => 0,
				'year_counter' => 0,
				'year_date' => $this->_current_time,
				'year_date_diff' => 0,
				'previous_year_counter' => 0,
				'total' => 0
			);
		} else {
			unset($this->_data['post_id']);
		}
	}

	/**
	 * Only for WP method, used WPDB
	 * 
	 * @global type $wpdb
	 * @return boolean
	 */
	public function delete() {
		global $wpdb;

		if (!$this->exists()) {
			return false;
		}

		return $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->post_views_counter WHERE post_id = %d", $this->_id));
	}

	public function increment( $force = false ) {
		if (!$this->exists()) {
			return false;
		}

		if ( !$this->is_allowed_to_increment() && $force == false ) {
			return false;
		}

		$this->increment_day();
		$this->increment_week();
		$this->increment_month();
		$this->increment_year();
		$this->increment_total();

		$this->commit();
		return true;
	}

	public function increment_day() {
		if (!$this->exists()) {
			return false;
		}

		if (!$this->_is_same_date('d/m/Y', $this->_data['day_date'])) {
			$this->_data['day_date'] = $this->_current_time;
			$this->_data['previous_day_counter'] = ( (int) $this->_data['day_date_diff'] > 1 ) ? 0 : $this->_data['day_counter'];
			$this->_data['day_counter'] = 0;
		}

		$this->_data['day_counter'] += $this->increment_sequence;
		return true;
	}

	public function increment_week() {
		if (!$this->exists()) {
			return false;
		}

		if (!$this->_is_same_date('W/Y', $this->_data['week_date'])) {
			$this->_data['week_date'] = $this->_current_time;
			$this->_data['previous_week_counter'] = ( (int) $this->_data['week_date_diff'] > 1 ) ? 0 : $this->_data['week_counter'];
			$this->_data['week_counter'] = 0;
		}

		$this->_data['week_counter'] += $this->increment_sequence;
		return true;
	}

	public function increment_month() {
		if (!$this->exists()) {
			return false;
		}

		if (!$this->_is_same_date('m/Y', $this->_data['month_date'])) {
			$this->_data['month_date'] = $this->_current_time;
			$this->_data['previous_month_counter'] = ( (int) $this->_data['month_date_diff'] > 1 ) ? 0 : $this->_data['month_counter'];
			$this->_data['month_counter'] = 0;
		}

		$this->_data['month_counter'] += $this->increment_sequence;
		return true;
	}

	public function increment_year() {
		if (!$this->exists()) {
			return false;
		}

		if (!$this->_is_same_date('Y', $this->_data['year_date'])) {
			$this->_data['year_date'] = $this->_current_time;
			$this->_data['previous_year_counter'] = ( (int) $this->_data['year_date_diff'] > 1 ) ? 0 : $this->_data['year_counter'];
			$this->_data['year_counter'] = 0;
		}

		$this->_data['year_counter'] += $this->increment_sequence;
		return true;
	}

	public function increment_total() {
		if (!$this->exists()) {
			return false;
		}

		$this->_data['total'] += $this->increment_sequence;
		return true;
	}

	public function commit() {
		if (!$this->exists()) {
			return false;
		}

		// Remove diff field before commit
		unset($this->_data['day_date_diff'], $this->_data['week_date_diff'], $this->_data['month_date_diff'], $this->_data['year_date_diff']);

		if ($this->_insertion == true) {
			$this->_data['post_id'] = $this->_id;
			return $this->_insert($this->_get_table_name(), $this->_data);
		} else {
			return $this->_update($this->_get_table_name(), $this->_data, array('post_id' => $this->_id));
		}
	}

	protected static function _filter_diff($value) {
		return !in_array($value, array('day_date_diff'));
	}

	protected function _get_table_name() {
		global $wpdb;
		return $wpdb->post_views_counter;
	}

	protected function _get_row($query = "") {
		global $wpdb;
		return $wpdb->get_row($query, ARRAY_A);
	}

	protected function _insert($table_name = '', $values = array()) {
		global $wpdb;
		return $wpdb->insert($table_name, $values);
	}

	protected function _update($table_name = '', $values = array(), $where = array()) {
		global $wpdb;
		return $wpdb->update($table_name, $values, $where);
	}

	protected function _format_date($format = '', $date = '') {
		$timestamp = strtotime($date);
		if ($timestamp == 0) {
			$timestamp = time();
		}

		return date($format, $timestamp);
	}

	protected function _is_same_date($format = '', $date1 = false, $date2 = false) {
		// Date1 is required
		if ($date1 == false) {
			return false;
		}

		// Date2 is optionnal, get it from class variable
		if ($date2 == false) {
			$date2 = $this->_current_time;
		}

		if ($this->_format_date($format, $date1) == $this->_format_date($format, $date2)) {
			return true;
		}

		return false;
	}

	public function get_data() {
		return $this->_data;
	}

	public function get_data_value($field) {
		if (isset($this->_data[$field])) {
			return $this->_data[$field];
		}

		return '';
	}

	public function get_post_id() {
		return $this->_id;
	}

	public function is_allowed_to_increment() {
		$current_options = $this->get_option('bea-pvc-main');
		if ( $current_options == false ) { // No option ? Allow everyone !
			return true;
		}
		
		// Exclude must be an array
		$current_options['exclude'] = !isset($current_options['exclude']) ? array() : $current_options['exclude'];
		$current_options['exclude'] = (array) $current_options['exclude'];
		
		// Exclusion : Robots
		if ( in_array('robots', $current_options['exclude']) && $this->is_robots() ) {
			return false;
		}
		
		// Exclusion : Logged administrator
		if ( in_array('administrator', $current_options['exclude']) && function_exists('current_user_can') && current_user_can('administrator') ) {
			return false;
		}
		
		// Exclusion IPs
		if ( isset($current_options['exclude_ips']) && !empty($current_options['exclude_ips']) ) {
			$current_options['exclude_ips'] = explode(',', $current_options['exclude_ips']);
			$current_options['exclude_ips'] = array_filter($current_options['exclude_ips'], 'strlen'); 
			
			$current_user_ip = self::get_user_ip();
			if ( in_array($current_user_ip, $current_options['exclude_ips']) ) {
				return false;
			}
		}
		
		// Inclusion, all, guest only, logged only
		$current_options['include'] = !isset($current_options['include']) ? 'all' : $current_options['include'];
		if ( $current_options['include'] == 'guests' && function_exists('is_user_logged_in') && is_user_logged_in() ) {
			return false;
		} elseif ( $current_options['include'] == 'registered' && function_exists('is_user_logged_in') && !is_user_logged_in() ) {
			return false;
		}
		
		return true;
	}
	
	protected function get_option( $option_name = '' ) {
		return get_option( $option_name );
	}


	/**
	 * Get current user IP, try all known sources 
	 * 
	 * @return string
	 */
	public static function get_user_ip() {
		$ipaddress = false;
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) && !empty( $_SERVER['HTTP_X_FORWARDED'] ) ) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) && !empty( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) && !empty( $_SERVER['HTTP_FORWARDED'] ) ) {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) && !empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		}

		return $ipaddress;
	}

	protected function is_robots() {
		$bots = array('Google Bot' => 'googlebot', 'Google Bot' => 'google', 'MSN' => 'msnbot', 'Alex' => 'ia_archiver', 'Lycos' => 'lycos', 'Ask Jeeves' => 'jeeves', 'Altavista' => 'scooter', 'AllTheWeb' => 'fast-webcrawler', 'Inktomi' => 'slurp@inktomi', 'Turnitin.com' => 'turnitinbot', 'Technorati' => 'technorati', 'Yahoo' => 'yahoo', 'Findexa' => 'findexa', 'NextLinks' => 'findlinks', 'Gais' => 'gaisbo', 'WiseNut' => 'zyborg', 'WhoisSource' => 'surveybot', 'Bloglines' => 'bloglines', 'BlogSearch' => 'blogsearch', 'PubSub' => 'pubsub', 'Syndic8' => 'syndic8', 'RadioUserland' => 'userland', 'Gigabot' => 'gigabot', 'Become.com' => 'become.com');
		foreach ($bots as $bot) {
			if (stristr($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
				return true;
			}
		}

		return false;
	}

}