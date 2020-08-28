<?php
/**
 * Algolia_Http_Client_Interface_Factory class file.
 *
 * @since   1.4.1-dev
 * @package WebDevStudios\WPSWA
 */

use Algolia\AlgoliaSearch\Http\Guzzle6HttpClient;
use Algolia\AlgoliaSearch\Http\HttpClientInterface;
use Algolia\AlgoliaSearch\Http\Php53HttpClient;

/**
 * Class Algolia_Http_Client_Interface_Factory
 *
 * Responsible for creating a shared instance of the HttpClientInterface object.
 *
 * @since 1.4.1-dev
 */
class Algolia_Http_Client_Interface_Factory {

	/**
	 * Create and return an instance of the HttpClientInterface.
	 *
	 * @author Richard Aber <richard.aber@webdevstudios.com>
	 * @since  1.4.1-dev
	 *
	 * @return HttpClientInterface The shared HttpClientInterface instance.
	 */
	public static function create(): HttpClientInterface {
		/**
		 * The static instance to share, else null.
		 *
		 * @since 1.4.1-dev
		 *
		 * @var null|HttpClientInterface $http_client
		 */
		static $http_client = null;

		if ( null !== $http_client ) {
			return $http_client;
		}

		if ( class_exists( '\GuzzleHttp\Client' ) && 6 <= self::get_guzzle_version() ) {
			$http_client = self::create_guzzle_6_http_client();
		} else {
			$http_client = self::create_php53_http_client();
		}

		return $http_client;
	}

	/**
	 * Get the Guzzle version.
	 *
	 * @author Richard Aber <richard.aber@webdevstudios.com>
	 * @since  1.4.1-dev
	 *
	 * @return int|null
	 */
	protected static function get_guzzle_version(): ?int {

		$guzzle_version = null;

		if ( interface_exists( '\GuzzleHttp\ClientInterface' ) ) {
			if ( defined( '\GuzzleHttp\ClientInterface::VERSION' ) ) {
				$guzzle_version = (int) substr( \GuzzleHttp\Client::VERSION, 0, 1 );
			} else {
				$guzzle_version = \GuzzleHttp\ClientInterface::MAJOR_VERSION;
			}
		}

		return $guzzle_version;
	}

	/**
	 * Create a Guzzle6HttpClient client.
	 *
	 * @author Richard Aber <richard.aber@webdevstudios.com>
	 * @since  1.4.1-dev
	 *
	 * @return Guzzle6HttpClient
	 */
	protected static function create_guzzle_6_http_client(): Guzzle6HttpClient {

		/**
		 * Allow developers to override the Guzzle6HttpClient options.
		 *
		 * @author Richard Aber <richard.aber@webdevstudios.com>
		 * @since 1.4.1-dev
		 *
		 * @param array $options Options for Guzzle6HttpClient construction.
		 */
		$options = apply_filters(
			'algolia_guzzle_6_http_client_options',
			[
				'CURLOPT_CAINFO' => ALGOLIA_PATH . 'resources/ca-bundle.crt',
			]
		);

		return new Guzzle6HttpClient( $options );
	}

	/**
	 * Create a Php53HttpClient client.
	 *
	 * @author Richard Aber <richard.aber@webdevstudios.com>
	 * @since  1.4.1-dev
	 *
	 * @return Php53HttpClient
	 */
	protected static function create_php53_http_client(): Php53HttpClient {

		/**
		 * Allow developers to override the Php53HttpClient options.
		 *
		 * @author Richard Aber <richard.aber@webdevstudios.com>
		 * @since 1.4.1-dev
		 *
		 * @param array $options Options for Php53HttpClient construction.
		 */
		$options = apply_filters(
			'algolia_php53_http_client_options',
			[
				'CURLOPT_CAINFO' => ALGOLIA_PATH . 'resources/ca-bundle.crt',
			]
		);

		return new Php53HttpClient( $options );
	}
}
