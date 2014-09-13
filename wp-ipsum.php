<?php
/**
 * WordPress Ipsum Generator
 *
 * @package   WP_Ipsum
 * @author    Pascal Birchler <pascal.birchler@spinpress.com>
 * @license   GPL-2.0+
 * @link      https://spinpress.com
 * @copyright 2014 SpinPress
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Ipsum
 * Plugin URI:        https://spinpress.com
 * Description:       Bored of all the normal lorem ipsum texts? Here's a lorem ipsum generator full of WordPress. Isn't that awesome?
 * Version:           1.0.0
 * Author:            Pascal Birchler
 * Author URI:        https://spinpress.com
 * Text Domain:       wp-ipsum
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_shortcode('wp-ipsum', 'spinpress_wp_ipsum_form');

function spinpress_wp_ipsum_form($atts) {
	$output = '';

	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

	if ( isset( $_REQUEST['filler'] ) ) {
		$use_filler_words = (bool) $_REQUEST['filler'];
	} else {
		$use_filler_words = false;
	}

	if ( isset( $_REQUEST['lorem'] ) ) {
		$start_with_lorem = (bool) $_REQUEST['lorem'];
	} else if ( isset ( $_REQUEST['paragraphs'] ) ) {
		$start_with_lorem = false;
	} else {
		$start_with_lorem = true;
	}

	$num_paragraphs = 5;

	if ( isset( $_REQUEST['paragraphs'] ) ) {
		$num_paragraphs = absint( $_REQUEST['paragraphs'] );

		if ( 50 < $num_paragraphs ) {
			$num_paragraphs = 50;
		}
	}

	if ( isset( $_REQUEST['paragraphs'] ) ) {

		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-wp-ipsum-generator.php' );

		$args = array(
			'paragraphs'       => $num_paragraphs,
			'use_filler_words' => $use_filler_words,
			'start_with_lorem' => $start_with_lorem
		);

		$generator = new SpinPress_WP_Ipsum_Generator( $args );

		$paragraphs = $generator->generate_lorem_ipsum();

		$output .= '<div class="wp-ipsum-text">';
		foreach( $paragraphs as $p ) {
			$output .= '<p>' . $p . '</p>';
		}

		$output .= '</div>';
	}

	$form = '
		<form class="wp-ipsum-form" action="' . esc_url( $current_url ) . '" method="get">
			<p>
				<label class="wp-ipsum-label wp-ipsum-label-paragraphs" for="wp-ipsum-paragraphs"> ' . __( 'Paragraphs', 'wp-ipsum' ) . '</label>
				<input class="wp-ipsum-input" type="number" id="wp-ipsum-paragraphs" name="paragraphs" min="1" max="50" value="' . $num_paragraphs . '" maxlength="2" />
			</p>
			<p>
				<label class="wp-ipsum-label"><input class="wp-ipsum-input" type="radio" name="filler" value="0" ' . checked( $use_filler_words, false, false ) . ' />All WordPress</label>
				<label class="wp-ipsum-label"><input class="wp-ipsum-input" type="radio" name="filler" value="1" ' . checked( $use_filler_words, true, false ) . ' />WordPress and Filler</label>
			</p>
			<p>
				<input class="wp-ipsum-input wp-ipsum-input-start" type="checkbox" name="lorem" id="wp-ipsum-start_with_lorem" value="1" ' . checked( $start_with_lorem, true, false ) . '  />
				<label class="wp-ipsum-label wp-ipsum-label-start" for="wp-ipsum-start_with_lorem">' . __( 'Start with “WordPress ipsum dolor sit amet...”', 'wp-ipsum' ) . '</label>
			</p>
			<p>
				<input class="wp-ipsum-input" type="submit" value="' . __( 'Generate', 'wp-ipsum' ) . '" />
			</p>
		</form>
	';

	return $form . $output;

}