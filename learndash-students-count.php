<?php
/**
 * Plugin Name: Students Count for Learndash
 * Plugin URI: https://wptrat.com/students-count-for-learndash/
 * Description: Students Count for LearnDash is the ultimate way to show your visitors how many students you have in your LearnDash courses.
 * Author: Luis Rock
 * Author URI: https://wptrat.com/
 * Version: 1.0.2
 * Text Domain: learndash-students-count
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package LearnDash Students Count
 */

if ( ! defined( 'ABSPATH' ) ) exit;
		
// Check if LearnDash is active. If not, deactivate...
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if( !is_plugin_active('sfwd-lms/sfwd_lms.php' ) ) {
    add_action( 'admin_init', 'trsc_deactivate' );
    add_action( 'admin_notices', 'trsc_admin_notice' );
    function trsc_deactivate() {
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }
    // Notice
    function trsc_admin_notice() { ?>
        <div class="notice notice-error is-dismissible">
            <p>
                <strong>
                    <?php echo esc_html_e( 'LearnDash LMS is not active: STUDENTS COUNT FOR LEARNDASH needs it, that\'s why was deactivated', 'learndash-students-count' ); ?>
                </strong>
            </p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">
                    Dismiss this notice.
                </span>
            </button>
        </div><?php
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] ); 
        }        
    }
}

add_action( 'init', 'trsc_load_textdomain' );
function trsc_load_textdomain() {
  load_plugin_textdomain( 'learndash-students-count', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

// FILES NEEDED
require_once('admin/trsc-settings.php');
require_once('includes/functions.php');

//ADMIN CSS
function trsc_enqueue_admin_script( $hook ) {
    global $trsc_settings_page;
	if( $hook != $trsc_settings_page ) {
        return;
    }
    wp_enqueue_style('trsc_admin_style', plugins_url('assets/css/trsc_admin.css',__FILE__ ));
}
add_action( 'admin_enqueue_scripts', 'trsc_enqueue_admin_script' );


//	$has_access   = sfwd_lms_has_access( $course_id, $user_id );
//	$is_completed = learndash_course_completed( $user_id, $course_id );

//FRONTEND CSS (only for courses posts and pages with ld course list shortcode) - copied and adpated from learndash
function trsc_enqueue_script() {
	global $post, $ld_course_grid_assets_needed;

	if ( ( is_a( $post, 'WP_Post' ) && ( preg_match( '/(\[ld_\w+_list)/', $post->post_content ) || preg_match( '/wp:learndash\/ld-course-list/', $post->post_content ) ) )
		|| ( isset( $ld_course_grid_assets_needed ) && $ld_course_grid_assets_needed === true )
		|| is_post_type_archive( 'sfwd-courses' )
		|| is_singular( array( 'sfwd-courses' ) )
	) {
        wp_enqueue_style('trsc_style', plugins_url('assets/css/trsc.css',__FILE__ ));    
    }
}
add_action( 'wp_enqueue_scripts', 'trsc_enqueue_script' );

//CONSTANTS
define("TRSC_SINGULAR_TEXT", get_option('trsc_singular_text'));
define("TRSC_SINGULAR_HIDE", get_option('trsc_singular_hide'));
define("TRSC_SINGULAR_STYLING", get_option('trsc_singular_styling'));
define("TRSC_PLURAL_TEXT", get_option('trsc_plural_text'));
define("TRSC_PLURAL_HIDE", get_option('trsc_plural_hide'));
define("TRSC_PLURAL_STYLING", get_option('trsc_plural_styling'));
define("TRSC_ZERO_TEXT", get_option('trsc_zero_text'));
define("TRSC_ZERO_HIDE", get_option('trsc_zero_hide'));
define("TRSC_ZERO_STYLING", get_option('trsc_zero_styling'));
define("TRSC_GRID_SHOW", get_option('trsc_grid_show'));
define("TRSC_SINGLE_SHOW", get_option('trsc_single_show'));
define("TRSC_WHO_CAN_SEE", get_option('trsc_who_can_see'));
define("TRSC_COUNT_UPDATE", get_option('trsc_count_update'));
define("TRSC_WHO_POSSIBLE_VALUES", ['all', 'visitors', 'logged']);
define("TRSC_UPDATE_POSSIBLE_VALUES", ['0', '1', '3', '6', '12', '24']);

//MAKE PLUGIN WORK!
add_action(
    'learndash-course-infobar-before',
    'trsc_students_enrolled_single',
    10,
    2
);

add_filter(
    'learndash_course_grid_html_output', 
    'trsc_students_enrolled_grid',
    999,
    4
);