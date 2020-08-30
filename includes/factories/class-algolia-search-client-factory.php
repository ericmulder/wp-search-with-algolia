<?php
/**
 * Algolia_Php53_Http_Client_Factory class file.
 *
 * @since   1.5.0-dev
 * @package WebDevStudios\WPSWA
 */

use Algolia\AlgoliaSearch\Algolia;
use Algolia\AlgoliaSearch\SearchClient;

/**
 * Class Algolia_Search_Client_Factory
 *
 * Responsible for creating a shared instance of the SearchClient object.
 *
 * @since 1.5.0-dev
 */
class Algolia_Search_Client_Factory {

	/**
	 * Create and return an instance of the SearchClient.
	 *
	 * @author Richard Aber <richard.aber@webdevstudios.com>
	 * @since  1.5.0-dev
	 *
	 * @return SearchClient
	 */
	public static function create(): SearchClient {

		/**
		 * The static instance to share, else null.
		 *
		 * @since 1.5.0-dev
		 *
		 * @var null|SearchClient $search_client
		 */
		static $search_client = null;

		if ( null !== $search_client ) {
			return $search_client;
		}

		$settings    = Algolia_Settings_Factory::create();
		$http_client = Algolia_Http_Client_Interface_Factory::create();

		Algolia::setHttpClient( $http_client );

		$search_client = SearchClient::create(
			$settings->get_application_id(),
			$settings->get_api_key()
		);

		return $search_client;
	}
}
