<?php /* Smarty version 2.3.1, created on 2017-03-24 12:26:21
         compiled from default/admin/submit.html */ ?>
<?php $this->_load_plugins(array(
array('function', 'pc_url', 'default/admin/submit.html', 167, false),
array('function', 'assign', 'default/admin/submit.html', 170, false),)); ?>
<!-- main navigation -->

<?php $this->_config_load("lang.$USER_LANG", null, 'local'); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/header.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/global/small_navigation.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '
<script language =\'Javascript\'>
function populateOffice(select) {
var EventCats = new Array();
 '; ?>
<?php if (count((array)$this->_tpl_vars['category'])):
    foreach ((array)$this->_tpl_vars['category'] as $this->_tpl_vars['cat']):
?><?php echo '
 
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"] = new Array();

EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["name"]="'; ?>
<?php echo $this->_tpl_vars['cat']['name']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["duration"]="'; ?>
<?php echo $this->_tpl_vars['cat']['event_duration']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["repeat"]="'; ?>
<?php echo $this->_tpl_vars['cat']['event_repeat']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["repeat_freq"]="'; ?>
<?php echo $this->_tpl_vars['cat']['event_repeat_freq']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["repeat_freq_type"]="'; ?>
<?php echo $this->_tpl_vars['cat']['event_repeat_freq_type']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["repeat_on_num"]="'; ?>
<?php echo $this->_tpl_vars['cat']['event_repeat_on_num']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["repeat_on_day"]="'; ?>
<?php echo $this->_tpl_vars['cat']['event_repeat_on_day']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["repeat_on_freq"]="'; ?>
<?php echo $this->_tpl_vars['cat']['event_repeat_on_freq']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["recurrspec"]="'; ?>
<?php echo $this->_tpl_vars['cat']['event_recurrspec']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["durationh"]="'; ?>
<?php echo $this->_tpl_vars['cat']['event_durationh']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["durationm"]="'; ?>
<?php echo $this->_tpl_vars['cat']['event_durationm']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["end_date_flag"]="'; ?>
<?php echo $this->_tpl_vars['cat']['end_date_flag']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["end_date_type"]="'; ?>
<?php echo $this->_tpl_vars['cat']['end_date_type']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["end_date_freq"]="'; ?>
<?php echo $this->_tpl_vars['cat']['end_date_freq']; ?>
<?php echo '";
EventCats["value'; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo '"]["all_day"]="'; ?>
<?php echo $this->_tpl_vars['cat']['end_all_day']; ?>
<?php echo '";


 '; ?>
<?php endforeach; endif; ?><?php echo '
 

var that = select.value;
var cat = "value";
 cat += select.value;
document.add_event.event_subject.value = EventCats.eval(cat).name;
document.add_event.event_repeat_freq.value = EventCats.eval(cat).repeat_freq;
document.add_event.event_repeat_on_freq.value = EventCats.eval(cat).repeat_on_freq;

var found_dur_min = 0;
for(var i=0;i < document.add_event.event_dur_minutes.length;i++){
	if(document.add_event.event_dur_minutes.options[i].value == EventCats.eval(cat).durationm){
	found_dur_min = 1;
		document.add_event.event_dur_minutes.options[i].selected =true;
	}
}

if(found_dur_min != 1){
document.add_event.event_dur_minutes.options[document.add_event.event_dur_minutes.options.length++]= new Option(EventCats.eval(cat).durationm,EventCats.eval(cat).durationm,false,true);

}

for(var i=0;i < document.add_event.event_allday.length;i++){
	
	if(document.add_event.event_allday[i].value == EventCats.eval(cat).all_day){
		
		document.add_event.event_allday[i].checked =true;
	}
}

for(var i=0;i < document.add_event.event_repeat.length;i++){
	if(document.add_event.event_repeat[i].value == EventCats.eval(cat).repeat){
		
		document.add_event.event_repeat[i].checked =true;
	}
}

for(var i=0;i < document.add_event.event_endtype.length;i++){
	if(document.add_event.event_endtype[i].value == EventCats.eval(cat).end_date_flag){
		
		document.add_event.event_endtype[i].checked =true;
	}
}

for(var i=0;i < document.add_event.event_repeat_freq_type.length;i++){
	if(document.add_event.event_repeat_freq_type.options[i].value == EventCats.eval(cat).repeat_freq_type){
		
		document.add_event.event_repeat_freq_type.options[i].selected =true;
	}
}
for(var i=0;i < document.add_event.event_repeat_on_num.length;i++){
	if(document.add_event.event_repeat_on_num.options[i].value == EventCats.eval(cat).repeat_on_num){
		
		document.add_event.event_repeat_on_num.options[i].selected =true;
	}
}
for(var i=0;i < document.add_event.event_repeat_on_day.length;i++){
	if(document.add_event.event_repeat_on_day.options[i].value == EventCats.eval(cat).repeat_on_day){
		
		document.add_event.event_repeat_on_day.options[i].selected =true;
	}
}

for(var i=0;i < document.add_event.event_dur_hours.length;i++){
	if(document.add_event.event_dur_hours.options[i].value == EventCats.eval(cat).durationh){
		
		document.add_event.event_dur_hours.options[i].selected =true;
	}
}

date = new Date();
date.setDate(document.add_event.event_startday.options[document.add_event.event_startday.selectedIndex].value);
date.setMonth(document.add_event.event_startmonth.options[document.add_event.event_startmonth.selectedIndex].value -1 );
date.setFullYear(document.add_event.event_startyear.options[document.add_event.event_startyear.selectedIndex].value);

if(EventCats.eval(cat).end_date_flag > 0)
{
	var num = parseInt(EventCats.eval(cat).end_date_freq);
	switch(EventCats.eval(cat).end_date_type)
	{
		case \'0\':
		case \'4\':
			date.setDate(date.getDate() + num);
			break;
		case \'1\':
			date.setDate(date.getDate() + (num * 7));
			break;
		case \'2\':
			date.setMonth(date.getMonth() + num);
			break;
		case \'3\':
			date.setFullYear(date.getFullYear() + num);
			break;
	}
	
	for(var i=0;i < document.add_event.event_endday.length;i++){
		if(document.add_event.event_endday.options[i].value == date.getDate()){
			
			document.add_event.event_endday.options[i].selected =true;
		}
	}
	
	for(var i=0;i < document.add_event.event_endmonth.length;i++){
		if(document.add_event.event_endmonth.options[i].value == (date.getMonth() +1)){
			
			document.add_event.event_endmonth.options[i].selected =true;
		}
	}
	
	for(var i=0;i < document.add_event.event_endyear.length;i++){
		if(document.add_event.event_endyear.options[i].value == date.getFullYear()){
			
			document.add_event.event_endyear.options[i].selected =true;
		}
	}
	
	for(var i=0;i < document.add_event.event_endtype.length;i++){
		if(document.add_event.event_endtype[i].value == EventCats.eval(cat).end_date_flag){
			
			document.add_event.event_endtype[i].checked =true;
		}
	}
}


'; ?>

<?php echo '

}

