<?php
   /*
   Plugin Name: Prelauncher
   Plugin URI: http://prelauncher.info
   Description: a plugin which allows to integrate Prelauncher (pre-launch website builder which helps new online store owners to easily build a viral pre-launch website and start referral prelaunch campaign) with Wordpress.
   Version: 1.0.0
   Author: Artem Efremov
   Author URI: https://www.facebook.com/artyom.efremov.9
   License: GPL2
   */







if ( ! class_exists( 'Prelauncher' ) ) :

	class Prelauncher {

		public $version = '1.0.0';

		public $plugin_name = 'prelauncher';

		public $companyID = null;

		public $token = null;

		public $clientID = null;

		public $page_id = null;

		public $add_scripts = false;

		protected static $_self = null;
	
		public function __construct() {

			$this->define_constants();
			$this->setup_hooks();
		}	

		public function setup_hooks() {
			add_shortcode( 'prelauncher', array($this,  'prelauncher_shortcode'));

			add_action( 'the_posts', array( $this, 'check_for_shortcode' ) );

			add_filter( "page_template", array ( $this, "get_prelauncher_template") ) ;


			add_action( 'wp_enqueue_scripts', array($this, 'prelauncher_enqueue_scripts'));

			add_action( 'init', array ( $this, 'check_theme_support') );
		}

		
		
		public function get_prelauncher_template($page_template){
			if ( $this->add_scripts ) {
		    	$page_template = dirname( __FILE__ ) . '/templates/index.php';
		    }
		    return $page_template;
		}

		public function check_for_shortcode( $posts ) {

			if ( empty( $posts ) )
				return $posts;

			foreach ( $posts as $post ) {

				if ( false !== stripos( $post->post_content, '[prelauncher' ) ) {
					$this->add_scripts = true;
					$this->page_id = $post->ID;
					break;
				}
			}



			return $posts;
		}


		public function check_theme_support() {

			if ( current_theme_supports('prelauncher') && get_option('page_on_front') ) {
				$this->add_scripts = true;
			}
		}
		
		public static function instance() {

			if ( is_null( self::$_self ) ) {
				self::$_self = new self();
			}

			return self::$_self;

		}

		public function define_constants() {
			define( 'PRELAUNCHER_VERSION', $this->get_version() );
			define( 'PRELAUNCHER_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
			define( 'PRELAUNCHER_TEMPLATE_PATH', PRELAUNCHER_PLUGIN_PATH . '/templates/');
			define( 'PRELAUNCHER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		}

		public function get_version() {
			return $this->version;
		}

		public function prelauncher_enqueue_scripts() {
			if ( $this->add_scripts ) {

				wp_enqueue_style('bootstrap', plugins_url( '/assets/stylesheets/bootstrap.min.css' , __FILE__ ), array(), "3.3.5");
				wp_enqueue_style('prelauncher', plugins_url( '/assets/stylesheets/prelauncher.css' , __FILE__ ), array(), PRELAUNCHER_VERSION);
				wp_enqueue_style('prelauncher-fonts', plugins_url('/assets/stylesheets/fonts.css' , __FILE__ ), array(), PRELAUNCHER_VERSION);
				wp_enqueue_style('font-awesome', plugins_url( '/assets/stylesheets/font-awesome.min.css' , __FILE__ ), array(), "4.4.0");

				wp_enqueue_script('prelauncher-api', plugins_url( '/assets/javascripts/prelauncher.js' , __FILE__ ), array('jquery'), PRELAUNCHER_VERSION);
			}
		}


		/**
		 * Check theme for templates overwise use default plugin templates
		 */
		public function prelauncher_get_template_part( $slug , $name = null ) {

			if ( empty( $slug ) ) {
				return;
			}

			$name = (string) $name;
			if ( '' !== $name ) {
				$template = "{$slug}-{$name}.php";
			} else {
				$template = "{$slug}.php";
			}

			load_template( PRELAUNCHER_TEMPLATE_PATH . $template );
		}


		function prelauncher_shortcode( $atts, $content = '' ){

			if ( empty( $atts['company_id'] ) ){
				return '<strong>Company ID is not specified </strong>';
			}

			if ( empty( $atts['token'] ) ){
				return "<strong>Token is not specified</string>";
			}

			$this->companyID = $atts['company_id'];
			$this->token = $atts["token"];

			if (empty($_GET["client_id"])){				
				$this->prelauncher_get_template_part( 'prelauncher', 'landing' );
			} else {
				$this->clientID = $_GET["client_id"];
				$this->prelauncher_get_template_part( 'prelauncher', 'refer' );
			}


			return ob_get_clean();
		}		

	}

endif;


function Prelauncher() {

	return Prelauncher::instance();
}

$Prelauncher = Prelauncher();







?>
