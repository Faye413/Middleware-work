$( initialize );

function initialize()
{
    send_get_client_list_request();
}

function send_get_client_list_request()
{
    var url = '/common/ajax/get_client_list.php';

    var map =
    {

    }

    $.get( url, map, receive_get_client_list_response );
}

function receive_get_client_list_response( doc, text_status )
{
    var message_node = validate_xml_response( doc, text_status, 'retrieve client list' );

    if( message_node == null )
    {
        return false;
    }
    else
    {
        var clients_node = xml_get_child_node( message_node, 'Clients' );
        var client_nodes = xml_get_child_nodes( clients_node, 'Client' );

        var fragment = document.createDocumentFragment();

        if( is_null_or_undefined( client_nodes ) )
        {
            fragment.appendChild( no_client() );
        }
        else
         {
            for( i = 0; i < client_nodes.length; i++ )
            {
                fragment.appendChild( get_client_row_element( client_nodes[i] ) );
            }
        }

        $( '#client_table' ).append( fragment );
    }
}

function get_client_row_element( client_node )
{
    var pk_client        = xml_get_child_value( client_node, 'PK' );
    var client_name      = xml_get_child_value( client_node, 'Name' );
    var address_line_one = xml_get_child_value( client_node, 'Address_line_one' );
    var address_line_two = xml_get_child_value( client_node, 'Address_line_two' );
    var city             = xml_get_child_value( client_node, 'City' );
    var region           = xml_get_child_value( client_node, 'Region' );
    var zip_postal       = xml_get_child_value( client_node, 'Zip_postal' );
    var created          = xml_get_child_value( client_node, 'Created' );
    var modified         = xml_get_child_value( client_node, 'Modified' );

    var client_row = create_element( 'tr',
    {
        id : 'client_' + pk_client
    });

    create_element( 'td',
    {
        text     : pk_client,
        appendTo : client_row
    });

    create_element( 'td',
    {
        text     : client_name,
        appendTo : client_row
     });

    create_element( 'td',
    {
        text     : address_line_one,
        appendTo : client_row
    });

    create_element( 'td',
    {
        text     : address_line_two,
        appendTo : client_row
    });

    create_element( 'td',
    {
        text     : city,
        appendTo : client_row
    });

    create_element( 'td',
    {
        text     : region,
        appendTo : client_row
    });

    create_element( 'td',
    {
        text     : zip_postal,
        appendTo : client_row
    });

    create_element( 'td',
    {
        text     : created,
        appendTo : client_row
    });

    create_element( 'td',
    {
        text     : modified,
        appendTo : client_row
    });

    var actions_cell = create_element( 'td',
    {
        appendTo : client_row
    });

    create_element( 'button',
    {
        text     : 'Update',
        onclick  : 'show_update_client_modal( ' + pk_client + ' )',
        appendTo : actions_cell
    });

    create_element( 'button',
    {
        text     : 'Delete',
        onclick  : 'send_delete_client_request( ' + pk_client + ' )',
        appendTo : actions_cell
    });

    return client_row;
}

function no_client()
{
    var empty_row = create_element( 'tr',
    {
        id : 'no_client'
    });

     create_element( 'td',
    {
        text     : 'No Clients',
        align    : 'center',
        appendTo : empty_row,
        colspan  : $('th').length
    });

    return empty_row;
}

function send_delete_client_request( pk_client )
{
    var url = '/common/ajax/delete_client.php';

    var map =
    {

    };

    map['pk_client'] = pk_client;

    $.get( url, map, receive_delete_client_response );
}

function receive_delete_client_response( doc, text_status )
{
    var message_node = validate_xml_response( doc, text_status, 'delete client' );

    if( message_node == null )
    {
        return false;
    }
    else
    {
        var client_nodes = xml_get_child_nodes( clients_node, 'Client' );
        var clients_node = xml_get_child_node( message_node, 'Clients' );
        var pk_client = xml_get_child_value( clients_node, 'PK' );
        $( '#client_' + pk_client ).remove();
     }

    var remaining_row = document.getElementById( "client_table" ).rows.length;

    if( remaining_row == 1 )
    {
        var fragment = document.createDocumentFragment();
    fragment.appendChild( no_client() );
        $( '#client_table' ).append( fragment );
    }
}