<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://stickybitsoftware.com
 * @since      0.0.1
 * @package    bbPress_Spam_Cleaner
 * @subpackage bbPress_Spam_Cleaner/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    bbPress_Spam_Cleaner
 * @subpackage bbPress_Spam_Cleaner/admin
 * @author     Leonard Smith <leonards@stickybitsoftware.com>
 */
class bbPress_Spam_Cleaner_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * bbPress posts scanned by the plugin
	 *
	 * @since 	0.1.1
	 * @access 	private
	 * @var 	array 		$posts 	bbPress posts scanned by the plugin
	 */
	private $posts;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.1
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bbpress-spam-cleaner-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bbpress-spam-cleaner-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function add_management_page(){
    	add_management_page('bbPress Spam Cleaner Management', 'bbPress Spam Cleaner', 'manage_options', 'bbpress-spam-cleaner-manage', array($this, 'include_management_page'));
//		add_action('admin_menu','bbpress-spam-cleaner-plugin-menu');
	}

	public function include_management_page(){
    	include('partials/bbpress-spam-cleaner-admin-display.php');
	}

	public function management_page_handler(){
		if ( ! bbp_is_post_request() )
			return;

		check_admin_referer( 'bbpress-spam-cleaner-scan' );

		// Stores messages
		$messages = array();

		wp_cache_flush();

		// Cycle through all bbp posts and check them for spam
/*		$is_spam = bbp_is_topic_spam( $topic_id );
		$message = true === $is_spam ? 'unspammed' : 'spammed';
		$success = true === $is_spam ? bbp_unspam_topic( $topic_id ) : bbp_spam_topic( $topic_id );

		$is_spam = bbp_is_reply_spam( $reply_id );
		$message = $is_spam ? 'unspammed' : 'spammed';
		$success = $is_spam ? bbp_unspam_reply( $reply_id ) : bbp_spam_reply( $reply_id );
*/

		if ( bbp_has_topics() ) {
			while( bbp_topics() ){
				bbp_the_topic();
				$content = bbp_get_topic_content();

				$this->posts[] = array(
					'id'			=> bbp_get_topic_id(),
					'title' 		=> bbp_get_topic_title(),
					'content' 		=> $content,
					'spam_status' 	=> $this->checkSpam($content),
				);
			}
		} else {
		//	bbp_get_template_part( 'feedback', 'no-forums' );
		}
	}
	/**
	 * Scan the passed content for spam using the Akismet plugin and return the result.
	 * Shamelessly lifted from Binary Moons function at http://www.binarymoon.co.uk/2010/03/akismet-plugin-theme-stop-spam-dead/
	 *
	 * @since    0.0.1
	 * @access   public
	 * @return boolean 
	 */
	public function checkSpam ($content) {

		// innocent until proven guilty
		$isSpam = FALSE;
		
		$content = (array) $content;
		
		if (function_exists('akismet_init')) {
			
			$wpcom_api_key = get_option('wordpress_api_key');
			
			if (!empty($wpcom_api_key)) {
			
				global $akismet_api_host, $akismet_api_port;

				// set remaining required values for akismet api
				$content['user_ip'] = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
				$content['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
				$content['referrer'] = $_SERVER['HTTP_REFERER'];
				$content['blog'] = get_option('home');
				
				if (empty($content['referrer'])) {
					$content['referrer'] = get_permalink();
				}
				
				$queryString = '';
				
				foreach ($content as $key => $data) {
					if (!empty($data)) {
						$queryString .= $key . '=' . urlencode(stripslashes($data)) . '&';
					}
				}
				
				$response = akismet_http_post($queryString, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);
				
				if ($response[1] == 'true') {
					update_option('akismet_spam_count', get_option('akismet_spam_count') + 1);
					$isSpam = TRUE;
				}
				
			}
			
		}
		
		return $isSpam;
	}
}
