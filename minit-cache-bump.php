<?php
/*
Plugin Name: Minit Cache Bump
Plugin URI: https://geek.hellyer.kiwi/plugins/
Description: Bumps the Minit cache version automatically
Author: Ryan Hellyer
Version: 1.0
Author URI: https://geek.hellyer.kiwi/
*/


/**
 * Bump the Minit cache version via WP Cron job.
 */
class Minit_Cache_Bump {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		// Schedule and deschedule on plugin activation/deactivation
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Hook the cache purge
		add_action( 'minit_cron_cache_bump', array( $this, 'cache_bump' ) );

		// Add new WP Cron schedule
		add_filter( 'cron_schedules', array( $this, 'cron_schedule' ) );

	}

	/*
	 * Activate Cron job
	 */
	public static function activate() {
		wp_schedule_event( time(), 'once_per_minute', 'minit_cron_cache_bump' );
	}

	/*
	 * Deactivate Cron job
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( 'minit_cron_cache_bump' );
	}

	/*
	 * Bump the cache version.
	 */
	public function cache_bump() {

		do_action( 'minit-cache-version-bump' );

	}

	/**
	 * Adds a new cron interval to the WordPress scheduled tasks.
	 *
	 * Method adapted from code by Tom McFarlin (https://tommcfarlin.com/new-wordpress-cron-schedule/)
	 *
	 * @param  array $schedules The array of schedules that WordPress provides.
	 * @return array The updated schedules with the weekly interval defined.
	 */
	public function cron_schedule( $schedules ) {

		$schedules['once_per_minute'] = array(
			'interval' => 60,
			'display'  => 'Once per minute'
		);

		return $schedules;
	}

}
new Minit_Cache_Bump;
