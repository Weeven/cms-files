<?php
require('settings.php');
require('check.php');
require_once "../scripts/connect.php";

$GalleryID = ereg_replace("[^0-9]", "", $_GET['GalleryID']);
$PageID = "gallery";

$AddedImages = $_SESSION['image_uploaded'];
$ErrorEmpty = $_SESSION['error_empty'];
$ErrorLarge = $_SESSION['error_large'];
$ErrorType = $_SESSION['error_type'];
$ErrorError = $_SESSION['error_error'];

$listcheck = $_SESSION['listdisplay'];

$sql = <<<SQL
	SELECT *
	FROM tblGalleries
	WHERE galleryID = "$GalleryID"
SQL;

if (!$result = $db->query($sql))
{
die('There was an error running the query [' . $db->error . ']');
$result->free();
$db->close();
}

while($row = $result->fetch_assoc())
{
	$GalleryName = $row["galleryName"];
}
$result->free();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require 'head.php'; ?>
<link href="<?php echo ("$AdminUrl"); ?>/css/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo ("$AdminUrl"); ?>/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo ("$AdminUrl"); ?>/js/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript" src="<?php echo ("$AdminUrl"); ?>/js/facebox.js" ></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('a[rel*=facebox]').facebox({
		loadingImage : '../src/loading.gif',
		closeImage   : '../src/closelabel.png'
	})
})
</script>
<script type="text/javascript">
$(document).ready(function(){ 
	$(function() {
			
			$(".content ul.gallerylist").sortable({ opacity: 0.6, cursor: 'move', update: function() 
				{
					var order = $(this).sortable("serialize") + '&action=updateRecordsListings';
					
					$.get('/cms/admin/ajaxcheck.php', function(data) {
			if( data == "Expired" ) {
				window.location.href = "<?php echo ("$AdminUrl"); ?>/logout.php";
			} else if (data == "Active" ) {
				
					$.post("<?php echo ("$AdminUrl"); ?>/photosort.php?GalID=<?php echo ("$GalleryID"); ?>", order, function(theResponse){
						$(".gallerylist").html(theResponse);
					});
					
					}
 				});
		
				}
			});				  
	});
	
	$(document).ready(function(){
		$('.nav li')
			.hover(function() {
				$(this).find('a:first').addClass("active2");
				$(this).find('ul').stop().show();
			}, function() {
				$(this).find('a:first').removeClass("active2");
				$(this).find('ul').stop().hide();	
		});
	});
	
	$("#fadebtn").click(function() {
		$(".hidethis").fadeOut('slow');
		$(".combover").fadeIn('slow');
	});
	
	
});	
</script>
<script language="JavaScript" type="text/JavaScript">
	function submitForm(ImageID,Action,GalID) {
		$.get('/cms/admin/ajaxcheck.php', function(data) {
			if( data == "Expired" ) {
				window.location.href = "<?php echo ("$AdminUrl"); ?>/logout.php";
			} else if (data == "Active" ) {
				
				var valImageID = ImageID;
				var valAction = Action;
				var valGalleryID = GalID;
				
				
				$.post("<?php echo ("$AdminUrl"); ?>/livephoto.php?ImageID=" + valImageID + "&ActionType=" + valAction + "&GalID=" + valGalleryID + "", function(theResponse){
					$("#recordsArray_" + valImageID).find(".cell-live").html(theResponse);
				});
				
				
				
			}
 		});
	}

	function deletePhoto(ImageID,GalID) {
		$.get('/cms/admin/ajaxcheck.php', function(data) {
			if( data == "Expired" ) {
				window.location.href = "<?php echo ("$AdminUrl"); ?>/logout.php";
			} else if (data == "Active" ) {
				var valImageID = ImageID;
				var valGalleryID = GalID;
				var r=confirm("Are you sure you want to delete this image?");
				if (r==true) {
					var order = $(".content ul.gallerylist").sortable("serialize") + '&action=updateRecordsListings'; 
					$.post("<?php echo ("$AdminUrl"); ?>/delphoto.php?ImageID=" + valImageID + "&GalID=<?php echo ("$GalleryID"); ?>", order, function(theResponse){
						$(".gallerylist").html(theResponse);
					});	
				} else {
					 
				}
			}
 		});
	}
	
	function listDisp(listtype) {
		$.get('/cms/admin/ajaxcheck.php', function(data) {
			if( data == "Expired" ) {
				window.location.href = "<?php echo ("$AdminUrl"); ?>/logout.php";
			} else if (data == "Active" ) {
				var vallisttype = listtype;
				$.post("<?php echo ("$AdminUrl"); ?>/listtype.php?type=" + vallisttype + "", function(theResponse){
					$(".listtype").html(theResponse);
					if (vallisttype=="grid") {
						$(".contentgal").removeClass("contentlist");
						$(".contentgal").addClass("contentgrid");
					} else if (vallisttype=="list") {
						$(".contentgal").removeClass("contentgrid");
						$(".contentgal").addClass("contentlist");
					}
				});
			}
 		});
	}
	
	function viewbtn() {
		var imgVal = $('#uploadImage').val();
		if(!(imgVal==''))
		{
			$('.hidethis').fadeIn();
		} else {
			$('.hidethis').hide();
		}
	}
