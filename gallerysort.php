<?php
//require('check.php');
require('settings.php');
require_once "../scripts/connect.php";

$action = mysqli_real_escape_string ($db , $_POST['action']);
$updateRecordsArray = $_POST['recordsArray'];

if ($action == "updateRecordsListings"){
	
$listingCounter = 1;
foreach ($updateRecordsArray as $recordIDValue) {
		
$sql = <<<SQL
UPDATE
tblGalleries
SET galleryOrder = "$listingCounter" WHERE galleryID = "$recordIDValue"
SQL;
	
if (!$result = $db->query($sql))
{
die('There was an error running the query [' . $db->error . ']');
}
$listingCounter = $listingCounter + 1;
}
	
$sql = <<<SQL
SELECT *
FROM tblGalleries
ORDER BY galleryOrder ASC
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
        	<div class="tbl-cell cell-gallery">No galleries have been added.</div>
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
		$GalleryID = $row['galleryID'];
		$GalleryName = $row["galleryName"];
		$GalleryOrder = $row["galleryOrder"];
		$GalleryLive = $row["Live"];
		
		if ($GalleryOrder == "0") {
			$GalleryOrder = "";
		}
		
		$testthing = $row['galleryID'];
			
		echo "<li id='recordsArray_".$testthing."'><div class='gallery-list tbl-row'>
			<div class='tbl-cell cell-gallery'>" . $GalleryName . " - <a class='grn-txt' href='" . $AdminUrl . "/admin-gallery-photos/".$GalleryID."'>add/view photos</a></div>
			<div class='tbl-cell cell-order'>".$GalleryOrder."</div>
			<div class='tbl-cell cell-live'>";
			
		if ($GalleryLive == "1") {
		?>
        
        	<input type="checkbox" name="gallery_live" onClick="javascript:submitForm(<?php echo ($GalleryID) ?>,0);" id="gallery_live" class="checkbox" value="<?php echo ($GalleryID) ?>" checked="checked" />
			
		<?php
       	} else if ($GalleryLive == "0") {
		?>
            
            <input type="checkbox" name="gallery_live" onClick="javascript:submitForm(<?php echo ($GalleryID) ?>,1);" id="gallery_live" class="checkbox" value="<?php echo ($GalleryID) ?>" />
        
		<?php
       	};
		
        echo "</div>
            <div class='tbl-cell cell-edit'><a class='edit-btn' href='" . $AdminUrl . "/admin-gallery-edit/".$GalleryID."'>Edit</a></div>
            <div class='tbl-cell cell-del'>";
		?>
        
        <a class='del-btn' href='javascript:void(0);' onclick='javascript:deleteGallery(<?php echo ($GalleryID) ?>);'>Delete</a></div>
        
		<?php
        echo "</div></li>";

	}
	
}
}
$result->free();
$db->close();
?>