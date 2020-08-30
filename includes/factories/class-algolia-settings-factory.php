<?php
/**
 * Algolia_Settings_Factory class file.
 *
 * @since   1.5.0-dev
 * @package WebDevStudios\WPSWA
 */

/**
 * Class Algolia_Settings_Factory
 *
 * Responsible for creating a shared instance of the Algolia_Settings object.
 *
 * @since 1.5.0-dev
 */
class Algolia_Settings_Factory {

	/**
	 * Create and return an instance of the Algolia_Settings.
	 *
	 * @author Richard Aber <richard.aber@webdevstudios.com>
	 * @since  1.5.0-dev
	 *
	 * @return Algolia_Settings The shared Algolia_Settings instance.
	 */
	public static function create(): Algolia_Settings {

		/**
		 * The static instance to share, else null.
		 *
		 * @since 1.5.0-dev
		 *
		 * @var null|Algolia_Settings $settings
		 */
		static $settings = null;

		if ( null !== $settings ) {
			return $settings;
		}

		$settings = new Algolia_Settings();

		return $settings;
	}
}
