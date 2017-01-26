<?php

require_once( "constants.php" );
require_once( "util.php" );

global $con;
$con = pg_connect( DBASE_CONNECT_PHP );

global $last_database_result;
$last_database_result = null;

function last_transaction_row_count()
{
    global $last_database_result;
    if( $last_database_result == null )
    {
        return 0;
    }

    if( !is_resource( $last_database_result ) )
    {
        return 0;
    }

    return pg_affected_rows( $last_database_result );
}

function error_log_backtrace( $msg, $ignore_recent_count )
{
    $backtrace_array = debug_backtrace();

    for( $i = 0; $i < $ignore_recent_count; $i++ )
    {
        array_shift( $backtrace_array );
    }

    $out = '';

      if( isset( $_SERVER['HTTP_REFERER'] ) )
    {
        $referer = $_SERVER['HTTP_REFERER'];
        $referer = preg_replace( '/^https?:\/\/[^\/]+/', '', $referer );
        $referer = preg_replace( '/\?.*$/', '', $referer );

        $out = "(referred from $referer) ";
    }

    foreach( array_reverse( $backtrace_array ) as $bt )
    {
        $file = $bt['file'];

        $file = str_replace( WEBROOT_DIRECTORY, '', $file );

        $line = $bt['line'];
        $function = $bt['function'];

        $out .= "$function($file:$line)->";
    }

    preg_replace( '/->$/', '', $out );

    if( strlen( $msg ) > 2000 )
    {
        $out .= ': ' . substr( $msg, 0, 950 );
        $out .= '  [.....]  ';
        $out .= substr( $msg, -950 );
        $out .= ' (truncated, total length was ' . strlen( $msg ) . ')';
    }
    else
    {
        $out .= ': ' . $msg;
    }

    if( isset( $_SERVER['REQUEST_URI'] ) )
    {
        $args = $_SERVER['REQUEST_URI'];
         $args = preg_replace( '/^.+\?/', '', $args );

        $out .= "(request args $args) ";
    }

    error_log( $out );
}

function query_log( $msg )
{
    $backtrace_array = debug_backtrace();

    if( array_key_exists( 3, $backtrace_array ) )
    {
        $function = $backtrace_array[3]['function'];
    }
    else
    {
        $function = $backtrace_array[2]['file'];
        $function = preg_replace( '/^.*\//', '', $function );
    }

    $line = $backtrace_array[2]['line'];

    if( isset( $_SERVER['REQUEST_URI'] ) )
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = preg_replace( '/\?.*$/', '', $uri );
    }
    else
    {
        $uri = "(undefined)";
    }

    syslog( LOG_DEBUG | LOG_USER, "$function($line): $msg ($uri:" . session_id() . ')' );
}

function my_pg_query( $con, $query )
{
    global $last_database_result;

    $time_begin     = microtime( true );
    $retry_counter  = 3;
    $try_count      = 0;
    $query_complete = false;

    while( $retry_counter > 0 )
    {
        $try_count++;

        if( $try_count > 1 )
        {
            sleep( rand( 0, 3 ) );
        }

        if( pg_send_query( $con, $query ) === false )
        {
            error_log_backtrace( $query . ': ' . get_last_db_error(), 2 );
            return false;
        }

        $result = pg_get_result( $con );
        $last_database_result = $result;

        if( pg_result_error( $result ) )
        {
            if( pg_result_error_field( $result, PGSQL_DIAG_SQLSTATE ) == '40P01' ) // Deadlock
            {
                $retry_counter--;
            }
            else
            {
                error_log_backtrace( $query . ': ' . get_last_db_error(), 2 );
                return false;
            }
        }
        else
          {
            $query_complete = true;
            $retry_counter  = 0;
        }
    }

    if( $try_count > 1 )
    {
        if( $query_complete )
        {
            error_log( "DEADLOCK DETECTED: QUERY SUCCESS $query TRY COUNT: $try_count" );
        }
        else
        {
            error_log( "DEADLOCK DETECTED, QUERY FAILED $query" );
        }

    }
    if(
        !is_production() or is_staging() or
        ( ( rand( 1, 1000 ) % 50 ) == 0 )
      )
    {
        # Only a 5% chance of logging

        $time_end = microtime( true );

        $execution_time = round( ( $time_end - $time_begin ) * 1000, 2 );

        query_log( $execution_time . ' ms' );
    }

    return $result;
}

