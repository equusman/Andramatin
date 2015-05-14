<?php
include 'startup.php';
$_PAGE_TITLE = 'Page not found!';
$_PAGE_CLASSES = 'base-page';
include 'forms/header.php';
?>
<h1><a href="#" onclick="history.go(-1); return false;"><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Error <small class="on-right">404</small>
</h1>
Sorry, the page you are looking for is not found.<br/>
<?php include 'forms/footer.php'; ?>