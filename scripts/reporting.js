$(document).ready(function() {
	update(<?=$tmpl->report_type?>);
});


$( '.item_selects' ).live('change', (function(){
	update(<?=$tmpl->report_type?>);
}));

$( '#add_item_selector' ).click(function(){
	var form = document.getElementById('report_form');
	var sel = document.createElement('select');
	sel.setAttribute('class', 'item_selects');
	var option;
	<?php
		foreach($tmpl->items as $item)
		{
			?>
				option = document.createElement('option');
				option.value = "<?=$tmpl->item_counts[$item->itemid]?>";
				option.id = "<?=$item->name?>";
				option.innerHTML = "<?=$item->name?>";
				sel.options.add(option);
			<?php
		}
	?>
	form.appendChild(sel);
	update(<?=$tmpl->report_type?>);
});

function update(type)
{	
	if(type==0)
	{
		var i = 0;
		var d1 = [];
		var ticks_arr = [];
		$.each($("#report_form > select"), function() { 
			var key = $(this).attr('value');
			var name = $("option:selected", this).attr('id');
			d1.push([i, key]);
			ticks_arr.push([ i + 0.5, name]);
			i++;
		});
		
		$.plot($("#placeholder"), [{data: d1, bars: { show: true }}], {xaxis: {ticks: ticks_arr}});
	}
	else if(type==1)
	{
		var d1 = [];
		var ticks_arr = [[0,"12:00am"],[3,"3:00"],[6,"6:00"], [9,"9:00"], [12, "12:00pm"], [15, "3:00"], [18, "6:00"], [21, "9:00"]];
		<?php
			for($i=0; $i<24; $i++)
			{
				$val = $tmpl->order_times[$i]=='' ? 0 : $tmpl->order_times[$i];
				print "d1.push([$i, $val]);";
			}
		?>
		
		 $.plot($("#hourly_placeholder"), [{data: d1}], {xaxis: {ticks: ticks_arr}});
	}
}