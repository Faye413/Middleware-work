// Make sure console.* commands don't break IE8
if( typeof console === 'undefined' )
{
    console =
    {
        log:  function() {},
        warn: function() {},
        info: function() {}
    };
}

function load_url( url )
{
    document.location.href = url;
}

function reload_page()
{
    window.location.reload();
}

function jquery_error( message )
{
    alert( "Ajax Error: " + message );
}

function xml_get_child_nodes( parent_node, child_name )
{
    if( ( parent_node == null ) || ( parent_node == undefined ) )
    {
        return null;
    }

    var child_nodes = $( parent_node ).children( child_name );

    return child_nodes;
}

function xml_get_child_node( parent_node, child_name )
{
    if ( ( parent_node === null ) || ( parent_node === undefined ) )
    {
        return null;
    }

    var child_nodes = parent_node.getElementsByTagName( child_name );

    if( child_nodes.length <= 0 )
    {
        return null;
    }

    var child_node = child_nodes[0];
    if ( ( child_node === null ) || ( child_node === undefined ) || ( child_node.childNodes.length <= 0 ) )
    {
        return null;
    }

    return child_node;
}

function xml_get_child_value( parent_node, child_name )
{
    child_node = xml_get_child_node( parent_node, child_name );

    if( child_node === null )
    {
        return null;
    }

    if( ( child_node.childNodes[0] === null ) || ( child_node.childNodes[0] === undefined ) )
    {
        return null;
    }

    if( child_node.childNodes[0].nodeValue === undefined )
    {
         return null;
    }

    return child_node.childNodes[0].nodeValue;
}

function create_text_node( text )
{
    if( typeof( text ) === 'string' )
    {
        return document.createTextNode( text );
    }

    return document.createTextNode( text );
}

function create_element( element_name, options )
{
    if( typeof( element_name ) !== 'string' )
    {
        alert( 'Element name was not a string' );
        return null;
    }

    var element = document.createElement( element_name );

    if( options !== null && options !== undefined )
    {
        for( var property in options )
        {
            if( property === 'style' )
            {
                for( var style_property in options[ property ] )
                {
                    if( style_property == 'float' || style_property == 'cssFloat' || style_property == 'styleFloat' )
                    {   //LOL IE
                         element.style.styleFloat = options[ property ][ style_property ];
                        element.style.cssFloat   = options[ property ][ style_property ];
                        continue;
                    }

                    element.style[ style_property ] = options[ property ][ style_property ];
                }
            }
            else if( property === 'checked' )
            {
                var checked = false;

                if( typeof( options[ property ] ) === 'string' )
                {
                    if( options[ property ] === 't' || options[ property ] === 'true' )
                    {
                        checked = true;
                    }
                }
                else if( typeof( options[ property ] ) === 'boolean' )
                {
                    if( options[ property ] === true )
                    {
                        checked = true;
                    }
                }

                $( element ).prop( 'checked', checked );
            }
            else if( property === 'text' )
            {
                if( typeof( options[ property ] ) === 'string' || typeof( options[ property ] ) === 'number' )
                {
                    element.appendChild( create_text_node( options[ property ] ) );
                }
            }
            else
            {
                 if( property === 'append' )
                {
                    if( typeof( options[ property ] ) === 'string' )
                    {
                        element.appendChild( $( options[ property ] )[0] );
                    }
                    else
                    {
                        element.appendChild( options[ property ] );
                    }

                    continue;
                }

                if( property === 'appendTo' )
                {
                    if( typeof( options[ property ] ) === 'string' )
                    {
                        $( options[ property ] ).append( element );
                    }
                    else if( typeof( options[ property ] ) === 'object' )
                    {
                        $( options[ property ] ).append( element );
                    }
                    else
                    {
                        console.log( 'DEBUG THIS: -- appendTo ', options, property, options[ property ] );
                    }

                    continue;
                }

                if( property === 'value' && is_null_or_undefined( options[ property ] ) )
                {
                    options[ property ] = '';
                    continue;
                }

                 if(
                    typeof( options[ property ] ) === 'string' ||
                    typeof( options[ property ] ) === 'number' ||
                    typeof( options[ property ] ) === 'boolean'
                  )
                {
                    element.setAttribute( property, options[ property ] );
                }
                else if( typeof( options[ property ] ) === 'function' )
                {
                    $( element ).bind( ( property.replace( 'on', '' ) ).toLowerCase(), options[ property ] );
                }
                else
                {
                    console.log( 'DEBUG THIS : -- events ', options, property, options[ property ], typeof( options[ property ] ) );
                }
            }
        }
    }

    return element;
}

function create_option( value, text, appendTo )
{
    var option = create_element( 'option',
    {
        text     : text,
        value    : value,
        appendTo : appendTo
    });

    return option;
}

String.prototype.translate = function()
{
     return this.toString();
};


function new_window()
{
    var modal = create_element( 'div',
    {
        id    : 'modal_window',
        class : 'modal_window'
    });

    create_element( 'div',
    {
        id       : 'modal_content',
        class    : 'modal_content',
        appendTo : modal
    });

    $( 'body').append( modal );
}

function cancel_function()
{
    $( '#modal_content' ).empty();
    $( '#modal_window' ).hide();
}
