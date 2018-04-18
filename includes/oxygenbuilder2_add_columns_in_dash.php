<?php

// load on init since the plugins_loaded hook has already been done!
add_action( 'plugins_loaded' , 'check_for_option_add_columns',10,1 );

// check the option if we want to add the columns
function check_for_option_add_columns() {
	if ( get_option('oadminplus_add_columns') ):
		add_filter('manage_edit-ct_template_columns', 'add_new_ct_template_columns');
		// Add to admin_init function
		add_action('manage_ct_template_posts_custom_column', 'manage_ct_template_columns', 10, 2);

	endif;
}

/**
 * Add a apply_to and in_use column to Oxygenbuilder2.0's ct_templates overview.
 * It will:
 * - tell you the rules on the templates
 * - show you if a re-usable is being used on a post/page/cpt so if not, you can safely delete it.
 */


/**
 * Add column(s) to the ct_template post-type edit-screen
 * @param [type] $columns [description]
 */
function add_new_ct_template_columns($columns) {

    $new_columns['apply_to'] = __('Apply To', 'oxygen-admin-plus');
    $new_columns['in_use'] = __('In Use', 'oxygen-admin-plus');

    return array_merge( $columns , $new_columns );
}




/**
 * Output for the extra column(s)
 * @param  [type] $column_name [description]
 * @param  [type] $post_id     [description]
 * @return [type]              [description]
 */
function manage_ct_template_columns( $column_name, $post_id ) {
    global $post;
    switch ($column_name) {
    case 'apply_to':

        $meta['ct_template_archive_post_types_all'] 	= 	array( 'text' => __('Archive Post Types All','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_archive_post_types_all', true ) );

        $meta['ct_template_categories'] 				= 	array( 'text' => __('Categories','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_categories', true ) );

        $meta['ct_template_categories_all'] 			= 	array( 'text' => __('Categories All','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_categories_all', true ) );

        $meta['ct_template_tags'] 						= 	array( 'text' => __('Tags','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_tags', true ) );

        $meta['ct_template_tags_all'] 					= 	array( 'text' => __('Tags All','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_tags_all', true ) );

        $meta['ct_template_custom_taxonomies'] 			= 	array( 'text' => __('Custom Taxonomies','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_custom_taxonomies', true ),
        														'return_data' => 'ct_get_as_taxonomies' );

        $meta['ct_template_custom_taxonomies_all'] 		= 	array( 'text' => __('Custom Taxonomies All','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_custom_taxonomies_all', true ) );

        $meta['ct_template_authors_archives_all'] 		= 	array( 'text' => __('Author Archives All','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_authors_archives_all', true ) );

        $meta['ct_template_index'] 						=	array( 'text' => __('Catch All','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_index', true ) );

        $meta['ct_template_front_page'] 				= 	array( 'text' => __('Front Page','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_front_page', true ) );

        $meta['ct_template_blog_posts']  				=	array( 'text' => __('Blog Posts','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_blog_posts', true ) );

        $meta['ct_template_date_archive']  				=	array( 'text' => __('Date Archive','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_date_archive', true ) );

        $meta['ct_template_search_page'] 				= 	array( 'text' => __('Search Page','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_search_page', true ) );

        $meta['ct_template_inner_content'] 				= 	array( 'text' => __('Inner Content','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_inner_content', true ) );

        $meta['ct_template_404_page'] 					= 	array( 'text' => __('404 Page','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_404_page', true ) );

        $meta['ct_template_all_archives'] 				= 	array( 'text' => __('All Archives','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_all_archives', true ) );

        $meta['ct_template_archive_among_taxonomies'] 	= 	array( 'text' => __('Archives Taxonomies','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_archive_among_taxonomies', true ),
        													'return_data' => 'ct_get_terms' );


        $meta['ct_template_archive_post_types'] 		= 	array( 'text' => __('Archive Post Types','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_archive_post_types', true ) );

        $meta['ct_template_authors_archives'] 			= 	array( 'text' => __('Authors Archives','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_authors_archives', true ),
        													'return_data' => 'ct_get_users' );

        $meta['ct_template_single_all'] 				= 	array( 'text' => __('All Single','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_single_all', true ) );

        $meta['ct_template_post_types'] 				= 	array( 'text' => __('Post Types','oxygen-admin-plus'),
        													'value' => get_post_meta( $post_id, 'ct_template_post_types', true ) );

        $meta['ct_template_exclude_ids'] 				= 	array( 	'text' => __('Exclude','oxygen-admin-plus'),
        															'value' => get_post_meta( $post_id, 'ct_template_exclude_ids', true ) );

        $meta['ct_template_include_ids'] 				= 	array( 	'text' => __('Include','oxygen-admin-plus'),
        															'value' => get_post_meta( $post_id, 'ct_template_include_ids', true ) );

        $meta['ct_template_post_of_parents'] 				= 	array( 	'text' => __('If Post of Parents','oxygen-admin-plus'),
        															'value' => get_post_meta( $post_id, 'ct_template_post_of_parents', true ) );

        $meta['ct_template_taxonomies'] 			= 	array( 	'text' => __('Taxonomies','oxygen-admin-plus'),
        														'value' => get_post_meta( $post_id, 'ct_template_taxonomies', true ),
        														'return_data' => 'ct_get_as_taxonomies' );


        foreach ($meta as $key=>$metakey) {
        	// skip if key returns empty value
        	if ((is_array($metakey['value']) && sizeof($metakey['value'])==0) || $metakey['value'] == '') continue;

        	// explode if key is array
        	if (is_array($metakey['value'])) {

        		// break if first and only item holds no value
        		if (sizeof($metakey['value'])==1 && $metakey['value'][0] == '') break;

        		// do we need to do some extra magic?
        		if (isset($metakey['return_data'])) {
        			$return = call_user_func_array( $metakey['return_data'] , array($metakey['value']));
        		} else {
        			$return = implode( ',', $metakey['value'] );
        		}

        		// only echo something if return value isn't empty
    			if ($return) echo $metakey['text'] . ': ' . $return . '<br>';
        	// echo text if value is true
        	} else if ( $metakey['value'] == true){
        		echo $metakey['text'] . '<br>';
        	}
        }

        break;
    case "in_use":

    	// only do this on ct_template_type == reusable_part
    	$meta = get_post_meta($post_id);
    	//var_dump($meta);
    	if ( isset($meta['ct_template_type'])){
 			if (!in_array( 'reusable_part',$meta['ct_template_type'] )) return;
    	} else {
    		return;
    	}

    	// regex expression that we will try to find in the postmeta ct_builder_shortcodes
    	$regex = '/(ct_options=\'{(.*"view_id":))(##)/';

		// use the global wpdb
		global $wpdb;

		$querystr = "
		    SELECT DISTINCT post_id, meta_value
		    FROM $wpdb->postmeta
		    WHERE meta_key LIKE 'ct_builder_shortcodes'
		    ORDER BY post_id ASC
		";

		// set of builder shortcodes
    	$builder_shortcodes = $wpdb->get_results( $querystr, OBJECT );

    	if ( $builder_shortcodes ):

    		foreach ($builder_shortcodes as $post):

    			// match the regex with the post_id for this row
		    	preg_match( str_replace( '##', $post_id, $regex ) , $post->meta_value , $matches );

		    	if ( $matches ) {
		    		echo '<div style="border:2px solid green;border-radius:50%;width:6px;height:6px;margin:5px;background-color:green;"></div>';
		    		return;
		    	}

    		endforeach;
    	endif;
		echo '<div style="border:2px solid red;border-radius:50%;width:6px;height:6px;margin:5px;background-color:red;"></div>';

    	break;
    default:
        break;
    } // end switch
}

