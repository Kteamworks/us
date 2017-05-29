<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");
?>
<html>
<head>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 1px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
//$alert="Hello";
$address = "{$GLOBALS['rootdir']}/main/finder/p_dynamic_finder.php";
$encounter = $_GET["encounter"] ? $_GET["encounter"] : $_GET["encounter"];
//$orderid = $_GET["orderid"] ? $_GET["orderid"] : $_GET["orderid"];
$name = $_GET["name"] ? $_GET["name"] : $_GET["name"];
if($name=="OPtoIP")
{
$pc_catid=12;
sqlStatement("UPDATE form_encounter SET pc_catid='".$pc_catid."' WHERE encounter='".$encounter."'");
echo"<script type='text/javascript'>top.restoreSession();window.location='$address';</script>";
}
?>

</body>
</html>