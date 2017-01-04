<?php //*********************************************READ ?>
    
    <hr>
    
    <strong>Read</strong><br><br>
    
    <?php
    
    // the service is to retrieve airport records
    $service = 'airport';
    
    // the api url : the table deterines the service to pull from the API
    $api_url = 'http://localhost/csys/api_sel_box/api/service/'.$service;
    
    // get the values from the api link
    {
        // allow for file_get_contes to run by
        // setting the PHP allwo_url_open value to 1
        ini_set("allow_url_fopen", 1);
        
        // get the contents of the url
        $json = file_get_contents($api_url);
        
        // decode the results
        $obj = json_decode($json);
    }
    
    ?>
    
    <table width="100%">
        <thead class="fw_b" valign="top">
            <tr>
                <td width="5%">SN</td>
                <td width="10%">Code</td>
                <td width="10%">IATA</td>
                <td width="35%">Airport Name</td>
                <td width="15%">Airport City</td>
                <td width="15%">State</td>
                <td width="10%">Actions</td>
            </tr>
        </thead>
        <tbody valign="top">
            <?php
            // loop through the results
            for ($i=0; $i<count($obj->rows); $i++)
            {
            ?>
                <tr>
                    <td><?php echo $i+1; ?></td>
                    <td><?php echo $obj->rows[$i]->airport_code; ?></td>
                    <td><?php echo $obj->rows[$i]->airport_iata_code; ?></td>
                    <td><?php echo $obj->rows[$i]->airport_desc; ?></td>
                    <td><?php echo $obj->rows[$i]->airport_city; ?></td>
                    <td><?php echo $obj->rows[$i]->airport_notes; ?></td>
                    <td></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


<?php //*********************************************READ ?>