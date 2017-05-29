<?php

 

$sanitize_all_escapes  = true;
$fake_register_globals = false;

 require_once("../globals.php");
 require_once("$srcdir/acl.inc");
 require_once("$srcdir/options.inc.php");
 require_once("$srcdir/formdata.inc.php");
 require_once("$srcdir/htmlspecialchars.inc.php");

 $alertmsg = '';
 $info_msg = "";
 $tmpl_line_no = 0;



function bucks($amount) {
  if ($amount) {
    $amount = sprintf("%.2f", $amount);
    if ($amount != 0.00) return $amount;
  }
  return '';
}


?>
<html>
<head>
        
        <link rel="stylesheet" href="public/css/default.css" type="text/css">
        <link rel="stylesheet" href="datepicker/public/css/style.css" type="text/css">
		<link type="text/css" rel="stylesheet" href="datepicker/libraries/syntaxhighlighter/public/css/shCoreDefault.css">

<?php html_header_show(); ?>
<title><?php echo $drug_id ? xlt("Edit") : xlt("Add New"); echo ' ' . xlt('Drug'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style>

 



input[class=rgt] { text-align:right }

td { font-size:10pt; }


input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
     text-align:right;
}




</style>



</head>

<body class="body_top">
<?php
// If we are saving, then save and close the window.
// First check for duplicates.




if (($_POST['form_save'] || $_POST['form_delete']) && !$alertmsg) {
  $new_drug = false;
 
   if ($_POST['form_save']) { // saving a new drug
 
			 
			
			 
			  
			  $j=0;
	foreach($_POST['date'] as $selected){
		
		
		 
		$com= $_POST['com'][$j];

		$hb= $_POST['hb'][$j];
		$pallor= $_POST['pallor'][$j];
		$weight= $_POST['weight'][$j];
		$bp= $_POST['bp'][$j];
		$oed= $_POST['oed'][$j];
		$PA= $_POST['PA'][$j];
		$pv= $_POST['pv'][$j];
		
		$exam= $_POST['exam'][$j];
		$advise= $_POST['advise'][$j];
		
	  
		
        if(empty($selected))
			continue;
	
	
	  
//-------------------------------------------------------------------------------//		
		
		 
		
			 $drug_id = sqlInsert("INSERT INTO form_vitals ( " .
    "date,bps,weight,note" .
    
    ") VALUES ( " .
    "'" . $selected       . "', " .
    "'" . $bp          . "', " .
    "'" . $weight          . "', " .
	 "'" .$advise. "' " .
    
    
    ")");
	
	$j++;
		}  
		
	

	
  header('location:treatment.php');
	
  
	
  }
}


?>