</script>
</head>

<body>
	
    <?php require 'top.php'; ?>
    <?php require 'left.php'; ?>
     
  	<div class="content contentgal <?php if ($listcheck == "grid") { ?> contentgrid <?php } else if ($listcheck == "list") { ?> contentlist <?php } else { ?> contentgrid <?php }; ?>">
    	
        <div style="float: left; width:100%;">
        <a class="grn-btn-3" href="<?php echo ($AdminUrl) ?>/admin-gallery"><span class="left"></span><img src="<?php echo ($AdminUrl) ?>/images/icon-back.png" />Back<span class="right"></span></a>
        </div>
        
        <div style="float: left; width:690px;">
        <h2 class="az azh2">Gallery - <?php echo ("$GalleryName"); ?></h2>
        <div class="inner az azinner">
        	<h3>Add Images</h3>
        	<div class="inner2 nmb">
            	
				<p>
                	You can uplaod new images using the feature below, multiple files can be uploaded at once but may take longer.
                </p>
               	
                <form class="settings mt10" name="upload" action="<?php echo ($AdminUrl) ?>/galleryphotos2.php" method="post" enctype="multipart/form-data">
                	<ol>
                    	<li>
                        	<label>choose images:</label>
                            <input class="file" id="uploadImage" type="file" name="files[]" onchange="javascript:viewbtn()" multiple>
                		</li>
                        <li class="hidethis">
                        	<label></label>
                            <input name="gid" type="hidden" value="<?php echo $GalleryID; ?>" />
                        	<input class="grn-btn-2 mt10" type="submit" value="Upload Images" id="fadebtn" />
                        </li>
                	</ol>
                </form>
                
                <div class="progress">
                    <div class="loadbar"></div>
                    <div class="loadbar2"></div>
                    <div class="percent">0%</div>
                    <div class="percent2">Processing Image</div>
                </div>
                
                <div id="status"></div>
                
            </div> <!--end inner2-->
        </div> <!--end inner-->      
      	</div>
        
        <div class="gallery-list gallery-list-top tbl-row mt40">
        	<span class="gallery-list-top-left"></span>
            <span class="gallery-list-top-right"></span>
        	<div class="tbl-cell cell-gallery listtype">
            	<?php
					if ($listcheck == "grid") {
				?>
                	<div class="listlink">
						<a href='javascript:void(0);' class='listicon' onclick='javascript:listDisp("list");'>view list</a>
                    </div>
				<?php
					} else if ($listcheck == "list") {
				?>
						<a href='javascript:void(0);' class='gridicon' onclick='javascript:listDisp("grid");'>view grid</a>
				<?php
					} else {
				?>
                	<div class="listlink">
						<a href='javascript:void(0);' class='listicon' onclick='javascript:listDisp("list");'>view list</a>
                    </div>
				<?php
					};
				?>
            </div>
            <div class="tbl-cell cell-order">Order</div>
            <div class="tbl-cell cell-live">Live</div>
            <div class="tbl-cell cell-edit">Edit</div>
            <div class="tbl-cell cell-del">Delete</div>
        </div>         
      	
        <ul class="gallerylist gallerylistop mb60">         
                    
<?php 
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
        	<div class="tbl-cell cell-gallery">No images have been added to this gallery.</div>
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
	                 
}
$result->free();
$db->close();
?>                   
                    
       </ul>             
                    
      	<form name="HiddenForm" method="POST" action="">
			<input name="ImageID" type="hidden" value="">
			<input name="ActionType" type="hidden" value="">
		</form>      
           
    </div> <!--end content-->

	<div class="combover"></div>

<script type="text/javascript" src="<?php echo ("$AdminUrl"); ?>/js/jquery.form.js"></script>
<script>
(function() {

var bar = $('.loadbar');
var percent = $('.percent');
var status = $('#status');

$('form').ajaxForm({
    beforeSend: function() {
		$('.loadbar2').hide();
		$('.loadbar').show();
		$('.percent2').hide();
		$('.percent').show();
		$('.progress').fadeIn();
        status.empty();
        var percentVal = '0%';
        bar.width(percentVal);
        percent.html(percentVal);
    },
    uploadProgress: function(event, position, total, percentComplete) {
        var percentVal = percentComplete + '%';
        bar.width(percentVal);
        percent.html(percentVal);
        //console.log(percentVal, position, total);
    }, 
    complete: function(xhr) {
        status.html(xhr.responseText);
		$('.progress').fadeOut();
		$('.gallerylist').load('<?php echo ("$AdminUrl"); ?>/updatephotolist.php?GalID=<?php echo ("$GalleryID");?>');
    }
}); 

})();       
</script>
    
</body>
</html>