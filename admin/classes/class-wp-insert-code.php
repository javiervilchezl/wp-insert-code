<?php
defined( 'ABSPATH' ) || exit;
/**
 * 
 */
class Wp_Insert_Code
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
	}

	/**
     * Load the plugin classes
     */
    public function load_classes()
    {
        require_once 'class-wp-insert-code-admin.php';
    }

    /**
     * Initialize the plugin functionality
     */
    public function init()
    {
    	$admin = new Wp_Insert_Code_Admin();
    }
    
}

?>