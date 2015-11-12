$(document).ready(function(){
	$('#acompanhamento-galeria').val(idDefaultGaleria);
	$('#acompanhamento-galeria').parent().styleForm();
	
	$('#acompanhamento-galeria').change(function(){
       var val = $(this).val();
       $('.imgs').find('a[rel="prettyPhoto"]').remove();
       $.ajax({
            type: "POST",
            url: galeria_ajax_call + "?galeria_id=" + val,
            success: function(data) {
            	var div = $('.imgs');
                $.each(data, function(key, value) {
	    			html = '<a href="'+urlImage+'/'+value+'" rel="prettyPhoto"><img src="'+urlImage+'/'+value+'/1"></a>';
	    			div.append(html);
                });
                $("a[rel^='prettyPhoto']").prettyPhoto();
            }
        });
        return false;
    }).trigger("change");

});