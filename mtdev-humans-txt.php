<?php
/**
 * Plugin Name:       MTDEV Humans TXT
 * Plugin URI:        https://github.com/martatorredev/mtdev-humans-txt
 * Description:       Create and serve a virtual humans.txt file in your site root and add the rel="author" link to the head, following the humanstxt.org standard.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Author:            Marta Torre
 * Author URI:        https://martatorre.dev
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mtdev-humans-txt
 * Domain Path:       /languages
 *
 * @package MtdevHumansTxt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MTDEV_OPTION_CONTENT', 'mtdev_humans_txt_content' );
define( 'MTDEV_OPTION_LINK', 'mtdev_add_head_link' );
define( 'MTDEV_QUERY_VAR', 'mtdev_humans_txt' );

final class Mtdev_Humans_Txt {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'add_rewrite_rule' ) );
		add_filter( 'query_vars', array( $this, 'register_query_var' ) );
		add_action( 'template_redirect', array( $this, 'maybe_serve_humans_txt' ) );
		add_action( 'wp_head', array( $this, 'print_head_link' ) );

		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_settings_link' ) );
	}

	public static function default_content() {
		$lines = array(
			'/* TEAM */',
			'',
			'	Name: ',
			'	Role: ',
			'	Contact: ',
			'	Twitter: ',
			'	Location: ',
			'',
			'/* THANKS */',
			'',
			'	Name: ',
			'',
			'/* SITE */',
			'',
			'	Last update: ' . gmdate( 'Y/m/d' ),
			'	Site: ' . get_bloginfo( 'name' ) . ' (' . home_url() . ')',
			'	Language: ' . get_bloginfo( 'language' ),
			'	Standards: HTML5, CSS',
			'	Software: WordPress, PHP',
			'',
		);

		return implode( "\n", $lines );
	}

	public function add_rewrite_rule() {
		add_rewrite_rule( '^humans\.txt$', 'index.php?' . MTDEV_QUERY_VAR . '=1', 'top' );
	}

	public function register_query_var( $vars ) {
		$vars[] = MTDEV_QUERY_VAR;
		return $vars;
	}

	public function maybe_serve_humans_txt() {
		if ( ! get_query_var( MTDEV_QUERY_VAR ) ) {
			return;
		}

		$content = get_option( MTDEV_OPTION_CONTENT, '' );
		if ( '' === $content ) {
			$content = self::default_content();
		}

		if ( ! headers_sent() ) {
			header( 'Content-Type: text/plain; charset=utf-8' );
			header( 'X-Robots-Tag: noindex' );
		}

		status_header( 200 );

		// Plain text, sanitized on save and served as text/plain.
		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		exit;
	}

	public function print_head_link() {
		if ( ! get_option( MTDEV_OPTION_LINK, true ) ) {
			return;
		}

		printf(
			'<link type="text/plain" rel="author" href="%s" />' . "\n",
			esc_url( home_url( '/humans.txt' ) )
		);
	}

	public function add_settings_page() {
		add_options_page(
			__( 'Humans.txt', 'mtdev-humans-txt' ),
			__( 'Humans.txt', 'mtdev-humans-txt' ),
			'manage_options',
			'mtdev-humans-txt',
			array( $this, 'render_settings_page' )
		);
	}

	public function register_settings() {
		register_setting(
			'mtdev_settings_group',
			MTDEV_OPTION_CONTENT,
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_content' ),
				'default'           => '',
			)
		);

		register_setting(
			'mtdev_settings_group',
			MTDEV_OPTION_LINK,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => 'rest_sanitize_boolean',
				'default'           => true,
			)
		);
	}

	public function sanitize_content( $value ) {
		$value = (string) wp_unslash( $value );
		$value = wp_strip_all_tags( $value );
		return str_replace( "\r\n", "\n", $value );
	}

	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$content = get_option( MTDEV_OPTION_CONTENT, '' );
		if ( '' === $content ) {
			$content = self::default_content();
		}

		$add_link = (bool) get_option( MTDEV_OPTION_LINK, true );
		$file_url = home_url( '/humans.txt' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'MTDEV Humans TXT', 'mtdev-humans-txt' ); ?></h1>

			<p>
				<?php
				printf(
					/* translators: %s: humans.txt file URL. */
					esc_html__( 'Your file is served at: %s', 'mtdev-humans-txt' ),
					'<a href="' . esc_url( $file_url ) . '" target="_blank" rel="noopener noreferrer"><code>' . esc_html( $file_url ) . '</code></a>'
				);
				?>
			</p>

			<form action="options.php" method="post">
				<?php settings_fields( 'mtdev_settings_group' ); ?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="mtdev_content"><?php esc_html_e( 'humans.txt content', 'mtdev-humans-txt' ); ?></label>
						</th>
						<td>
							<textarea id="mtdev_content" name="<?php echo esc_attr( MTDEV_OPTION_CONTENT ); ?>" rows="18" cols="70" class="large-text code"><?php echo esc_textarea( $content ); ?></textarea>
							<p class="description"><?php esc_html_e( 'Edit the content freely, following the humanstxt.org standard.', 'mtdev-humans-txt' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Head link', 'mtdev-humans-txt' ); ?></th>
						<td>
							<label for="mtdev_add_link">
								<input type="checkbox" id="mtdev_add_link" name="<?php echo esc_attr( MTDEV_OPTION_LINK ); ?>" value="1" <?php checked( $add_link ); ?> />
								<?php esc_html_e( 'Add the <link rel="author"> tag to the site <head>.', 'mtdev-humans-txt' ); ?>
							</label>
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	public function add_settings_link( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'options-general.php?page=mtdev-humans-txt' ) ),
			esc_html__( 'Settings', 'mtdev-humans-txt' )
		);
		array_unshift( $links, $settings_link );
		return $links;
	}

	public static function activate() {
		self::instance()->add_rewrite_rule();
		flush_rewrite_rules();
	}

	public static function deactivate() {
		flush_rewrite_rules();
	}
}

register_activation_hook( __FILE__, array( 'Mtdev_Humans_Txt', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Mtdev_Humans_Txt', 'deactivate' ) );

Mtdev_Humans_Txt::instance();
