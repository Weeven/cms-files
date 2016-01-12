<?php
$UserSess = $_SESSION['username'];

$sql = <<<SQL
    SELECT UserPic
    FROM tblUsers
    WHERE UserName= "$UserSess" LIMIT 1
SQL;

if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

while($row = $result->fetch_assoc()){
    $UserPic = $row["UserPic"];
}

$result->free();
?>

<div class="top">
	<div class="welcome">
    	<div class="avatar">
        	<div class="avatar-holder">
    			<img src="<?php echo ($AdminUrl) ?>/<?php echo ($UserPic) ?>" alt="avatar" />
            </div>
        </div>
		<?php  
            echo ("Welcome ". $_SESSION['username']); 
        ?>
    </div>
    <div class="top-btns">
        <a class="grn-btn ml50" href="<?php echo ($AdminUrl) ?>/admin-settings"><span class="left"></span><img src="<?php echo ($AdminUrl) ?>/images/icon-gear.png" />Settings<span class="right"></span></a>
        <a class="grn-btn ml50" href="<?php echo ($AdminUrl) ?>/logout.php"><span class="left"></span><img src="<?php echo ($AdminUrl) ?>/images/icon-logout.png" />Logout<span class="right"></span></a>		
    </div>
    <div class="bar"></div>
</div>