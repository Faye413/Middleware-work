<?php

function format_ui_date( $date )
{
    if( $date != "" )
    {
        $new_timestamp      = strtotime( $date );
        $new_date_formatted = date( "m/d/Y", $new_timestamp );

        return $new_date_formatted;
    }
    else
    {
        return $date;
    }
}

function format_ui_date_and_time( $date )
{
    # this is a comment
    if( $date != "" )
    {
        $new_timestamp      = strtotime( $date );
        $new_date_formatted = date( "m/d/Y g:i a", $new_timestamp );

        return $new_date_formatted;
    }
    else
    {
        return $date;
    }
}

function format_phone_number( $number )
{
    $number = preg_replace( '/[^0-9]/', '', $number );

    $len = strlen( $number );

    if( $len == 7 )
    {
        $number = preg_replace( '/([0-9]{3})([0-9]{4})/', '$1-$2', $number );
    }
    elseif( $len == 10 )
    {
        $number = preg_replace( '/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $number );
    }

    return $number;
}

function sanitize( $string )
{
    echo( htmlentities( $string , ENT_QUOTES, "UTF-8" ) );
}

function translate( $string )
{
    return $string;
}

?>