</script>'; ?>


<form name="add_event" action="<?php $this->_plugins['function']['pc_url'][0](array('action' => "submit"), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>" method="post" enctype="application/x-www-form-urlencoded" onsubmit="return top.restoreSession()">
<table border="0" cellpadding="1" cellspacing="0" bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
" width="100%">
	<?php if ($this->_tpl_vars['double_book'] == 1): ?>
		<?php $this->_plugins['function']['assign'][0](array('var' => "disable",'value' => " readonly "), $this); if($this->_extract) { extract($this->_tpl_vars); $this->_extract=false; } ?>
	<?php endif; ?>
    <tr><td align="left" valign="middle" width="100%">
        <table border="0" cellpadding="2" cellspacing="0" bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR1']; ?>
">

<!-- EVENT INFO ROWS -->
            <tr>
                <th bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
" colspan="2" align="left" valign="middle"><?php echo $this->_tpl_vars['NewEventHeader']; ?>
</th>
            </tr>
            <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR1']; ?>
" align="left" valign="top">
                <?php echo $this->_tpl_vars['EventTitle']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['Required']; ?>
<br />
                <input type="text" name="<?php echo $this->_tpl_vars['InputEventTitle']; ?>
" value="<?php echo $this->_tpl_vars['ValueEventTitle']; ?>
" <?php echo $this->_tpl_vars['disable']; ?>
/><br />
                <?php echo $this->_tpl_vars['DateTimeTitle']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['Required']; ?>
<br />
                <?php echo $this->_tpl_vars['SelectDateTime']; ?>
<br />
                <table border="0" cellpadding="0" cellspacing="1">
                    <tr>
                        <td valign="top" align="left"><input type="radio" name="<?php echo $this->_tpl_vars['InputAllday']; ?>
