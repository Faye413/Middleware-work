<?php
// Must define RESPONSE_XML_OUTER_TAG before including common_header_xml.php
define( 'RESPONSE_XML_OUTER_TAG', 'GetContactList' );
require_once( '../../common/includes/common_header_xml.php' );

$contact_list = get_contact_array();

if( !is_array( $contact_list ) )
{
    xml_fail_and_exit( 'Database call failed.' );
}

$message = '';

foreach( $contact_list as $contact )
{
    $message .= xml_contact_fragment( $contact );
}

$message = "<Contacts>$message</Contacts>";

xml_success_and_exit( $message );

?>
