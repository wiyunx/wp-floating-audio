<?php
/**
 * Plugin Name: WP Floating Audio
 * Description: Floating audio player with autoplay, visibility pause, and shortcode support.
 * Version: 1.2.0
 * Author: wiyunx
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) exit;

// Global variable to hold the audio source
$wpfa_audio_source = false;

/**
 * Shortcode: [floating_audio src="" autoplay="1"]
 * NOW: Only outputs the BUTTON, not the audio tag.
 */
function wpfa_shortcode($atts) {
    global $wpfa_audio_source;

    $atts = shortcode_atts([
        'src' => '',
        'autoplay' => '1',
    ], $atts);

    if (!$atts['src']) return '';

    // Save the source to the global variable for the footer to use
    $wpfa_audio_source = $atts['src'];

    ob_start();
    ?>

    <div class="wpfa-container">
        <button class="wpfa-toggle wpfa-paused" aria-label="Toggle audio">
            <svg aria-hidden="true" class="wpfa-icon wpfa-icon-play" viewBox="0 0 496 512" xmlns="http://www.w3.org/2000/svg"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zM88 256H56c0-105.9 86.1-192 192-192v32c-88.2 0-160 71.8-160 160zm160 96c-53 0-96-43-96-96s43-96 96-96 96 43 96 96-43 96-96 96zm0-128c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32z"></path></svg>
            <svg aria-hidden="true" class="wpfa-icon wpfa-icon-pause" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm-16 328c0 8.8-7.2 16-16 16h-48c-8.8 0-16-7.2-16-16V176c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16v160zm112 0c0 8.8-7.2 16-16 16h-48c-8.8 0-16-7.2-16-16V176c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16v160z"></path></svg>
        </button>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('floating_audio', 'wpfa_shortcode');

/**
 * Enqueue Assets
 */
function wpfa_assets() {
    wp_enqueue_style('wpfa-style', plugin_dir_url(__FILE__) . 'assets/css/wpfa.css', [], '1.2.0');
    wp_enqueue_script('wpfa-script', plugin_dir_url(__FILE__) . 'assets/js/wpfa.js', [], '1.2.0', true);
}
add_action('wp_enqueue_scripts', 'wpfa_assets');

/**
 * Render Splash + The SINGLE Master Audio Element
 */
function wpfa_render_footer() {
    global $wpfa_audio_source;

    // Only render if a shortcode set the source
    if ( ! $wpfa_audio_source ) return;

    ?>
    <audio id="wpfa-master-audio" loop preload="auto" style="display:none;">
        <source src="<?php echo esc_url($wpfa_audio_source); ?>" type="audio/mpeg">
    </audio>

    <div id="wpfa-splash">
        <div class="wpfa-splash-inner">
            <button id="wpfa-start">Open Invitation</button>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'wpfa_render_footer');