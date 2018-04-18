<?php
/**
 * Add our admin page to the settings section
 */
add_action('admin_menu' , 'add_oplus_admin_page');

/**
 * Add a submenu option to Dashboard > Settings
 */
function add_oplus_admin_page() {

	if ( get_option('ct_control_post_types') ) add_filter( 'ct_post_types', 'get_options_fixed_post_types' , 90 ,1 );

	add_submenu_page( 	'options-general.php',
						__('Oxygen Admin Plus', 'oxygen-admin-plus'),
						__('Oxygen Admin Plus', 'oxygen-admin-plus'),
						'manage_options',
						'oplus_settings',
						'oplus_settings_callback');
}

function get_options_fixed_post_types( $post_types ) {
	// forget about previous settings, just use these instead
	return ( is_array( get_option('ct_control_post_types') )? get_option('ct_control_post_types') : array() );
}

// main display callback
function oplus_settings_callback() {
		?>
        <div class="wrap">
        	<h2><?php echo __('Oxygen Admin Plus Settings', 'oxygen-admin-plus'); ?></h2>
		<form method="post" action="options.php">
		    <?php settings_fields( 'oplus-settings' ); ?>
		    <?php //do_settings_sections( 'selected_post_type' ); ?>
			<?php wp_nonce_field( 'update'); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e( 'Add Oxygen Plus Row Actions on these selected Post Types', 'oxygen-admin-plus' );?></th>
					<td>
<?php
		$post_types 	= 	get_post_types ( array( 'public' => true ), 'objects' );

		$options_post_types = get_option( 'ct_control_post_types' );

	foreach ($post_types as $post_type) :
		// skip the attachment post_type
		if($post_type->name == 'attachment')		 			continue;

		?><p><input type="checkbox" name="post_types[]" id="post_type_<?php echo $post_type->name; ?>" value="<?php echo $post_type->name; ?>" <?php checked(in_array($post_type->name, $options_post_types)); ?>/>
		<label for="post_type_<?php echo $post_type->name; ?>"><?php echo $post_type->label; ?></label><p>
		<?php

	endforeach;
?>

					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('include "Remove Oxygen Contents" button on Row Actions', 'oxygen-admin-plus'); ?></th>
					<td><input type="checkbox" value="add" name="add_remove_oxsc" id="add_remove_oxsc" <?php checked( get_option('ct_control_removeoxsc') ); ?>></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Add columns "Apply To" and "In Use" on Templates overview', 'oxygen-admin-plus'); ?></th>
					<td><input type="checkbox" value="add" name="add_columns" id="add_columns" <?php checked( get_option('ct_control_add_columns') ); ?>></td>
				</tr>
			</table>
        <?php submit_button(); ?>
        </form>
        </div>
<?php
}

add_action( 'admin_action_update' , 'cl_update_oplus_options' );

function cl_update_oplus_options() {

	$nonce = $_GET['_wpnonce'];

	wp_verify_nonce( $nonce, 'update' );

	if (isset($_POST['post_types'])):
		if (is_array($_POST['post_types'])):
			$post_types = json_encode( $_POST['post_types']);
			update_option( 'ct_control_post_types' , $_POST['post_types']);
		endif;
	else:
		update_option( 'ct_control_post_types' , array());
	endif;

	update_option( 'ct_control_add_columns', 'add' == $_POST['add_columns'] );

	update_option( 'ct_control_removeoxsc', 'add' == $_POST['add_remove_oxsc'] );

	// if ( isset($_POST['extra_post_types'])):
	// 	update_option( 'ct_control_post_types' , $_POST['extra_post_types']);
	// endif;

	wp_redirect( add_query_arg(
		array(
		    'ct_message' => urlencode(__( 'Successfully updated.', 'oxygen-admin-plus' ) ),
		),
		$_SERVER['HTTP_REFERER'])
	);



	exit();
}
