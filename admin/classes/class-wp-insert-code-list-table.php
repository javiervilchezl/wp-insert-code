<?php
defined( 'ABSPATH' ) || exit;
/**
 * Class to manage the list table of snippets
 *
 * @package wp-insert-code
 */

if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
require_once(WPIC_CLASSES.'/class-wp-insert-code-management.php');

/**
 *  The class extends WP_List_Table
 */
class Wp_Insert_Code_List_table extends WP_List_Table
{


	/**
	 * Construct: should call this constructor from its own constructor to override the default $args
	 */
	public function __construct() 
	{
		parent::__construct(
			array(
				'singular' => __( 'file', 'wp-insert-code' ),
				'plural'   => __( 'files', 'wp-insert-code' ),
				'ajax'     => false,
			)
		);
	}

	/**
	 * Get the columns for the table
	 *
	 * @since 1.0
 	 * @author Javier Vílchez Luque
	 * 
	 */
    function get_columns()
    {
        $columns = array(
                'cb'          => '<input type="checkbox" />',
                'title'       => __( 'Title', 'wp-insert-code' ),
				'description' => __( 'Description', 'wp-insert-code' ),
				'category'    => __( 'Category', 'wp-insert-code' ),
				'status'       => __( 'Status', 'wp-insert-code' )
        );
        return $columns;
    }

    /**
	 * Gets the list of sortable columns
	 *
	 * @since 1.0
 	 * @author Javier Vílchez Luque
 	 * 
 	 * @return array An associative array containing all sortable columns.
	 * 
	 */
    protected function get_sortable_columns() {
		$sortable_columns = array(
			'title'       => array( 'title', false ),
			'description' => array( 'description', false ),
			'category'    => array( 'category', false ),
			'status'       => array( 'status', false ),
		);

		return $sortable_columns;
	}