function query_return_rows( $query )
{
    global $con;

     if( !verify_connection() )
    {
        return 'Invalid $con object.';
    }

    $result = my_pg_query( $con, $query );

    if( $result === false )
    {
        return "$result : error";
    }
    else
    {
        return pg_affected_rows( $result );
    }
}

function exists_and_not_null( $map, $key )
{
    if( !isset( $map[$key] ) )
    {
        return false;
    }
    if( is_null( $map[$key] ) )
    {
        return false;
    }
    return true;
}

function db_prep_positive_int_from_map( $map, $key, $zero_ok )
{
    if( !isset( $map[$key] ) )
    {
        return 'null';
    }

     return db_prep_positive_int( $map[$key], $zero_ok );
}

function db_prep_timestamp( $time )
{
    if(
        !is_null( $time ) &&
        ( $time != '' ) &&
        !is_numeric( $time )
      )
    {
        $result = db_prep_string( db_escape( $time ) );
        $result = strtotime( str_replace( "'", '', $result ) );
        $result = " to_timestamp( $result ) ";
    }
    else if( is_numeric( $time ) )
    {
        $result = db_prep_int( $time, true );
        $result = " to_timestamp( $result ) ";
    }
    else
    {
        $result = 'null ';
    }

    return $result;
}

function db_prep_timestamp_from_map( $map, $key )
{
    if( !isset( $map[$key] ) )
    {
        return 'null';
    }

    return db_prep_timestamp( $map[$key] );
}

function query_blind_return_null_or_error( $query, $itemtext = '' )
{
    $result = query_blind( $query );

    if( $result === false )
    {
        if( $itemtext )
        {
            return "failed to $itemtext: $result : " . get_last_db_error();
        }
        else
        {
            return "$result : " . get_last_db_error();
        }
    }

    return null;

}

function exec_creation_stored_procedure( $query, $itemtext, &$pk_out )
{
    $return_val = null;

    $result = exec_stored_procedure( $query, $return_val );

    if( $result === -1 )
    {
        return "failed to create $itemtext: '$result' - " . get_last_db_error();
    }

    $pk_out = $return_val[0];

    return null;

}

// Takes in UNIX time, outputs for a date column
function db_prep_date_from_map( $map, $key )
{
    if( !isset( $map[$key] ) )
    {
        return 'null';
    }

    return db_prep_date( $map[$key] );
}

// Takes in UNIX time, outputs for a date column
function db_prep_date( $value )
{
    $value = db_prep_positive_int( $value, true );
    if( $value == 'null' )
    {
        return 'null';
    }

    return "to_timestamp( $value )::date";
}

function db_prep_string_from_map( $map, $key, $maxlen = -1, $allow_html = false )
{
    if( !isset( $map[$key] ) )
    {
        return 'null';
    }

    return db_prep_string( $map[$key], $maxlen, $allow_html );
}

function verify_connection()
{
    global $con;

    if( !is_resource( $con ) )
    {
          error_log( 'Database $con is not valid!' );
        return false;
    }

    return true;
}

function my_pg_query_params( $con, $query, $params )
{
    global $last_database_result;

    $time_begin     = microtime( true );
    $retry_counter  = 3;
    $try_count      = 0;
    $query_complete = false;

    while( $retry_counter > 0 )
    {
        $try_count++;

        if( $try_count > 1 )
        {
            sleep( rand( 0, 3 ) );
        }

        if( pg_send_query_params( $con, $query, $params ) === false )
        {
            error_log_backtrace( $query . ': ' . get_last_db_error(), 2 );
            return false;
        }

        $result = pg_get_result( $con );
        $last_database_result = $result;

        if( pg_result_error( $result ) )
        {
            if( pg_result_error_field( $result, PGSQL_DIAG_SQLSTATE ) == '40P01' ) // Deadlock
            {
                 $retry_counter--;
            }
            else
            {
                error_log_backtrace( $query . ': ' . get_last_db_error(), 2 );
                return false;
            }
        }
        else
        {
            $retry_counter  = 0;
            $query_complete = true;
        }
    }

    //Deadlock debug code 
    if( $try_count > 1 )
    {
        if( $query_complete )
        {
            error_log( "DEADLOCK DETECTED: QUERY SUCCESS " . debug_dump_query( $query, $params ) . " TRY COUNT: $try_count" );
        }
        else
        {
            error_log( "DEADLOCK DETECTED, QUERY FAILED " . debug_dump_query( $query, $params ) );
        }
    }

    if(
        !is_production() or
        ( ( rand( 1, 1000 ) % 50 ) == 0 )
      )
    {
        # Only a 5% chance of logging

        $time_end = microtime( true );
        
        $execution_time = round( ( $time_end - $time_begin ) * 1000, 2 );

        query_log( $execution_time . ' ms' );
    }

    return $result;
}

