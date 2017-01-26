<?php
define( 'RESPONSE_XML_OUTER_TAG', 'DeleteClient' );
require_once( '../../common/includes/common_header_xml.php' );

$pk_client    = $_REQUEST['pk_client'];
$client_info  = get_client( $pk_client );
$contact_list = get_contact_array();

if( !is_number_positive( $_REQUEST, 'pk_client' ) )
{
    xml_fail_and_exit( "Invalid client." );
}
else
{
    foreach( $contact_list as $contact )
    {
        if( $pk_client == $contact['client'] )
        {
            xml_fail_and_exit( "Client is associated with the contact." );
        }
    }

    $delete_result = delete_client( $pk_client );

    if( !is_null( $delete_result ) )
    {
        xml_fail_and_exit( "failed to delete client." );
    }

    $message = '';
    $message .= xml_client_fragment( $client_info );

    $message = "<Clients>$message</Clients>";
    xml_success_and_exit( $message );
}
?>
