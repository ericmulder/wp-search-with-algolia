<?php
/**
 * Admin Page Settings Section template partial.
 *
 * @author  WebDevStudios <contact@webdevstudios.com>
 * @since   1.5.0-dev
 *
 * @package WebDevStudios\WPSWA
 */

?>

	<?php if ( ! Algolia_Settings_Utilities::has_credentials() ) : ?>
		<p>
			<?php esc_html_e( 'Configure your Algolia account credentials. You can find them in the "API Keys" section of your Algolia dashboard.', 'wp-search-with-algolia' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Once you provide your Algolia Application ID and API key, this plugin will be able to securely communicate with Algolia servers. We ensure your information is correct by testing them against the Algolia servers upon save.', 'wp-search-with-algolia' ); ?>
		</p>
		<p>
			<?php
			echo wp_kses(
				sprintf(
					/* Translators: the placeholder contains the URL to Algolia's website. */
					__( 'No Algolia account yet? <a href="%s">Follow this link</a> to create one for free in a couple of minutes!', 'wp-search-with-algolia' ),
					'https://www.algolia.com/users/sign_up'
				),
				[
					'a' => [
						'href' => [],
					],
				]
			);
			?>
		</p>
	<?php endif; ?>

	<?php if ( Algolia_Settings_Utilities::has_credentials() && ! Algolia_Search_Client_Utilities::is_alive() ) : ?>

		<p>
			<?php esc_html_e( 'Unable to connect to Algolia API.', 'wp-search-with-algolia' ); ?>
		</p>

		<p>
			<?php
			echo wp_kses(
				sprintf(
				/* Translators: the placeholder contains the URL to Algolia's API Status website. */
					__( 'Algolia API Status can be monitored <a href="%s">at this link</a>.', 'wp-search-with-algolia' ),
					'https://status.algolia.com/'
				),
				[
					'a' => [
						'href' => [],
					],
				]
			);
			?>
		</p>

	<?php endif; ?>

<?php
