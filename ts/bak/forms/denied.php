<?php
include '../startup.php';
$_PAGE_TITLE = 'Access Denied';
$_PAGE_CLASSES = 'base-page';
include 'header.php';
?>
<h1><a href="#" onclick="history.go(-1); return false;"><i class="icon-arrow-left-3 fg-darkRed"></i></a>
    Access <small class="on-right">Denied</small>
</h1>
You are not allowed to access this page.<br/>
<?php include 'footer.php'; ?>