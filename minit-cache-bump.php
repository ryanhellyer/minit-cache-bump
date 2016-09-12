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

	}

	/*
	 * Activate Cron job
	 */
	public static function activate() {
		wp_schedule_event( time(), 'hourly', 'minit_cron_cache_bump' );
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

}
new Minit_Cache_Bump;
