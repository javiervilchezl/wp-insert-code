<?php
defined( 'ABSPATH' ) || exit;

	/**
	 * Class to handle database table data
	 */
	class Wp_Insert_Code_Management
	{

		/**
		 * Create table wpic_insert_code_snippet
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @return void
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
		 * Drop table wpic_insert_code_snippet
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @return void
		 */
	    public function wpic_drop_table()
	    {

	    	global $wpdb;
		    $table_name = $wpdb->prefix . "wpic_insert_code_snippet"; 
		    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
	    	
		}

		/**
		 * Insert new code snippet in table wpic_insert_code_snippet
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @param array $data: (name, description, category, code, state).
		 * @return html notice success/error
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
		 * Get all code snippet of table wpic_insert_code_snippet
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @return array $res (result query)
		 */
		public function wpic_select_all_table()
	    {
		    global $wpdb;
		    $table_name = $wpdb->prefix . "wpic_insert_code_snippet";
		    $res = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A);

			return $res;
		}

		/**
		 * Gets the id of a code snippet
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @param string $name (name/title of code snippet).
		 * @return int $snippet_id (id code snippet)
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
		 * Gets a code snippet
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @param int $id (id of code snippet).
		 * @return array $snippet_row (row: result query)
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
		 * Delete a code snippet
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @param int $id (id of code snippet).
		 * @return boool result for wpdb query true/false
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
		 * Enable a code snippet
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @param int $id (id of code snippet).
		 * @return boool result for wpdb query true/false
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
		 * Disable a code snippet
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @param int $id (id of code snippet).
		 * @return boool result for wpdb query true/false
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
		 * Update a code snippet
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @param array $data_snippet: (name, description, category, code, state).
		 * @return boool result for wpdb query true/false
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
		 * Gets all code snippets that belong to the header category
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @return array $res (result query)
		 */
		public function wpic_get_head_snippet()
	    {
		    global $wpdb;
		    $table_name = $wpdb->prefix . "wpic_insert_code_snippet";
		    $res = $wpdb->get_results( "SELECT * FROM $table_name WHERE category = 'Header' AND state = 'Enabled'", ARRAY_A);
			return $res;
		}

		/**
		 * Gets all code snippets that belong to the footer category
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @return array $res (result query)
		 */
		public function wpic_get_footer_snippet()
	    {
		    global $wpdb;
		    $table_name = $wpdb->prefix . "wpic_insert_code_snippet";
		    $res = $wpdb->get_results( "SELECT * FROM $table_name WHERE category = 'Footer' AND state = 'Enabled'", ARRAY_A);
			return $res;
		}

		/**
		 * Gets all code snippets that belong to the header functions
		 *
		 * @since 1.0
		 * @author Javier Vílchez Luque
		 *
		 * @return array $res (result query)
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

