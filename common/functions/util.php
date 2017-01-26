<?php

function is_staging()
{
    if ( php_uname( 'n' ) == 'helium.neadwerx.com' )
    {
        return true;
    }
    else
    {
        return false;
    }
}

function is_production()
{
    if ( php_uname( 'n' ) == 'atlas.thdises.com' )
    {
        return true;
    }
    else
    {
        return false;
    }
}

function add_to_map( &$map, $hash, $key )
{
    $map[$key] = null;

    if( array_key_exists( $key, $hash ) )
    {
        if( ( $hash[$key] != null ) and ( $hash[$key] != '' ) )
        {
            $map[$key] = $hash[$key];
            return true;
        }
    }
    return false;
}

function add_to_map_if_set( &$map, $hash, $key )
{
    if( array_key_exists( $key, $hash ) )
    {
        if( ( $hash[$key] != null ) and ( $hash[$key] != '' ) )
        {
            $map[$key] = $hash[$key];
            return true;
        }
    }
    return false;
}

function debug( $msg )
{
    if ( is_production() or is_staging() )
    {
        // Do not log on production or staging
        return;
    }
    error_log( $msg );
}

function debug_dump( $structure )
{
    ob_start();
    var_dump($structure);
    debug('The structure is: ' . ob_get_contents());
    ob_end_clean();
}

?>
