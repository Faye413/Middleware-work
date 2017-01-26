function show_update_client_modal( pk_client )
{
    new_window();
    $( '#modal_content' ).load( 'modal_update_client.php?pk_client=' + pk_client );
    $( '#modal_window' ).show();
}

function submit_function_update()
{
    if( validate_form()== false )
    {
        return false;
    }
    else
    {
        send_update_client_request();
        cancel_function();
        return false;
    }
}

function validate_form()
{
    var pk_client        = $( "#pk_client" ).val();
    var name             = $( "#name" ).val();
    var address_line_one = $( "#address_line_one" ).val();
    var city             = $( "#city" ).val();
    var region           = $( "#region" ).val();
    var zip_postal       = $( "#zip_postal" ).val();

    if( !is_number_positive( pk_client ) || !is_integer( pk_client ) )
    {
        alert( "Invalid client." );
        return false;
    }

    if( is_missing_or_empty( name ) == true )
    {
        alert( "Name is requred." );
        document.getElementById( "name" ).focus();
        return false;
    }

    if( is_missing_or_empty( address_line_one ) == true )
    {
        alert( "Address is requred." );
        document.getElementById( "address_line_one" ).focus();
        return false;
    }

    if( is_missing_or_empty( city ) == true )
    {
        alert( "City is requred." );
        document.getElementById( "city" ).focus();
        return false;
    }

    if( is_missing_or_empty( region ) == true )
    {
        alert( "Region is requred." );
        document.getElementById( "region" ).focus();
        return false;
    }

    if( is_missing_or_empty( zip_postal ) == true )
    {
        alert( "Zip postal is requred." );
        document.getElementById( "zip_postal" ).focus();
        return false;
    }

    if( !( /^[0-9]{5}([-]?[0-9]{4})?$/.test( zip_postal ) ) )
    {
        alert( "Invalid zip postal" );
        document.getElementById( "zip_postal" ).focus();
        return false;
    }

     return true;
}

function receive_update_client_response( doc, text_status )
{
    var message_node = validate_xml_response( doc, text_status, 'update client' );

    if( message_node == null )
    {
        return false;
    }
    else
    {
        var client_node = xml_get_child_node( message_node, 'Client' );
        var pk_client   = xml_get_child_value( client_node, 'PK'     );

        var fragment = document.createDocumentFragment();

        if( is_null_or_undefined( client_node ) )
        {
            return false;
        }
        else
        {
            fragment.appendChild( get_client_row_element( client_node ) );
        }
    }

    $( '#client_' + pk_client ).replaceWith( fragment );
}

function send_update_client_request()
{
    var url = '/common/ajax/update_client.php';

    var pk_client        = $( "#pk_client" ).val();
    var name             = $( "#name" ).val();
    var address_line_one = $( "#address_line_one" ).val();
    var address_line_two = $( "#address_line_two" ).val();
    var city             = $( "#city" ).val();
    var region           = $( "#region" ).val();
    var zip_postal       = $( "#zip_postal" ).val();

    var map =
    {
        pk_client        : pk_client,
        name             : name,
        address_line_one : address_line_one,
        address_line_two : address_line_two,
        city             : city,
        region           : region,
        zip_postal       : zip_postal
    };

    $.get( url, map, receive_update_client_response );
}
