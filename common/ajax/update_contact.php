<?php
define( 'RESPONSE_XML_OUTER_TAG', 'UpdateContact' );
require_once( '../../common/includes/common_header_xml.php' );

if( is_missing_or_null( $_REQUEST, 'pk_contact' ) )
{
    xml_fail_and_exit( "contact id was not provided." );
}
elseif( is_missing_or_null( $_REQUEST, 'first_name' ) )
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
add_to_map( $map, $_REQUEST, 'pk_contact'        );
add_to_map( $map, $_REQUEST, 'first_name'        );
add_to_map( $map, $_REQUEST, 'last_name'         );
add_to_map( $map, $_REQUEST, 'phone_primary'     );
add_to_map( $map, $_REQUEST, 'extension_primary' );
add_to_map( $map, $_REQUEST, 'email_address'     );
add_to_map( $map, $_REQUEST, 'client'            );

$pk_contact    = $_REQUEST['pk_contact'];
$update_result = update_contact( $map );

if( !is_number_positive( $_REQUEST, 'pk_contact' ) )
{
    xml_fail_and_exit( "Invalid contact." );
}
else
{
    if( !is_null( $update_result ) )
    {
        xml_fail_and_exit( "failed to update contact." );
    }

    $message = '';
    $message .= xml_contact_fragment( get_contact( $pk_contact ) );

    $message = "<Contacts>$message</Contacts>";
    xml_success_and_exit( $message );
}
?>
