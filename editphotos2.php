<?php
require('settings.php');
require('check.php');

$ImageID = ereg_replace("[^0-9]", "", $_GET['ImageID']);
$ImageTitle = $_GET['ImageTitle'];
$ImageCaption = $_GET['ImageCaption'];

function filterFunction ($var) { 
    $var = nl2br(htmlspecialchars($var));
    $var = eregi_replace("'", "&#39;", $var);
    $var = eregi_replace("`", "&#39;", $var);		
    return $var; 
} 
$ImageTitle = filterFunction($ImageTitle);
$ImageCaption = filterFunction($ImageCaption);

require_once "../scripts/connect.php";


$sql = <<<SQL
	UPDATE
	tblPhotos
	SET imageTitle = "$ImageTitle", captionText = "$ImageCaption" WHERE imageID = "$ImageID" LIMIT 1
SQL;

if (!$result = $db->query($sql))
{
die('There was an error running the query [' . $db->error . ']');
$result->free();
$db->close();
}

$_SESSION['UpdateImage'] = "True";

//header("Location: $AdminUrl/admin-gallery-photos/". $GalleryID);
//exit();
?>