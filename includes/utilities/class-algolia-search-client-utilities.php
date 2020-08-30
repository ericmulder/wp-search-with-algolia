<?php
/**
 * Algolia_Client_Utilities class file.
 *
 * @since   1.4.1-dev
 * @package WebDevStudios\WPSWA
 */

/**
 * Class Algolia_Client_Utilities
 *
 * Static utilities for working with the Algolia Client.
 *
 * @since 1.4.1-dev
 */
class Algolia_Search_Client_Utilities {

	/**
	 * Determine if the Aloglia API "is alive".
	 *
	 * This does not determine if there are server configuration or network issues.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.4.1-dev
	 *
	 * @return bool
	 */
	public static function is_alive(): bool {

		$client = Algolia_Search_Client_Factory::create();

		try {
			$is_alive = $client->isAlive();
		} catch ( Exception $e ) {
			return false;
		}

		if (
			empty( $is_alive['message'] )
			|| 'server is alive' !== ( $is_alive['message'] )
		) {
			return false;
		}

		return true;
	}
}