" value="<?php echo $this->_tpl_vars['ValueAllday']; ?>
" <?php echo $this->_tpl_vars['SelectedAllday']; ?>
 <?php echo $this->_tpl_vars['disable']; ?>
/></td>
                        <td valign="top" align="left"><?php echo $this->_tpl_vars['AlldayEventTitle']; ?>
</td>
                    </tr>
                    <tr>
                        <td valign="top" align="left"><input type="radio" name="<?php echo $this->_tpl_vars['InputTimed']; ?>
" value="<?php echo $this->_tpl_vars['ValueTimed']; ?>
" <?php echo $this->_tpl_vars['SelectedTimed']; ?>
 <?php echo $this->_tpl_vars['disable']; ?>
/></td>
                        <td valign="top" align="left"><?php echo $this->_tpl_vars['TimedEventTitle']; ?>
</td>
                        <td valign="top" align="left"><?php echo $this->_tpl_vars['SelectTimedHours']; ?>
 <?php echo $this->_tpl_vars['SelectTimedMinutes']; ?>
 <?php echo $this->_tpl_vars['SelectTimedAMPM']; ?>
</td>
                    </tr>
                    <tr>
                        <td valign="top" align="left">&nbsp;</td>
                        <td valign="top" align="left"><?php echo $this->_tpl_vars['TimedDurationTitle']; ?>
</td>
                        <td valign="top" align="left">
                        <select name="<?php echo $this->_tpl_vars['InputTimedDurationHours']; ?>" <?php echo $this->_tpl_vars['disable']; ?>><?php if (count((array)$this->_tpl_vars['TimedDurationHours'])):foreach ((array)$this->_tpl_vars['TimedDurationHours'] as $this->_tpl_vars['time']):?><option id="<?php echo $this->_tpl_vars['time']['name']; ?>" name="<?php echo $this->_tpl_vars['time']['name']; ?>" value="<?php echo $this->_tpl_vars['time']['value']; ?>" <?php echo $this->_tpl_vars['time']['selected']; ?>><?php echo $this->_tpl_vars['time']['name']; ?></option><?php endforeach; endif; ?></select>
                        <?php echo $this->_tpl_vars['TimedDurationHoursTitle']; ?>


                        <select name="<?php echo $this->_tpl_vars['InputTimedDurationMinutes']; ?>" <?php echo $this->_tpl_vars['disable']; ?>><?php if (count((array)$this->_tpl_vars['TimedDurationMinutes'])):foreach ((array)$this->_tpl_vars['TimedDurationMinutes'] as $this->_tpl_vars['time']):?><option id="<?php echo $this->_tpl_vars['InputTimedDurationMinutes']; ?><?php echo $this->_tpl_vars['time']['value']; ?>" name="<?php echo $this->_tpl_vars['InputTimedDurationMinutes']; ?><?php echo $this->_tpl_vars['time']['value']; ?>" value="<?php echo $this->_tpl_vars['time']['value']; ?>" <?php echo $this->_tpl_vars['time']['selected']; ?>><?php echo $this->_tpl_vars['time']['name']; ?></option><?php endforeach; endif; ?></select>
                        <?php echo $this->_tpl_vars['TimedDurationMinutesTitle']; ?>

                        </td>
                    </tr>
                </table>
                <?php echo $this->_tpl_vars['EventDescTitle']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
                <textarea name="<?php echo $this->_tpl_vars['InputEventDesc']; ?>
" wrap="virtual" rows="2" cols="40" <?php echo $this->_tpl_vars['disable']; ?>
><?php echo $this->_tpl_vars['ValueEventDesc']; ?>
</textarea>
				</td>

                <td bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR1']; ?>
" align="left" valign="top">
                <?php if ($this->_tpl_vars['displayTopics'] == 1): ?>
                    <?php echo $this->_tpl_vars['EventTopicTitle']; ?>
<br />
                    <select name="<?php echo $this->_tpl_vars['InputEventTopic']; ?>" <?php echo $this->_tpl_vars['disable']; ?>><?php if (count((array)$this->_tpl_vars['topics'])):foreach ((array)$this->_tpl_vars['topics'] as $this->_tpl_vars['topic']):?><option id="<?php echo $this->_tpl_vars['topic']['name']; ?>" name="<?php echo $this->_tpl_vars['topic']['name']; ?>" value="<?php echo $this->_tpl_vars['topic']['value']; ?>" <?php echo $this->_tpl_vars['topic']['selected']; ?>><?php echo $this->_tpl_vars['topic']['name']; ?></option><?php endforeach; endif; ?></select>
                    <br />
                <?php endif; ?>
				<table>
				<tr><td>Patient<br />
				<input type="text"	name="patient_name" size="15" value="<?php echo $this->_tpl_vars['patient_value']; ?>
