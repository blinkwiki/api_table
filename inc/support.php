<?php
/**
*
* This function is a variant of the ordinary select_box(); select box
* populator function. Full details can be found here. 
* It simply populates a select box with values retrieved from an API link
*
* $tbl          is the table to generate values from
* $opt_value    is the name of the column to use for the option value property
* $opt_text     is the name of the column to use for the option text property
* $rid          is the record id representing the selected option
* $conn         is the database connector resource
*
*/
function api_sel_box($tbl, $opt_value, $opt_text, $rid=0)
{
    
    // the api url : the table deterines the service to pull from the API
    $api_url = 'http://localhost/csys/api_sel_box/api/service/'.$tbl;
    
    // get the values from the api link
    ini_set("allow_url_fopen", 1);
    $json = file_get_contents($api_url);
    $obj = json_decode($json);
    
    // initialize the html
    $html = '';
    
    // determine the selected option
    $sel = ($rid == 0) ? 'selected' : '';
    
    // initiate the options html
    $html .= '<option value="0" '.$sel.'>--select--</option>';
    
    // in case the opt_text is in CSV format allowing us to display values from more than one column 
    $opt_txts = explode(',', str_replace(' ', '', $opt_text));
    
    // loop through the rows
    for ($i=0; $i<count($obj->rows); $i++)
    {
    
        // convert the object to an array
        // array allow for referencing of key names with variables
        $row = get_object_vars($obj->rows[$i]);
        
        // if the current options is the selected option then set the option's selected property
        $sel = ($row[$opt_value] == $rid) ? 'selected' : '';
        
        // apend the option html
        $html .= ''
            // start the option tag
            .'<option value="'
                // append the airport id as the option value
                //.$obj->rows[$i]->airport_id
                .$row[$opt_value]
            .'" title="'
                // append the airport desc as the option title
                .$row[$opt_text]
            .'"'
                // append the selected attribute
                .' '.$sel
            .'>'
        ;
        // display all the text values
        for ($j=0; $j<count($opt_txts); $j++)
        {
            if ($j > 0)
            {
                // the separator
                $html .= ' | ';                
            }
            $html .= ''
                // append the airport name as the option text
                .$row[$opt_txts[$j]]
            ;
        }
        $html .= ''
            // close the option tag
            . '</option>'
        ;
    }
    
    return $html;
    
}


/**
*
* function to generate the options of a select boxes
*
* $tbl          is the table to generate values from
* $opt_value    is the name of the column to use for the option value property
* $opt_text     is the name of the column to use for the option text property
* $rid          is the record id representing the selected option
* $conn         is the database connector resource
*
*/

function select_box($tbl, $opt_value, $opt_text, $rid, $conn)
{
    // build the read query
    $sql = get_read_sql($tbl);
    
    // submit the query to generate rows
    $rs = mysql_query($sql, $conn) or die(mysql_error());
    
    // fetch the first 
    $row = mysql_fetch_assoc($rs);
    
    // calculate total rows
    $total_rows = mysql_num_rows($rs);
    
    // determine the selected option
    $sel = ($rid == 0) ? 'selected' : '';
    
    // initiate the options html
    $html = '<option value="0" '.$sel.'>--select--</option>';
    
    // loop throw the result count
    for ($i=0; $i<$total_rows; $i++)
    {
        // if the current options is the selected option then set the option's selected property
        $sel = ($row[$opt_value] == $rid) ? 'selected' : '';
        
        // append the current options html
        $html .= '<option value="'.$row[$opt_value].'" '.$sel.'>'.$row[$opt_text].'</option>';
        
        // generate the next row
        $row = mysql_fetch_assoc($rs);
    }
    
    // return the html
    return $html;
}

?>