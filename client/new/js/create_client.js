function show_add_client_modal()
{
    new_window();
    $( '#modal_content' ).load( 'modal_create_client.php' );
    $( '#modal_window' ).show();
}

function submit_function()
{
    if( validate_form() == false )
    {
        return false;
    }
    else
    {
        send_add_client_request();
        cancel_function();
        return false;
    }
}

function validate_form()
{
    var name             = $( "#name" ).val();
    var address_line_one = $( "#address_line_one" ).val();
    var city             = $( "#city" ).val();
    var region           = $( "#region" ).val();
    var zip_postal       = $( "#zip_postal" ).val();

    if( is_missing_or_empty( name ) == true )
    {
        alert( "Name is requred." );
        document.getElementById( "address_line_one" ).focus();
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

    if( !( /^[0-9]{5}([- ]?[0-9]{4})?$/.test( zip_postal ) ) )
    {
        alert( "Invalid zip postal" );
        document.getElementById( "zip_postal" ).focus();
        return false;
    }

    return true;
}

function send_add_client_request()
{
    var url = '/common/ajax/create_client.php';

    var name             = $( "#name" ).val();
    var address_line_one = $( "#address_line_one" ).val();
    var address_line_two = $( "#address_line_two" ).val();
    var city             = $( "#city" ).val();
    var region           = $( "#region" ).val();
    var zip_postal       = $( "#zip_postal" ).val();

    var map =
    {
        name             : name,
        address_line_one : address_line_one,
        address_line_two : address_line_two,
        city             : city,
        region           : region,
        zip_postal       : zip_postal
    };

    $.get( url, map, receive_add_client_response );
}

function receive_add_client_response( doc, text_status )
{
    var message_node = validate_xml_response( doc, text_status, 'create new client' );

    if( message_node == null )
    {
        return false;
    }
    else
    {
        var client_node = xml_get_child_node( message_node, 'Client' );

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

    $( '#no_client' ).remove();
    $( '#client_table' ).append( fragment );
}
