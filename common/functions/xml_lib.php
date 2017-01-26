<?php

require_once( 'db_lib.php' );
require_once( 'ui_lib.php' );

function xml_client_fragment( $client, $map = array() )
{
    if( !is_array( $client ) )
    {
        $client = get_client( $client );
    }

    $pk_client        = $client['client'];
    $name             = $client['name'];
    $address_line_one = $client['address_line_one'];
    $address_line_two = $client['address_line_two'];
    $city             = $client['city'];
    $region           = $client['region'];
    $zip_postal       = $client['zip_postal'];
    $created          = format_ui_date_and_time( $client['created'] );
    $modified         = format_ui_date_and_time( $client['modified'] );

    $fragment  = "<PK>$pk_client</PK>";
    $fragment .= "<Name><![CDATA[$name]]></Name>";
    $fragment .= "<Address_line_one><![CDATA[$address_line_one]]></Address_line_one>";
    $fragment .= "<Address_line_two><![CDATA[$address_line_two]]></Address_line_two>";
    $fragment .= "<City><![CDATA[$city]]></City>";
    $fragment .= "<Region><![CDATA[$region]]></Region>";
    $fragment .= "<Zip_postal><![CDATA[$zip_postal]]></Zip_postal>";
    $fragment .= "<Created><![CDATA[$created]]></Created>";
    $fragment .= "<Modified><![CDATA[$modified]]></Modified>";

    $fragment = "<Client>$fragment</Client>";
    return $fragment;
}

function xml_contact_fragment( $contact, $map = array() )
{
    if( !is_array( $contact ) )
    {
        $contact = get_contact( $contact );
    }

    $pk_client = $contact['client'];

    $the_client = get_client( $pk_client );

    $pk_contact        = $contact['contact'];
    $first_name        = $contact['first_name'];
    $last_name         = $contact['last_name'];
    $phone_primary     = $contact['phone_primary'];
    $extension_primary = $contact['extension_primary'];
    $email_address     = $contact['email_address'];
    $contact_client    = $the_client['name'];
    $created           = format_ui_date_and_time( $contact['created'] );
    $modified          = format_ui_date_and_time( $contact['modified'] );

    $fragment  = "<PK>$pk_contact</PK>";
    $fragment .= "<FirstName><![CDATA[$first_name]]></FirstName>";
    $fragment .= "<LastName><![CDATA[$last_name]]></LastName>";
    $fragment .= "<PhonePrimary><![CDATA[$phone_primary]]></PhonePrimary>";
    $fragment .= "<ExtensionPrimary><![CDATA[$extension_primary]]></ExtensionPrimary>";
    $fragment .= "<EmailAddress><![CDATA[$email_address]]></EmailAddress>";
    $fragment .= "<Client><![CDATA[$contact_client]]></Client>";
    $fragment .= "<Created><![CDATA[$created]]></Created>";
    $fragment .= "<Modified><![CDATA[$modified]]></Modified>";
    $fragment = "<Contact>$fragment</Contact>";
    return $fragment;

}

?>
