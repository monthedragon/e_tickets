<?if(count($callback) > 0){?> 
	<fieldset>
	<legend>Callback Leads</legend>
	<table class='tbl-lead-views'>
		<tr>
			<td> </td>
			<td>firstname</td>
			<td>lastname</td>
			<td>Callback Date</td>
		</tr>
	<?foreach($callback as $detail){?>
		<tr class='tr-list'>
			<td>
			<?if(isset($privs[189])){?>
				<input type='checkbox' <?=($detail['is_active']) ? 'checked' : ''?> class='chk-contact-active'>
			<?}?>
			</td>
			<td><?=$detail['firstname']?></td>
			<td><?=$detail['lastname']?></td>
			<td><?=$detail['callbackdate'] . ' ' . $detail['callbacktime']?></td>
			<?if(isset($privs[174])){?>
				<td class='td-pick'><a href='<?=base_url()?>main/edit/<?=$detail['id']?>' class='a-link '>Pick</a></td>
			<?}?>
		</tr>
	<?}?>
	</table>
	<div id='cb-selector'></div>
	</fieldset> 
	
<script>
	$(document).ready(function(){
		a_link_fx('cb-selector','<?=count($callback)?>');
	})
</script>

<?}?> 
