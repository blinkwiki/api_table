<?php
// constants file
include_once('inc/constants.php');
// For make the connections to the database
require('inc/conn.php');
// import the required CRUD functions
//require('inc/crud.php');
// import CRUD support
require('inc/support.php');
?><html lang="en-US" style="height: 100%;">
<head>
<title>Display a Table with values from an API</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="Keywords" content="">
<meta name="Description" content="">
<?php /*<script src="main.js"></script>*/ ?>
<link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
    
<strong>Display a Table with values from an API</strong><br>

<?php
    $get_act = mysql_real_escape_string($_GET['a']);
    $get_act = (strlen($get_act) > 0) ? $get_act : 'r';
?>
    
<?php if ($get_act == 'c') { ?>
    
<?php //*********************************************CREATE ?>

    <?php include ('inc/c.php') ;?>
    
<?php //*********************************************CREATE ?>
    
<?php } else if ($get_act == 'r'){ ?>
    
<?php //*********************************************READ ?>
    
    <?php include ('inc/r.php') ;?>
    
<?php //*********************************************READ ?>

<?php } else if ($get_act == 'u') { ?>
    
<?php //*********************************************UPDATE ?>

    <?php include ('inc/u.php') ;?>
    
<?php //*********************************************UPDATE ?>
    
<?php } else if ($get_act == 'd'){ ?>
    
<?php //*********************************************DELETE ?>

    <?php include ('inc/d.php') ;?>
    
<?php //*********************************************DELETE ?>

<?php } ?>
    
    <hr>
    &copy; BlinkWIki
    
</body>
</html>