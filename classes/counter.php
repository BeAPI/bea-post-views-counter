<?php
/**
 * Class for counter object, make abstraction for DB request, allow full PHP implementation with child
 */
class BEA_PVC_Counter {
	protected $_id = 0;
	protected $_data = null;
	protected $_insertion = false;
	protected $_current_time = 0;
	
	public  $increment_sequence = 1;
	
	public function __construct( $post_id = 0, $auto_fill = true ) {
		$post_id = (int) $post_id;
		if ( $post_id > 0 ) {
			$this->_current_time = gmdate( 'Y-m-d H:i:s' );
			$this->_id = $post_id;
			
			if ( $auto_fill == true ) {
				$this->_fill_data();
			}
		}
	}
	
	public function exists() {
		return ( $this->_id > 0 ) ? true : false;
	}
	
	protected function _fill_data() {
		$this->_data = $this->_get_row( sprintf("SELECT * FROM %s WHERE post_id = %d", $this->_get_table_name(), $this->_id) );
		if ( $this->_data == false ) {
			// Mark flag for insertion request
			$this->_insertion = true;
			
			// Default values
			$this->_data = array( 
				'day_counter' => 0, 
				'day_date' => $this->_current_time, 
				'previous_day_counter' => 0, 
				'week_counter' => 0, 
				'week_date' => $this->_current_time, 
				'previous_week_counter' => 0, 
				'month_counter' => 0, 
				'month_date' => $this->_current_time, 
				'previous_month_counter' => 0, 
				'year_counter' => 0, 
				'year_date' => $this->_current_time,
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
		
		if ( !$this->exists() ) {
			return false;
		}
		
		return $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->post_views_counter WHERE post_id = %d", $this->_id));
	}
	
	public function increment() {
		if ( !$this->exists() ) {
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
		if ( !$this->exists() ) {
			return false;
		}
		
		if ( !$this->_is_same_date( 'd/m/Y', $this->_data['day_date'] ) ) {
			$this->_data['day_date'] = $this->_current_time;
			$this->_data['previous_day_counter'] = $this->_data['day_counter'];
			$this->_data['day_counter'] = 0;
		}
		
		$this->_data['day_counter'] += $this->increment_sequence;
		return true;
	}
	
	public function increment_week() {
		if ( !$this->exists() ) {
			return false;
		}
		
		if ( !$this->_is_same_date( 'W/Y', $this->_data['week_date'] ) ) {
			$this->_data['week_date'] = $this->_current_time;
			$this->_data['previous_week_counter'] = $this->_data['week_counter'];
			$this->_data['week_counter'] = 0;
		}
		
		$this->_data['week_counter'] += $this->increment_sequence;
		return true;
	}
	
	public function increment_month() {
		if ( !$this->exists() ) {
			return false;
		}
		
		if ( !$this->_is_same_date( 'm/Y', $this->_data['month_date'] ) ) {
			$this->_data['month_date'] = $this->_current_time;
			$this->_data['previous_month_counter'] = $this->_data['month_counter'];
			$this->_data['month_counter'] = 0;
		}
		
		$this->_data['month_counter'] += $this->increment_sequence;
		return true;
	}
	
	public function increment_year() {
		if ( !$this->exists() ) {
			return false;
		}
		
		if ( !$this->_is_same_date( 'Y', $this->_data['year_date'] ) ) {
			$this->_data['year_date'] = $this->_current_time;
			$this->_data['previous_year_counter'] = $this->_data['year_counter'];
			$this->_data['year_counter'] = 0;
		}
		
		$this->_data['year_counter'] += $this->increment_sequence;
		return true;
	}
	
	public function increment_total() {
		if ( !$this->exists() ) {
			return false;
		}
		
		$this->_data['total'] += $this->increment_sequence;
		return true;
	}
	
	public function commit() {
		if ( !$this->exists() ) {
			return false;
		}
		
		if ( $this->_insertion == true ) {
			$this->_data['post_id'] = $this->_id;
			$this->_insert( $this->_get_table_name(), $this->_data );
		} else {
			$this->_update( $this->_get_table_name(), $this->_data, array( 'post_id' => $this->_id ) );
		}
		return true;
	}
	
	protected function _get_table_name() {
		global $wpdb;
		return $wpdb->post_views_counter;
	}
	
	protected function _get_row( $query = "" ) {
		global $wpdb;
		return $wpdb->get_row( $query, ARRAY_A );
	}
	
	protected function _insert( $table_name = '', $values = array() ) {
		global $wpdb;
		return $wpdb->insert( $table_name, $values );
	}
	
	protected function _update( $table_name = '', $values = array(), $where = array() ) {
		global $wpdb;
		return $wpdb->update( $table_name, $values, $where );
	}
	
	protected function _format_date( $format = '', $date = '' ) {
		$timestamp = strtotime( $date );
		if ( $timestamp == 0 ) {
			$timestamp = time();
		}
		
		return date( $format, $timestamp );
	}
	
	protected function _is_same_date( $format = '', $date1 = false, $date2 = false ) {
		// Date1 is required
		if ( $date1 == false ) {
			return false;
		}
		
		// Date2 is optionnal, get it from class variable
		if ( $date2 == false ) {
			$date2 = $this->_current_time;
		}
		
		if ( $this->_format_date($format, $date1) == $this->_format_date($format, $date2) ) {
			return true;
		}
		
		return false;
	}
	
	public function get_data() {
		return $this->_data;
	}
	
	public function get_post_id() {
		return $this->_id;
	}
}