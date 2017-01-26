<?php
define( 'RESPONSE_XML_OUTER_TAG', 'CreateNewClient' );
require_once( '../../common/includes/common_header_xml.php' );

if( is_missing_or_null( $_REQUEST, 'name' ) )
{
    xml_fail_and_exit( "name was not provided." );
}
elseif( is_missing_or_null( $_REQUEST, 'address_line_one' ) )
{
    xml_fail_and_exit( "address line one was not provided." );
}
elseif( is_missing_or_null( $_REQUEST, 'city' ) )
{
    xml_fail_and_exit( "city was not provided." );
}
elseif( is_missing_or_null( $_REQUEST, 'region' ) )
{
    xml_fail_and_exit( "region was not provided." );
}
elseif( is_missing_or_null( $_REQUEST, 'zip_postal') )
{
    xml_fail_and_exit( "zip/postal code was not provided." );
}

$map = array();
add_to_map( $map, $_REQUEST, 'name'             );
add_to_map( $map, $_REQUEST, 'address_line_one' );
add_to_map( $map, $_REQUEST, 'address_line_two' );
add_to_map( $map, $_REQUEST, 'city'             );
add_to_map( $map, $_REQUEST, 'region'           );
add_to_map( $map, $_REQUEST, 'zip_postal'       );

$pk_client_out = -1;

$create_result = create_new_client( $map, $pk_client_out );

if( !is_null( $create_result ) )
{
    xml_fail_and_exit( "failed to create new client." );
}

$message = '';
$message .= xml_client_fragment( $pk_client_out );

$message = "<Clients>$message</Clients>";

xml_success_and_exit( $message );

?>