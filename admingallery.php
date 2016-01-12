<?php
require('settings.php');
require('check.php');
require_once "../scripts/connect.php";
$PageID = "gallery";

$ActionType = ereg_replace("[^0-9]", "", $_POST['ActionType']);
$GalleryID = ereg_replace("[^0-9]", "", $_POST['GalleryID']);

if ($ActionType == "1")
{
$sql = <<<SQL
UPDATE
tblGalleries
SET Live = "$ActionType" WHERE galleryID = "$GalleryID"
SQL;
}
elseif ($ActionType == "0")
{
$sql = <<<SQL
UPDATE
tblGalleries
SET Live = "$ActionType" WHERE galleryID = "$GalleryID"
SQL;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require 'head.php'; ?>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	$(function() {
		$(".content ul.gallerylist").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize") + '&action=updateRecordsListings'; 
			
			$.get('/cms/admin/ajaxcheck.php', function(data) {
			if( data == "Expired" ) {
				window.location.href = "<?php echo ("$AdminUrl"); ?>/logout.php";
			} else if (data == "Active" ) {
			
			$.post("<?php echo ($AdminUrl) ?>/gallerysort.php", order, function(theResponse){
				$(".gallerylist").html(theResponse);
			});
			
			}
 		});
			
																 
		}								  
		});
	});
});	
</script>
<script type="text/JavaScript">
	function submitForm(GalleryID,Action) {
		var valGalleryID = GalleryID;
		var valAction = Action;
		document.location.href="<?php echo ($AdminUrl) ?>/livegallery.php?GalleryID=" + valGalleryID + "&ActionType=" + valAction + "";
	}
	function deleteGallery(GalleryID) {
		var valGalleryID = GalleryID;
		var r=confirm("Are you sure you want to delete this gallery?");
		if (r==true) {
		  document.location.href="<?php echo ($AdminUrl) ?>/delgallery.php?GalleryID=" + valGalleryID + "";
		} else {
			false 
		}
	}
</script>
</head>

<body>
	
    <?php require 'top.php'; ?>
    <?php require 'left.php'; ?>
     
  	<div class="content">
    	
        <h2>Gallery</h2>
        <div class="inner">
        	<div class="inner2 nmb">
            	<p class="nmb">
                	Welcome to your gallery. Click and drag a row to change the order you wish your galleries to appear.
                </p>
            </div>
        </div> <!--end inner-->
        
        <div class="gallery-list gallery-list-top tbl-row mt40">
        	<div class="tbl-cell cell-gallery">Gallery Name</div>
            <div class="tbl-cell cell-order">Order</div>
            <div class="tbl-cell cell-live">Live</div>
            <div class="tbl-cell cell-edit">Edit</div>
            <div class="tbl-cell cell-del">Delete</div>
        </div>
        
        <div class="gallery-list">
        	<div class="tbl-cell cell-gallery"><a class="grn-txt" href="<?php echo ($AdminUrl) ?>/admin-gallery-add">add new gallery</a></div>
            <div class="tbl-cell cell-order"></div>
            <div class="tbl-cell cell-live"></div>
            <div class="tbl-cell cell-edit"></div>
            <div class="tbl-cell cell-del"></div>
        </div>
        
        <ul class="gallerylist">
        
<?php 
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

$result->free();
$db->close();
?>
        
        </ul>
        
        <form name="HiddenForm" method="POST" action="">
			<input name="GalleryID" type="hidden" value="">
			<input name="ActionType" type="hidden" value="">
		</form>
        
    </div>
     
</body>
</html>