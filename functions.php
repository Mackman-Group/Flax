<?php

/*
 * Flax functions file
 */


/*
 * [mg_show_con]
 * Used to switch out shortcodes in the hook manager of Canvas
 */ 
add_shortcode( 'mg_show_con', 'mackman_show_content' );
function mackman_show_content ( $atts, $content = null ) {
	
	$this_uri = strtok($_SERVER["REQUEST_URI"],'?');
	
	extract( shortcode_atts( array( 'uri' => '/', 'condition' => 'include'), $atts ) );
	
	if ( strtolower ( $uri ) == strtolower ( $this_uri ) && !is_null( $content ) && !is_feed() ){
	  if ( $condition=='include' ) {
	    return do_shortcode ( $content );
		} else {
		  return '';
		}
	} else {
    if ( $condition=='exclude' ) {
			return do_shortcode ( $content );
		} else {
			return '';
		}
	}
}

/*
 * [mg_show_featured_image]
 * Used to display a featured image from a shortcode
 */
add_shortcode( 'mg_show_featured_image', 'mackman_show_featured_image' );
function mackman_show_featured_image( $atts, $content='' ) {
  
  global $post;
  
  $post_id = $post->ID;
  
  if ( has_post_thumbnail( $post_id ) ) {
    return '<div class="post_thumbnail">' . get_the_post_thumbnail($post_id, array(1600,400) ) . '</div>';
  } else {
    return '<div class="post_thumbnail">Banner Image</div>';
  }
}

/*
 * [mg_show_title]
 * Used to display a page title from a shortcode
 */
add_shortcode( 'mg_show_title', 'mackman_show_title' );
function mackman_show_title() {
  
  global $post;
  
  $title = get_the_title($post->ID);
  
  if ( is_category() ) {
    $category = get_the_category(); 
    $title = single_cat_title($prefix = '',$display = false);
  }
  
  if ( $title ) {
    return '<h1>' . $title . '</h1>';
  } else {
    return "Something went wrong";
  }
  
}

/*
 * [mg_show_strapline]
 * Used to display a page specific strapline from a shortcode
 */
add_shortcode( 'mg_show_strapline', 'mackman_show_strapline' );
function mackman_show_strapline() {
  
  global $post;
  
  $strapline = get_post_meta( $post->ID, 'mg_strapline', true );
  
  if ( $strapline ) {
    return $strapline;
  } else {
    return "<p>Show strap line here</p>";
  }
}

/*
 * Initialise child theme actions
 */
add_action( 'init', 'childtheme_widgets_init' );
function childtheme_widgets_init() {
	register_sidebar(array('name' => 'Full Width Footer','id' => 'newwidgetarea','description' => "Full Width Footer Widget Area", 'before_widget' => '<div id="%1$s" class="widget %2$s">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
}


/*
 * Remove the dimensions from a featured image
 */
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10 );
function remove_thumbnail_dimensions( $html ) { 
  
  $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
  
  return $html;
}