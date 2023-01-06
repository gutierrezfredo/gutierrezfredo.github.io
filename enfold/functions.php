// Calling Global styles
//----------------------------------------------------------------------------------------
add_action( 'wp_enqueue_scripts', 'theme_enqueue_assets', 999999999999 );

function theme_enqueue_assets() {

    $styles = array();
    $styles['global-css'] = array(
        'src' => '//gutierrezfredo.github.io/enfold/global.css',
        'type' => 'all'
    );

    foreach ( $styles as $style => $val ) {
        wp_deregister_style( $style );
        if ( isset( $val['src'] ) && isset( $val['type'] ) )
            wp_enqueue_style( $style, $val['src'], false, false, $val['type'] );
        else wp_enqueue_style( $style );
    }

    $scripts = array();
    $scripts['jquery'] = array(
        'src' => '//cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js',
        'dep' => false
    );
    $scripts['jquery-migrate'] = array(
        'src' => '//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js',
        'dep' => array( 'jquery' )
    );

    foreach ( $scripts as $script => $val ) {
        wp_deregister_script( $script );
        if ( isset( $val['src'] ) && isset( $val['dep'] ) )
            wp_enqueue_script( $script, $val['src'], $val['dep'], false, true );
        else wp_enqueue_script( $script );
    }
}


// Add not-home to body class
//----------------------------------------------------------------------------------------
function add_not_home_body_class($classes) {
    if( !is_front_page() ) $classes[] = 'not-home';
    return $classes;
}
add_filter('body_class','add_not_home_body_class');



// Use Relevanssi in search instead of the default search
//----------------------------------------------------------------------------------------
add_filter('avf_ajax_search_function', 'avia_init_relevanssi', 10, 4);
function avia_init_relevanssi($function_name, $search_query, $search_parameters, $defaults) {
    $function_name = 'avia_relevanssi_search';
    return $function_name;
}
function avia_relevanssi_search($search_query, $search_parameters, $defaults) {
    global $query;
    $tempquery = $query;
    if(empty($tempquery)) $tempquery = new WP_Query();

    $tempquery->query_vars = $search_parameters;
    relevanssi_do_query($tempquery);
    $posts = $tempquery->posts;

    return $posts;
}


/* Solves HTTP ERROR when uploading an image
WordPress runs on PHP which uses two modules to handle images.
These modules are called GD Library and Imagick. WordPress may
use either one of them depending on which one is available.

However, Imagick is known to often run into memory issues causing
the http error during image uploads. To fix this, you can make
the GD Library your default image editor.*/
//----------------------------------------------------------------------------------------
function wpb_image_editor_default_to_gd( $editors ) {
    $gd_editor = 'WP_Image_Editor_GD';
    $editors = array_diff( $editors, array( $gd_editor ) );
    array_unshift( $editors, $gd_editor );
    return $editors;
}
add_filter( 'wp_image_editors', 'wpb_image_editor_default_to_gd' );



// Hides empty categories in products category widget
//----------------------------------------------------------------------------------------
function woo_hide_product_categories_widget( $list_args )
{
    $list_args[ 'hide_empty' ] = 1;
    return $list_args;
}
add_filter( 'woocommerce_product_categories_widget_args', 'woo_hide_product_categories_widget' );



// The body_class function is nice for adding a bunch of classes to the body tag
//----------------------------------------------------------------------------------------
function category_id_class($classes) {
    global $post;
    foreach((get_the_category($post->ID)) as $category)
        $classes[] = $category->category_nicename;
    return $classes;
}
add_filter('body_class', 'category_id_class');



// Changes the text in Thank you page after checkout
//----------------------------------------------------------------------------------------
add_filter( 'woocommerce_thankyou_order_received_text', 'avia_thank_you' );
function avia_thank_you() {
 $added_text = '<p>Thank you for your order. You will receive an email shortly with more information.</p>';
 return $added_text ;
}



/* This changes the event link to the event website URL if that is set.
 * NOTE: Comment out the add_filter() line to disable this function. */
//----------------------------------------------------------------------------------------
function tribe_set_link_website ( $link, $postId ) {
  $website_url = tribe_get_event_website_url( $postId );
  // Only swaps link if set
  if ( !empty( $website_url ) ) {
    $link = $website_url;
  }
  return $link;
}
add_filter( 'tribe_get_event_link', 'tribe_set_link_website', 100, 2 );




// Activates Header widget area
//----------------------------------------------------------------------------------------
add_action( 'ava_after_main_menu', 'enfold_customization_header_widget_area' );
function enfold_customization_header_widget_area() {
dynamic_sidebar( 'header' );
}



// Disables portfolio items
//----------------------------------------------------------------------------------------
add_action( 'after_setup_theme', 'remove_portfolio' );
function remove_portfolio() {
    remove_action( 'init', 'portfolio_register' );
}



// Hides web field from comment form
//----------------------------------------------------------------------------------------
add_filter('comment_form_default_fields', 'website_remove');
function website_remove($fields) {
    if(isset($fields['url']))
    unset($fields['url']);
    return $fields;
}



/* Fixes buggy view in events tribe plugin list admin table */
//----------------------------------------------------------------------------------------
add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
	table.fixed {
	  table-layout: auto !important;
	}
	.avia-template-save-button-container {
	  position: relative !important;
	  float: right !important;
	  top: 40px !important;
	}
  </style>';
}



// Adds custom post type to avia builder
//----------------------------------------------------------------------------------------
add_theme_support('add_avia_builder_post_type_option');
add_theme_support('avia_template_builder_custom_post_type_grid');




// Activates Advanced Layout Builder on Custom Post types
//----------------------------------------------------------------------------------------
function avf_alb_supported_post_types_mod( array $supported_post_types )
{
  $supported_post_types[] = 'template';
  return $supported_post_types;
}
add_filter('avf_alb_supported_post_types', 'avf_alb_supported_post_types_mod', 10, 1);

function avf_metabox_layout_post_types_mod( array $supported_post_types )
{
 $supported_post_types[] = 'template';
 return $supported_post_types;
}
add_filter('avf_metabox_layout_post_types', 'avf_metabox_layout_post_types_mod', 10, 1);

add_theme_support('avia_template_builder_custom_post_type_grid');
add_theme_support('add_avia_builder_post_type_option');