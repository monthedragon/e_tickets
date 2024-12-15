<fieldset>
    <legend>TICKETS</legend>

    <div id='selector'></div>

    <table class='tbl-lead-views' id='tbl-lead-views' style='width:100% !important' >
        <?
        if(count($tickets) <= 0){
            ?>
            <tr>
                <td colspan=10>
                    <center><span class='warning'>no record found</span>
                </td>
            </tr>
        <?
        }else{
            ?>
            <tr>
                <td>ticket id</td>
                <td>priority</td>
                <td>category</td>
                <td>date created</td>
                <td>created by</td> 
                <td>assigned dept.</td> 
                <td>status</td> 
            </tr>
        <?
        }
        $i=1;
        $ctr=0;

        foreach($tickets as $details){

            if($ctr == ITEM_PER_PAGE){
                $i++;
                $ctr=0;
            }
			$day_lapse = compute_day_lapse($details['date_created']);
			$ticket_warning_cls = get_ticket_warning($day_lapse,$details,$user_type);
            ?>
            <tr  class='tr-link-view tr-list selector_selection selector_page-<?=$i?> <?=$ticket_warning_cls;?>' trgt_url='<?=base_url()?>main/view_ticket/<?=$details['id']?>/1'>
				<td valign='top'><?=$details['id']?></td>
				<td valign='top'> 
					<span class='<?=$details['priority'];?>_ticket_alert'><?=$priority_arr[$details['priority']]?></span>
				</td>
				<td valign='top'><?=$category_arr[$details['category']]?></td>
				<td valign='top'><?=date('M. d, Y H:i',strtotime($details['date_created']))?></td>
				<td valign='top'><?=$users[$details['created_by']]?></td>
				<td valign='top'><?=$usertype_arr[$details['send_to']]?></td>
				<td valign='top'><?=(!empty($details['status'])) ? $ticket_status_arr[$details['status']] : 'New'?></td>

            </tr>
            <?
            $ctr++;
        }?>
    </table>

</fieldset>

<script>

    function renderPage(pageNumber,selector)
    { 
        var page="."+selector+"_page-"+pageNumber;
				console.log('page:'+page);
				console.log('selection:'+'.'+selector+'_selection');				
        $('.'+selector+'_selection').hide()
				
        $(page).show()

    }

    function a_link_fx(selector,itemCounts){

        var itemPerAge = Math.round('<?=ITEM_PER_PAGE?>');

        if(itemCounts > itemPerAge)
        {

            renderPage(1,selector);
            $('#'+selector).pagination({
                items: itemCounts,
                itemsOnPage: <?=ITEM_PER_PAGE?>,
                cssStyle: 'compact-theme',
                onPageClick: function(pageNumber){renderPage(pageNumber,selector)}
            });

        }


        //$(".tr-list").mouseover(function(){$(this).find('a.a-link').removeClass('hidden');})
        //$(".tr-list").mouseout(function(){$(this).find('a.a-link').addClass('hidden');})

        $(".tr-list").mouseover(function(){$(this).addClass('tr_highlight');})
        $(".tr-list").mouseout(function(){$(this).removeClass('tr_highlight');})


        $(".tr-link-view").click(function(){
            var url = $(this).attr('trgt_url');
            $.ajax({
                url:url,
                success:function(data){
                    $("#div-contact-list").html(data);
                }
            })

            return false;
        })

    }

    $(document).ready(function(){
        a_link_fx('selector','<?=count($tickets)?>');
       
        $(".a-link-pop").click(function(){
            var url = $(this).prop('href');
            do_modal(url,'div-general-modal','',150,300);

            return false;
        })


    })
</script>