	 /**
	 * Get the value of the default columns
	 *
	 * @since 1.0
	 * @author Javier Vílchez Luque
	 * 
	 * @param object $item singular item.
	 * @param string $column_name The column to be processed.
	 * 
	 * @return string Text/HTML for column content <td>.
	 * 
	 */
    protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'title':
				if ($item['status']=="Enabled") {
					$disabled_snippet = wp_nonce_url(
						add_query_arg(
							array(
								'title'   => $item['title'],
								'action' => 'disabled',
							)
						)
					);
					$edit_snippet = wp_nonce_url(
						add_query_arg(
							array(
								'title'   => $item['title'],
								'action' => 'edit',
							)
						)
					);
					$delete_snippet = wp_nonce_url(
						add_query_arg(
							array(
								'title'   => $item['title'],
								'action' => 'delete',
							)
						)
					);
					$actions = array(
						'disabled' => sprintf( '<a href="%1$s">%2$s</a>', $disabled_snippet, __( 'Disabled', 'wp-insert-code' ) ),
						'edit' => sprintf( '<a href="%1$s">%2$s</a>', $edit_snippet, __( 'Edit', 'wp-insert-code' ) ),
						'delete' => sprintf( '<a href="%1$s">%2$s</a>', $delete_snippet, __( 'Delete', 'wp-insert-code' ) ),
					);
				}else{
					$enabled_snippet = wp_nonce_url(
						add_query_arg(
							array(
								'title'   => $item['title'],
								'action' => 'enabled',
							)
						)
					);
					$edit_snippet = wp_nonce_url(
						add_query_arg(
							array(
								'title'   => $item['title'],
								'action' => 'edit',
							)
						)
					);
					$delete_snippet = wp_nonce_url(
						add_query_arg(
							array(
								'title'   => $item['title'],
								'action' => 'delete',
							)
						)
					);
					$actions = array(
						'enabled' => sprintf( '<a href="%1$s">%2$s</a>', $enabled_snippet, __( 'Enabled', 'wp-insert-code' ) ),
						'edit' => sprintf( '<a href="%1$s">%2$s</a>', $edit_snippet, __( 'Edit', 'wp-insert-code' ) ),
						'delete' => sprintf( '<a href="%1$s">%2$s</a>', $delete_snippet, __( 'Delete', 'wp-insert-code' ) ),
					);
				}
				
				
				return sprintf( '<span id="%s">%s</span> %s', $item[ $column_name ], $item[ $column_name ], $this->row_actions( $actions ) );
				break;
			case 'description':
			case 'category':
			case 'status':
				return $item[ $column_name ];
				break;
			default:
				return print_r( $item, true );
		}
	}

	/**
	 * Prepare all the data for the table
	 * 
	 * @return void.
	 * 
	 */
	public function prepare_items() {
		$this->perform_actions();

		$columns = $this->get_columns();
		$hidden  = array();
		$sortables = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortables );

		/* Populate the table */
		$items_snippets = $this->get_items_snippets(50);
		$this->items = $items_snippets['items'];

		/* Build pagination */
		$current_page = $this->get_pagenum();
		$total_items  = $items_snippets['total_items'];
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => 50,
				'total_pages' => ceil( $total_items / 50 ),
			)
		);
	}

	/**
	 * Get all snippets
	 * 
	 * @param int $per_page Number of items per page.
	 * 
	 * @return array. An associative array with the array of snippets and the total number of items.
	 * 
	 */
	private function get_items_snippets( $per_page = 50 ) {

		$snippets_items = array();

		
		try 
		{
			$results_snippets = new Wp_Insert_Code_Management();
			$results = $results_snippets->wpic_select_all_table();

			foreach ( $results as $result ) {
				$item = array();
				$item['title'] = $result['name'] ;
				$item['description'] = $result['description'];
				$item['category'] = $result['category'];
				$item['status'] = $result['state'];
				array_push( $snippets_items, $item );
			}
			
		}catch(\Throwable $e){
			error_log(sprintf('Error: %s.', $e->getMessage()), $e->getCode()); 
		}
		

		
		if ( ! empty( $_GET['s'] ) ) { 
			$snippets_item_filter = array_filter(
				$snippets_items,
				function ( $array ) {
					return sanitize_key( $_GET['s'] ) !== '' && mb_strpos( $array['title'], sanitize_key( $_GET['s'] ) ) !== false; 
				}
			);
			
		} else {
			$snippets_item_filter = $snippets_items;
			
		}

	
		if ( ! empty( $_GET['orderby'] ) ) {
			usort(
				$snippets_item_filter,
				function( $a, $b ) {
					$order_by = sanitize_key( $_GET['orderby'] ); 
					if ( isset( $_GET['order'] ) && 'asc' === $_GET['order'] ) { 
						return $a[ $order_by ] <=> $b[ $order_by ];
					} else {
						return $b[ $order_by ] <=> $a[ $order_by ];
					}
				}
			);
		}

		
		$offset = ( $this->get_pagenum() - 1 ) * $per_page;
		$all_snippets_items = array_slice( $snippets_item_filter, $offset, $per_page, $preserve_keys = true );

		return array(
			'items'       => $all_snippets_items,
			'total_items' => count( $snippets_item_filter ),
		);

	}

	/**
	 * Get value for cb columns (input checkbox)
	 *
	 * @since 1.0
	 * @author Javier Vílchez Luque
	 * 
	 * @param object $item singular item.
	 * 
	 * @return string Text for column content <td>>.
	 * 
	 */
	protected function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],
			$item['title']
		);
	}

	/**
	 * Process actions snippets list table
	 *
	 * @since 1.0
	 * @author Javier Vílchez Luque
	 * 
	 * @return void
	 * 
	 */
	private function perform_actions() {
		if ( ! isset( $_REQUEST['title'] ) ) {
			return;
		}

		/* Put files in array to treat single action and bulk action the same way */
		$array_names = (array) ( $_REQUEST['title'] );

		//Action enabled
		if ((!isset($_POST['save_code_snippet']) && $this->current_action() === 'enabled' && wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'] )) || (!isset($_POST['save_code_snippet']) && $this->current_action() === 'enabled' && wp_verify_nonce( $_REQUEST['_wpnonce'] ))){

			foreach ( $array_names as $name ) {

				$enabled_snippets = new Wp_Insert_Code_Management();
				$id_snippet = $enabled_snippets->wpic_get_id_snippet( $name );
				
				if($enabled_snippets->wpic_enabled_snippet( $id_snippet )){
					echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'The snippet has been successfully enabled.' ) . '</p></div>';
				}else{
					echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error: An error occurred while enabling the snippet.' ) . '</p></div>';
				}
				

			}
		}
		//Action disabled
		if ((!isset($_POST['save_code_snippet']) && $this->current_action() === 'disabled' && wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'] )) || (!isset($_POST['save_code_snippet']) && $this->current_action() === 'disabled' && wp_verify_nonce( $_REQUEST['_wpnonce'] ))){

			foreach ( $array_names as $name ) {

				$disabled_snippets = new Wp_Insert_Code_Management();
				$id_snippet = $disabled_snippets->wpic_get_id_snippet( $name );
				
				if($disabled_snippets->wpic_disabled_snippet( $id_snippet )){
					echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'The snippet has been successfully disabled.' ) . '</p></div>';
				}else{
					echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error: An error occurred while disabling the snippet.' ) . '</p></div>';
				}
				

			}
		}
		//Action edit
		if ((!isset($_POST['save_code_snippet']) && $this->current_action() === 'edit' && wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'] )) || (!isset($_POST['save_code_snippet']) && $this->current_action() === 'edit' && wp_verify_nonce( $_REQUEST['_wpnonce'] ))){
			/*foreach ( $array_names as $name ) {
				$snippet = $name;
			}
			
			$url =  home_url() .'?edit_snippet='.$snippet );
			wp_safe_redirect( $url );
			exit;*/
			/*foreach ( $array_names as $name ) {
				
			}*/
		}
		//Action delete
		if ((!isset($_POST['save_code_snippet']) && $this->current_action() === 'delete' && wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'] )) || (!isset($_POST['save_code_snippet']) && $this->current_action() === 'delete' && wp_verify_nonce( $_REQUEST['_wpnonce'] ))){

			foreach ( $array_names as $name ) {

				$delete_snippets = new Wp_Insert_Code_Management();
				$id_snippet = $delete_snippets->wpic_get_id_snippet( $name );
				
				if($delete_snippets->wpic_delete_snippet( $id_snippet )){
					echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'The snippet has been successfully deleted.' ) . '</p></div>';
				}else{
					echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'Error: An error occurred while deleting the snippet.' ) . '</p></div>';
				}
				

			}
		}
		
	}

	/**
	 * Bulk action
	 *
	 * @since 1.0
	 * @author Javier Vílchez Luque
	 * 
	 * @return array.
	 * 
	 */
	/*protected function get_bulk_actions() {
		$actions = array(
			'enabled' => __( 'Enabled', 'wp-insert-code' ),
			'disabled' => __( 'Disabled', 'wp-insert-code' ),
			'delete' => __( 'Delete', 'wp-insert-code' ),
		);
		return $actions;
	}*/

	/**
	 * 
	 * Eliminate the action and action2 parameters to avoid invalidating the same scripts more times
	 * 
	 */
	public function remove_parameters() {
		$options = array(
			'action',
			'action2',
		);
		$_SERVER['REQUEST_URI'] = remove_query_arg( $options, $_SERVER['REQUEST_URI'] ); 
	}

	/**
	 * Displays the search box.
	 *
	 * @param string $text: The 'submit' button label.
	 * @param string $input_id: ID attribute value for the search input field.
	 */
	public function search_box( $text, $input_id ) {
		if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
			return;
		}

		$input_id = $input_id . '-search-input';

		if ( ! empty( $_REQUEST['orderby'] ) ) { // phpcs:ignore
			echo '<input type="hidden" name="orderby" value="' . esc_attr( wp_unslash( $_REQUEST['orderby'] ) ) . '" />'; 
		}
		if ( ! empty( $_REQUEST['order'] ) ) { // phpcs:ignore
			echo '<input type="hidden" name="order" value="' . esc_attr( wp_unslash( $_REQUEST['order'] ) ) . '" />'; 
		}
		if ( ! empty( $_REQUEST['post_mime_type'] ) ) { // phpcs:ignore
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( wp_unslash( $_REQUEST['post_mime_type'] ) ) . '" />'; 
		}
		if ( ! empty( $_REQUEST['detached'] ) ) { // phpcs:ignore
			echo '<input type="hidden" name="detached" value="' . esc_attr( wp_unslash( $_REQUEST['detached'] ) ) . '" />'; 
		}
		if ( ! empty( $_REQUEST['tab'] ) ) { // phpcs:ignore
			echo '<input type="hidden" name="tab" value="' . esc_attr( wp_unslash( $_REQUEST['tab'] ) ) . '" />';
		}
		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_attr( $text ); ?>:</label>
			<input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
				<?php
				submit_button(
					$text,
					'',
					'',
					false,
					array(
						'id'  => 'search-submit',
					)
				);
				?>
		</p>

		<?php
	}
}
?>