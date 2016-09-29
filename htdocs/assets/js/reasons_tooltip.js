var outage_reasosn = $.map($("#reason-select option"),function(val,i) {
    if($(val).val() != "null")
    	return $(val).val()+ " - " + $(val).attr('description');
});

$("#reason-select").popover({
    trigger:"none",
    html: true,
    title: $("#reason-select").attr("title"),
    content: outage_reasosn.join('<br/>')
});

$("#reason-select").hover(function(event) {
    event.preventDefault();
    event.stopPropagation();
    $("#reason-select").popover('show');
},
function(){
   $('#reason-select').popover('hide');
});
