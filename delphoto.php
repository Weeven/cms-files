<?php
require('settings.php');
//require('check.php');
require_once "../scripts/connect.php";
$ImageID = ereg_replace("[^0-9]", "", $_GET['ImageID']);
$GalleryID = ereg_replace("[^0-9]", "", $_GET['GalID']);

$sql = <<<SQL
	DELETE
	FROM tblPhotos
	WHERE imageID = "$ImageID" LIMIT 1
SQL;

if (!$result = $db->query($sql))
{
	die('There was an error running the query [' . $db->error . ']');
	$result->free();
	$db->close();
}

$action = mysqli_real_escape_string ($db , $_POST['action']);
$updateRecordsArray = $_POST['recordsArray'];

if ($action == "updateRecordsListings"){
	
$listingCounter = 1;
foreach ($updateRecordsArray as $recordIDValue) {
		
$sql = <<<SQL
UPDATE
tblPhotos
SET sortOrder = "$listingCounter" WHERE imageID = "$recordIDValue"
SQL;
	
if (!$result = $db->query($sql))
{
die('There was an error running the query [' . $db->error . ']');
}
$listingCounter = $listingCounter + 1;
}
	
$sql = <<<SQL
SELECT *
FROM tblPhotos
WHERE galleryID = "$GalleryID"
ORDER BY sortOrder ASC
SQL;
	
if (!$result = $db->query($sql))
{
die('There was an error running the query [' . $db->error . ']');
$result->free();
$db->close();
}

$totalRows = $result->num_rows;

if ($totalRows == "0") {
?>
	<li>
    	<div class="gallery-list tbl-row">
        	<div class="tbl-cell cell-gallery">No photos have been added.</div>
            <div class="tbl-cell cell-order"></div>
            <div class="tbl-cell cell-live"></div>
            <div class="tbl-cell cell-edit"></div>
            <div class="tbl-cell cell-del"></div>       
        </div>
    </li>
<?php	
} else { 
	
	while($row = $result->fetch_assoc())
	{
		$GalID = $row['galleryID'];
		$ImageID = $row['imageID'];
		$ImageTitle = $row["imageTitle"];
		$CaptionText = $row["captionText"];
		$ImageThumb = $row["imageThumb"];
		$PhotosOrder = $row["sortOrder"];
		$PhotosLive = $row["Live"];
		
		if ($PhotosOrder == "0") {
			$PhotosOrder = "";
		}
		
		$testthing = $row['imageID'];
		
		// echo the output to browser
		echo "<li id='recordsArray_".$testthing."'><div class='gallery-list tbl-row'>
			<div class='tbl-cell cell-gallery'><div><img src='" . $AdminUrl . "/" . $upload_path . $ImageThumb . "' alt='" . $ImageTitle . "' height='100'/></div>";		
			
		echo "<div class='listgrid'><div class='tbl-cell cell-order'><span>order</span>".$PhotosOrder."</div><div class='tbl-cell cell-live'><span>live</span>";
		
		if ($PhotosLive == "1") {
		?>
    
    		<input type="checkbox" name="photo_live" onClick="javascript:submitForm(<?php echo ($ImageID) ?>,0,<?php echo ($GalID) ?>);" id="photo_live" class="checkbox" value="<?php echo ($ImageID) ?>" checked="checked" />
    	
        <?php
       	} else if ($PhotosLive == "0") {
		?>
        	
            <input type="checkbox" name="photo_live" onClick="javascript:submitForm(<?php echo ($ImageID) ?>,1,<?php echo ($GalID) ?>);" id="photo_live" class="checkbox" value="<?php echo ($ImageID) ?>" />
        
    	<?php
		};
		
		echo "</div>
            <div class='tbl-cell cell-edit'><span>edit</span><a class='edit-btn' rel='facebox' href='".$AdminUrl."/admin-gallery-photo-edit/".$ImageID."'>Edit</a></div>
            <div class='tbl-cell cell-del'><span>delete</span>";
		?>
		
        <a class='del-btn' href='javascript:void(0);' onclick='javascript:deletePhoto(<?php echo ($ImageID) ?>,<?php echo ($GalID) ?>);'>Delete</a></div>
        
		<?php
        echo "</div>";
			
		echo "<span class='recordstitle'>" . $ImageTitle . "</span><span class='recordscaption'>" . $CaptionText . "</span></div>
			<div class='tbl-cell cell-order listlist'>".$PhotosOrder."</div>
			<div class='tbl-cell cell-live listlist'>";
			
		if ($PhotosLive == "1") {
		?>
    
    		<input type="checkbox" name="photo_live" onClick="javascript:submitForm(<?php echo ($ImageID) ?>,0,<?php echo ($GalID) ?>);" id="photo_live" class="checkbox" value="<?php echo ($ImageID) ?>" checked="checked" />
    	
        <?php
       	} else if ($PhotosLive == "0") {
		?>
        	
            <input type="checkbox" name="photo_live" onClick="javascript:submitForm(<?php echo ($ImageID) ?>,1,<?php echo ($GalID) ?>);" id="photo_live" class="checkbox" value="<?php echo ($ImageID) ?>" />
        
    	<?php
		};
		
		echo "</div>
            <div class='tbl-cell cell-edit listlist'><a class='edit-btn' rel='facebox' href='".$AdminUrl."/admin-gallery-photo-edit/".$ImageID."'>Edit</a></div>
            <div class='tbl-cell cell-del listlist'>";
		?>
		
        <a class='del-btn' href='javascript:void(0);' onclick='javascript:deletePhoto(<?php echo ($ImageID) ?>,<?php echo ($GalID) ?>);'>Delete</a></div>
        
		<?php
        echo "</div></li>";
		
	}
	
	echo "<script type='text/javascript'>
jQuery(document).ready(function($) {
	$('a[rel*=facebox]').facebox({
		loadingImage : '../src/loading.gif',
		closeImage   : '../src/closelabel.png'
	})
})
</script>";
	
}
}
$result->free();
$db->close();
?>