<form method='post' name='theform' action=''>
<center>


	

 
 <br><br><br><br><br><br>
 
 <table border='0' width='100%'   id="dataTable" style=" border: 1px solid black;"> 
 <tr>
  <th nowrap>S.No.</th>
  <th nowrap>Date</th>
  <th nowrap>Complaints</th>
   <th nowrap>HB </br>% </br>Unire RE</th>
   
   <th nowrap>Pallor</th>
    <th nowrap>Weight</th>
   <!--<th nowrap>New</br>Medicine</th>-->
   <th nowrap>BP</th>
   <th  nowrap>Oedema </br>Number</th>
  <th  nowrap>PA</th>
  <th  nowrap>PV</th>
  <th  nowrap>Examination Findings</th>
  <th  nowrap>Treatment And Advise </br>Type</th>
  
 </tr>
 
 
 
 <?php
 include_once('dbconnect.php');
 $list = "SELECT * FROM `form_vitals`";
 $rid=mysqli_query($conn,$list);
  $num=mysqli_num_rows($rid);
 $j=1;
   while($result=mysqli_fetch_array($rid)) 

   {  
   ?>
	   <tr>
	   <td>
	    <?php echo $j;  ?>
	   </td>
	   
  <td>
   <input type='text' size="10"  name ='date1[]'  value='<?php echo $result['date'] ?>'  style='width:100%'/>
  </td>
  
    <td>
   <input type='text'  maxlength='80' name='com1[]' value='' style='width:100%'  />
  </td>
  

  <td>
   <input type='text' size="10"  name='hb1[]' maxlength='80' value=''  style='width:100%' />
  </td>
  
  <td>
   <input type='text' size="10"  name='pallor1[]' maxlength='80' value=''  style='width:100%' />
  </td> 
  
  <td>
   <input type='text' size="10"  maxlength='80' name='weight1[]' value='<?php echo $result['weight']; ?>'  style='width:100%' />
  </td>
  
  <td>
   <input type='text' size="10"  maxlength='80' name='bp1[]' value='<?php echo $result['bps']; ?>' style='width:100%'  />
  </td>
  
   <td>
 <input type='text' size="10"  maxlength='80' name='ode1[]' style='width:100%'  />
 </td>
  

  <td>
  <input type='text' size="10" maxlength='80' value='' name='pa1[]' style='width:100%' />
  </td>
  
  
  
  <td>
  <input type='text' size="10"  maxlength='80' value='' name='pv1[]' style='width:100%' />
   
  </td>
  
 
  
  
   <td><textarea name="exam1[]" rows="1" cols="19" value=''></textarea></td>
  
  
   
  
    <td><textarea name="advise1[]" rows="1" cols="20"><?php echo $result['note']; ?></textarea></td>
  
	<td><a href="editrecord.php?id=<?php echo $result['id']; ?>" onclick="return confirm('Do You  Want To Edit This Record');">Edit</a></td>   
	   </tr>
	   
	   
<?php 
$j++;
  } 

 
 
 
 $i=$num;
  while($i<=$num+5) 
  {
  
     
?> 
 
 <tr>
 
   <td>
   <?php echo $i ?>
  </td> 
  



  <td>
   <input type='text' size="10" name='date[]' maxlength='80' value=''  style='width:100%'placeholder='yyyy-mm-dd'pattern='[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])'/>
  </td>
  
    <td>
   <input type='text' name='com[]' maxlength='80' value='' style='width:100%' />
  </td>
  

  <td>
   <input type='text' size="10" name='hb[]' maxlength='80' value=''  style='width:100%'  />
  </td>
  
  <td>
   <input type='text' size="10" name='pallor[]' maxlength='80' value=''  style='width:100%'  />
  </td> 
  
  <td>
   <input type='text' size="10" name='weight[]' maxlength='80' value=''  style='width:100%'  />
  </td>
  
  <td>
   <input type='text' size="10" name='bp[]' maxlength='80'   style='width:100%'  />
  </td>
  
   <td>
 <input type='text' size="10" name='oed[]' maxlength='80'  style='width:100%' />
 </td>
  

  <td>
  <input type='text' size="10" name='PA[]' maxlength='80' value=''  style='width:100%'   />
  </td>
  
  
  
  <td>
  <input type='text' size="10" name='pv[]' maxlength='80' value=''  style='width:100%' />
   
  </td>
  
 
  
   <td><textarea name="exam[]" rows="1" cols="20"></textarea></td>
  
    <td><textarea name="advise[]" rows="1" cols="20"></textarea></td>
  
  
  </tr>
  
  <?php
   

 $i++;  

         
}
 

?>


 
  
  

   </table>
 

<p>
<!--
<--INPUT type="button" value="Add Row" onclick="addRow('dataTable')" /> -->
<input type='submit' name='form_save' value='<?php echo xla('Save'); ?>' />

<?php if (acl_check('admin', 'super')) { ?>
&nbsp;

<?php } ?>

&nbsp;
<input type='button' value='<?php echo xla('Cancel'); ?>' onclick='window.close()' />

</p>

</center>
</form>

       



 
		
		
		

  
   
    <style type="text/css">


.typeahead {
	background-color: #FFFFFF;
}
.typeahead:focus {
	border: 2px solid #0097CF;
}
.tt-query {
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
}
.tt-hint {
	color: #999999;
}
.tt-dropdown-menu {
	background-color: #FFFFFF;
	border: 1px solid rgba(0, 0, 0, 0.2);
	border-radius: 8px;
	box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	margin-top: 12px;
	padding: 8px 0;
	width: 422px;
}
.tt-suggestion {
	font-size: 24px;
	line-height: 24px;
	padding: 3px 20px;
}
.tt-suggestion.tt-is-under-cursor {
	background-color: #0097CF;
	color: #FFFFFF;
}
.tt-suggestion p {
	margin: 0;
}
</style>		
		
		



<div width="100%">		
<script language="JavaScript">



<?php
 if ($alertmsg) {
  echo "alert('" . htmlentities($alertmsg) . "');\n";
 }
?>
</script>
</div>
</body>
</html>
<?php

 