function ct_get_as_taxonomies( $value = array() ) {
	if (!is_array($value)) return false;

	$out_array = array();

	if (isset($value['names'])) {
		if ( count($value['names'])== 0 ) return false;
		foreach ($value['names'] as $name) {
			if ($name == 'all_taxonomies'):
				$out_array[] = __('All Taxonomies', 'oxygen-admin-plus');
				break;
			endif;

			$terms = get_terms( array('taxonomy' => $name ) );
			foreach ($terms as $term ){
				// match the array to a value in the settings
				if ( in_array( $term->term_id, $value['values'] )) $out_array[] = $name . '.' .$term->name;
			}
		}
	}

	return implode( ', ', $out_array );
}

function ct_get_terms( $value = array() ) {
	if (!is_array($value)) return false;

	$out_array = array();

	foreach ($value as $termid) {
		if ($termid == 'all_taxonomies'):
			$out_array[] = __('All Taxonomies', 'oxygen-admin-plus');
		elseif ( $termid == 'all_tags'):
			$out_array[] = __('All Tags', 'oxygen-admin-plus');
		elseif ( $termid == 'all_categories'):
			$out_array[] = __('All Categories', 'oxygen-admin-plus');
		endif;
	}

	foreach ($value as $termid ){
		$term = get_term_by( 'term_taxonomy_id', $termid );
		// match the array to a value in the settings
		if ( is_object($term) && !in_array( $term->name , $out_array ) ) $out_array[] = $term->name;
	}

	return implode( ', ', $out_array );
}

function ct_get_users( $value = array() ) {
	if (!is_array($value)) return false;

	$out_array = array();

	foreach ($value as $authorid) {
		if ($authorid == 'all_authors'):
			$out_array[] = __('All Users', 'oxygen-admin-plus');
		endif;
	}

	foreach ($value as $authorid ){

		$user = get_user_by( 'id', $authorid );
		// match the array to a value in the settings
		if ( is_object($user) && !in_array( $user->data->user_login , $out_array ) ) $out_array[] = $user->data->user_login;
	}

	return implode( ', ', $out_array );
}
