$(document).ready(function() {

});

$( '#update_form' ).click(function(){
	
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
});

$( '#add_item_selector' ).click(function(){
	var form = document.getElementById('report_form');
	var sel = document.createElement('select');
	sel.setAttribute("name", "item_selects");
	sel.setAttribute("class", "item_selects");
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
});
