$(function() {
    $("#table-list tbody").sortable({
        stop: function(event, ui) {
    		var array = [];
    		page = 1;
    		
    		$("#table-list tbody").find(":input").each(function(){
    			array.push($(this).val());
    		});

    	    if(getParameterByName('page') != "")
    	    {
        	    page = getParameterByName('page');
    	    }
    		
    		$.ajax({
    			  url: window.location.pathname + '/sort',
    			  data: { 'data': array, 'page' : page }
			}).done(function(data) {
				   if(data.success)
				   {
					   var $div = $('<div class="alerta sucesso">'+data.message+'!</div>');
					   $div.insertAfter('.controles_pagina');
					   setTimeout(function() {
						   $div.remove();
						}, 5000);   
				   }else{
					   var $div = $('<div class="alerta erro">Ocorreu um erro ao reordenar!</div>');
					   $div.insertAfter('.controles_pagina');
    			   }
			});
        }
    });
});

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
};