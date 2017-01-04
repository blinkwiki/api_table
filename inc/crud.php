<?php
/**
* CRUD Functions.
*
* @package    crud.php
* @subpackage 
* @author     BlinkWiki
* @version    1.0
* Copyright 2016 BlinkWiki
* 
*/
?>
<?php
function get_read_sql($tbl)
{
    switch($tbl)
    {
        case 'airport':
        case 'airports':
        case 'tbl_airport':
            $sql = 'SELECT'
                    .' *'
                . ' FROM'
                    .' `tbl_airports`'
                . ' WHERE 1'
                    . ' AND NOT ISNULL(`tbl_airports`.`airport_id`)'
                    . ' AND NOT ISNULL(`tbl_airports`.`airport_code`)'
                    . ' AND NOT ISNULL(`tbl_airports`.`airport_desc`)'
                . ' ORDER BY'
                    .' `tbl_airports`.`airport_code` ASC'
            ;
            break;
        case 'tbl_states':
        default:
            $sql = 'SELECT * FROM `'.$tbl.'` WHERE 1';
            break;
            
    }
    return $sql;
}
?>