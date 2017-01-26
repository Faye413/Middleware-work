<?php

function trim_and_shorten( $value, $max_length )
{
    return substr( trim( $value ), 0, $max_length );
}

function is_missing_or_null( $array, $key )
{
    if( !isset( $array[$key] ) )
    {
        return true;
    }
    elseif( is_null( $array[$key] ) )
    {
        return true;
    }

    return false;
}

function is_missing_or_empty( $array, $key )
{
    if( !isset( $array[$key] ) )
    {
        return true;
    }
    elseif( empty( $array[$key] ) )
    {
        return true;
    }

    return false;
}

function is_number_positive( $array, $key )
{
    if( is_missing_or_null( $array, $key ) )
    {
         return false;
    }
    else if( !is_numeric( $array[ $key ] ) )
    {
        return false;
    }
    else if( $array[ $key ] <= 0 )
    {
        return false;
    }

    return true;
}

function is_number_negative( $array, $key )
{
    if( is_missing_or_null( $array, $key ) )
    {
        return false;
    }
    else if( !is_numeric( $array[ $key ] ) )
    {
        return false;
    }
    else if( $array[ $key ] >= 0 )
    {
        return false;
    }

    return true;
}

function is_number( $array, $key )
{
    if( is_missing_or_null( $array, $key ) )
    {
        return false;
    }
    else if( !is_numeric( $array[ $key ] ) )
    {
        return false;
    }

    return true;
}

function is_number_in_range( $array, $key, $min, $max )
{
    if( !is_number( $array, $key ) )
    {
        return false;
    }
    else if( $array[ $key ] < $min )
    {
        return false;
    }
    else if( $array[ $key ] > $max )
    {
        return false;
    }

    return true;
}

function is_valid_integer( $array, $key )
{
    if( is_missing_or_null( $array, $key ) )
    {
        return false;
    }

    if( gettype( $array[$key] ) === 'integer' )
    {
        return true;
    }

    $int = (int) $array[$key];
    if( (string)$int !== $array[$key] )
    {
        return false;
    }

    return true;
}

function is_integer_positive( $array, $key, $zero_allowed = false )
{
    if( !is_valid_integer( $array, $key ) )
    {
        return false;
    }

    if(
        ( $array[ $key ] < 0 ) ||
        ( !$zero_allowed && ( intVal( $array[ $key ] ) === 0 ) )
      )
    {
        return false;
    }

    return true;
}

function is_integer_negative( $array, $key )
{
    if( !is_valid_integer( $array, $key ) )
    {
        return false;
    }

    if( $array[ $key ] >= 0 )
    {
        return false;
    }

    return true;
}

function is_true( $array, $key )
{
    if( is_missing_or_null( $array, $key ) )
    {
        return false;
    }

    if(
        ( $array[$key] === 't'    ) or
        ( $array[$key] === 'true' ) or
        ( $array[$key] === true   ) or
        ( $array[$key] === 1      ) or
        ( $array[$key] === '1'    )
      )
    {
        return true;
    }

    return false;
}

function is_false( $array, $key )
{
    if( is_missing_or_null( $array, $key ) )
    {
        return false;
    }

    if(
        ( $array[$key] === 'f'     ) or
        ( $array[$key] === 'false' ) or
        ( $array[$key] === false   ) or
        ( $array[$key] === 0       ) or
        ( $array[$key] === '0'     )
      )
    {
        return true;
    }

    return false;
}

function is_boolean( $array, $key )
{
    if( is_missing_or_null( $array, $key ) )
    {
        return false;
    }

    if(
        !is_true( $array,  $key ) &&
        !is_false( $array, $key )
      )
    {
        return false;
    }

    return true;
}

# empty() can't take in a return value, only an object
# so this is a workaround
function is_empty( $value )
{
    return empty( $value );
}

?>
