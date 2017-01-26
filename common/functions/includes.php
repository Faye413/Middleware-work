<?

# This is also a comment
function includes()
{
    $params = func_get_args();

    $default_params = array(
                                'main_css',
                                'jquery_js',
                                'common_js',
                                'validate_js',
                           );

    $total_params = array_merge( $default_params, $params );

    print_includes( $total_params );
}

function modal_includes()
{
    $params = func_get_args();

    $default_params = array(
                                'main_css',
                                'jquery_js',
                                'common_js',
                                'validate_js',
                           );

    $total_params = array_merge( $default_params, $params );

    print_includes( $total_params );
}

function print_includes( $params )
{
    $css_includes = array( 'main_css' => 'common/css/main.css' );
    
    $js_includes = array(
                            'validate_js' => 'common/js/validate.js',
                            'common_js'   => 'common/js/common.js',
                            'jquery_js'   => 'common/js/jquery/jquery-1.10.2.min.js'
                        );

    $css_for_use = array();
    $js_for_use  = array();

    foreach( $params as $param )
    {
        $found = 0;

        if( array_key_exists( $param, $js_includes ) )
        {
            array_push( $js_for_use, $js_includes[ $param ] );
            $found = 1;
        }

        if( array_key_exists( $param, $css_includes ) )
        {
            array_push( $css_for_use, $css_includes[ $param ] );
            $found = 1;
        }

        if( !$found )
        {
            # If not found in array, assume it is a full path
            if( strstr( $param, '.js' ) )
            {
                array_push( $js_for_use, $param );
            }

            if( strstr( $param, '.css' ) )
            {
                array_push( $css_for_use, $param );
            }

           }
    }

    $includes = '';

    foreach( $js_for_use as $js_file )
    {
        $includes .= '<script type="text/javascript" src="/.__INCLUDE_VERSION__/' . $js_file . '"></script>' . "\n";
    }

    foreach( $css_for_use as $css_file )
    {
        $includes .= '<link href="/.__INCLUDE_VERSION__/' . $css_file . '" rel="stylesheet" type="text/css">' . "\n";
    }

    $meta_data = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'."\n";
    $title     = '<title>NeadWerx Training</title>'."\n\n";

    echo( $meta_data );
    echo( $title );
    echo( $includes );
}

?>
