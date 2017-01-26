<?php
// Must define RESPONSE_XML_OUTER_TAG before including common_header_xml.php
define( 'RESPONSE_XML_OUTER_TAG', 'CreateContactList' );
require_once( '../../common/includes/common_header_xml.php' );

if( is_missing_or_null( $_REQUEST, 'first_name' ) )
{
    xml_fail_and_exit( "first name was not provided." );
}
elseif( is_missing_or_null( $_REQUEST, 'last_name' ) )
{
    xml_fail_and_exit( "last name was not provided." );
}
elseif( is_missing_or_null( $_REQUEST, 'phone_primary' ) )
{
    xml_fail_and_exit( "phone number was not provided." );
}
elseif( is_missing_or_null( $_REQUEST, 'email_address' ) )
{
    xml_fail_and_exit( "email address was not provided." );
}

$map = array();
add_to_map( $map, $_REQUEST, 'first_name'        );
add_to_map( $map, $_REQUEST, 'last_name'         );
add_to_map( $map, $_REQUEST, 'phone_primary'     );
add_to_map( $map, $_REQUEST, 'extension_primary' );
add_to_map( $map, $_REQUEST, 'email_address'     );
add_to_map( $map, $_REQUEST, 'client'            );

$pk_contact_out = -1;

if( !is_null( create_new_contact( $map, $pk_contact_out ) ) )
{
    xml_fail_and_exit( "failed to create new contact." );
}

$message = '';
$message .= xml_contact_fragment( $pk_contact_out );

$message = "<Contacts>$message</Contacts>";

xml_success_and_exit( $message );

?>