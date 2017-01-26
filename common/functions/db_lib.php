<?php

require_once( 'postgres_db_lib.php' );

function get_client_array()
{
    $query = <<<SQL
        select client, name, address_line_one, address_line_two,
               city, region, zip_postal, created, modified
          from tb_client
      order by name
SQL;

    return query_associative_all( $query );
}

function get_client( $pk_client )
{
    $query = <<<SQL
        select client, name, address_line_one, address_line_two,
               city, region, zip_postal, created, modified
          from tb_client
         where client = ?pk_client?
SQL;

    return param_query_associative_one( $query, array( 'pk_client' => $pk_client ) );
}

function get_contact_array()
{
    $query  = 'select contact, first_name, last_name, phone_primary, ';
    $query .= '       extension_primary, email_address, client, created, modified ';
    $query .= '  from tb_contact ';
    $query .= ' order by last_name, first_name ';

    return query_associative_all( $query );
}

function get_contact( $pk_contact )
{
    $query  = 'select contact, first_name, last_name, phone_primary, ';
    $query .= '       extension_primary, email_address, client, created, modified ';
    $query .= '  from tb_contact ';
    $query .= ' where contact = ?pk_contact? ';

    return param_query_associative_one( $query, array( 'pk_contact' => $pk_contact ) );
}

function get_contacts_by_client( $pk_client )
{
    $query  = 'select contact, first_name, last_name, phone_primary, ';
    $query .= '       extension_primary, email_address, client, created, modified ';
    $query .= '  from tb_contact ';
    $query .= ' where client = ?pk_client? ';
    $query .= ' order by last_name, first_name ';

    return param_query_associative_all( $query, array( 'pk_client' => $pk_client ) );
}

// Creates a new client
// Keys in $map are:
//    name                => string (200)
//    address_line_one    => string (300)
//    address_line_two    => string (350) (may be null)
//    city                => string (200)
//    region              => string (200)
//    zip_postal          => string (10 )
function create_new_client( $map, &$pk_client_out )
{
    $query  = 'select fn_new_client ';
    $query .= '( ';
    $query .= '   ?name?, ';
    $query .= '   ?address_line_one?, ';
    $query .= '   ?address_line_two?, ';
    $query .= '   ?city?, ';
    $query .= '   ?region?, ';
    $query .= '   ?zip_postal? ';
     $query .= ') ';

    return param_exec_creation_stored_procedure( $query, $map, 'client', $pk_client_out );
}

// Creates a new contact
// Keys in $map are:
//    first_name         => string  (200)
//    last_name          => string  (200)
//    phone_primary      => string  (20)
//    extension_primary  => string  (10)           (may be null)
//    email_address      => string  (200)
//    client             => integer (FK tb_client) (may be null)
function create_new_contact( $map, &$pk_contact_out )
{
    $query  = 'select fn_new_contact ';
    $query .= '( ';
    $query .= '   ?first_name?, ';
    $query .= '   ?last_name?, ';
    $query .= '   ?phone_primary?, ';
    $query .= '   ?extension_primary?, ';
    $query .= '   ?email_address?, ';
    $query .= '   ?client? ';
    $query .= ') ';

    return param_exec_creation_stored_procedure( $query, $map, 'contact', $pk_contact_out );
}

// Updates a client
// Any map keys not provided means to not update that column
// Any values that are empty or undefined means to set that value to null
// Keys in $map can be:
//    pk_client           => integer (primary key, required)
//    name                => string (200)
//    address_line_one    => string (300)
//    address_line_two    => string (350)
//    city                => string (200)
//    region              => string (200)
//    zip_postal          => string (10 )
function update_client( $map )
{
    # These are the fields we want to process from the map
    $columns = array( 'name', 'address_line_one', 'address_line_two', 'city',
                           'region', 'zip_postal' );

    $query = "update tb_client set ";

    $query .= prepare_update_string( $columns, $map );

    $query .= ' modified = now() ';

    $query .= ' where client = ?pk_client? ';
    return param_query_blind_return_null_or_error( $query, $map, 'update client' );
}

// Updates a contact
// Any map keys not provided means to not update that column
// Any values that are empty or undefined means to set that value to null
// Keys in $map can be:
//    pk_contact         => integer (primary key, required)
//    first_name         => string  (200)
//    last_name          => string  (200)
//    phone_primary      => string  (20)
//    extension_primary  => string  (10)
//    email_address      => string  (200)
//    client             => integer (FK tb_client)
function update_contact( $map )
{
    # These are the fields we want to process from the map
    $columns = array( 'first_name', 'last_name', 'phone_primary', 'extension_primary',
                           'email_address', 'client' );

    $query  = "update tb_contact set ";

     $query .= prepare_update_string( $columns, $map );

    $query .= ' modified = now() ';

    $query .= ' where contact = ?pk_contact? ';

    return param_query_blind_return_null_or_error( $query, $map, 'update contact' );
}

function delete_client( $pk_client )
{
    $query  = 'delete ';
    $query .= '  from tb_client ';
    $query .= ' where client = ?pk_client? ';

    return param_query_blind_return_null_or_error( $query,
        array( 'pk_client' => $pk_client ), 'delete client' );
}

function delete_contact( $pk_contact )
{
    $query  = 'delete ';
    $query .= '  from tb_contact ';
    $query .= ' where contact = ?pk_contact? ';

    return param_query_blind_return_null_or_error( $query,
        array( 'pk_contact' => $pk_contact ), 'delete contact' );
}

?>
