<?
global $webroot;

$webroot = substr(__FILE__, 0, strpos(__FILE__, '/common/includes/common_header.php'));

require_once( $webroot . '/common/functions/db_lib.php' );
require_once( $webroot . '/common/functions/ui_lib.php' );
require_once( $webroot . '/common/functions/includes.php' );
require_once( $webroot . '/common/functions/util.php' );
require_once( $webroot . '/common/functions/validate_lib.php' );

function insert_header()
{
    global $webroot, $pk_entity, $entity_name, $entity;
    require_once( $webroot . '/common/includes/header.php' );
}

function insert_footer()
{
    global $webroot, $pk_entity, $entity_name, $entity;
    require_once( $webroot . '/common/includes/footer.php' );
}

?>
