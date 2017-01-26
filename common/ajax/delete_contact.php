<?php
define( 'RESPONSE_XML_OUTER_TAG', 'DeleteContact' );
require_once( '../../common/includes/common_header_xml.php' );

$pk_contact    = $_REQUEST['pk_contact'];
$contact_info  = get_contact( $pk_contact );
$delete_result = delete_contact( $pk_contact );

if( !is_number_positive( $_REQUEST, 'pk_contact' ) )
{
    xml_fail_and_exit( "Invalid contact." );
}
else
{
    if( !is_null( $delete_result ) )
    {
        xml_fail_and_exit( "failed to delete contact." );
    }

    $message = '';
    $message .= xml_contact_fragment( $contact_info );

    $message = "<Contacts>$message</Contacts>";
    xml_success_and_exit( $message );
}
?>
