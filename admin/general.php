<?php
	if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    require_once(WPIC_CLASSES.'/class-wp-insert-code-management.php');
    require_once(WPIC_CLASSES.'/class-wp-insert-code-list-table.php');

    //When we save a new code snippet
    if (isset($_POST['save_code_snippet'])) {
    	$wpic_insert_code_table = new Wp_Insert_Code_Management();
    	$data = array('name' => $_POST['snippetTitle'], 'description' => $_POST['snippetDescription'], 'category' => $_POST['snippetCategory'], 'code' => wp_unslash($_POST['snippetCode'], true), 'state' => $_POST['snippetState']);
		$wpic_insert_code_table->wpic_insert_table($data);
    }
    
    //When we edit a code snippet
    if (isset($_POST['edit_code_snippet'])) {
        $wpic_updated_snippet = new Wp_Insert_Code_Management();
    	$data_snippet = array('id' => $_POST['snippetId'], 'name' => $_POST['snippetTitle2'], 'description' => $_POST['snippetDescription2'], 'category' => $_POST['snippetCategory2'], 'code' => wp_unslash($_POST['snippetCode2'], true), 'state' => $_POST['snippetState2']);
		$wpic_updated_snippet->wpic_update_snippet($data_snippet);
    
    }
    
?>
<div class="wrap">
    <h2 class="ow-h2">WP Insert Code Snippet</h2>
    <br />
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdropLive">
	    New Code Snippet
	</button>
	<form method="get">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php
            $render_list_table = new Wp_Insert_Code_List_table();
            $render_list_table->prepare_items();
            $render_list_table->remove_parameters();
            $render_list_table->search_box( __('Search files', 'wp-insert-code'), 'search_id');
            $render_list_table->display();
        ?>
    </form>

    <?php
        if(isset($_GET['action']) && $_GET['action']=='edit' && !isset($_POST['edit_code_snippet']) && !isset($_POST['save_code_snippet'])){
            if(isset($_GET['title'])){
                $name_snippet = $_GET['title'];
                $wpic_get_snippet = new Wp_Insert_Code_Management();
                $id_snippet = $wpic_get_snippet->wpic_get_id_snippet( $name_snippet );
                $row_snippet = $wpic_get_snippet->wpic_get_a_snippet( $id_snippet );
                $nameedit = $row_snippet["name"];
                $descriptionedit = $row_snippet["description"];
                $categoryeedit = $row_snippet["category"];
                $codeedit = $row_snippet["code"];
                $stateedit = $row_snippet["state"];

                ?>

                <div class="modal fade show" id="staticBackdropLive2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLiveLabel" style="display:block;" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="newSnippetForm2" method="post" action="">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLiveLabel">Edit Code Snippet</h1>
                                    <button type="button" class="btn-close"  onclick="cerrarmodal();" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    
                                        <div class="mb-3">
                                            <label for="snippetTitle">Title: <span class="label-danger">(The title must be unique and not repeated)</span></label>
                                            <input type="text" class="form-control" id="snippetTitle2" name="snippetTitle2" value="<?php echo $nameedit; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="snippetDescription">Description:</label>
                                            <textarea class="form-control" id="snippetDescription2" name="snippetDescription2" rows="3"><?php echo $descriptionedit; ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="snippetCategory">Category:</label>
                                            <select class="form-select" id="snippetCategory2" name="snippetCategory2">
                                                <option value="Header" <?php if($categoryeedit=="Header"){ echo "selected";} ?> >Header</option>
                                                <option value="Footer" <?php if($categoryeedit=="Footer"){ echo "selected";} ?> >Footer</option>
                                                <option value="Functions" <?php if($categoryeedit=="Functions"){ echo "selected";} ?> >Functions</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="snippetCode">Code:<span class="label-danger"> (You should use the opening and closing tags in CSS < style >...< /style >, for JavaScript < script >...< /script >.)</span></label>
                                            <textarea class="form-control" id="snippetCode2" name="snippetCode2" rows="6" required><?php echo $codeedit; ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="snippetState">status:</label>
                                            <select class="form-select" id="snippetState2" name="snippetState2">
                                                <option value="Enabled" <?php if($stateedit=="Enabled"){ echo "selected";} ?> >Enabled</option>
                                                <option value="Disabled" <?php if($stateedit=="Disabled"){ echo "selected";} ?> >Disabled</option>
                                            </select>
                                        </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="cerrarmodal();">Close</button>
                                    <button type="submit" name="edit_code_snippet" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                            <input type="hidden" id="snippetId" name="snippetId" value="<?php echo $id_snippet; ?>" />
                            </form>
                        </div>
                    </div>

                <?php

            }
        }
    ?>


	<div class="modal fade" id="staticBackdropLive" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
	    aria-labelledby="staticBackdropLiveLabel" style="display: none;" aria-hidden="true">
	    <div class="modal-dialog">
	    	<form id="newSnippetForm" method="post" action="">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h1 class="modal-title fs-5" id="staticBackdropLiveLabel">New Code Snippet</h1>
	                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	            </div>
	            <div class="modal-body">
	                
                        <div class="mb-3">
                            <label for="snippetTitle">Title: <span class="label-danger">(The title must be unique and not repeated)</span></label>
                            <input type="text" class="form-control" id="snippetTitle" name="snippetTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="snippetDescription">Description:</label>
							<textarea class="form-control" id="snippetDescription" name="snippetDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="snippetCategory">Category:</label>
                            <select class="form-select" id="snippetCategory" name="snippetCategory">
                                <option value="Header">Header</option>
                                <option value="Footer">Footer</option>
                                <option value="Functions">Functions</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="snippetCode">Code:<span class="label-danger"> (You should use the opening and closing tags in CSS < style >...< /style >, for JavaScript < script >...< /script >.)</span></label>
                            <textarea class="form-control" id="snippetCode" name="snippetCode" rows="6" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="snippetState">status:</label>
                            <select class="form-select" id="snippetState" name="snippetState">
                                <option value="Enabled">Enabled</option>
                                <option value="Disabled">Disabled</option>
                            </select>
                        </div>
                    
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	                <button type="submit" name="save_code_snippet" class="btn btn-primary">Save</button>
	            </div>
	        </div>
	        </form>
	    </div>
	</div>
</div>
