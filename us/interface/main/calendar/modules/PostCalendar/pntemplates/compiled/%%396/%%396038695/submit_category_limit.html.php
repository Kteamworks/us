<?php /* Smarty version 2.3.1, created on 2017-03-17 13:09:09
         compiled from default/admin/submit_category_limit.html */ ?>
<?php $this->_load_plugins(array(
array('function', 'assign', 'default/admin/submit_category_limit.html', 89, false),
array('modifier', 'date_format', 'default/admin/submit_category_limit.html', 89, false),)); ?>
<!-- main navigation -->

<?php $this->_config_load("lang.$USER_LANG", null, 'local'); ?>
    
<html>
<head></head>
<body bgcolor="<?php echo $this->_tpl_vars['BGCOLOR2']; ?>
"/>
<?php echo $this->_tpl_vars['AdminMenu']; ?>

<form name="limit" action="<?php echo $this->_tpl_vars['action']; ?>
" method="post" enctype="application/x-www-form-urlencoded">
<table border="1" cellpadding="5" cellspacing="0">
			<tr>
				<td>
					<table  width ='%100' border='1'>
						<tr>
							<td colspan ='5'>
								<table width ='%100'>
									<th align="center" ><?php echo $this->_tpl_vars['_PC_NEW_LIMIT_TITLE']; ?>
</th>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table cellspacing='8' cellpadding='2'>
								<tr><td><?php echo $this->_tpl_vars['catTitle']; ?>
</td><td><?php echo $this->_tpl_vars['StartTimeTitle']; ?>
</td><td><?php echo $this->_tpl_vars['EndTimeTitle']; ?>
</td><td><?php echo $this->_tpl_vars['LimitTitle']; ?>
</td></tr>
            						<tr>
            							<td valign="top" align="left">
            								<select name="new<?php echo $this->_tpl_vars['catid']; ?>
