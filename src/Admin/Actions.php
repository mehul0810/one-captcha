<?php
/**
 * OneCaptcha | Admin Actions.
 *
 * @package    WordPress
 * @subpackage OneCaptcha
 * @since      1.0.0
 */

namespace OneCaptcha\Admin;

use OneCaptcha\Helpers;

// Bailout, if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Actions {
	/**
	 * OneCaptcha Settings.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @var array<string, mixed>
	 */
	public array $settings = [];

	/**
	 * OneCaptch Services.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @var array<string, mixed>
	 */
	public array $services = [];

	/**
	 * Service Fields.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @var array<string, mixed>
	 */
	public array $service_fields = [];

	/**
	 * Allowed HTML Tags.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @var array<string, mixed>
	 */
	public array $allowed_html = [];

	/**
	 * Get Active Service.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public string $active_service = '';

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @access public
     */
    public function __construct() {
		// Setup some variables.
		$this->settings       = Helpers::get_settings();
		$this->services       = Helpers::get_services();
		$this->service_fields = Helpers::get_service_fields();
		$this->active_service = Helpers::get_active_service();
		$this->allowed_html   = Helpers::get_allowed_html();

        add_action( 'in_admin_header', [ $this, 'add_settings_header' ] );
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

	/**
	 * Add Settings Page Header.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function add_settings_header() : void {
		$current_screen = get_current_screen();

		// Bailout, if not on the settings page.
		if (
			$current_screen &&
			$current_screen->base !== 'settings_page_onecaptcha'
		) {
			return;
		}

		$logo_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="240" height="60" viewBox="0 0 693 140" fill="none">
		<g clip-path="url(#clip0_706_2)">
		<rect width="693" height="140" fill="white"/>
		<path d="M143.8 62.768C143.8 57.904 144.728 53.296 146.584 48.944C148.44 44.592 151 40.752 154.264 37.424C157.592 34.032 161.432 31.376 165.784 29.456C170.136 27.536 174.808 26.576 179.8 26.576C184.728 26.576 189.368 27.536 193.72 29.456C198.072 31.376 201.912 34.032 205.24 37.424C208.632 40.752 211.256 44.592 213.112 48.944C215.032 53.296 215.992 57.904 215.992 62.768C215.992 67.76 215.032 72.432 213.112 76.784C211.256 81.136 208.632 84.976 205.24 88.304C201.912 91.568 198.072 94.128 193.72 95.984C189.368 97.84 184.728 98.768 179.8 98.768C174.808 98.768 170.136 97.84 165.784 95.984C161.432 94.128 157.592 91.568 154.264 88.304C151 84.976 148.44 81.136 146.584 76.784C144.728 72.432 143.8 67.76 143.8 62.768ZM158.2 62.768C158.2 65.904 158.744 68.848 159.832 71.6C160.984 74.288 162.552 76.688 164.536 78.8C166.584 80.848 168.92 82.448 171.544 83.6C174.232 84.752 177.144 85.328 180.28 85.328C183.288 85.328 186.072 84.752 188.632 83.6C191.256 82.448 193.528 80.848 195.448 78.8C197.368 76.688 198.872 74.288 199.96 71.6C201.048 68.848 201.592 65.904 201.592 62.768C201.592 59.568 201.016 56.592 199.864 53.84C198.776 51.088 197.24 48.688 195.256 46.64C193.336 44.528 191.064 42.896 188.44 41.744C185.816 40.592 182.968 40.016 179.896 40.016C176.824 40.016 173.976 40.592 171.352 41.744C168.728 42.896 166.424 44.528 164.44 46.64C162.456 48.688 160.92 51.088 159.832 53.84C158.744 56.592 158.2 59.568 158.2 62.768ZM239.854 56.048L240.91 63.536L240.718 62.864C242.19 60.24 244.27 58.128 246.958 56.528C249.646 54.864 252.942 54.032 256.846 54.032C260.814 54.032 264.11 55.216 266.734 57.584C269.422 59.888 270.798 62.896 270.862 66.608V98H257.422V71.6C257.358 69.744 256.846 68.272 255.886 67.184C254.99 66.032 253.454 65.456 251.278 65.456C249.23 65.456 247.438 66.128 245.902 67.472C244.366 68.816 243.182 70.64 242.35 72.944C241.518 75.248 241.102 77.904 241.102 80.912V98H227.662V56.048H239.854ZM305.302 99.152C300.182 99.152 295.83 98.192 292.246 96.272C288.726 94.352 286.038 91.696 284.182 88.304C282.326 84.912 281.398 81.008 281.398 76.592C281.398 72.368 282.486 68.56 284.662 65.168C286.838 61.776 289.75 59.088 293.398 57.104C297.046 55.056 301.11 54.032 305.59 54.032C311.606 54.032 316.534 55.792 320.374 59.312C324.278 62.768 326.806 67.792 327.958 74.384L295.318 84.752L292.342 77.456L315.958 69.488L313.174 70.736C312.662 69.072 311.734 67.632 310.39 66.416C309.11 65.136 307.158 64.496 304.534 64.496C302.55 64.496 300.79 64.976 299.254 65.936C297.782 66.832 296.63 68.144 295.798 69.872C295.03 71.536 294.646 73.52 294.646 75.824C294.646 78.448 295.126 80.656 296.086 82.448C297.046 84.176 298.358 85.488 300.022 86.384C301.686 87.28 303.542 87.728 305.59 87.728C307.062 87.728 308.47 87.472 309.814 86.96C311.222 86.448 312.598 85.776 313.942 84.944L319.894 94.928C317.654 96.208 315.222 97.232 312.598 98C310.038 98.768 307.606 99.152 305.302 99.152Z" fill="black"/>
		<path d="M390.808 93.488C389.336 94.384 387.64 95.248 385.72 96.08C383.8 96.912 381.688 97.552 379.384 98C377.08 98.512 374.584 98.768 371.896 98.768C366.712 98.768 362.008 97.872 357.784 96.08C353.56 94.224 349.944 91.728 346.936 88.592C343.928 85.392 341.592 81.744 339.928 77.648C338.328 73.488 337.528 69.072 337.528 64.4C337.528 59.408 338.392 54.768 340.12 50.48C341.848 46.192 344.28 42.448 347.416 39.248C350.552 36.048 354.2 33.552 358.36 31.76C362.52 29.968 367.032 29.072 371.896 29.072C375.48 29.072 378.84 29.552 381.976 30.512C385.112 31.472 387.96 32.72 390.52 34.256L388.504 38.576C386.264 37.168 383.736 35.984 380.92 35.024C378.104 34.064 375.128 33.584 371.992 33.584C367.96 33.584 364.152 34.384 360.568 35.984C356.984 37.52 353.816 39.696 351.064 42.512C348.376 45.264 346.264 48.496 344.728 52.208C343.192 55.92 342.424 59.92 342.424 64.208C342.424 68.304 343.096 72.176 344.44 75.824C345.848 79.472 347.832 82.672 350.392 85.424C353.016 88.176 356.152 90.352 359.8 91.952C363.448 93.488 367.512 94.256 371.992 94.256C375.32 94.256 378.36 93.808 381.112 92.912C383.928 91.952 386.424 90.768 388.6 89.36L390.808 93.488ZM421.471 98.768C417.887 98.768 414.623 97.968 411.679 96.368C408.799 94.704 406.495 92.4 404.767 89.456C403.103 86.512 402.271 83.088 402.271 79.184C402.271 75.024 403.167 71.472 404.959 68.528C406.751 65.52 409.183 63.248 412.255 61.712C415.327 60.112 418.815 59.312 422.719 59.312C426.047 59.312 429.183 60.304 432.127 62.288C435.135 64.272 437.279 66.768 438.559 69.776L437.887 71.12L438.463 60.464H442.495V98H437.983V86.384L438.943 88.112C438.495 89.392 437.727 90.672 436.639 91.952C435.551 93.168 434.207 94.32 432.607 95.408C431.071 96.432 429.343 97.264 427.423 97.904C425.567 98.48 423.583 98.768 421.471 98.768ZM422.431 94.928C425.247 94.928 427.775 94.352 430.015 93.2C432.319 91.984 434.175 90.32 435.583 88.208C436.991 86.096 437.791 83.664 437.983 80.912V75.536C437.471 73.168 436.447 71.056 434.911 69.2C433.439 67.344 431.615 65.872 429.439 64.784C427.263 63.696 424.895 63.152 422.335 63.152C419.519 63.152 416.927 63.824 414.559 65.168C412.191 66.448 410.303 68.272 408.895 70.64C407.487 73.008 406.783 75.792 406.783 78.992C406.783 81.936 407.487 84.624 408.895 87.056C410.303 89.424 412.191 91.344 414.559 92.816C416.991 94.224 419.615 94.928 422.431 94.928ZM480.28 98.768C476.696 98.768 473.336 97.84 470.2 95.984C467.064 94.064 464.76 91.6 463.288 88.592L464.056 86.96V116.048H459.544V60.176H463.576L464.056 72.464L463.192 69.68C464.856 66.672 467.288 64.208 470.488 62.288C473.688 60.304 477.176 59.312 480.952 59.312C484.536 59.312 487.768 60.176 490.648 61.904C493.528 63.632 495.8 66 497.464 69.008C499.192 71.952 500.056 75.344 500.056 79.184C500.056 82.96 499.16 86.352 497.368 89.36C495.64 92.304 493.272 94.608 490.264 96.272C487.32 97.936 483.992 98.768 480.28 98.768ZM479.512 95.024C482.456 95.024 485.112 94.32 487.48 92.912C489.912 91.504 491.832 89.616 493.24 87.248C494.712 84.816 495.448 82.128 495.448 79.184C495.448 76.176 494.744 73.488 493.336 71.12C491.928 68.688 490.04 66.768 487.672 65.36C485.368 63.952 482.744 63.248 479.8 63.248C476.984 63.248 474.424 63.888 472.12 65.168C469.816 66.448 467.96 68.176 466.552 70.352C465.144 72.528 464.312 74.992 464.056 77.744V80.816C464.248 83.44 465.048 85.84 466.456 88.016C467.928 90.192 469.784 91.92 472.024 93.2C474.328 94.416 476.824 95.024 479.512 95.024ZM517.194 43.952H521.802V60.944H533.418V64.592H521.802V98H517.194V64.592H509.226V60.944H517.194V43.952ZM573.661 94.352C571.933 95.76 569.981 96.848 567.805 97.616C565.629 98.384 563.357 98.768 560.989 98.768C557.149 98.768 553.725 97.936 550.717 96.272C547.709 94.544 545.341 92.208 543.613 89.264C541.885 86.32 541.021 82.96 541.021 79.184C541.021 75.408 541.917 72.048 543.709 69.104C545.501 66.16 547.869 63.856 550.813 62.192C553.821 60.464 557.085 59.6 560.605 59.6C563.229 59.6 565.661 59.984 567.901 60.752C570.141 61.52 572.093 62.608 573.757 64.016L571.357 67.088C570.013 66 568.445 65.104 566.653 64.4C564.861 63.696 562.941 63.344 560.893 63.344C558.077 63.344 555.485 64.048 553.117 65.456C550.813 66.864 548.957 68.784 547.549 71.216C546.205 73.584 545.533 76.24 545.533 79.184C545.533 82.064 546.205 84.72 547.549 87.152C548.957 89.52 550.845 91.408 553.213 92.816C555.645 94.224 558.333 94.928 561.277 94.928C563.197 94.928 564.989 94.64 566.653 94.064C568.317 93.424 569.821 92.592 571.165 91.568L573.661 94.352ZM586.2 98V25.04H590.616V70.448L590.328 68.816C591.608 66.192 593.656 63.984 596.472 62.192C599.352 60.336 602.648 59.408 606.36 59.408C609.88 59.408 612.728 60.464 614.904 62.576C617.08 64.624 618.2 67.312 618.264 70.64V98H613.656V71.888C613.592 69.456 612.824 67.44 611.352 65.84C609.88 64.24 607.736 63.408 604.92 63.344C602.424 63.344 600.088 63.984 597.912 65.264C595.736 66.544 593.976 68.272 592.632 70.448C591.352 72.624 590.712 75.088 590.712 77.84V98H586.2ZM650.877 98.768C647.293 98.768 644.029 97.968 641.085 96.368C638.205 94.704 635.901 92.4 634.173 89.456C632.509 86.512 631.677 83.088 631.677 79.184C631.677 75.024 632.573 71.472 634.365 68.528C636.157 65.52 638.589 63.248 641.661 61.712C644.733 60.112 648.221 59.312 652.125 59.312C655.453 59.312 658.589 60.304 661.533 62.288C664.541 64.272 666.685 66.768 667.965 69.776L667.293 71.12L667.869 60.464H671.901V98H667.389V86.384L668.349 88.112C667.901 89.392 667.133 90.672 666.045 91.952C664.957 93.168 663.613 94.32 662.013 95.408C660.477 96.432 658.749 97.264 656.829 97.904C654.973 98.48 652.989 98.768 650.877 98.768ZM651.837 94.928C654.653 94.928 657.181 94.352 659.421 93.2C661.725 91.984 663.581 90.32 664.989 88.208C666.397 86.096 667.197 83.664 667.389 80.912V75.536C666.877 73.168 665.853 71.056 664.317 69.2C662.845 67.344 661.021 65.872 658.845 64.784C656.669 63.696 654.301 63.152 651.741 63.152C648.925 63.152 646.333 63.824 643.965 65.168C641.597 66.448 639.709 68.272 638.301 70.64C636.893 73.008 636.189 75.792 636.189 78.992C636.189 81.936 636.893 84.624 638.301 87.056C639.709 89.424 641.597 91.344 643.965 92.816C646.397 94.224 649.021 94.928 651.837 94.928Z" fill="black"/>
		<rect x="24" y="26" width="88" height="88" rx="10" fill="#EDEDED"/>
		<path d="M117.32 99.6747C116.536 100.295 115.074 101.122 112.935 102.155C110.86 103.19 108.308 104.085 105.28 104.84C102.252 105.595 98.9148 105.893 95.2696 105.736C89.7067 105.465 84.7418 104.346 80.3748 102.377C76.0734 100.346 72.429 97.6602 69.4416 94.3186C66.5182 90.9786 64.3108 87.1768 62.8196 82.9132C61.3283 78.6496 60.6441 74.1186 60.7671 69.3202C60.9048 63.9459 61.8307 59.0401 63.545 54.6025C65.3232 50.1666 67.7576 46.3557 70.8482 43.1698C74.0028 39.9856 77.7144 37.5519 81.9829 35.8686C86.2514 34.1854 90.9448 33.4094 96.0631 33.5405C100.798 33.6618 104.972 34.409 108.586 35.782C112.2 37.1551 115.14 38.6068 117.405 40.1374L111.598 53.1449C110.03 51.8883 107.918 50.5858 105.264 49.2374C102.675 47.8266 99.6536 47.0769 96.1987 46.9884C93.5116 46.9196 90.9057 47.429 88.381 48.5167C85.9219 49.542 83.7065 51.0538 81.7346 53.0519C79.8268 55.0517 78.2938 57.4132 77.1357 60.1364C75.9792 62.7957 75.3599 65.7248 75.278 68.9237C75.1911 72.3146 75.5914 75.4299 76.479 78.2696C77.4305 81.1109 78.8077 83.579 80.6107 85.6738C82.4153 87.7047 84.5832 89.2968 87.1145 90.45C89.7097 91.6049 92.6388 92.2241 95.9018 92.3077C99.6765 92.4044 102.923 91.8794 105.641 90.7326C108.36 89.5859 110.44 88.3588 111.882 87.0513L117.32 99.6747Z" fill="black"/>
		<path d="M36.8 38.352H55.904V102H41.888V51.792H36.8V38.352Z" fill="black"/>
		</g>
		<defs>
		<clipPath id="clip0_706_2">
		<rect width="693" height="140" fill="white"/>
		</clipPath>
		</defs>
		</svg>';
		?>
		<div class="onecaptcha-header">
			<div class="onecaptcha-logo">
				<?php echo Helpers::sanitize_svg_code( $logo_svg ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="onecaptcha-extra">
				<p class="onecaptcha-version">
					<?php echo esc_html( ONECAPTCHA_VERSION ); ?>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Add Settings Page.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
    public function add_settings_page() {
		add_options_page(
			esc_html__( 'OneCaptcha', 'onecaptcha' ),
			esc_html__( 'OneCaptcha', 'onecaptcha' ),
			'manage_options',
			'onecaptcha',
			[ $this, 'render_admin_page' ],
			99
		);
	}

	/**
	 * Render admin page.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return mixed
	 */
	public function render_admin_page() {
		?>
		<div class="onecaptcha-main">
			<div class="onecaptcha-content">
				<form method="post" action="options.php">
					<?php
					settings_fields( 'onecaptcha_settings_group' );
					do_settings_sections( 'onecaptcha_settings' );
					submit_button();
					?>
				</form>
			</div>
			<div class="onecaptcha-sidebar">
			</div>
		</div>
		<?php
	}

	/**
	 * Register Admin Assets.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function register_assets() {
		wp_enqueue_style( 'onecaptcha-admin', ONECAPTCHA_PLUGIN_URL . 'assets/dist/css/admin.css', [], ONECAPTCHA_VERSION );
		wp_enqueue_script( 'onecaptcha-admin', ONECAPTCHA_PLUGIN_URL . 'assets/dist/js/admin.min.js', [], ONECAPTCHA_VERSION, true );
	}

	/**
	 * Register admin settings.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function register_settings() :void {
		// Register Settings.
		register_setting(
			'onecaptcha_settings_group',
			'onecaptcha_settings',
			[
				'sanitize_callback' => [ $this, 'onecaptcha_sanitize_settings' ],
			]
		);

		// Add Settings Section.
		add_settings_section(
			'onecaptcha_main_section',
			esc_html__( 'Captcha Settings', 'onecaptcha' ),
			[ $this, 'onecaptcha_settings_section_callback' ],
			'onecaptcha_settings'
		);

		// Add `Captcha Service` field.
		add_settings_field(
			'onecaptcha_captcha_service',
			'Captcha Service',
			[ $this, 'onecaptcha_captcha_service_callback' ],
			'onecaptcha_settings',
			'onecaptcha_main_section'
		);

		// Loop through each captcha service and generate fields based on that.
		foreach ( $this->services as $key => $value ) {
			$active_class = $this->active_service === $key ? 'active' : '';

			add_settings_field(
				"onecaptcha_{$key}_fields_group",
				$value,
				[ $this, "onecaptcha_{$key}_fields_group_callback" ],
				'onecaptcha_settings',
				'onecaptcha_main_section',
				[
					'class' => "onecaptcha-fields-group onecaptcha-{$key}-fields-group {$active_class}",
				]
			);
		}
	}

	/**
	 * Section callback
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function onecaptcha_settings_section_callback() : void {
		?>
		<h4>
			<?php esc_html_e( 'Welcome to OneCaptcha — Your All-in-One Captcha Solution!', 'onecaptcha' ); ?>
		</h4>
		<p>
			<?php esc_html_e( 'OneCaptcha empowers you to protect your WordPress site against spam and bots by integrating seamlessly with a wide range of captcha services, including Cloudflare Turnstile, Google ReCaptcha, and hCaptcha. With OneCaptcha, you can safeguard common spam-prone areas across your website, whether you’re using contact forms, comments, login pages, or other plugins that require bot protection.', 'onecaptcha' ); ?>
		</p>
		<?php
	}

	/**
	 * Dropdown field callback
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function onecaptcha_captcha_service_callback() : void {
		?>
		<select name="onecaptcha_settings[service]" id="onecaptcha-service">
			<?php
			foreach ( $this->services as $key => $value ) {
				?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->active_service, $key ); ?>>
					<?php echo esc_html( $value ); ?>
				</option>
				<?php
			}
			?>
		</select>
		<?php
	}

	/**
	 * Cloudflare Turnstile field group callback
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function onecaptcha_cloudflare_turnstile_fields_group_callback() : void {
		echo wp_kses( Helpers::display_settings_field( 'cloudflare_turnstile' ), $this->allowed_html );
	}

	/**
	 * Google ReCaptcha field group callback
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function onecaptcha_google_recaptcha_fields_group_callback() : void {
		echo wp_kses( Helpers::display_settings_field( 'google_recaptcha' ), $this->allowed_html );
	}

	/**
	 * hCaptcha field group callback
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function onecaptcha_hcaptcha_fields_group_callback() : void {
		echo wp_kses( Helpers::display_settings_field( 'hcaptcha' ), $this->allowed_html );
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $input Input data.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function onecaptcha_sanitize_settings( array $input ) : array {
		$output = [];

		// Sanitize service.
		$output['service'] = sanitize_text_field( $input['service'] ?? Helpers::get_default_service() );

		// Sanitize credentials.
		$output['credentials'] = [];
		if ( isset( $input['credentials'] ) && is_array( $input['credentials'] ) ) {
			foreach ( $input['credentials'] as $type => $keys ) {
				$output['credentials'][$type] = [
					'site_key'   => sanitize_text_field( $keys['site_key'] ?? '' ),
					'secret_key' => sanitize_text_field( $keys['secret_key'] ?? '' ),
				];
			}
		}

		return $output;
	}
}
