<?php

require_once ($_SERVER['DOCUMENT_ROOT'].'/test/common/config.php.inc');
ob_start();
?>
<!DOCTYPE html>

<html>
<head >
<meta charset="utf-8"/>
<title>welcome</title>
<link href="<?php echo "//".BASE_URL."style/basic.css"?>" rel="stylesheet" >
<link rel="stylesheet" href="<?php echo "//".BASE_URL."style/blue/jquery-ui.css"?>">
<script><?php
	echo 'var USER_URL="'.USER_URL.'";';
?>

</script>
<script src="<?php echo "//".BASE_URL."scripts/jquery-1.11.2.min.js"?>"> </script>
<script src="<?php echo "//".BASE_URL."style/blue/jquery-ui.js"?>"> </script>
<script src="<?php echo "//".BASE_URL."scripts/basic.js"?>"> </script>
</head>
<body>


<header>
<?php require_once (BASE_URI.'views/header.html.inc') ; ?>

</header>





<div id="main" >
<?php require_once (BASE_URI.'controllers/CentralController.php.inc') ;

?>
</div>


</body>
</html>

<?php ob_end_flush();?>