<?php
// Must define RESPONSE_XML_OUTER_TAG before including common_header_xml.php

$webroot = substr(__FILE__, 0, strpos(__FILE__, '/common/includes/common_header_xml.php'));

require_once( $webroot . '/common/functions/db_lib.php' );
require_once( $webroot . '/common/functions/util.php' );
require_once( $webroot . '/common/functions/xml_lib.php' );
require_once( $webroot . '/common/functions/validate_lib.php' );

header( 'Content-type: text/xml' );

$response = null;

function xml_fail_and_exit( $message )
{
    header( 'Content-type: text/xml' );
    echo( "<?xml version=\"1.0\" encoding=\"" . AJAX_ENCODING . "\"?>\n" );
    echo( generate_failure_xml( $message ) );
    exit;
}

function xml_success_and_exit( $message )
{
    header( 'Content-type: text/xml' );
    echo( "<?xml version=\"1.0\" encoding=\"" . AJAX_ENCODING . "\"?>\n" );
    echo( generate_success_xml( $message ) );
    exit;
}

function generate_failure_xml( $message )
{
    $outer = 'UnlabeledResponse';
    if( defined( 'RESPONSE_XML_OUTER_TAG' ) && constant( 'RESPONSE_XML_OUTER_TAG' ) )
    {
        $outer = constant( 'RESPONSE_XML_OUTER_TAG' );
    }

    $message = "<![CDATA[$message]]>";
    return
        '<' . $outer . '>' .
        '<Valid>false</Valid>' .
        "<Message>$message</Message>" .
        '</' . $outer . '>';
}

function generate_success_xml( $message )
{
    $outer = 'UnlabeledResponse';
    if( defined( 'RESPONSE_XML_OUTER_TAG' ) && constant( 'RESPONSE_XML_OUTER_TAG' ) )
    {
        $outer = constant( 'RESPONSE_XML_OUTER_TAG' );
    }

    return
        '<' . $outer . '>' .
        '<Valid>true</Valid>' .
        "<Message>$message</Message>" .
        '</' . $outer . '>';
}

?>
