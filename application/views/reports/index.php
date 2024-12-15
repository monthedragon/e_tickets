<form id='frm-report'>
	<input type='input' name='startDate' class='date' value='<?php echo date('Y-m-d');?>'>
	<input type='input' name='endDate' class='date' value='<?=date('Y-m-d')?>'>
	
	<!--select name='rpt_presentation'>
		<option value='DAY_R'>By day</option>
		<option value='MONTH_R'>By month</option>
		<option value='YEAR_R'>By year</option>
	</select-->
	
	<input type='submit' value=' search ' id='btnSubmit'>
</form>

<div id='div-report-viewer'></div>

<script>
	$(document).ready(function(){
		$(".date").datepicker({'dateFormat':'yy-mm-dd'});
		
		$("#frm-report").submit(function(){
			window.open('<?=base_url()?>reports/generate_xls_report/?'+$(this).serialize(),'eform');
			
			/*$.ajax({
				url:'<?=base_url()?>reports/generate_report',
				type:'POST',
				data:$(this).serialize(),
				beforeSend:function(){$("#btnSubmit").val('please wait. . .').prop('disabled',true);},
				success:function(data){
					
					$("#btnSubmit").val(' search ').removeProp('disabled');
				}
			})*/
			
			return false;
		})
		
	})
</script>