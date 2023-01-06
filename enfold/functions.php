/* Calling Global styles */
add_action( 'wp_enqueue_scripts', 'theme_enqueue_assets', 999999999999 );

function theme_enqueue_assets() {

    $styles = array();
    $styles['theme-style'] = array(
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