$sanitize_all_escapes  = true;
$fake_register_globals = false;

 require_once("../globals.php");
 require_once("$srcdir/acl.inc");
 require_once("$srcdir/options.inc.php");
 require_once("$srcdir/formdata.inc.php");
 require_once("$srcdir/htmlspecialchars.inc.php");

 $alertmsg = '';
 $info_msg = "";
 $tmpl_line_no = 0;



function bucks($amount) {
  if ($amount) {
    $amount = sprintf("%.2f", $amount);
    if ($amount != 0.00) return $amount;
  }
  return '';
}


?>
<html>
<head>
        
        <link rel="stylesheet" href="public/css/default.css" type="text/css">
        <link rel="stylesheet" href="datepicker/public/css/style.css" type="text/css">
		<link type="text/css" rel="stylesheet" href="datepicker/libraries/syntaxhighlighter/public/css/shCoreDefault.css">

<?php html_header_show(); ?>
<title><?php echo $drug_id ? xlt("Edit") : xlt("Add New"); echo ' ' . xlt('Drug'); ?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style>

 



input[class=rgt] { text-align:right }

td { font-size:10pt; }


input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type="number"] {
    -moz-appearance: textfield;
     text-align:right;
}




</style>



</head>

<body class="body_top">
<?php
// If we are saving, then save and close the window.
// First check for duplicates.




if (($_POST['form_save'] || $_POST['form_delete']) && !$alertmsg) {
  $new_drug = false;
 
   if ($_POST['form_save']) { // saving a new drug
 
			 
			
			 
			  
			  $j=0;
	foreach($_POST['date'] as $selected){
		
		
		 
		$com= $_POST['com'][$j];

		$hb= $_POST['hb'][$j];
		$pallor= $_POST['pallor'][$j];
		$weight= $_POST['weight'][$j];
		$bp= $_POST['bp'][$j];
		$oed= $_POST['oed'][$j];
		$PA= $_POST['PA'][$j];
		$pv= $_POST['pv'][$j];
		
		$exam= $_POST['exam'][$j];
		$advise= $_POST['advise'][$j];
		
	  
		
        if(empty($selected))
			continue;
	
	
	  
//-------------------------------------------------------------------------------//		
		
		 
		
			 $drug_id = sqlInsert("INSERT INTO form_vitals ( " .
    "date,bps,weight,note" .
    
    ") VALUES ( " .
    "'" . $selected       . "', " .
    "'" . $bp          . "', " .
    "'" . $weight          . "', " .
	 "'" .$advise. "' " .
    
    
    ")");
	
	$j++;
		}  
		
	

	
  header('location:treatment.php');
	
  
	
  }
}


?>