function query_associative_all( $query )
{
    global $con;

    if( !verify_connection() )
    {
        return null;
    }

    if( !($result = my_pg_query( $con, $query ) ) )
    {
        return null;
    }

    if( pg_num_rows( $result ) === 0 )
    {
        return array();
    }

    return pg_fetch_all( $result );
}

function param_query_associative_one( $query, $params = array() )
{
    global $con;

    if( !verify_connection() )
    {
        return null;
     }

    prepare_param_query( $query, $params );

    if( !( $result = my_pg_query_params( $con, $query, $params ) ) )
    {
        return null;
    }

    $retval = pg_fetch_assoc( $result );

    return $retval;
}

function query_associative_one( $query )
{
    global $con;

    if( !verify_connection() )
    {
        return null;
    }

    if( !($result = my_pg_query($con, $query ) ) ) {
        return null;
    }

    $retval = pg_fetch_assoc( $result );

    return $retval;
}

function query_remote_associative_all( $con_string, $query, &$row_count = -1 )
{
    $con = pg_connect( $con_string );
    
    if( !verify_connection() )
    {
        $row_count = -1;
        return null;
    }

    if( !($result = my_pg_query( $con, $query ) ) )
    {
        $row_count = -1;
        return null;
    }

    $row_count = pg_num_rows( $result );
    $retval = pg_fetch_all( $result );

    return $retval;
}

function db_escape( $value )
{
    $retval = pg_escape_string( $value );

    return $retval;
}

function db_prep_positive_real_from_map( $map, $key, $zero_ok )
{
    if( !isset( $map[$key] ) )
    {
        return 'null';
    }

    return db_prep_positive_real( $map[$key], $zero_ok );
}

function db_prep_positive_real( $value, $zero_ok )
{
    if(
            !isset( $value ) ||
            (is_null($value)) ||
            !is_numeric($value) ||
            ($value == "" )
      )
    {
        $value = 'null';
    }
    else {
        if( $value < 0 ) {
            $value = 'null';
        }
        else if( $value == 0 ) {
            if( !$zero_ok ) {
                $value = 'null';
            }
        }
        else
        {
            $value = (real)$value;
        }
    }

    return $value;
}

function db_prep_positive_int( $to_prep, $zero_ok )
{
    $value = (int) $to_prep;

    if( !isset( $value ) )
    {
        return 'null';
    }

     if( is_null( $value ) )
    {
        return 'null';
    }

    if( $value === '' )
    {
        return 'null';
    }

    if( !is_numeric($value) )
    {
        return 'null';
    }

    if( $value < 0 )
    {
        return 'null';
    }

    if( ( $value == 0 ) && (!$zero_ok) )
    {
        return 'null';
    }

    return $value;
}

function db_prep_int_from_map( $map, $key, $zero_ok )
{
    if( !isset( $map[$key] ) )
    {
        return 'null';
     }

    return db_prep_int( $map[$key], $zero_ok );
}

function db_prep_int( $value, $zero_ok )
{
    if(
            !isset( $value ) ||
            (is_null($value)) ||
            !is_numeric($value)
      )
    {
        $value = 'null';
    }
    else {
        if(
                ($value == 0 ) &&
                ( !$zero_ok )
          )
        {
            $value = 'null';
        }
    }

    return $value;
}

function db_prep_real( $value, $zero_ok )
{
    if(
            !isset( $value ) ||
            (is_null($value)) ||
            !is_numeric($value) ||
            ($value == "" )
      )
    {
        return 'null';
     }
    else {
        if( $value == 0 )
        {
            if( !$zero_ok )
            {
                return 'null';
            }
        }
    }

    return (real) $value;
}

function db_prep_real_from_map( $map, $key, $zero_ok = true )
{
    if( !isset( $map[$key] ) )
    {
        return 'null';
    }

    return db_prep_real( $map[$key], $zero_ok );
}

function db_prep_string( $value, $maxlen = -1, $allow_html = false )
{
    if(
            !isset( $value ) ||
            (is_null($value)) ||
            preg_match( '/^\s+$/', $value ) ||
            ( $value == '' )
      )
    {
        $value = 'null';
    }
    else
    {
        if( $allow_html === false )
         {
            $filter_retval = filter_var( $value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );

            if( $filter_retval === false )
            {
                error_log( "Failed to filter $value in db_prep_string" );
            }
            else
            {
                $value = $filter_retval;
            }
        }

        if( $maxlen > -1 )
        {
            $value = substr( $value, 0, $maxlen );
        }

        $value = db_escape($value);
        $value = "'$value'";
    }

    return $value;
}

