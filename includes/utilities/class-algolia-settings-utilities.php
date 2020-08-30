<?php
/**
 * Algolia_Settings_Utilities class file.
 *
 * @since   1.5.0-dev
 * @package WebDevStudios\WPSWA
 */

/**
 * Class Algolia_Settings_Utilities
 *
 * Static utilities for working with Settings.
 *
 * @since 1.5.0-dev
 */
class Algolia_Settings_Utilities {

	/**
	 * Determine if an Algolia Application ID is set.
	 *
	 * This does not check if the Algolia Application ID is valid,
	 * only that the ID is not empty.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since 1.5.0-dev
	 *
	 * @return bool
	 */
	public static function has_app_id(): bool {
		$settings = Algolia_Settings_Factory::create();
		return ! empty( $settings->get_application_id() );
	}

	/**
	 * Determine if an Algolia Admin API Key is set.
	 *
	 * This does not check if the Algolia Admin API Key is valid,
	 * only that the Admin API Key is not empty.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since 1.5.0-dev
	 *
	 * @return bool
	 */
	public static function has_admin_api_key(): bool {
		$settings = Algolia_Settings_Factory::create();
		return ! empty( $settings->get_api_key() );
	}

	/**
	 * Determine if an Algolia Search-Only API Key is set.
	 *
	 * This does not check if the Algolia Search-Only API Key is valid,
	 * only that the Search-Only API Key is not empty.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since 1.5.0-dev
	 *
	 * @return bool
	 */
	public static function has_search_api_key(): bool {
		$settings = Algolia_Settings_Factory::create();
		return ! empty( $settings->get_search_api_key() );
	}

	/**
	 * Determine if we have credentials.
	 *
	 * This does not determine if the credentials are valid,
	 * only that they are not empty.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.5.0-dev
	 *
	 * @return bool
	 */
	public static function has_credentials(): bool {
		if (
			! self::has_app_id() ||
			! self::has_admin_api_key() ||
			! self::has_search_api_key()
		) {
			return false;
		}

		return true;
	}
}
