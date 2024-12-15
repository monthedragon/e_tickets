<form id='frm-export'>
    <table>
        <tr>
            <td valign=top>
                <label for="start_calldate">start date</label>
                <input name='start_calldate' class='date'>
            </td>
            <td valign=top>
                <label for="end_calldate">end date</label>
                <input name='end_calldate' class='date'>
            </td>

            <td valign=top>
                <label for="agents">user list</label>
                <select multiple name='agents[]' style="height:300px;width:200px;">
                    <?foreach($users as $userid=>$username){?>
                    <option value='<?=$userid?>'><?=$username?>
                        <?}?>
                </select>
            </td> 
            <td valign=top>
                <label for="columns">columns</label>
                <select multiple name='columns[]' style="height:300px;width:200px;">
                    <?foreach($cols as $details){?>
                    <option value='<?=$details?>'><?=$details?>
                        <?}?>
                </select>
            </td> 
						<td valign=top>
							<label for="callresult">callresult</label>
							<?=select('callresult','callresult','required','',null,null,$callresult)?> 
							<div id='div-add-on-result'></div>
							<div id='div-ag-type' style='display:none'>
									<?=select('ag_type','ag_type','required','',null,null,$ag_type)?>
							</div>
						</td> 
            <td colspan =2 valign=bottom>
				include remarks<input type='checkbox' name='include_remarks'>
                <input type='submit' value=' search '>
            </td>		
        </tr> 

</form>
</table>
<script>
    $(function(){
        $('.date').datepicker({'dateFormat':'yy-mm-dd'});

        $('#frm-export').submit(function(){
            window.location = '<?=base_url()?>export/do_export?'+$(this).serialize();
            return false;
        })
				
			$("#callresult").change(function(){
				var val = $(this).val();

							if(val == 'AG'){
									$('#div-ag-type').show();
									$('#ag_type').addClass('required');
									$("#div-add-on-result").html('');
							}else if(val == '<?=CB_TAG?>' || val == '<?=NI_TAG?>'){
									$('#div-ag-type').hide();
									$('#ag_type').removeClass('required');

					$.ajax({
						url:'<?=base_url()?>main/get_sub_callresult/'+val,
						dataType:'json',
						beforeSend:function(){$("#btnSubmit").val('please wait...').prop('disabled',true);},
						error:function(){
							$("#div-add-on-result").html('');
						},
						complete:function(){$("#btnSubmit").removeProp('disabled').val(' update ');},
						success:function(data){ 
								
								//append the select object
								$("#div-add-on-result").html('').append("<select name='sub_callresult' class='required' id='sub-callresult'></select>");
								
								//remove any option generated from the obj
								$('#sub-callresult option').remove(); 
								
								$('#sub-callresult').append("<option value=''>--select--</option>");
								
								$.each(data, function(key,value){  
									
									$('#sub-callresult').append($('<option>', { 
										value: key,
										text : value 
									})); 
									
								})  

								//if callresult then append the callback date and time
								if(val == '<?=CB_TAG?>'){
									$("#div-add-on-result").append("<input type='input' id='callbackdate' name='callbackdate' class='required dateISO' readonly>");
									$("#callbackdate").datepicker({'dateFormat':'yy-mm-dd', 'minDate': 0});
									
									$("#div-add-on-result").append("<input type='input' id='callbacktime' name='callbacktime' class='required datetime' >").mask("99:99:99");
									$("#callbacktime").mask("99:99:99");
								}
						}
					})
					
				}else{
									$('#div-ag-type').hide();
									$('#ag_type').removeClass('required');
									$("#div-add-on-result").html('');
							}

				
				
			})
    })
</script>