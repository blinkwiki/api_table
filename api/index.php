<?php //header("Access-Control-Allow-Origin: http://localhost");

// import the required CRUD functions
require('../inc/crud.php');
 
require 'Slim/Slim.php';
use \Slim\Slim;
\Slim\Slim::registerAutoloader();

$app = new Slim();
 
// define the url command and the function to run: get_service();
$app->get('/service/:serviceid', 'get_service');

$app->run();


// the function get_service();
function get_service($serviceid)
{
    
	// return the SELECT sql required for the service
	// get_read_sql() function contained in crud.php 
	$sql = get_read_sql($serviceid);
    
	// connect to db and process the sql
	try {
		// connect to database
		$db = getConnection();

		// run the query
		$stmt = $db->query($sql);
		
		// get the values
		$posts = $stmt->fetchAll(PDO::FETCH_OBJ);

		// clear out the db resource
		$db = null;
	
		// clean up collected data
		$posts = utf8($posts);
		
        // complete the json
		$json = '{"rows": ' . json_encode($posts) . '}';

	}
	catch(PDOException $e)
	{
        // the error message
		$json = '{"error":{"text":'. $e->getMessage() .'}}';
 	}

	// pad the json (jsonp)
	jsonp ($json);
    
}

function getConnection() {
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="db_bw_normilized_tables";

    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
 
function jsonp ( $json ) {
	
	/* If a callback has been supplied then prepare to parse the callback
	 ** function call back to browser along with JSON. */
	
	$jsonp = false;
	
	if ( isset( $_GET[ 'callback' ] ) ) {
		
	 	$_GET[ 'callback' ] = strip_tags( $_GET[ 'callback' ] );
	 	$jsonp = true;
		
		$pre  = $_GET[ 'callback' ] . '(';
		$post = ');';
		
	}
	
	/* Encode JSON, and if jsonp is true, then ouput with the callback
	 ** function; if not - just output JSON. */
	print( ( $jsonp ) ? $pre . $json . $post : $json );

}

function utf8 ($data) {
	if (is_array($data))
	{
		for ($i=0; $i<count($data); $i++ )
		{
			foreach ($data[$i] as $pkey => $prow)
			{
				//if ($pkey == 'post_content')
				//{
					$data[$i]->$pkey = utf8_encode($data[$i]->$pkey);
				//}
			}
		}
	}
	else
	{
		foreach ($data as $pkey => $prow)
		{
			$data->$pkey = utf8_encode($data->$pkey);
		}
	}
	return $data;
}

?>