">
                								<?php if (count((array)$this->_tpl_vars['categories'])):
    foreach ((array)$this->_tpl_vars['categories'] as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['id']; ?>
">
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                								<?php endforeach; endif; ?>
                							</select>
                						</td>
            							<td valign="top" align="left">
    										<select name="new<?php echo $this->_tpl_vars['starttimeh']; ?>
">
                								<?php if (count((array)$this->_tpl_vars['hour_array'])):
    foreach ((array)$this->_tpl_vars['hour_array'] as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
">
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; ?>
                							</select>:
                							
                							<select name="new<?php echo $this->_tpl_vars['starttimem']; ?>
">
                								<?php if (count((array)$this->_tpl_vars['min_array'])):
    foreach ((array)$this->_tpl_vars['min_array'] as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
">
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; ?>
                							</select>
               							
                						</td>
               							<td valign="top" align="left">                							
                							<select name="new<?php echo $this->_tpl_vars['endtimeh']; ?>
">
                								<?php if (count((array)$this->_tpl_vars['hour_array'])):
    foreach ((array)$this->_tpl_vars['hour_array'] as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
" >
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; ?>
                							</select>:
                							<select name="new<?php echo $this->_tpl_vars['endtimem']; ?>
">
                								<?php if (count((array)$this->_tpl_vars['min_array'])):
    foreach ((array)$this->_tpl_vars['min_array'] as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
">
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; ?>
                							</select>
                					
               							</td>
               							<td align='right'>
               								<input type="text" name="new<?php echo $this->_tpl_vars['InputLimit']; ?>
" value="" size="4" />
               							</td>
                					</tr>
                				</table>
                				
                			</td>
           				</tr>
            		</table>
            		<?php echo $this->_tpl_vars['FormSubmit']; ?>

            	</td>
            </tr>
	</table>
<table border="1" cellpadding="5" cellspacing="0">
	<!--START REPEATION SECTION -->
	
		<?php if (count((array)$this->_tpl_vars['limits'])):
    foreach ((array)$this->_tpl_vars['limits'] as $this->_tpl_vars['limit']):
?>
		<?php $this->_plugins['function']['assign'][0](array('var' => "shour",'value' => $this->_run_mod_handler('date_format', true, $this->_tpl_vars['limit']['startTime'], "%H")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
		<?php $this->_plugins['function']['assign'][0](array('var' => "smin",'value' => $this->_run_mod_handler('date_format', true, $this->_tpl_vars['limit']['startTime'], "%M")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
		<?php $this->_plugins['function']['assign'][0](array('var' => "ehour",'value' => $this->_run_mod_handler('date_format', true, $this->_tpl_vars['limit']['endTime'], "%H")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
		<?php $this->_plugins['function']['assign'][0](array('var' => "emin",'value' => $this->_run_mod_handler('date_format', true, $this->_tpl_vars['limit']['endTime'], "%M")), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
		
		
		<?php if (count((array)$this->_tpl_vars['categories'])):
    foreach ((array)$this->_tpl_vars['categories'] as $this->_tpl_vars['repeat']):
?>
       		
       		<?php if ($this->_tpl_vars['repeat']['id'] == $this->_tpl_vars['limit']['catid']): ?>
       				<?php $this->_plugins['function']['assign'][0](array('var' => "title_color",'value' => $this->_tpl_vars['repeat']['color']), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
       			
       		<?php endif; ?>
        <?php endforeach; endif; ?>	
			<tr>
				<td>
					<table  width ='%100' border='1'>
						<tr>
							<td colspan ='5'>
								<table width ='%100'>
									<th align="center"  bgcolor="<?php echo $this->_tpl_vars['title_color']; ?>
"><?php echo $this->_tpl_vars['_PC_LIMIT_TITLE']; ?>
</th>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
							<input type="checkbox" name="del[]" value="<?php echo $this->_tpl_vars['limit']['limitid']; ?>
"/>
										<?php echo $this->_tpl_vars['_PC_CAT_DELETE']; ?>

							</td>
							<td><?php echo $this->_run_mod_handler('date_format', true, $this->_tpl_vars['limit']['starttime'], "%H"); ?>

								<input type="hidden" name="id[]" value="<?php echo $this->_tpl_vars['limit']['limitid']; ?>
"/>
								<table cellspacing='8' cellpadding='2'>
								<tr><td><?php echo $this->_tpl_vars['catTitle']; ?>
</td><td><?php echo $this->_tpl_vars['StartTimeTitle']; ?>
</td><td><?php echo $this->_tpl_vars['EndTimeTitle']; ?>
</td><td><?php echo $this->_tpl_vars['LimitTitle']; ?>
</td></tr>
            						<tr>
            							<td valign="top" align="left">
            								<select name="<?php echo $this->_tpl_vars['catid']; ?>
[]">
                								<?php if (count((array)$this->_tpl_vars['categories'])):
    foreach ((array)$this->_tpl_vars['categories'] as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['id']; ?>
"
                    									<?php if ($this->_tpl_vars['repeat']['id'] == $this->_tpl_vars['limit']['catid']): ?>
                    										selected
                    									<?php endif; ?>
                    									>
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                								<?php endforeach; endif; ?>
                							</select>
                						</td>
            							<td valign="top" align="left">
            								<select name="<?php echo $this->_tpl_vars['starttimeh']; ?>
[]">
                								<?php if (count((array)$this->_tpl_vars['hour_array'])):
    foreach ((array)$this->_tpl_vars['hour_array'] as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
"
                    									<?php if ($this->_tpl_vars['repeat'] == $this->_tpl_vars['shour']): ?>
                    										selected 
                    									<?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; ?>
                							</select>:
                							
                							<select name="<?php echo $this->_tpl_vars['starttimem']; ?>
[]">
                								<?php if (count((array)$this->_tpl_vars['min_array'])):
    foreach ((array)$this->_tpl_vars['min_array'] as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
"
                    									<?php if ($this->_tpl_vars['repeat'] == $this->_tpl_vars['smin']): ?>
                    										selected 
                    									<?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; ?>
                							</select>
                					
                						</td>
               							<td valign="top" align="left">                							
                							<select name="<?php echo $this->_tpl_vars['endtimeh']; ?>
[]">
                								<?php if (count((array)$this->_tpl_vars['hour_array'])):
    foreach ((array)$this->_tpl_vars['hour_array'] as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
" 
														<?php if ($this->_tpl_vars['repeat'] == $this->_tpl_vars['ehour']): ?>
                    										selected 
                    									<?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; ?>
                							</select>:
                							<select name="<?php echo $this->_tpl_vars['endtimem']; ?>
[]">
                								<?php if (count((array)$this->_tpl_vars['min_array'])):
    foreach ((array)$this->_tpl_vars['min_array'] as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
"
                    									<?php if ($this->_tpl_vars['repeat'] == $this->_tpl_vars['emin']): ?>
                    										selected 
                    									<?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; ?>
                							</select>
                							
               							</td>
               							<td align='right'>
               								<input type="text" name="<?php echo $this->_tpl_vars['InputLimit']; ?>
[]" value="<?php echo $this->_tpl_vars['limit']['limit']; ?>
" size="4" />
               							</td>
                					</tr>
                				</table>
                				
                			</td>
           				</tr>
            		</table>
            		<?php echo $this->_tpl_vars['FormSubmit']; ?>

            	</td>
            </tr>
 		<!-- /REPEATING ROWS -->
		<?php endforeach; endif; ?>
	</table>



<?php echo $this->_tpl_vars['FormHidden']; ?>



</form>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/footer.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>