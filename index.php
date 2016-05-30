<?php
include_once (dirname (__FILE__) . '/includes/init.php')
?>

<!DOCTYPE html>
<html>

<?php
include_once (MAIN_PATH . 'static/head.php');
?>

<body>

<?php
include_once (MAIN_PATH . 'content/header.php');

if (file_exists (MAIN_PATH . 'content/' . CONTENT_PAGE . '.php'))
{
	include_once (MAIN_PATH . 'content/' . CONTENT_PAGE . '.php');
}
else
{
	include_once (MAIN_PATH . 'content/404' . '.php');
}

include_once (MAIN_PATH . 'content/footer.php');
include_once (MAIN_PATH . 'static/foot.php');
?>
</body>
</html>
