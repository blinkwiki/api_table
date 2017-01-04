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

function getPosts() {
    $sql = 'SELECT *'
		. ' FROM bw_posts'
			. ' WHERE 1'
			. ' AND NOT ISNULL(ID)'
			. ' AND NOT ISNULL(post_content)'
		//	. ' AND post_type="post"'
				. ' AND post_status="publish"'
		. ' ORDER BY post_date DESC'
	;
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
	
		// clean up the data
		$posts = utf8($posts);
		
		$json = '{"post": ' . json_encode($posts) . '}';
    } catch(PDOException $e) {
		$json = '{"error":{"text":'. $e->getMessage() .'}}';
    }
	// pad it
	jsonp ($json);
}
 
function getPost($id) {
    $sql = 'SELECT *'
		. ' FROM bw_posts'
			. ' WHERE 1'
				. ' AND id=:id'
			. ' AND ID != ""'
			. ' AND post_content != ""'
				. ' AND post_type="post"'
				. ' AND post_status="publish"'
		. ' ORDER BY post_date DESC';
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $post = $stmt->fetchObject();
        $db = null;
	
		// clean up the data
		$post = utf8($post);
		
		$json = json_encode($post);
    } catch(PDOException $e) {
		$json = '{"error":{"text":'. $e->getMessage() .'}}';
    }
	// pad it
	jsonp ($json);
}




/*/ CUD OPS : DIsable for READ ONLY
 
function addPost() {
    $request = Slim::getInstance()->request();
    $post = json_decode($request->getBody());
	$sql = getPostSQL();
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("name", $post->name);
        $stmt->bindParam("grapes", $post->grapes);
        $stmt->bindParam("country", $post->country);
        $stmt->bindParam("region", $post->region);
        $stmt->bindParam("year", $post->year);
        $stmt->bindParam("description", $post->description);
        $stmt->execute();
        $post->id = $db->lastInsertId();
        $db = null;
		$json = json_encode($post);
    } catch(PDOException $e) {
        $json = '{"error":{"text":'. $e->getMessage() .'}}';
    }
	// pad it
	jsonp ($json);
}
 
function updatePost($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$post = json_decode($body);
	$sql = "UPDATE post SET name=:name, grapes=:grapes, country=:country, region=:region, year=:year, description=:description WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("name", $post->name);
		$stmt->bindParam("grapes", $post->grapes);
		$stmt->bindParam("country", $post->country);
		$stmt->bindParam("region", $post->region);
		$stmt->bindParam("year", $post->year);
		$stmt->bindParam("description", $post->description);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		$json = json_encode($post);
	} catch(PDOException $e) {
		$json = '{"error":{"text":'. $e->getMessage() .'}}';
	}
	// pad it
	jsonp ($json);
}
 
// create version that uses GET 
//
function addPostByGet() {
	
	// collect parameters from all the GET
	$post = getPostByGet();
	
	$sql = getPostSQL();
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		foreach ($post as $key => $value)
		{
			if ($key != 'id')
			$stmt->bindParam($key, $value);
		}
		//$stmt->bindParam("name", $post['name']);
		//$stmt->bindParam("grapes", $post['grapes']);
		//$stmt->bindParam("country", $post['country']);
		//$stmt->bindParam("region", $post['region']);
		//$stmt->bindParam("year", $post['year']);
		//$stmt->bindParam("description", $post['description']);
		
		$stmt->execute();
		$post['id'] = $db->lastInsertId();
		$db = null;
		$json = json_encode($post);
	} catch(PDOException $e) {
		$json = '{"error":{"text":'. $e->getMessage() .'}}';
	}
	// pad it
	jsonp ($json);
}
 
// update version that uses GET 
//
function updatePostByGet($id) {
	
	// collect parameters from all the GET
	$post = getPostByGet();
	
	// build the query
	$sql = getPostSQL($id);
	
	// send the query
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		foreach ($post as $key => $value)
		{
			$stmt->bindParam($key, $value);
		}
		//$stmt->bindParam("name", $post['name']);
		//$stmt->bindParam("grapes", $post['grapes']);
		//$stmt->bindParam("country", $post['country']);
		//$stmt->bindParam("region", $post['region']);
		//$stmt->bindParam("year", $post['year']);
		//$stmt->bindParam("description", $post['description']);
		//$stmt->bindParam("id", $id);
		
		$stmt->execute();
		$db = null;
		$json = json_encode($post);
	} catch(PDOException $e) {
		$json = '{"error":{"text":'. $e->getMessage() .'}}';
	}

	$json = json_encode($post);

	// pad it
	jsonp ($json);
}

function deletePostByGet ($id)  {

	$sql = "DELETE FROM bw_posts WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		$json = '{"message":"Entry successfully deleted!"}';
	} catch(PDOException $e) {
		$json = '{"error":{"text":'. $e->getMessage() .'}}';
	}
	// pad it
	jsonp ($json);
	
}

function getPostSQL ( $id = NULL ) {
		
	if (intval($id) > 0)
	{
		$sql = "UPDATE bw_posts SET"
			." id=:id,"
			." post_author=:post_author,"
			." post_date=:post_date,"
			." post_date_gmt=:post_date_gmt,"
			." post_content=:post_content,"
			." post_title=:post_title,"
			." post_excerpt=:post_excerpt,"
			." post_status=:post_status,"
			." comment_status=:comment_status,"
			." ping_status=:ping_status,"
			." post_password=:post_password,"
			." post_name=:post_name,"
			." to_ping=:to_ping,"
			." pinged=:pinged,"
			." post_modified=:post_modified,"
			." post_modified_gmt=:post_modified_gmt,"
			." post_content_filtered=:post_content_filtered,"
			." post_parent=:post_parent,"
			." guid=:guid,"
			." menu_order=:menu_order,"
			." post_type=:post_type,"
			." post_mime_type=:post_mime_type,"
			." comment_count=:comment_count"
		." WHERE 1"
		." AND id=:id"
		;
	}
	else
	{
		$sql = "INSERT INTO bw_posts ("
			." id,"
			." post_author,"
			." post_date,"
			." post_date_gmt,"
			." post_content,"
			." post_title,"
			." post_excerpt,"
			." post_status,"
			." comment_status,"
			." ping_status,"
			." post_password,"
			." post_name,"
			." to_ping,"
			." pinged,"
			." post_modified,"
			." post_modified_gmt,"
			." post_content_filtered,"
			." post_parent,"
			." guid,"
			." menu_order,"
			." post_type,"
			." post_mime_type,"
			." comment_count"
		 ." ) VALUES ("
			." :id,"
			." :post_author,"
			." :post_date,"
			." :post_date_gmt,"
			." :post_content,"
			." :post_title,"
			." :post_excerpt,"
			." :post_status,"
			." :comment_status,"
			." :ping_status,"
			." :post_password,"
			." :post_name,"
			." :to_ping,"
			." :pinged,"
			." :post_modified,"
			." :post_modified_gmt,"
			." :post_content_filtered,"
			." :post_parent,"
			." :guid,"
			." :menu_order,"
			." :post_type,"
			." :post_mime_type,"
			." :comment_count"
		." )"
		;
	}
}

function deletePost($id) {
    $sql = "DELETE FROM bw_posts WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        $json = '{"error":{"text":'. $e->getMessage() .'}}';
	// pad it
	jsonp ($json);
    }}

// CUD OPS : DIsable for READ ONLY*/
 



