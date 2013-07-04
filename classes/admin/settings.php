<?php

class BEA_PVC_Admin_Settings {
	static $settings_api;
	static $id = 'bea-pvc-main';

	/**
	 * Register hooks
	 * 
	 * @access public
	 *
	 */
	public function __construct() {
		self::$settings_api = new WeDevs_Settings_API();

		add_action('admin_menu', array(__CLASS__, 'admin_menu'));
		add_action('admin_init', array(__CLASS__, 'admin_init'));
	}

	/**
	 * Register page on WP admin
	 *
	 * @access public
	 * @static
	 */
	public static function admin_menu() {
		add_options_page(__('BEA Post Views Counter', 'bea-post-views-counter'), __('Post Views Counter', 'bea-post-views-counter'), 'manage_options', 'bea-pvc-settings', array(__CLASS__, 'render_page_settings'));
	}

	/**
	 * Include settings view (MVC)
	 * 
	 * @access public
	 * @static
	 */
	public static function render_page_settings() {
		include (BEA_PVC_DIR . 'views/admin/page-settings.php');
	}

	/**
	 * Declare sections, fields used WeDevs_Settings_API class
	 * 
	 * @access public
	 * @static
	 */
	public static function admin_init() {
		//set the settings
		self::$settings_api->set_sections(self::get_settings_sections());
		self::$settings_api->set_fields(self::get_settings_fields());

		//initialize settings
		self::$settings_api->admin_init();
	}

	/**
	 * Declaration of all settings sections
	 * 
	 * @return array
	 */
	public static function get_settings_sections() {
		$sections = array(
			array(
				'id' => 'bea-pvc-main',
				'tab_label' => __('General', 'bea-post-views-counter'),
				'title' => __('General', 'bea-post-views-counter'),
				'desc' => false,
			),
		);
		return $sections;
	}

	/**
	 * Declaration of all the settings fields
	 *
	 * @return array settings fields
	 */
	public static function get_settings_fields() {
		$settings_fields = array(
			'bea-pvc-main' => array(
				array(
					'name' => 'mode',
					'label' => __('Counter mode', 'bea-post-views-counter'),
					'type' => 'radio',
					'default' => 'js-wp',
					'options' => array(
						'inline' => __('Inline', 'bea-post-views-counter'),
						'js-wp' => __('JS call with WordPress (default, best compromise)', 'bea-post-views-counter'),
					),
					'desc' => __('Mode <strong>inline</strong> is the simplest, most reliable, but it is not compatible with plugins static cache.<br />The  mode "JS Call" add asynchronous JavaScript code in the footer of your site for compatibilizing the number of views. A 100% PHP implementation is available as an <a href="https://github.com/herewithme/bea-post-views-counter-fullphp-addon">additional plugin</a>, it offers performance 10 times better than the two native modes.', 'bea-post-views-counter'),
				),
				array(
					'name' => 'include',
					'label' => __('Include', 'bea-post-views-counter'),
					'type' => 'radio',
					'default' => 'all',
					'options' => array(
						'all' => __('Everyone', 'bea-post-views-counter'),
						'guests' => __('Guests only', 'bea-post-views-counter'),
						'registered' => __('Users logged only', 'bea-post-views-counter'),
					),
					'desc' => __('Note that this setting does not work with pure PHP mode, all visitors will be recorded.', 'bea-post-views-counter')
				),
				array(
					'name' => 'exclude',
					'label' => __('Exclude', 'bea-post-views-counter'),
					'options' => array(
						'robots' => __('Robots (search, etc)', 'bea-post-views-counter'),
						'administrator' => __('Administrators', 'bea-post-views-counter')
					),
					'type' => 'multicheck',
					'desc' => __('It is important to exclude robots counters views because they regularly browsing your site and they distort the statistics. Note that the exclusion of users logged in with the administrator role does not work with pure PHP mode.', 'bea-post-views-counter')
				),
				array(
					'name' => 'exclude_ips',
					'label' => __('Exclude IPs:', 'bea-post-views-counter'),
					'desc' => __('You can exclude IP addresses of your choice, separate them with commas.', 'bea-post-views-counter'),
					'type' => 'textarea',
					'default' => ''
				),
				array(
                    'name' => 'session',
                    'label' => __( 'Session', 'mpt' ),
                    'options' => __( 'Increment only once counter per user session?', 'mpt' ),
                    'type' => 'checkbox',
                    'default' => 0,
                    'desc' => __('This setting prevents manipulation counters views. It allows only one counter increments during a browsing session of the user. This feature uses the PHP SESSION, to avoid technical limitations of cookies. Finally, it reduces the performance of your server if your site generates a lot of traffic!', 'mpt' )
                ),
			),
		);
		
		return apply_filters( 'bea_pvc_get_settings_fields', $settings_fields );
	}

}