" readonly >
				<input type="hidden" name="event_pid" value="<?php echo $this->_tpl_vars['event_pid']; ?>
">
				<a href="javascript:{}" onclick="top.restoreSession();var URL='<?php echo $this->_tpl_vars['webroot']; ?>
/controller.php?patient_finder&find&form_id=add_event[\'event_pid\']&form_name=add_event[\'patient_name\']&pid=true'; window.open(URL, 'add_event', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=450,height=400,left = 425,top = 250');">
					<img src="<?php echo $this->_tpl_vars['webroot']; ?>
/images/stock_search-16.png" border="0"</a>
                
				</td><td>&nbsp;</td><td>
				Provider<br />
                <select name="event_userid" <?php echo $this->_tpl_vars['disable']; ?>><?php if (count((array)$this->_tpl_vars['user'])):foreach ((array)$this->_tpl_vars['user'] as $this->_tpl_vars['provider']):?><option value="<?php echo $this->_tpl_vars['provider']['id']; ?>"<?php if ($this->_tpl_vars['event_aid'] == $this->_tpl_vars['provider']['id']): ?>selected<?php elseif ($this->_tpl_vars['ProviderID'] == $this->_tpl_vars['provider']['id'] && empty ( $this->_tpl_vars['event_aid'] )): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['provider']['lname']; ?>, <?php echo $this->_tpl_vars['provider']['fname']; ?></option><?php endforeach; endif; ?></select>
				</td>
				<td>&nbsp;</td>
				<td>
                <?php echo $this->_tpl_vars['EventCategoriesTitle']; ?>
<br />
                <select name="<?php echo $this->_tpl_vars['InputEventCategory']; ?>" <?php echo $this->_tpl_vars['disable']; ?>onChange="populateOffice(this);"><?php if (count((array)$this->_tpl_vars['categories'])):foreach ((array)$this->_tpl_vars['categories'] as $this->_tpl_vars['category']):?><option value="<?php echo $this->_tpl_vars['category']['value']; ?>" <?php echo $this->_tpl_vars['category']['selected']; ?>><?php echo $this->_tpl_vars['category']['name']; ?></option><?php endforeach; endif; ?></select>
				</td></tr></table>
			<hr>
			<table border="0">

            <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR1']; ?>
" colspan="2" align="left" valign="middle" width="100%">
				<?php echo $this->_tpl_vars['EndDateTitle']; ?>
&nbsp;
                <input type="radio" name="<?php echo $this->_tpl_vars['InputEndOn']; ?>
" value="<?php echo $this->_tpl_vars['ValueEndOn']; ?>
" <?php echo $this->_tpl_vars['SelectedEndOn']; ?>
 <?php echo $this->_tpl_vars['disable']; ?>
/>&nbsp;
                <?php echo $this->_tpl_vars['SelectEndDate']; ?>

                &nbsp;
                <input type="radio" name="<?php echo $this->_tpl_vars['InputNoEnd']; ?>
" value="<?php echo $this->_tpl_vars['ValueNoEnd']; ?>
" <?php echo $this->_tpl_vars['SelectedNoEnd']; ?>
 <?php echo $this->_tpl_vars['disable']; ?>
/>&nbsp;
                <?php echo $this->_tpl_vars['NoEndDateTitle']; ?>

				<br />
				<input type="radio" name="<?php echo $this->_tpl_vars['InputNoRepeat']; ?>
" value="<?php echo $this->_tpl_vars['ValueNoRepeat']; ?>
" <?php echo $this->_tpl_vars['SelectedNoRepeat']; ?>
 <?php echo $this->_tpl_vars['disable']; ?>
/>
                <?php echo $this->_tpl_vars['NoRepeatTitle']; ?>
<br />

                <input type="radio" name="<?php echo $this->_tpl_vars['InputRepeat']; ?>
" value="<?php echo $this->_tpl_vars['ValueRepeat']; ?>
" <?php echo $this->_tpl_vars['SelectedRepeat']; ?>
 <?php echo $this->_tpl_vars['disable']; ?>
/>
                <?php echo $this->_tpl_vars['RepeatTitle']; ?>

                <input type="text" name="<?php echo $this->_tpl_vars['InputRepeatFreq']; ?>
" value="<?php echo $this->_tpl_vars['InputRepeatFreqVal']; ?>
" size="4" <?php echo $this->_tpl_vars['disable']; ?>
/>
                <select name="<?php echo $this->_tpl_vars['InputRepeatFreqType']; ?>
" <?php echo $this->_tpl_vars['disable']; ?>
>
                <?php if (count((array)$this->_tpl_vars['repeat_freq_type'])):
    foreach ((array)$this->_tpl_vars['repeat_freq_type'] as $this->_tpl_vars['repeat']):
?>
                    <option id="<?php echo $this->_tpl_vars['InputRepeatFreqType']; ?>
<?php echo $this->_tpl_vars['repeat']['value']; ?>
" name="<?php echo $this->_tpl_vars['InputRepeatFreqType']; ?>
<?php echo $this->_tpl_vars['repeat']['value']; ?>
" value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" <?php echo $this->_tpl_vars['repeat']['selected']; ?>
><?php echo $this->_tpl_vars['repeat']['name']; ?>
</option>
                <?php endforeach; endif; ?>
                </select>
                <br />

                <input type="radio" name="<?php echo $this->_tpl_vars['InputRepeatOn']; ?>
" value="<?php echo $this->_tpl_vars['ValueRepeatOn']; ?>
" <?php echo $this->_tpl_vars['SelectedRepeatOn']; ?>
 <?php echo $this->_tpl_vars['disable']; ?>
/>
                <?php echo $this->_tpl_vars['RepeatOnTitle']; ?>

                <select name="<?php echo $this->_tpl_vars['InputRepeatOnNum']; ?>
" <?php echo $this->_tpl_vars['disable']; ?>
>
                <?php if (count((array)$this->_tpl_vars['repeat_on_num'])):
    foreach ((array)$this->_tpl_vars['repeat_on_num'] as $this->_tpl_vars['repeat']):
?>
                    <option id="<?php echo $this->_tpl_vars['repeat']['name']; ?>
" name="<?php echo $this->_tpl_vars['repeat']['name']; ?>
" value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" <?php echo $this->_tpl_vars['repeat']['selected']; ?>
><?php echo $this->_tpl_vars['repeat']['name']; ?>
</option>
                <?php endforeach; endif; ?>
                </select>
                <select name="<?php echo $this->_tpl_vars['InputRepeatOnDay']; ?>
" <?php echo $this->_tpl_vars['disable']; ?>
>
                <?php if (count((array)$this->_tpl_vars['repeat_on_day'])):
    foreach ((array)$this->_tpl_vars['repeat_on_day'] as $this->_tpl_vars['repeat']):
?>
                    <option id="<?php echo $this->_tpl_vars['repeat']['name']; ?>
" name="<?php echo $this->_tpl_vars['repeat']['name']; ?>
" value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" <?php echo $this->_tpl_vars['repeat']['selected']; ?>
><?php echo $this->_tpl_vars['repeat']['name']; ?>
</option>
                <?php endforeach; endif; ?>
                </select>&nbsp;
                <?php echo $this->_tpl_vars['OfTheMonthTitle']; ?>
&nbsp;
				<input type="text" name="<?php echo $this->_tpl_vars['InputRepeatOnFreq']; ?>
" value="<?php echo $this->_tpl_vars['InputRepeatOnFreqVal']; ?>
" size="4" <?php echo $this->_tpl_vars['disable']; ?>
/>
				<?php echo $this->_tpl_vars['MonthsTitle']; ?>
.
                <br /><br />

				<br />
				<?php echo $this->_tpl_vars['FormSubmit']; ?>

                </td>
            </tr>
        </table>

                </td>
            </tr>
			</table>
<!-- EVENT INFO ROWS -->


     </td></tr>
</table>
<input type="hidden" name="double_book" value="<?php echo $this->_tpl_vars['double_book']; ?>
"/>
<input type="hidden" name="event_sharing" value="1"><!-- default of 1 is sharing type "public" -->
<input type="hidden" name="pc_html_or_text" value="text" selected>


<?php echo $this->_tpl_vars['FormHidden']; ?>



</form>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include("$TPL_NAME/views/footer.html", array());
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>