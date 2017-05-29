<?php /* Smarty version 2.3.1, created on 2016-09-19 15:29:47
         compiled from default/user/details.html */ ?>
<?php $this->_load_plugins(array(
array('function', 'fetch', 'default/user/details.html', 7, false),
array('function', 'eval', 'default/user/details.html', 8, false),
array('function', 'assign', 'default/user/details.html', 28, false),
array('function', 'pc_date_format', 'default/user/details.html', 36, false),
array('function', 'pc_url', 'default/user/details.html', 103, false),
array('function', 'pc_form_nav_close', 'default/user/details.html', 115, false),
array('modifier', 'date_format', 'default/user/details.html', 28, false),)); ?>
<?php $this->_config_load("default.conf", null, 'local'); ?>

<?php $this->_config_load("lang.$USER_LANG", null, 'local'); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/header.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->_plugins['function']['fetch'][0](array('file' => "$TPL_STYLE_PATH/details.css",'assign' => "css"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
<?php $this->_plugins['function']['eval'][0](array('var' => $this->_tpl_vars['css']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>


<?php if ($this->_tpl_vars['PRINT_VIEW'] != 1): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/global/details_navigation.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="calcontainer">
	<tr>
		<td class="eventtitle" width="100%" nowrap="nowrap">
			<strong><?php echo $this->_tpl_vars['A_EVENT']['catname']; ?>
</strong>
				<?php if ($this->_tpl_vars['A_EVENT']['patient_name']): ?>
				with patient <strong><?php echo $this->_tpl_vars['A_EVENT']['patient_name']; ?>
</strong> (pid <?php echo $this->_tpl_vars['A_EVENT']['pubpid']; ?>
)
				<?php endif; ?>
			<br /><b><?php echo $this->_tpl_vars['A_EVENT']['title']; ?>
</b>
		</td>
	</tr>
	<tr>
		<td class="eventtime" width="100%" nowrap="nowrap">
			<?php if ($this->_tpl_vars['A_EVENT']['alldayevent'] != true): ?>
				<?php if ($this->_tpl_vars['24HOUR_TIME']): ?>
					<?php $this->_plugins['function']['assign'][0](array('var' => "timestamp",'value' => $this->_run_mod_handler('date_format', true, $this->_tpl_vars['A_EVENT']['startTime'], "%H:%M")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
				<?php else: ?>
					<?php $this->_plugins['function']['assign'][0](array('var' => "timestamp",'value' => $this->_run_mod_handler('date_format', true, $this->_tpl_vars['A_EVENT']['startTime'], "%I:%M %p")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
				<?php endif; ?>
				<?php echo $this->_tpl_vars['timestamp']; ?>

			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['USE_INT_DATES'] == true): ?>
				<?php $this->_plugins['function']['pc_date_format'][0](array('date' => $this->_tpl_vars['A_EVENT']['date'],'format' => "%A, %d %B %Y"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
			<?php else: ?>	
				<?php $this->_plugins['function']['pc_date_format'][0](array('date' => $this->_tpl_vars['A_EVENT']['date'],'format' => "%A, %B %d %Y"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['A_EVENT']['alldayevent']): ?>
				<?php echo $this->_config[0]['vars']['_PC_ALL_DAY']; ?>

			<?php else: ?>
				<?php echo $this->_config[0]['vars']['_PC_DURATION']; ?>
: <?php echo $this->_tpl_vars['A_EVENT']['duration_hours']; ?>
:<?php echo $this->_tpl_vars['A_EVENT']['duration_minutes']; ?>

			<?php endif; ?>
		</td>
	</tr>
</table>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="calcontainer">
	<tr>
		<td valign="top" align="left" width="100%">
			<?php echo $this->_tpl_vars['A_EVENT']['hometext']; ?>

		</td>
		<td valign="top" align="left" nowrap="nowrap">
			<?php if ($this->_tpl_vars['LOCATION_INFO']): ?>
				<p><b><?php echo $this->_config[0]['vars']['_PC_LOCATION']; ?>
:</b><br />
				<?php if ($this->_tpl_vars['A_EVENT']['location'] != ''): ?>
					<?php echo $this->_tpl_vars['A_EVENT']['location']; ?>
<br />
				<?php endif; ?>
				<?php if ($this->_tpl_vars['A_EVENT']['street1'] != ''): ?>
					<?php echo $this->_tpl_vars['A_EVENT']['street1']; ?>
<br />
				<?php endif; ?>
				<?php if ($this->_tpl_vars['A_EVENT']['street2'] != ''): ?>
					<?php echo $this->_tpl_vars['A_EVENT']['street2']; ?>
<br />
				<?php endif; ?>
				<?php if ($this->_tpl_vars['A_EVENT']['city'] != ''): ?>
					<?php echo $this->_tpl_vars['A_EVENT']['city']; ?>

				<?php endif; ?>
				<?php if ($this->_tpl_vars['A_EVENT']['state'] != ''): ?>
					<?php echo $this->_tpl_vars['A_EVENT']['state']; ?>

				<?php endif; ?>
				<?php if ($this->_tpl_vars['A_EVENT']['postal'] != ''): ?>
					<?php echo $this->_tpl_vars['A_EVENT']['postal']; ?>

				<?php endif; ?>
				</p>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['CONTACT_INFO']): ?>
				<p><b><?php echo $this->_config[0]['vars']['_PC_CONTACT']; ?>
:</b><br />
				<?php if ($this->_tpl_vars['A_EVENT']['contname']): ?>
					<?php echo $this->_tpl_vars['A_EVENT']['contname']; ?>
<br />
				<?php endif; ?>
				<?php if ($this->_tpl_vars['A_EVENT']['conttel']): ?>
					<?php echo $this->_tpl_vars['A_EVENT']['conttel']; ?>
<br />
				<?php endif; ?>
				<?php if ($this->_tpl_vars['A_EVENT']['contemail']): ?>
					<a href="mailto:<?php echo $this->_tpl_vars['A_EVENT']['contemail']; ?>
"><?php echo $this->_tpl_vars['A_EVENT']['contemail']; ?>
</a><br />
				<?php endif; ?>
				<?php if ($this->_tpl_vars['A_EVENT']['website']): ?>
					<a href="<?php echo $this->_tpl_vars['A_EVENT']['website']; ?>
" target="_blank"><?php echo $this->_tpl_vars['A_EVENT']['website']; ?>
</a><br />
				<?php endif; ?>
				</p>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['A_EVENT']['fee'] != ''): ?>
				<br />Fee: <?php echo $this->_tpl_vars['A_EVENT']['fee']; ?>

			<?php endif; ?>
		</td>
	</tr>
</table>
<?php if ($this->_tpl_vars['PRINT_VIEW'] != 1): ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="right">
            <a href="<?php $this->_plugins['function']['pc_url'][0](array('action' => "detail",'print' => "true",'eid' => $this->_tpl_vars['A_EVENT']['eid']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>"><?php echo $this->_config[0]['vars']['_PC_THEME_PRINT']; ?>
</a>
        </td>
    </tr>
</table>

<?php if ($this->_tpl_vars['USER_CAN_EDIT']): ?>
	<a href="<?php echo $this->_tpl_vars['USER_EDIT']; ?>
" <?php echo $this->_tpl_vars['USER_TARGET']; ?>
><?php echo $this->_config[0]['vars']['_PC_ADMIN_EDIT']; ?>
</a>
	<a href="<?php echo $this->_tpl_vars['USER_DELETE']; ?>
" <?php echo $this->_tpl_vars['USER_TARGET']; ?>
><?php echo $this->_config[0]['vars']['_PC_ADMIN_DELETE']; ?>
</a>
<?php elseif ($this->_tpl_vars['ACCESS_ADMIN']): ?>
	<a href="<?php echo $this->_tpl_vars['ADMIN_EDIT']; ?>
" <?php echo $this->_tpl_vars['ADMIN_TARGET']; ?>
><?php echo $this->_config[0]['vars']['_PC_ADMIN_EDIT']; ?>
</a>
	<a href="<?php echo $this->_tpl_vars['ADMIN_DELETE']; ?>
" <?php echo $this->_tpl_vars['ADMIN_TARGET']; ?>
><?php echo $this->_config[0]['vars']['_PC_ADMIN_DELETE']; ?>
</a>
<?php endif; ?>
<?php $this->_plugins['function']['pc_form_nav_close'][0](array(), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/footer.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>