<?php /* Smarty version 2.3.1, created on 2016-09-19 15:26:49
         compiled from default/user/patient_line.html */ ?>
<?php $this->_load_plugins(array(
array('function', 'assign', 'default/user/patient_line.html', 2, false),
array('modifier', 'truncate', 'default/user/patient_line.html', 2, false),)); ?><?php if (is_numeric ( $this->_tpl_vars['event']['pid'] ) && $this->_tpl_vars['show_patient'] == true): ?>
<?php $this->_plugins['function']['assign'][0](array('var' => "temppatient_name",'value' => $this->_run_mod_handler('truncate', true, $this->_tpl_vars['event']['patient_name'], "$day_td_width", "")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
<?php $this->_plugins['function']['assign'][0](array('var' => "temppatient_name",'value' => $this->_tpl_vars['event']['patient_name']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
	 <a href="../../patient_file/patient_file.php?set_pid=<?php echo $this->_tpl_vars['event']['pid']; ?>
&pid=<?php echo $this->_tpl_vars['event']['pid']; ?>
" target="_top"><?php echo $this->_tpl_vars['temppatient_name']; ?>
</a> 
		<br/>(<?php echo $this->_tpl_vars['event']['patient_dob']; ?>
)
<?php endif; ?>
<?php if (is_numeric ( $this->_tpl_vars['event']['aid'] ) && $this->_tpl_vars['show_provider'] == true): ?>
	provider <strong><?php echo $this->_tpl_vars['event']['provider_name']; ?>
</strong>
<?php endif; ?>
<?php if (is_numeric ( $this->_tpl_vars['event']['pid'] ) && $this->_tpl_vars['show_patient'] == true && $this->_tpl_vars['show_icons'] == true): ?>
	
	
	<a href="<?php echo $this->_tpl_vars['TPL_ROOTDIR']; ?>
/patient_file/patient_file.php?go=encounter&pid=<?php echo $this->_tpl_vars['event']['pid']; ?>
&set_pid=<?php echo $this->_tpl_vars['event']['pid']; ?>
" target="_top"><img align="middle" src="<?php echo $this->_tpl_vars['TPL_IMAGE_PATH']; ?>
/encounter_hotlink.gif" border="0" vspace="0" hspace="0"/></a>
	{php} $demovar = 'demographics_full.php'; {/php}
	<a href="<?php echo $this->_tpl_vars['TPL_ROOTDIR']; ?>
/patient_file/summary/<?php echo $this->_tpl_vars['demovar']; ?>
?pid=<?php echo $this->_tpl_vars['event']['pid']; ?>
&set_pid=<?php echo $this->_tpl_vars['event']['pid']; ?>
" target="Main"><img align="middle" src="<?php echo $this->_tpl_vars['TPL_IMAGE_PATH']; ?>
/demo_hotlink.gif" border="0" vspace="0" hspace="0"/></a>
<?php endif; ?>