function db_prep_boolean_from_map( $map, $key )
{
    if( !isset( $map[$key] ) )
    {
        return 'null';
    }

    return db_prep_boolean( $map[$key] );
}

function db_prep_boolean( $value )
{
    if( !isset( $value ) )
    {
        return 'null';
    }

    if( ( $value === true ) or ( $value === 1 ) or ( $value === 't' ) or ( $value === 'true' ) or ( $value === '1' ) )
    {
        return 'true';
    }

    if( ( $value === false ) or ( $value === 0 ) or ( $value === 'f' ) or ( $value === 'false' ) or ( $value === '0' ) )
    {
        return 'false';
    }

    return 'null';
}

function get_last_db_error()
{
    global $con;

    if( !verify_connection() )
    {
        return '$con is not valid!';
    }

    $retval = pg_last_error( $con );

    return $retval;
}

function query_blind( $query )
{
    global $con;

    if( !verify_connection() )
    {
         return false;
    }

    return my_pg_query( $con, $query );
}

function exec_stored_procedure( $query, &$retval )
{
    global $con;

    if( !verify_connection() )
    {
        return false;
    }

    $result = my_pg_query( $con, $query );

    if( $result )
    {
        $retval = pg_fetch_array( $result );
    }
    else
    {
        $retval = -1;
    }

    return $result;
}

function db_prep_search_string( $search_string, $for_param_query = false )
{
    $result = null;

    if( !preg_match( '/[\w]+/', $search_string ) )
    {
        return  'null';
    }

    $search_string = trim( $search_string );
     $search_string = preg_replace( '/[^\w]+/', ' ', $search_string );
    $search_string = trim( $search_string );

    $search_string = preg_replace( '/([\w]+)/', '$1:*', $search_string );
    $search_string = preg_replace( '/ +/', ' & ', $search_string );

    if( !$for_param_query )
    {
        $search_string = db_prep_string( $search_string );
    }

    return $search_string;
}

function db_prep_search_string_from_map( $map, $key )
{
    if( !isset( $map[$key] ) )
    {
        return 'null';
    }

    return db_prep_search_string( $map[$key] );
}

function db_prep_identifier( $value )
{
    if( !isset( $value )
        || (is_null($value))
        || preg_match( '/^\s+$/', $value )
        || ( $value == '' ) )
    {
        return null;
    }

    return db_escape($value);
}

function db_prep_identifier_from_map( $map, $key )
 if( !isset( $map[$key] ) )
    {
        return 'null';
    }

    return db_prep_identifier( $map[$key] );
}

function db_prep_set_password( $password )
{
    $password = db_prep_string( $password );

    if( $password == 'null' )
    {
        return 'null';
    }

    return "crypt( $password, gen_salt( 'bf' ) )";
}

function db_prep_set_password_from_map( $map, $key )
{
    if( !isset( $map[$key] ) )
    {
        return 'null';
    }

    return db_prep_set_password( $map[$key] );
}

function db_explode_array_agg( $array_agg_string )
{
    # Takes an array agg from db call in the form {val1,val2,val3} and converts it into a PHP array

    if( preg_match( '/^{.*}$/', $array_agg_string ) )
    {
        $array_agg_list = preg_replace( '/{|}/', '', $array_agg_string );
         $array  = explode( ',', $array_agg_list );
        return $array;
    }

    return array();
}

function prepare_param_query( &$query, &$params )
{
    $param_index = 1;
    $inserted_params = array();

    foreach( $params as $key => $param )
    {
        $split_query = explode( '?' . $key . '?', $query );
        $num = count( $split_query );

        if( $num === 1 )
        {
            continue;
        }

        $param_str = '';

        if( is_array( $param ) )
        {
            foreach( $param as $value )
            {
                if( $value === true )
                {
                    $value = 't';
                }
                elseif( $value === false )
                {
                    $value = 'f';
                }
                 
                $param_str .= '$' . $param_index . ',';
                array_push( $inserted_params, $value );
                $param_index++;
            }

            # Strip off final comma
            $param_str = substr( $param_str, 0, -1 );
        }
        else
        {
            if( $param === true )
            {
                $param = 't';
            }
            elseif( $param === false )
            {
                $param = 'f';
            }

            $param_str = '$' . $param_index;
            array_push( $inserted_params, $param );
            $param_index++;
        }

        $query = $split_query[0];

        for( $i = 1; $i < $num; $i++ )
        {
            $query .= $param_str . $split_query[$i];
        }
    }

    $param_index--;

    $params = $inserted_params;

    # Find any unsubstituted parameters in query, use last $param_index for each
    # which will reference the null at the end of the params

    $query = preg_replace_callback( '/\?[0-9a-zA-Z_]+\?/',
        function( $matches ) use ( &$params, &$param_index )
        {
            array_push( $params, null );
            $param_index++;
            return '$' . $param_index;
        }, $query );

    return null;
}

