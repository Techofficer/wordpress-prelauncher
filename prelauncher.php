<?php
   /*
   Plugin Name: Prelauncher
   Plugin URI: http://prelauncher.info/docs/wordpress
   Description: a plugin which allows to integrate Prelauncher (pre-launch website builder which helps new online store owners to easily build a viral pre-launch website and start referral prelaunch campaign) with Wordpress website.
   Version: 1.0.0
   Author: Artem Efremov
   Author URI: https://ru.linkedin.com/in/efremovartyom
   License: GPL2
   */


$prelaunchr_admin = new PrelauncherSettings();

register_activation_hook( __FILE__ , "loadAPI" );
register_activation_hook( __FILE__ , array($prelaunchr_admin, 'activate' ) );
register_deactivation_hook( __FILE__ , array($prelaunchr_admin, 'deactivate' ) );


function setMetaTags(){
	$ds = \Prelauncher\Constructor::metaTags();
	echo $ds;
}

if ( ! class_exists( 'Prelauncher' ) ) :
	class Prelauncher {

		public $version = '1.0.0';

		public $plugin_name = 'prelauncher';

		public $companyID = null;

		public $token = null;

		/*
			Unique identificator of subscriber
		*/
		public $clientID = null;

		public $page_id = null;

		public $add_scripts = false;

		public $body = null;
		/*
			Associated array with company's ID and private API key
		*/
		public $credentials = null;

		protected static $_self = null;
	

		/*
			Initialization
		*/
		public function __construct() {
			$this->define_constants();
			$this->load_dependencies();
			$this->loadAPI();
			$this->setCredentials();
			$this->setup_hooks();
			
		}

		/*
			Authenticate API calls to Prelauncher.info
		*/
		public function setCredentials(){
			$this->credentials = get_option('prelauncher-credentials');
			\Prelauncher\Settings::configure($this->credentials['company_id'], $this->credentials["private_key"]);
		}	

		public function setup_hooks() {
			add_action( 'the_posts', array( $this, 'check_for_shortcode' ) );
			add_filter( "page_template",array ( $this, "get_prelauncher_template") ) ;
			add_action( 'wp_enqueue_scripts', array($this, 'prelauncher_enqueue_scripts'));
		}

		public function load_dependencies(){
			require_once(dirname( __FILE__ ) . "/lib/httpful/bootstrap.php");
			require_once(dirname( __FILE__ ) . "/lib/restful/bootstrap.php");
			require_once(dirname( __FILE__ ) . "/lib/prelauncher/bootstrap.php");
			require_once dirname( __FILE__ ) . '/includes/prelauncher-settings.php';
		}

		public function loadAPI(){
			\Httpful\Bootstrap::init();
			\RESTful\Bootstrap::init();
			\Prelauncher\Bootstrap::init();
		}

		/*
			Request HTML code of referral page
		*/
		public function uploadReferPage(){
			$ds = \Prelauncher\Constructor::referPage();
			return $ds->clients->html_version;			
		}

		/*
			Request HTML code of landing page
		*/
		public function uploadLandingPage(){
			$ds = \Prelauncher\Constructor::landingPage();
			return $ds->clients->html_version;			
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
					add_shortcode( 'prelauncher', array($this,  'prelauncher_shortcode'));
					$this->add_scripts = true;
					$this->page_id = $post->ID;
					break;
				}
			}

			if ($this->add_scripts)
			    add_action('wp_head', "setMetaTags");
	
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
			if (empty($_GET["uid"])){		
				$this->prelauncher_get_template_part( 'prelauncher', 'landing' );
			} else {
				$this->clientID = $_GET["uid"];
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
