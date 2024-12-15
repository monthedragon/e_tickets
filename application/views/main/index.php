
    <form id='frm-search-ticket'>
        <table >
			<tr>
				<td >
                    <label for='btnCreateTicket'>&nbsp;</label>
                    <input type='button' value=' CREATE TICKET ' name='btnCreateTicket' id='btnCreateTicket' onclick = 'window.location = "<?=base_url();?>main/create_ticket"'>
				</td>
			</tr>
            <tr> 			
                <td valign=top>
                    <label for='firstname'>ticket id</label>
					<?=input('ticket_id','ticket_id','input')?>
                </td>
                <td valign=top>
                    <label for='firstname'>category</label>
					<?=select('category','category','required','',null,null,$category_arr)?>
                </td>
                <td  valign=top>
                    <label for='lastname'>priority</label>
					<?=select('priority','priority','required','',null,null,$priority_arr)?>
                </td> 
                <td  valign=top>
					<label >
						<input type='checkbox' name='include_completed' > 
						include completed
					</label>
					
					<label >
						<input type='checkbox' name='include_own_ticket' > 
						include created tickets
					</label>
                </td> 
				<?if($user_type == ADMIN_CODE){?>
                <td  valign=top>
					<label>
						<input type='checkbox' name='all_tickets'>
						all tickets
					</label>
                </td> 
				<?}?>
				
                <td>
                    <label for='btnSearch'>&nbsp;</label>
                    <input type='submit' value=' search ticket ' name='btnSearch'	id='btnSearch'>
                </td>
            </tr>
        </table>
    </form>

<div id='div-contact-list'></div>
<div id='div-general-modal'></div>



<script>
function do_modal(url,objModal,functionCall,height,width){
		$.ajax({
				url:url,
				success:function(data){
					$("#"+objModal).modal({
						containerCss: { height:height,width: width},
						onOpen:function(dialog){  
								dialog.overlay.fadeIn('fast', function () {
									dialog.container.slideDown('slow', function () {
										dialog.data.fadeIn('slow');
									});
								});
							},
							onClose: function(dialog){
									$("#"+objModal).html(''); 
									dialog.container.slideUp('slow', function () { 
									$.modal.close(); // must call this! 
									if(functionCall == 'card')
										getCardDetails();
									else if(functionCall == 'supple')
										getSuppleCards();
									
										
								}); 
						}
					});
					$("#"+objModal).html(data);
				}
				
			})
	}
	
	
	$(document).ready(function(){  
					
		$("#div-contact-list").load('<?=base_url();?>main/search_ticket/');

		$("#frm-search-ticket").submit(function(){
		
			$.ajax({
				url:'<?=base_url();?>main/search_ticket/0',
				data:$(this).serialize(),
				type:'POST',
				success:function(data){
					$("#div-contact-list").html(data);
					$("#div-manage").html('');
				}
			})
			return false;
		})
	})
</script>