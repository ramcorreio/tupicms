var $collectionHolder = $('.clones');

function addTagForm($collectionHolder) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = $(prototype.replace(/__name__/g, index));
    initSlider(newForm.find('.slide'));
    newForm.styleForm();
    $collectionHolder.data('index', index + 1);
    
    return $collectionHolder.find('li').last().before(newForm);
}

function initSlider(seletor)
{
	$(seletor).each(function() {
		
		var value = $(this).text() ? parseInt($(this).text(), 0) : 0;
		
		$(this).prev().html(value + "%");
		$(this).parent().parent().find("input[id$='_valor']").val(value);
		
		$(this).empty().slider({
			value: value,
			range: "min",
			min: 0,
			max: 100,
			slide: function( event, ui ) {
				 $(this).prev().html(ui.value + "%");
				 $(this).parent().parent().find("input[id$='_valor']").val(ui.value);
			}
		});
	});
}

$(document).ready(function(){
	
	initSlider(".slide");
	
	$('#etapa').on('click', '.del-one', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
	});
	
	$('a.more-one').on('click', function(e) {
        e.preventDefault();
        $ulClone = $(this).next('.clones');

        addTagForm($ulClone); 
	});
	
	$collectionHolder.each(function(){
    	$(this).append('<li></li>');
    	$(this).data('index', $(this).find(':input').length);
    });
});

