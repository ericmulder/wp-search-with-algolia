<?php
/**
 * Algolia_Admin_Page_Settings class file.
 *
 * @author  WebDevStudios <contact@webdevstudios.com>
 * @since   1.0.0
 *
 * @package WebDevStudios\WPSWA
 */

/**
 * Class Algolia_Admin_Page_Settings
 *
 * @since 1.0.0
 */
class Algolia_Admin_Page_Settings {

	/**
	 * Admin page slug.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @var string
	 */
	private $slug = 'algolia-account-settings';

	/**
	 * Admin page capabilities.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @var string
	 */
	private $capability = 'manage_options';

	/**
	 * Admin page section.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @var string
	 */
	private $section = 'algolia_section_settings';

	/**
	 * Admin page option group.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @var string
	 */
	private $option_group = 'algolia_settings';

	/**
	 * The Algolia_Plugin instance.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @var Algolia_Plugin
	 */
	private $plugin;

	/**
	 * Algolia_Admin_Page_Settings constructor.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @param Algolia_Plugin $plugin The Algolia_Plugin instance.
	 */
	public function __construct( Algolia_Plugin $plugin ) {
		$this->plugin = $plugin;

		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_init', array( $this, 'add_settings' ) );
		add_action( 'admin_notices', array( $this, 'display_errors' ) );

		// Display a link to this page from the plugins page.
		add_filter( 'plugin_action_links_' . ALGOLIA_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
	}

	/**
	 * Add action links.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @param array $links Array of action links.
	 *
	 * @return array
	 */
	public function add_action_links( array $links ) {
		return array_merge(
			$links, array(
				'<a href="' . esc_url( admin_url( 'admin.php?page=' . $this->slug ) ) . '">' . esc_html__( 'Settings', 'wp-search-with-algolia' ) . '</a>',
			)
		);
	}

	/**
	 * Add admin menu page.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @return string|void The resulting page's hook_suffix.
	 */
	public function add_page() {
		$api = $this->plugin->get_api();
		if ( ! $api->is_reachable() ) {
			// Means this is the only reachable admin page, so make it the default one!
			return add_menu_page(
				'WP Search with Algolia',
				esc_html__( 'Algolia Search', 'wp-search-with-algolia' ),
				'manage_options',
				$this->slug,
				array( $this, 'display_page' ),
				''
			);
		}

		add_submenu_page(
			'algolia',
			esc_html__( 'Settings', 'wp-search-with-algolia' ),
			esc_html__( 'Settings', 'wp-search-with-algolia' ),
			$this->capability,
			$this->slug,
			array( $this, 'display_page' )
		);
	}

	/**
	 * Add settings.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 */
	public function add_settings() {

		add_settings_section(
			$this->section,
			null,
			array( $this, 'print_section_settings' ),
			$this->slug
		);

		$this->register_settings();
		$this->add_settings_fields();
	}

	/**
	 * Application ID callback.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 */
	public function application_id_callback() {
		$settings = Algolia_Settings_Factory::create();
		?>
		<input name="algolia_application_id"
				class="regular-text"
				type="text"
				value="<?php echo esc_attr( $settings->get_application_id() ); ?>"
				<?php disabled( $settings->is_application_id_in_config() ); ?> />
		<p class="description" id="home-description">
			<?php esc_html_e( 'Your Algolia Application ID.', 'wp-search-with-algolia' ); ?>
		</p>
		<?php
	}

	/**
	 * Search API key callback.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 */
	public function search_api_key_callback() {
		$settings = Algolia_Settings_Factory::create();
		?>
		<input name="algolia_search_api_key"
				class="regular-text"
				type="text"
				value="<?php echo esc_attr( $settings->get_search_api_key() ); ?>"
				<?php disabled( $settings->is_search_api_key_in_config() ); ?> />
		<p class="description" id="home-description">
			<?php esc_html_e( 'Your Algolia Search-only API key (public).', 'wp-search-with-algolia' ); ?>
		</p>
		<?php
	}

	/**
	 * Admin API key callback.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 */
	public function api_key_callback() {
		$settings = Algolia_Settings_Factory::create();
		?>
		<input name="algolia_api_key"
				class="regular-text"
				type="password"
				value="<?php echo esc_attr( $settings->get_api_key() ); ?>"
				<?php disabled( $settings->is_api_key_in_config() ); ?> />
		<p class="description" id="home-description">
			<?php esc_html_e( 'Your Algolia ADMIN API key (kept private).', 'wp-search-with-algolia' ); ?>
		</p>
		<?php
	}

	/**
	 * Index name prefix callback.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 */
	public function index_name_prefix_callback() {
		$settings = Algolia_Settings_Factory::create();
		?>
		<input name="algolia_index_name_prefix"
				class="small-text"
				type="text"
				value="<?php echo esc_attr( $settings->get_index_name_prefix() ); ?>"
				<?php disabled( $settings->is_index_name_prefix_in_config() ); ?> />
		<p class="description" id="home-description">
			<?php esc_html_e( 'This prefix will be prepended to your index names.', 'wp-search-with-algolia' ); ?>
		</p>
		<?php
	}

	/**
	 * Powered by enabled callback.
	 *
	 * @author Richard Aber <richard.aber@webdevstudios.com>
	 * @since  2020-07-24
	 */
	public function powered_by_enabled_callback() {
		$settings = Algolia_Settings_Factory::create();
		?>
		<input name="algolia_powered_by_enabled"
				type="checkbox"
				value="no"
			<?php checked( ( ! $settings->is_powered_by_enabled() ) ); ?> />
		<p class="description" id="home-description">
			<?php esc_html_e( 'This will remove the Algolia logo from the autocomplete and the search page. We require that you keep the Algolia logo if you are using a free plan.', 'wp-search-with-algolia' ); ?>
		</p>
		<?php
	}

	/**
	 * Sanitize application ID.
	 *
	 * @author Richard Aber <richard.aber@webdevstudios.com>
	 * @since  2020-07-24
	 *
	 * @param string $value The value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_application_id( $value ) {
		if ( $this->plugin->get_settings()->is_application_id_in_config() ) {
			$value = $this->plugin->get_settings()->get_application_id();
		}
		$value = sanitize_text_field( $value );

		if ( empty( $value ) ) {
			add_settings_error(
				$this->option_group,
				'empty',
				esc_html__( 'Application ID should not be empty.', 'wp-search-with-algolia' )
			);

		}

		return $value;
	}

	/**
	 * Sanitize search API key.
	 *
	 * @author Richard Aber <richard.aber@webdevstudios.com>
	 * @since  2020-07-24
	 *
	 * @param string $value The value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_search_api_key( $value ) {
		if ( $this->plugin->get_settings()->is_search_api_key_in_config() ) {
			$value = $this->plugin->get_settings()->get_search_api_key();
		}
		$value = sanitize_text_field( $value );

		if ( empty( $value ) ) {
			add_settings_error(
				$this->option_group,
				'empty',
				esc_html__( 'Search-only API key should not be empty.', 'wp-search-with-algolia' )
			);
		}

		return $value;
	}

	/**
	 * Sanitize Admin API key.
	 *
	 * @author Richard Aber <richard.aber@webdevstudios.com>
	 * @since  2020-07-24
	 *
	 * @param string $value The value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_api_key( $value ) {
		if ( $this->plugin->get_settings()->is_api_key_in_config() ) {
			$value = $this->plugin->get_settings()->get_api_key();
		}
		$value = sanitize_text_field( $value );

		if ( empty( $value ) ) {
			add_settings_error(
				$this->option_group,
				'empty',
				esc_html__( 'Admin API key should not be empty.', 'wp-search-with-algolia' )
			);
		}
		$errors = get_settings_errors( $this->option_group );
		if ( ! empty( $errors ) ) {
			return $value;
		}

		$settings = $this->plugin->get_settings();

		$valid_credentials = true;
		try {
			Algolia_API::assert_valid_credentials( $settings->get_application_id(), $value );
		} catch ( Exception $exception ) {
			$valid_credentials = false;
			add_settings_error(
				$this->option_group,
				'login_exception',
				$exception->getMessage()
			);
		}

		if ( ! $valid_credentials ) {
			add_settings_error(
				$this->option_group,
				'no_connection',
				esc_html__(
					'We were unable to authenticate you against the Algolia servers with the provided information. Please ensure that you used a valid Admin API key and Application ID.',
					'wp-search-with-algolia'
				)
			);
			$settings->set_api_is_reachable( false );
		} else {
			if ( ! Algolia_API::is_valid_search_api_key( $settings->get_application_id(), $settings->get_search_api_key() ) ) {
				add_settings_error(
					$this->option_group,
					'wrong_search_API_key',
					esc_html__(
						'It looks like your search API key is wrong. Ensure that the key you entered has only the search capability and nothing else. Also ensure that the key has no limited time validity.',
						'wp-search-with-algolia'
					)
				);
				$settings->set_api_is_reachable( false );
			} else {
				add_settings_error(
					$this->option_group,
					'connection_success',
					esc_html__( 'We succesfully managed to connect to the Algolia servers with the provided information. Your search API key has also been checked and is OK.', 'wp-search-with-algolia' ),
					'updated'
				);
				$settings->set_api_is_reachable( true );
			}
		}

		return $value;
	}

	/**
	 * Determine if the index name prefix is valid.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @param string $index_name_prefix The index name prefix.
	 *
	 * @return bool
	 */
	public function is_valid_index_name_prefix( $index_name_prefix ) {
		$to_validate = str_replace( '_', '', $index_name_prefix );

		return ctype_alnum( $to_validate );
	}

	/**
	 * Sanitize the index name prefix.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @param string $value The value to sanitize.
	 *
	 * @return bool|mixed|string|void
	 */
	public function sanitize_index_name_prefix( $value ) {
		if ( $this->plugin->get_settings()->is_index_name_prefix_in_config() ) {
			$value = $this->plugin->get_settings()->get_index_name_prefix();
		}

		if ( $this->is_valid_index_name_prefix( $value ) ) {
			return $value;
		}

		add_settings_error(
			$this->option_group,
			'wrong_prefix',
			esc_html__( 'Indices prefix can only contain alphanumeric characters and underscores.', 'wp-search-with-algolia' )
		);

		return $value;
	}

	/**
	 * Sanitize the powered by enabled setting.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 *
	 * @param string $value The value to sanitize.
	 *
	 * @return string
	 */
	public function sanitize_powered_by_enabled( $value ) {
		return 'no' === $value ? 'no' : 'yes';
	}

	/**
	 * Display the page.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 */
	public function display_page() {
		require_once dirname( __FILE__ ) . '/partials/form-options.php';
	}

	/**
	 * Display errors.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 */
	public function display_errors() {
		settings_errors( $this->option_group );
	}

	/**
	 * Print the settings section.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.0.0
	 */
	public function print_section_settings() {
		require_once dirname( __FILE__ ) . '/partials/admin-page-settings-section.php';
	}

	/**
	 * Add settings fields.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.5.0-dev
	 */
	protected function add_settings_fields(): void {

		add_settings_field(
			'algolia_application_id',
			esc_html__( 'Application ID', 'wp-search-with-algolia' ),
			array( $this, 'application_id_callback' ),
			$this->slug,
			$this->section
		);

		add_settings_field(
			'algolia_search_api_key',
			esc_html__( 'Search-only API key', 'wp-search-with-algolia' ),
			array( $this, 'search_api_key_callback' ),
			$this->slug,
			$this->section
		);

		add_settings_field(
			'algolia_api_key',
			esc_html__( 'Admin API key', 'wp-search-with-algolia' ),
			array( $this, 'api_key_callback' ),
			$this->slug,
			$this->section
		);

		add_settings_field(
			'algolia_index_name_prefix',
			esc_html__( 'Index name prefix', 'wp-search-with-algolia' ),
			array( $this, 'index_name_prefix_callback' ),
			$this->slug,
			$this->section
		);

		add_settings_field(
			'algolia_powered_by_enabled',
			esc_html__( 'Remove Algolia powered by logo', 'wp-search-with-algolia' ),
			array( $this, 'powered_by_enabled_callback' ),
			$this->slug,
			$this->section
		);
	}

	/**
	 * Register settings.
	 *
	 * @author WebDevStudios <contact@webdevstudios.com>
	 * @since  1.5.0-dev
	 */
	protected function register_settings(): void {

		register_setting(
			$this->option_group,
			'algolia_application_id',
			[
				'type'              => 'string',
				'sanitize_callback' => [ $this, 'sanitize_application_id' ],
				'default'           => ( defined( 'ALGOLIA_APPLICATION_ID' ) ) ? ALGOLIA_APPLICATION_ID : '',
			]
		);

		register_setting(
			$this->option_group,
			'algolia_search_api_key',
			[
				'type'              => 'string',
				'sanitize_callback' => [ $this, 'sanitize_search_api_key' ],
				'default'           => ( defined( 'ALGOLIA_SEARCH_API_KEY' ) ) ? ALGOLIA_SEARCH_API_KEY : '',
			]
		);

		register_setting(
			$this->option_group,
			'algolia_api_key',
			[
				'type'              => 'string',
				'sanitize_callback' => [ $this, 'sanitize_api_key' ],
				'default'           => ( defined( 'ALGOLIA_API_KEY' ) ) ? ALGOLIA_API_KEY : '',
			]
		);

		register_setting(
			$this->option_group,
			'algolia_index_name_prefix',
			[
				'type'              => 'string',
				'sanitize_callback' => [ $this, 'sanitize_index_name_prefix' ],
				'default'           => ( defined( 'ALGOLIA_INDEX_NAME_PREFIX' ) ) ? ALGOLIA_INDEX_NAME_PREFIX : 'wp_',
			]
		);

		register_setting(
			$this->option_group,
			'algolia_powered_by_enabled',
			[
				'type'              => 'string',
				'sanitize_callback' => [ $this, 'sanitize_powered_by_enabled' ],
				'default'           => 'yes',
			]
		);
	}
}
