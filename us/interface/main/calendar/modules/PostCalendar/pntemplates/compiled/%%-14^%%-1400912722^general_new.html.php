<?php /* Smarty version 2.6.2, created on 2017-01-23 11:49:45
         compiled from C:%5Cxampp%5Chtdocs%5Cgreencity_test%5Cinterface%5Cforms%5Cvitals/templates/vitals/general_new.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:\xampp\htdocs\greencity_test\interface\forms\vitals/templates/vitals/general_new.html', 54, false),array('modifier', 'date_format', 'C:\xampp\htdocs\greencity_test\interface\forms\vitals/templates/vitals/general_new.html', 60, false),)), $this); ?>
<html>
<head>
<?php html_header_show();  echo '
<script language="javascript">
function calculateBMI()
{
  var bmi = 0;
  var height = document.vitals.height.value;
  var weight = document.vitals.weight.value;
  if(height > 0 && weight > 0)
  {
    bmi = weight/height/height*10000;
  }
  document.vitals.BMI.value = bmi;
  return bmi;
}
</script>
<style type="text/css" title="mystyles" media="all">
<!--
td {
  font-size: 12pt;
  font-family: helvetica;
}
li {
  font-size: 11pt;
  font-family: helvetica;
  margin-left: 15px;
}
a {
  font-size: 11pt;
  font-family: helvetica;
}
.title {
  font-family: sans-serif;
  font-size: 12pt;
  font-weight: bold;
  text-decoration: none;
  color: #000000;
}

.form_text{
  font-family: sans-serif;
  font-size: 9pt;
  text-decoration: none;
  color: #000000;
}
-->
</style>
'; ?>

</head>

<body bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
">
<p><span class="title"><?php echo smarty_function_xl(array('t' => "Vitals (Metric)"), $this);?>
</span></p>
<form name="vitals" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/interface/forms/vitalsM/save.php"
 onsubmit="return top.restoreSession()">
<table>
  <tr>
    <th align="left"><?php echo smarty_function_xl(array('t' => 'Name'), $this);?>
</th><th align="left"><?php echo smarty_function_xl(array('t' => 'Unit'), $this);?>
</th>
    <th align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['vitals']->get_date())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m/%d/%y") : smarty_modifier_date_format($_tmp, "%m/%d/%y")); ?>
</th>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <th align='right'><?php echo ((is_array($_tmp=$this->_tpl_vars['result']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m/%d/%y") : smarty_modifier_date_format($_tmp, "%m/%d/%y")); ?>
</th>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr>
    <td><?php echo smarty_function_xl(array('t' => 'Weight'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'kg'), $this);?>
</td>
    <td align='right'><input type="text"  size='5'
    name="weight" value="<?php echo $this->_tpl_vars['vitals']->get_weight(); ?>
"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo $this->_tpl_vars['result']['weight']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr>
    <td><?php echo smarty_function_xl(array('t' => 'Height'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'cm'), $this);?>
</td>
    <td align='right'><input type="text" size='5'
      name="height" value="<?php echo $this->_tpl_vars['vitals']->get_height(); ?>
" onchange="calculateBMI();"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo $this->_tpl_vars['result']['height']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr>
    <td><?php echo smarty_function_xl(array('t' => 'BP Systolic'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'mmHg'), $this);?>
</td>
    <td align='right'><input type="text" size='5'
      name="bps" value="<?php echo $this->_tpl_vars['vitals']->get_bps(); ?>
"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo $this->_tpl_vars['result']['bps']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr>
    <td><?php echo smarty_function_xl(array('t' => 'BP Diastolic'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'mmHg'), $this);?>
</td>
    <td align='right'><input type="text" size='5'
      name="bpd" value="<?php echo $this->_tpl_vars['vitals']->get_bpd(); ?>
"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo $this->_tpl_vars['result']['bpd']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr>
    <td><?php echo smarty_function_xl(array('t' => 'Pulse'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'per min'), $this);?>
</td>
    <td align='right'><input type="text" size='5'
      name="pulse" value="<?php echo $this->_tpl_vars['vitals']->get_pulse(); ?>
"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo $this->_tpl_vars['result']['pulse']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr><td><?php echo smarty_function_xl(array('t' => 'Respiration'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'per min'), $this);?>
</td>
    <td align='right'><input type="text" size='5'
      name="respiration" value="<?php echo $this->_tpl_vars['vitals']->get_respiration(); ?>
"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo $this->_tpl_vars['result']['respiration']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr>
    <td><?php echo smarty_function_xl(array('t' => 'Temperature'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'C'), $this);?>
</td>
    <td align='right'><input type="text" size='5'
      name="temperature" value="<?php echo $this->_tpl_vars['vitals']->get_temperature(); ?>
"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo $this->_tpl_vars['result']['temperature']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr>
    <td><?php echo smarty_function_xl(array('t' => 'Temp Location'), $this);?>
</td>
    <td colspan='2' >
      <select name="temp_method"/><option value=""> </option>
      <option value="Oral"              <?php if ($this->_tpl_vars['vitals']->get_temp_method() == 'Oral' || $this->_tpl_vars['vitals']->get_temp_method() == 2): ?> selected<?php endif; ?>><?php echo smarty_function_xl(array('t' => 'Oral'), $this);?>

      <option value="Tympanic Membrane" <?php if ($this->_tpl_vars['vitals']->get_temp_method() == 'Tympanic Membrane' || $this->_tpl_vars['vitals']->get_temp_method() == 1): ?> selected<?php endif; ?>><?php echo smarty_function_xl(array('t' => 'Tympanic Membrane'), $this);?>

      <option value="Rectal"            <?php if ($this->_tpl_vars['vitals']->get_temp_method() == 'Rectal' || $this->_tpl_vars['vitals']->get_temp_method() == 3): ?> selected<?php endif; ?>><?php echo smarty_function_xl(array('t' => 'Rectal'), $this);?>

      <option value="Axillary"          <?php if ($this->_tpl_vars['vitals']->get_temp_method() == 'Axillary' || $this->_tpl_vars['vitals']->get_temp_method() == 4): ?> selected<?php endif; ?>><?php echo smarty_function_xl(array('t' => 'Axillary'), $this);?>

      <option value="Temporal Artery"   <?php if ($this->_tpl_vars['vitals']->get_temp_method() == 'Temporal Artery'): ?> selected<?php endif; ?>><?php echo smarty_function_xl(array('t' => 'Temporal Artery'), $this);?>

      </select>
    </td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td align='right'><?php echo smarty_function_xl(array('t' => $this->_tpl_vars['result']['temp_method']), $this);?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr><td><?php echo smarty_function_xl(array('t' => 'Oxygen Saturation'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => "%"), $this);?>
</td>
    <td align='right'><input type="text" size='5'
      name="oxygen_saturation" value="<?php echo $this->_tpl_vars['vitals']->get_oxygen_saturation(); ?>
"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo $this->_tpl_vars['result']['oxygen_saturation']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr><td><?php echo smarty_function_xl(array('t' => 'Head Circumference'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'cm'), $this);?>
</td>
    <td align='right'><input type="text" size='5'
      name="head_circ" value="<?php echo $this->_tpl_vars['vitals']->get_head_circ(); ?>
"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo $this->_tpl_vars['result']['head_circ']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr><td><?php echo smarty_function_xl(array('t' => 'Waist Circumference'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'cm'), $this);?>
</td>
    <td align='right'><input type="text" size='5'
      name="waist_circ" value="<?php echo $this->_tpl_vars['vitals']->get_waist_circ(); ?>
"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td align='right'><?php echo $this->_tpl_vars['result']['waist_circ']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr><td><?php echo smarty_function_xl(array('t' => 'BMI'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => "%"), $this);?>
</td>
    <td align='right'><input type="text" size='5'
      name="BMI" value="<?php echo $this->_tpl_vars['vitals']->get_BMI(); ?>
"/></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo $this->_tpl_vars['result']['BMI']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?></tr>

  <tr><td><?php echo smarty_function_xl(array('t' => 'BMI Status'), $this);?>
</td><td><?php echo smarty_function_xl(array('t' => 'Type'), $this);?>
</td>
    <td align='right'><?php echo $this->_tpl_vars['vitals']->get_BMI_status(); ?>
</td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td  align='right'><?php echo smarty_function_xl(array('t' => $this->_tpl_vars['result']['BMI_status']), $this);?>
</td>
  <?php endforeach; unset($_from); endif; ?>
  </tr>

  <tr><td><?php echo smarty_function_xl(array('t' => 'Other Notes'), $this);?>
</td>
    <td colspan='2' align='right'><input type="text" size='20'
      name="note" value="<?php echo $this->_tpl_vars['vitals']->get_note(); ?>
" /></td>
  <?php if (count($_from = (array)$this->_tpl_vars['results'])):
    foreach ($_from as $this->_tpl_vars['result']):
?>
    <td align='right'><?php echo $this->_tpl_vars['result']['note']; ?>
</td>
  <?php endforeach; unset($_from); endif; ?></tr>

  <tr>
    <td><input type="submit" name="Submit" value="<?php echo smarty_function_xl(array('t' => 'Save Form'), $this);?>
">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->_tpl_vars['DONT_SAVE_LINK']; ?>
" class="link">[<?php echo smarty_function_xl(array('t' => "Don't Save"), $this);?>
]</a></td>
  </tr>
</table>
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['vitals']->get_id(); ?>
" />
<input type="hidden" name="activity" value="<?php echo $this->_tpl_vars['vitals']->get_activity(); ?>
">
<input type="hidden" name="pid" value="<?php echo $this->_tpl_vars['vitals']->get_pid(); ?>
">
<input type="hidden" name="process" value="true">
</form>
</body>
</html>