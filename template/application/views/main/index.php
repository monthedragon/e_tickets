<?if(isset($privs[175])){?>
    <form id='frm-search-contact'>
        <table>
            <tr>
                <td>
                    <label for='firstname'>firstname</label>
                    <input type='input' name='firstname'>
                </td>
                <td>
                    <label for='lastname'>lastname</label>
                    <input type='input' name='lastname'>
                </td>
                <td>
                    <label for='lastname'>pd_name</label>
                    <input type='input' name='pd_name'>
                </td>
                <td>
                    <label for='btnSearch'>&nbsp;</label>
                    <input type='submit' value=' search ' name='btnSearch'	id='btnSearch'>
                </td>
                <td>
                    <label for='btnGetnew'>&nbsp;</label>
                    <input type='button' value=' get new record ' name='btnGetnew' id='btnGetnew'>
                </td>
                <?if(isset($privs[193])){?>
                    <td>
                        <input type='checkbox' id='chk-show-inactive' name='show_all'>show all
                    </td>
                <?}?>
            </tr>
        </table>
    </form>
<?}?>
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
					
		$("#div-contact-list").load('<?=base_url();?>main/search_contact/');

		$("#frm-search-contact").submit(function(){
		
			$.ajax({
				url:'<?=base_url();?>main/search_contact/0',
				data:$(this).serialize(),
				type:'POST',
				success:function(data){
					$("#div-contact-list").html(data);
					$("#div-manage").html('');
				}
			})
			return false;
		})
		
		
		$("#btnGetnew").click(function(){
			$.ajax({
				url:'<?=base_url()?>main/get_new_record',
				success:function(data){
					$("#div-contact-list").html(data);
					$("#div-manage").html('');
				}
			})
		})
	})
</script>