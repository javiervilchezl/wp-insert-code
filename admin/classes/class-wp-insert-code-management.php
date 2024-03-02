<?php
defined( 'ABSPATH' ) || exit;

	/**
	 * 
	 */
	class Wp_Insert_Code_Management
	{

		/**
	     * Create table
	     */
	    public function wpic_create_table()
	    {
	    	global $wpdb;
		    $charset_collate = $wpdb->get_charset_collate();
		    $table_name = $wpdb->prefix . "wpic_insert_code_snippet"; 
		    $sql = "CREATE TABLE $table_name (
		      id int NOT NULL AUTO_INCREMENT,
		      name text NOT NULL,
		      description text NOT NULL,
		      category text NOT NULL,
		      code text NOT NULL,
		      state text NOT NULL,
		      PRIMARY KEY  (id)
		    ) $charset_collate;";

		    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		    dbDelta( $sql );
	    	
		}

		/**
	     * Drop table
	     */
	    public function wpic_drop_table()
	    {

	    	global $wpdb;
		    $table_name = $wpdb->prefix . "wpic_insert_code_snippet"; 
		    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
	    	
		}

		/**
	     * Insert
	     */
	    public function wpic_insert_table($data)
	    {
	    	
		    global $wpdb;

			$table = $wpdb->prefix.'wpic_insert_code_snippet';
			if($this->wpic_get_id_snippet($data['name'])){
				echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error: The title you are trying to enter for the new snippet already exists, please use another unique title.' ) . '</p></div>';
			}else{
				$format = array('%s', '%s','%s','%s','%s');
				if($wpdb->insert($table,$data,$format)){
					echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Data has been saved successfully.' ) . '</p></div>';
				}else{
					echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error: An error occurred while processing the data.' ) . '</p></div>';
				}
			}
			
			
		}

		/**
	     * Get All Snippets
	     */
		public function wpic_select_all_table()
	    {
		    global $wpdb;
		    $table_name = $wpdb->prefix . "wpic_insert_code_snippet";
		    $res = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);

			return $res;
		}

		/**
	     * Get ID Snippet
	     */
		function wpic_get_id_snippet( $name ) {
			global $wpdb; 	
			$table_name = $wpdb->prefix . "wpic_insert_code_snippet";						 
			$snippet_id = $wpdb->get_var(
				$wpdb->prepare("SELECT id FROM $table_name WHERE name = %s", $name)
				);
			return $snippet_id;  
		} 

		/**
	     * Get a Snippet
	     */
		function wpic_get_a_snippet( $id ) {
			global $wpdb; 	
			$table_name = $wpdb->prefix . "wpic_insert_code_snippet";						 
			$snippet_row = $wpdb->get_row(
				$wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id)
				, ARRAY_A);
			return $snippet_row;   
		} 

		/**
	     * Delete snippet
	     */
		function wpic_delete_snippet( $id ) {
			global $wpdb; 							

		    return $wpdb->delete(
		        $wpdb->prefix . 'wpic_insert_code_snippet',
		        ['id' => $id], 						
		        ['%d'], 							
		    );
		} 

		/**
	     * Enabled snippet
	     */
		function wpic_enabled_snippet( $id ) {
			global $wpdb; 							

		    return $wpdb->update(
				$wpdb->prefix . 'wpic_insert_code_snippet',
				array( 
					'state' => 'Enabled'
				),
				array(
					'id' => $id,
				)
			);
		} 

		/**
	     * Disabled snippet
	     */
		function wpic_disabled_snippet( $id ) {
			global $wpdb; 							

		    return $wpdb->update(
				$wpdb->prefix . 'wpic_insert_code_snippet',
				array( 
					'state' => 'Disabled'
				),
				array(
					'id' => $id,
				)
			);
		} 

		/**
	     * Update snippet
	     */
		function wpic_update_snippet( $data_snippet) {
			global $wpdb; 							

		    return $wpdb->update(
				$wpdb->prefix . 'wpic_insert_code_snippet',
				array( 
					'name' => $data_snippet['name'],
					'description' => $data_snippet['description'],
					'category' => $data_snippet['category'],
					'code' => $data_snippet['code'],
					'state' => $data_snippet['state']
				),
				array(
					'id' => $data_snippet['id'],
				)
			);
		} 

		/**
	     * Get head snippets
	     */
		public function wpic_get_head_snippet()
	    {
		    global $wpdb;
		    $table_name = $wpdb->prefix . "wpic_insert_code_snippet";
		    $res = $wpdb->get_results( "SELECT * FROM $table_name WHERE category = 'Header' AND state = 'Enabled'", ARRAY_A);
			return $res;
		}

		/**
	     * Get footer snippets
	     */
		public function wpic_get_footer_snippet()
	    {
		    global $wpdb;
		    $table_name = $wpdb->prefix . "wpic_insert_code_snippet";
		    $res = $wpdb->get_results( "SELECT * FROM $table_name WHERE category = 'Footer' AND state = 'Enabled'", ARRAY_A);
			return $res;
		}

		/**
	     * Get function snippets
	     */
		public function wpic_get_function_snippet()
	    {
		    global $wpdb;
		    $table_name = $wpdb->prefix . "wpic_insert_code_snippet";
		    $res = $wpdb->get_results( "SELECT * FROM $table_name WHERE category = 'Functions' AND state = 'Enabled'", ARRAY_A);
			return $res;
		}
		
	}
	

?>