function getPostByGet() {
	return array(
		'id'=>$_GET['id'],
		'post_author'=>$_GET['post_author'],
		'post_date'=>$_GET['post_date'],
		'post_date_gmt'=>$_GET['post_date_gmt'],
		'post_content'=>$_GET['post_content'],
		'post_title'=>$_GET['post_title'],
		'post_excerpt'=>$_GET['post_excerpt'],
		'post_status'=>$_GET['post_status'],
		'comment_status'=>$_GET['comment_status'],
		'ping_status'=>$_GET['ping_status'],
		'post_password'=>$_GET['post_password'],
		'post_name'=>$_GET['post_name'],
		'to_ping'=>$_GET['to_ping'],
		'pinged'=>$_GET['pinged'],
		'post_modified'=>$_GET['post_modified'],
		'post_modified_gmt'=>$_GET['post_modified_gmt'],
		'post_content_filtered'=>$_GET['post_content_filtered'],
		'post_parent'=>$_GET['post_parent'],
		'guid'=>$_GET['guid'],
		'menu_order'=>$_GET['menu_order'],
		'post_type'=>$_GET['post_type'],
		'post_mime_type'=>$_GET['post_mime_type'],
		'comment_count'=>$_GET['comment_count'],
	);
}
 
function findByName($query) {
    $sql = "SELECT *"
		." FROM bw_posts"
		." WHERE 1"
				. ' AND post_status="publish"'
		."UPPER(name) LIKE :query ORDER BY name"
	;
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $query = "%".$query."%";
        $stmt->bindParam("query", $query);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $json = '{"post": ' . json_encode($posts) . '}';
    } catch(PDOException $e) {
        $json = '{"error":{"text":'. $e->getMessage() .'}}';
    }
	// pad it
	jsonp ($json);
}
 
function getConnection() {
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="db_bw_normilized_tables";

/*
    $dbhost="127.0.0.1";
    $dbuser="blinkwiki";
    $dbpass="oosagaboaci2011its";
*/

    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
 
function jsonp ( $json ) {
	
	/* If a callback has been supplied then prepare to parse the callback
	 ** function call back to browser along with JSON. */
	
	$jsonp = false;
	
	//$json = strip_tags($json);
	//$json = stripslashes($json);
	//$json = str_replace('<\/a>', '</a>', $json);
	//$json = str_replace('<\/p>', '</p>', $json);
	
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

function utf8 ($data)
{
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