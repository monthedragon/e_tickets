<form id='frm-create-ticket'>  
	<table>
	<tr>
		<td width=100px>
			CATEGORY:
		</td>
		<td>
			<?=select('category','category','required','',null,null,$category_arr)?>
		</td>
	</tr>
	<tr>
		<td >
			PRIORITY:
		</td>
		<td>
			<?=select('priority','priority','required','',null,null,$priority_arr)?>
		</td>
	</tr>
	<tr>
		<td >
			REQUEST TO:
		</td>
		<td>
			<?=select('send_to','send_to','required','',null,null,$usertype_arr)?>
		</td>
	</tr>
	<tr>
		<td colspan=2>		
			<label for="request">REQUEST</label>
			<?=textarea('request','request','','','','',20,100)?>
		</td>
	</tr>
	<tr>
		<td colspan=2>		
			<input type='submit' id='btnSubmit' value=' save '> 
		</td>
	</tr>
	</table>
</form>

<script>

	
	$(document).ready(function(){
		$("#frm-create-ticket").submit(function(){
			if($(this).valid()){

				$.ajax({
					url:'<?=base_url()?>main/save_ticket/',
					data:$(this).serialize(),
					type:'POST',
					beforeSend:function(){$("#btnSubmit").val('please wait...').prop('disabled',true)},
					success:function(data){
						$("#btnSubmit").val(' save ').removeProp('disabled');
						alert('Saved!');
						window.location = "<?=base_url();?>main";
						// console.log(data);
					}
				});
			}
			return false;
		});
					
	})

</script>