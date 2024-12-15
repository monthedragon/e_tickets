<fieldset class='fs-info'>
<legend >Ticket Details</legend>
<form id='frm-view-ticket'>  
	<table>
	<tr>
		<td width=100px>
			CATEGORY:
		</td>
		<td>
			<?=$category_arr[$ticket_info['category']]?>
		</td>
	</tr>
	<tr>
		<td >
			PRIORITY:
		</td>
		<td>
			<span class="<?=$ticket_info['priority']?>_ticket_alert">
				<?=$priority_arr[$ticket_info['priority']]?>
			</span>
		</td>
	</tr>
	<tr>
		<td >
			REQUESTED BY:
		</td>
		<td>
			
			<?=$users[$ticket_info['created_by']]?>
		</td>
	</tr>
	<tr>
		<td >
			DATE CREATED:
		</td>
		<td>
			
			<?=date('F d, Y H:i',strtotime($ticket_info['date_created']))?>
		</td>
	</tr>
	<tr>
		<td >
			AGE IN DAY:
		</td>
		<td>
			
			<?=compute_day_lapse($ticket_info['date_created']);?> day/s
		</td>
	</tr>
	<tr>
		<td colspan=2>		
			<div class='div_view_ticket'>
				<?=nl2br($ticket_info['request'])?>
			</div>
		</td>
	</tr>
	</table>
	
	<fieldset class='fs-info'>
	<legend >Remarks</legend>
		<table> 
			<tr >
				<td colspan=5 >
					<label for="callresult">remarks</label>
					<?=textarea('remarks','remarks')?>
				</td>
			</tr>		
			<tr>
				<td valign='top'>
					<label for="ticket_status">Ticket Status:</label>
					<?=select('ticket_status','ticket_status','required',$ticket_info['status'],null,null,$ticket_status)?> 
				</td>
				<td >
					<label for="ticket_status">Assign To:</label>
					<?=select('send_to','send_to',false,'',null,null,$usertype_arr)?>
					
					<label for="ticket_status">Priority:</label>
					<?=select('priority','priority',false,'',null,null,$priority_arr)?>
				</td>
			</tr>
			<tr>
				<td valign=top> 
						<input type='submit' id='btnSubmit' value=' update '> 
						<br><br><br>
						<input type='button' id='btnBack' value=' back ' onclick='window.location = "<?=base_url();?>main";'> 
				</td>
			</tr>
		</table> 
	</fieldset>	
	<br>
	<div id='div-history'></div> 
	
</form>

</fieldset>	
<script>

	
	function getHistory(){
		$.ajax({
			url:'<?=base_url()?>main/get_history/<?=$ticket_info['id']?>',
			beforeSend:function(){$("#div-history").html('please wait...')},
			success:function(data){
				$("#div-history").html(data);
			}
		})
	} 
	
		
	$(document).ready(function(){
		getHistory();		

		$("#frm-view-ticket").submit(function(){
		
            if($(this).valid()){

				$.ajax({
					url:'<?=base_url()?>main/add_ticket_remarks/<?=$ticket_info['id']?>',
					data:$(this).serialize(),
					type:'POST',
					beforeSend:function(){$("#btnSubmit").val('please wait...').prop('disabled',true)},
					success:function(data){
						$("#btnSubmit").val(' update ').removeProp('disabled');
						//getHistory();
						alert('Saved!');
						window.location = "<?=base_url();?>main";
					}
				});
			}
			return false;
		});		
	})

</script>