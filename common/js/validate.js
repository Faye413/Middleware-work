function is_null_or_undefined( s )
{
    if( s === null )
    {
        return true;
    }
    else if( s === undefined )
    {
        return true;
    }

    return false;
}

function is_valid_date( date_string )
{
    var date_regex = /^\d{1,2}\/\d{1,2}\/\d{4}$/g;

    if( is_null_or_undefined( date_string ) )
    {
        return false;
    }

    if( !date_regex.test( date_string ) )
    {
        return false;
    }

    return true;
}

function is_missing_or_empty( s )
{
    if( is_null_or_undefined( s ) )
    {
        return true;
    }
    else if( s.length === 0 )
    {
         return true;
    }
    else if( !s )
    {
        return true;
    }

    return false;
}

function is_missing_empty_or_blank( s )
{
    if( is_missing_or_empty( s ) )
    {
        return true;
    }
    else if( is_blank( s ) )
    {
        return true;
    }

    return false;
}

function is_blank( s )
{
    for(var i = 0; i < s.length; i++ )
    {
        var c = s.charAt(i);
        if( (c != ' ') && ( c != '\n') && (c != '\t') )
        {
            return false;
        }
    }

    return true;
}

function is_integer(s)
{
    var i;
    for (i = 0; i < s.length; i++)
    {
        // Check that current character is number.
        var c = s.charAt(i);
        if(
            ((c < "0") || (c > "9"))
          )
        {
            return false;
        }
    }
    // All characters are numbers.
    return true;
}

function is_number_positive( input )
{
    if( is_null_or_undefined( input ) )
    {
        return false;
    }

    if( !is_numeric( input ) )
    {
        return false;
    }

    if( parseFloat( input ) <= 0 )
    {
        return false;
    }

    return true;
}

function is_numeric( input )
{
    return !isNaN( parseFloat( input ) ) && isFinite( input );
}

function validate_xml_response( doc, text_status, item_name )
{
    var valid_nodes   = doc.getElementsByTagName( 'Valid'   );
    var message_nodes = doc.getElementsByTagName( 'Message' );

    if( valid_nodes.length <= 0 )
    {
        alert( "Unable to " + item_name + ", no validity element" );
        return null;
    }

    var valid_node = valid_nodes[0];

    if(
            ( null == valid_node.childNodes[0] ) ||
            ( undefined == valid_node.childNodes[0] )
      )
    {
        alert( "Unable to " + item_name + ", validity elements bad" );
        return null;
    }

    var valid_str = valid_node.childNodes[0].nodeValue;
    var is_valid  = ( valid_str.toLowerCase() === "true" );

    if( is_valid )
    {
        if( message_nodes.length <= 0 )
        {
            alert( "Unable to " + item_name + ", no message (1)" );
            return null;
        }

        var message_node = message_nodes[0];

         if(
                ( null == message_node.childNodes[0] ) ||
                ( undefined == message_node.childNodes[0] )
          )
        {
            alert( "Unable to " + item_name + ", no message (2)" );
            return null;
        }

        return message_node;
    }
    else
    {
        if( message_nodes.length <= 0 )
        {
            alert( "Unable to " + item_name + ", no message (3)" );
            return null;
        }

        var message_node = message_nodes[0];

        if(
                ( null == message_node.childNodes[0] ) ||
                ( undefined == message_node.childNodes[0] )
          )
        {
            alert( "Unable to " + item_name + ", no message (4)" );
            return null;
        }

        var message = message_node.childNodes[0].nodeValue;

        alert( "Unable to " + item_name + ": " + message );
        return null;
    }
}
