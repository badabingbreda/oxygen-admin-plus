<?php

/**
 * Add an 'edit with Oxygen' button on set posttypes, use filter ct_post_types to add to the array;
 */

// add a filter to add my cpt's
add_filter( 'ct_post_types', 'add_my_custom_post_types' , 10 ,1 );

// merge arrays
function add_my_custom_post_types( $post_types ) {
	// add these post-types:
	$new_post_types = array( 'books' );
	return array_merge( $post_types, $new_post_types) ;
}

// add action to current_screen because that's where we will get info on the current post_type
add_action('current_screen' , 'ct_add_action_edit_oxygen' , 10, 1);

/**
 * Add the edit with oxygen action on filtered my_post_types
 * @return [type] [description]
 */
function ct_add_action_edit_oxygen () {

	// set the baseline for the post_types where we want to add the 'edit with oxygen'
	$my_post_types = apply_filters( 'ct_post_types', array( 'page', 'post', 'ct_template' ), $array = array() );

	// get current screen info
	$current_screen = get_current_screen();

	// if current post_type is found in our set $my_post_types
	if ( in_array($current_screen->post_type, $my_post_types)  ) {
		// either one is good, both is better
		add_filter( "post_row_actions" , 'show_post_row_actions', 10, 2 );
		add_filter( "page_row_actions" , 'show_post_row_actions', 10, 2 );
	}

}

add_action( 'current_screen' , 'add_remove_oxygen_options', 10, 1);

function add_remove_oxygen_options() {

	if (!get_option('ct_control_removeoxsc')) return;

	$current_screen = get_current_screen();
	// return early of on ct_template admin-page.
	// we wouldn't want to remove the contents from the templates, now would we?
	if ( $current_screen->post_type == 'ct_template' ) return;

	add_action('admin_footer', 'jq_confirm_delete_oxygen_safeguard' , 10, 1);
	add_filter( "page_row_actions" , 'add_remove_oxygen_action', 15, 2 );
	add_filter( "post_row_actions" , 'add_remove_oxygen_action', 15, 2 );
}

// Add a popup on the 'Remove Oxygen Contents' button to prevent accidental deletion
function jq_confirm_delete_oxygen_safeguard() {
$output = '
<script>
    jQuery(function($) {
		$(".row-actions .removeoxygen a").on("click", function() {
	        return confirm("'.sprintf( __('Are you sure you want to remove Oxygen contents on postid %s ? \nAll layout contents will be lost forever!', 'oxygen-admin-plus'), '"+$(this).data("postid")+"' ) .'");
		});
    });
</script>';
echo $output;
}

/**
 * Filter that adds an action-button to the row_actions to enable direct editing
 * @param  [type] $actions [description]
 * @param  [type] $post    [description]
 * @return [type]          [description]
 */
function add_remove_oxygen_action ( $actions , $post ) {
	$meta = get_post_meta($post->ID);
	if (array_key_exists( 'ct_builder_shortcodes', $meta) && $meta['ct_builder_shortcodes'][0]!=='' ):
		//var_dump( $meta['ct_builder_shortcodes'][0]);
		$nonced_url = wp_nonce_url('post.php?post='.$post->ID.'&action=removeoxsc', 'removeoxsc_'.$post->ID  );
		$actions['removeoxygen'] = '<a href="'.$nonced_url.'" data-postid="'.$post->ID.'">'.__('Remove Oxygen Contents', 'oxygen-admin-plus').'</a>';
	endif;

	//var_dump( $post);
	return $actions;
}

add_action('admin_notices', 'show_success');

/**
 * Display an admin notice to the wp admin panel
 *
 * @type function
 * @date 17/10/16
 * @since  1.0.0
 * @return void
 */
function show_success() {
	if (!isset($_GET['ct_message'])) return;
?>
<div class="notice notice-success is-dismissible">
    <p><?php _e( $_GET['ct_message'] ); ?></p>
</div>
<?php
}

add_action( 'admin_action_removeoxsc' , 'cl_remove_shortcode_metas' );

function cl_remove_shortcode_metas() {

	$nonce = $_GET['_wpnonce'];

	wp_verify_nonce( $nonce, 'removeoxsc_'.$_GET['post'] );

	// use the global wpdb
	global $wpdb;

	$querystr = "
	    DELETE
	    FROM $wpdb->postmeta
	    WHERE (meta_key LIKE 'ct_builder_shortcodes' AND post_id = ".$_GET['post'].")
	    OR (meta_key LIKE 'ct_page_settings' AND post_id = ".$_GET['post'].")
	    LIMIT 2;
	";

	$wpdb->get_results( $querystr, OBJECT );

	wp_redirect( add_query_arg(
		array(
		    'ct_message' => urlencode('Successfully removed Oxygen shortcodes on postid: '. $_GET['post']),
		),
		$_SERVER['HTTP_REFERER'])
	);



	exit();
}

/**
 * Filter that adds an action-button to the row_actions to enable direct editing
 * @param  [type] $actions [description]
 * @param  [type] $post    [description]
 * @return [type]          [description]
 */
function show_post_row_actions ( $actions , $post ) {
	$meta = get_post_meta($post->ID);

	$color = ( array_key_exists( 'ct_builder_shortcodes', $meta) && $meta['ct_builder_shortcodes'][0]!=='' ) ?'green':'lightgrey';

	$actions['oxygen'] = '<div style="display:inline-block;border:2px solid '.$color.';border-radius:50%;width:3px;height:3px;margin:2px;margin-right:5px;background-color:'.$color.';"></div><a href="'.get_permalink($post->ID).'?ct_builder=true">'.__('Edit with Oxygen', 'oxygen-admin-plus').'</a>';
	//var_dump( $post);
	return $actions;
}
