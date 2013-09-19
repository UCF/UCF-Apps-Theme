<?php
require_once('functions/base.php');   			# Base theme functions
require_once('functions/feeds.php');			# Where functions related to feed data live
require_once('custom-taxonomies.php');  		# Where per theme taxonomies are defined
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes

//Add theme-specific functions here.

/**
 * Generate page/post breadcrumbs based on a passed post id.
 * Outputs bootstrap-ready HTML.
 * @return string
 * @author Jo Greybill
 **/
function get_breadcrumbs($post_id) {
	// If this is the home page, don't return anything
	if (is_home() || is_front_page()) {
		return '';
	}

	$ancestors = get_post_ancestors($post_id);

	$output = '<ul class="breadcrumb">';
	$output .= '<li><a href="'.get_site_url().'">Home</a> <span class="divider">/</span></li>';
	if ($ancestors) {
		// Ancestor IDs return from being the most direct parent first,
		// to the most distant last.  krsort returns the IDs in the order
		// we need:
		krsort($ancestors);
		foreach ($ancestors as $id) {
			$output .= '<li><a href="'.get_permalink($id).'">'.get_the_title($id).'</a> <span class="divider">/</span></li>';
		}
	}
	$output .= '<li class="active"><a href="'.get_permalink($post_id).'">'.get_the_title($post_id).'</a></li>';
	$output .= '</ul>';

	return $output;
}

?>

<?php
/*
Plugin Name: Category Checklist Tree
Version: 1.3.1
Description: Preserves the category hierarchy on the post editing screen
Author: scribu
Author URI: http://scribu.net
Plugin URI: http://scribu.net/wordpress/category-checklist-tree
*/

class Category_Checklist {

	function init() {
		add_filter( 'wp_terms_checklist_args', array( __CLASS__, 'checklist_args' ) );
	}

	function checklist_args( $args ) {
		add_action( 'admin_footer', array( __CLASS__, 'script' ) );

		$args['checked_ontop'] = false;

		return $args;
	}

	// Scrolls to first checked category
	function script() {
?>

<script type="text/javascript">
	jQuery(function(){
		jQuery('[id$="-all"] > ul.categorychecklist').each(function() {
			var $list = jQuery(this);
			var $firstChecked = $list.find(':checkbox:checked').first();

			if ( !$firstChecked.length )
				return;

			var pos_first = $list.find(':checkbox').position().top;
			var pos_checked = $firstChecked.position().top;

			$list.closest('.tabs-panel').scrollTop(pos_checked - pos_first + 5);
		});
	});
</script>

<?php
	}
}

Category_Checklist::init();