function param_query_one( $query, $params = array() )
{
    global $con;

    if( !verify_connection() )
    {
        return null;
    }

    prepare_param_query( $query, $params );

    if( !($result = my_pg_query_params( $con, $query, $params ) ) )
    {
        return null;
    }

    return pg_fetch_assoc( $result );
}

function param_query_all( $query, $params = array(), &$row_count = -1 )
{
    global $con;

    if( !verify_connection() )
    {
         $row_count = -1;
        return null;
    }

    prepare_param_query( $query, $params );

    if( !($result = my_pg_query_params( $con, $query, $params ) ) )
    {
        $row_count = -1;
        return null;
    }

    $row_count = pg_num_rows( $result );

    return pg_fetch_all( $result );
}

function param_query_return_rows( $query, $params = array() )
{
    global $con;

    if( !verify_connection() )
    {
        return 'Invalid $con object.';
    }

    prepare_param_query( $query, $params );

    $result = my_pg_query_params( $con, $query, $params );

    if( $result === false )
    {
        return "$result : error";
    }
    else
    {
        return pg_affected_rows( $result );
     }
}

function param_query_blind_return_null_or_error( $query, $params = array(), $itemtext = '' )
{
    $result = param_query_blind( $query, $params );

    if( $result === false )
    {
        if( $itemtext )
        {
            return "failed to $itemtext: $result : " . get_last_db_error();
        }
        else
        {
            return "$result : " . get_last_db_error();
        }
    }

    return null;
}

function param_exec_creation_stored_procedure( $query, $params, $itemtext, &$pk_out )
{
    $return_val = null;

    $result = param_exec_stored_procedure( $query, $params, $return_val );

    if( $result === -1 )
    {
        return "failed to create $itemtext: '$result' - " . get_last_db_error();
    }

    $pk_out = $return_val[0];

    return null;

}

function param_exec_stored_procedure( $query, $params, &$retval )
{
    global $con;

    if( !verify_connection() )
    {
        return false;
    }

    prepare_param_query( $query, $params );

    if( $result = my_pg_query_params( $con, $query, $params ) )
    {
        $retval = pg_fetch_array( $result );
    }
    else
    {
        $retval = -1;
    }

    return $retval;
}

function param_query_associative_all( $query, $params )
{
    global $con;

    if( !verify_connection() )
    {
        return null;
    }

    prepare_param_query( $query, $params );

    if( !($result = my_pg_query_params( $con, $query, $params ) ) )
    {
        return null;
    }

     if( pg_num_rows( $result ) === 0 )
    {
        return array();
    }

    return pg_fetch_all( $result );
}

function param_query_remote_all( $con_string, $params, $query, &$row_count = -1 )
{
    $con = pg_connect( $con_string );

    if( !verify_connection() )
    {
        $row_count = -1;
        return null;
    }

    prepare_param_query( $query, $params );

    if( !($result = my_pg_query_params( $con, $query, $params ) ) )
    {
        $row_count = -1;
        return null;
    }

    $row_count = pg_num_rows( $result );

    return pg_fetch_all( $result );
}

function param_query_blind( $query, $params = array() )
{
    global $con;

     if( !verify_connection() )
    {
        return false;
    }

    prepare_param_query( $query, $params );

    return my_pg_query_params( $con, $query, $params );
}

# For each column specified in the array $columns,
# if the name is present in $map, build the query
# string so it contains that name/value
function prepare_update_string( $columns, $map )
{
    $query = '';

    foreach( $columns as $key )
    {
        if( array_key_exists( $key, $map ) )
        {
            $prepped_key = db_prep_identifier( $key );

            if( $map[$key] === '' )
            {
                $query .= " $prepped_key = null, ";
            }
            else
            {
                $query .= " $prepped_key = ?$key?, ";
            }
        }
    }

    return $query;
}

?>