<form method='post' name='theform' action=''>
<center>


	

 
 <br><br><br><br><br><br>
 
 <table border='0' width='100%'   id="dataTable" style=" border: 1px solid black;"> 
 <tr>
  <th nowrap>S.No.</th>
  <th nowrap>Date</th>
  <th nowrap>Complaints</th>
   <th nowrap>HB </br>% </br>Unire RE</th>
   
   <th nowrap>Pallor</th>
    <th nowrap>Weight</th>
   <!--<th nowrap>New</br>Medicine</th>-->
   <th nowrap>BP</th>
   <th  nowrap>Oedema </br>Number</th>
  <th  nowrap>PA</th>
  <th  nowrap>PV</th>
  <th  nowrap>Examination Findings</th>
  <th  nowrap>Treatment And Advise </br>Type</th>
  
 </tr>
 
 
 
 <?php
 include_once('dbconnect.php');
 $list = "SELECT * FROM `form_vitals`";
 $rid=mysqli_query($conn,$list);
  $num=mysqli_num_rows($rid);
 $j=1;
   while($result=mysqli_fetch_array($rid)) 

   {  
   ?>
	   <tr>
	   <td>
	    <?php echo $j;  ?>
	   </td>
	   
  <td>
   <input type='text' size="10"  name ='date1[]'  value='<?php echo $result['date'] ?>'  style='width:100%'/>
  </td>
  
    <td>
   <input type='text'  maxlength='80' name='com1[]' value='' style='width:100%'  />
  </td>
  

  <td>
   <input type='text' size="10"  name='hb1[]' maxlength='80' value=''  style='width:100%' />
  </td>
  
  <td>
   <input type='text' size="10"  name='pallor1[]' maxlength='80' value=''  style='width:100%' />
  </td> 
  
  <td>
   <input type='text' size="10"  maxlength='80' name='weight1[]' value='<?php echo $result['weight']; ?>'  style='width:100%' />
  </td>
  
  <td>
   <input type='text' size="10"  maxlength='80' name='bp1[]' value='<?php echo $result['bps']; ?>' style='width:100%'  />
  </td>
  
   <td>
 <input type='text' size="10"  maxlength='80' name='ode1[]' style='width:100%'  />
 </td>
  

  <td>
  <input type='text' size="10" maxlength='80' value='' name='pa1[]' style='width:100%' />
  </td>
  
  
  
  <td>
  <input type='text' size="10"  maxlength='80' value='' name='pv1[]' style='width:100%' />
   
  </td>
  
 
  
  
   <td><textarea name="exam1[]" rows="1" cols="19" value=''></textarea></td>
  
  
   
  
    <td><textarea name="advise1[]" rows="1" cols="20"><?php echo $result['note']; ?></textarea></td>
  
	<td><a href="editrecord.php?id=<?php echo $result['id']; ?>" onclick="return confirm('Do You  Want To Edit This Record');">Edit</a></td>   
	   </tr>
	   
	   
<?php 
$j++;
  } 

 
 
 
 $i=$num;
  while($i<=$num+5) 
  {
  
     
?> 
 
 <tr>
 
   <td>
   <?php echo $i ?>
  </td> 
  



  <td>
   <input type='text' size="10" name='date[]' maxlength='80' value=''  style='width:100%'placeholder='yyyy-mm-dd'pattern='[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])'/>
  </td>
  
    <td>
   <input type='text' name='com[]' maxlength='80' value='' style='width:100%' />
  </td>
  

  <td>
   <input type='text' size="10" name='hb[]' maxlength='80' value=''  style='width:100%'  />
  </td>
  
  <td>
   <input type='text' size="10" name='pallor[]' maxlength='80' value=''  style='width:100%'  />
  </td> 
  
  <td>
   <input type='text' size="10" name='weight[]' maxlength='80' value=''  style='width:100%'  />
  </td>
  
  <td>
   <input type='text' size="10" name='bp[]' maxlength='80'   style='width:100%'  />
  </td>
  
   <td>
 <input type='text' size="10" name='oed[]' maxlength='80'  style='width:100%' />
 </td>
  

  <td>
  <input type='text' size="10" name='PA[]' maxlength='80' value=''  style='width:100%'   />
  </td>
  
  
  
  <td>
  <input type='text' size="10" name='pv[]' maxlength='80' value=''  style='width:100%' />
   
  </td>
  
 
  
   <td><textarea name="exam[]" rows="1" cols="20"></textarea></td>
  
    <td><textarea name="advise[]" rows="1" cols="20"></textarea></td>
  
  
  </tr>
  
  <?php
   

 $i++;  

         
}
 

?>


 
  
  

   </table>
 

<p>
<!--
<--INPUT type="button" value="Add Row" onclick="addRow('dataTable')" /> -->
<input type='submit' name='form_save' value='<?php echo xla('Save'); ?>' />

<?php if (acl_check('admin', 'super')) { ?>
&nbsp;

<?php } ?>

&nbsp;
<input type='button' value='<?php echo xla('Cancel'); ?>' onclick='window.close()' />

</p>

</center>
</form>

       



 
		
		
		

  
   
    <style type="text/css">


.typeahead {
	background-color: #FFFFFF;
}
.typeahead:focus {
	border: 2px solid #0097CF;
}
.tt-query {
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
}
.tt-hint {
	color: #999999;
}
.tt-dropdown-menu {
	background-color: #FFFFFF;
	border: 1px solid rgba(0, 0, 0, 0.2);
	border-radius: 8px;
	box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	margin-top: 12px;
	padding: 8px 0;
	width: 422px;
}
.tt-suggestion {
	font-size: 24px;
	line-height: 24px;
	padding: 3px 20px;
}
.tt-suggestion.tt-is-under-cursor {
	background-color: #0097CF;
	color: #FFFFFF;
}
.tt-suggestion p {
	margin: 0;
}
</style>		
		
		



<div width="100%">		
<script language="JavaScript">



<?php
 if ($alertmsg) {
  echo "alert('" . htmlentities($alertmsg) . "');\n";
 }
?>
</script>
</div>
</body>
</html>
