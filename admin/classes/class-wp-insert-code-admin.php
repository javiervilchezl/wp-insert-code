<?php
defined( 'ABSPATH' ) || exit;

/**
 * 
 */
class Wp_Insert_Code_Admin
{
	/**
     * Constructor
     */
	public function __construct()
	{
		// Load the plugin classes
        $this->load_classes();

        // Initialize the plugin functionality
        $this->init();

		// Add menu to dashboard
		if ( is_multisite() && is_main_site() ) {
			add_action( 'network_admin_menu', array( $this, 'wpic_menu_administrador' ) );
		}else{
			add_action('admin_menu', array($this, 'wpic_menu_administrador'));
		}
		
		
		// Add the plugin's own scripts and css
		$page_url = "$_SERVER[REQUEST_URI]";

		if(strpos($page_url, "wp-insert-code")){
			add_action('admin_enqueue_scripts',array($this, 'wpic_enqueue_scripts'));
		}
		
		
	}

	/**
     * Load the plugin classes
     */
    public function load_classes()
    {
        
    }

    /**
     * Initialize the plugin functionality
     */
    public function init()
    {
    	
    	
	}

	/**
     * Menu Dashboard
     */
	public function wpic_menu_administrador()
	{
	    add_menu_page('WP Insert Code','WP Insert Code','manage_options',WPIC_ADMIN . '/general.php');//add_submenu_page( WPIC_ADMIN . '/general.php', 'Cache Navegador', 'Cache Navegador', 'manage_options', WPOW_INFINITED_SUBMENU_ADMIN . '/cache-browser.php');
	}

	/**
	 * Enqueue scripts js and css
	 *
	 * @since 1.0
	 * @author Javier Vílchez Luque
	 *
	 * @return void
	 */
	public function wpic_enqueue_scripts(){
	    // styles
	    wp_enqueue_style( 'bootstrapCss' , plugins_url( '../../assets/css/bootstrap.min.css', __FILE__ ) , array(), 20141119 );
	    wp_enqueue_style( 'bootstrapCssToggle' , plugins_url( '../../assets/css/bootstrap4-toggle.min.css', __FILE__ ) , array(), 20141119 );
	    wp_enqueue_style( 'wpicCss' , plugins_url( '../../assets/css/wpic.style.css', __FILE__ ) , array(), 20141119 );
	    
	    // scripts
	    wp_enqueue_script( 'bootstrapJs' , plugins_url( '../../assets/js/bootstrap.min.js', __FILE__ ) , array( 'jquery' ), '20120206' , true );
	    wp_enqueue_script( 'bootstrapJsToogle' , plugins_url( '../../assets/js/bootstrap4-toggle.min.js', __FILE__ ) , array( 'jquery' ), '20120206' , true );
	    wp_enqueue_script( 'wpowJs' , plugins_url( '../../assets/js/wpic.script.js', __FILE__ ) , array( 'jquery' ), '20120206' , true );
	    
	   
	}
}

?>



