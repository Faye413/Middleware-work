<?php
// Must define RESPONSE_XML_OUTER_TAG before including common_header_xml.php
define( 'RESPONSE_XML_OUTER_TAG', 'GetClientList' );
require_once( '../../common/includes/common_header_xml.php' );

$client_list = get_client_array();

if( !is_array( $client_list ) )
{
    xml_fail_and_exit( 'Database call failed.' );
}

$message = '';

foreach( $client_list as $client )
{
    $message .= xml_client_fragment( $client );
}

$message = "<Clients>$message</Clients>";
xml_success_and_exit( $message );

?>
