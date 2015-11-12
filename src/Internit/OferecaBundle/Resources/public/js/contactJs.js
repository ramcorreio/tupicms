//ADD CLONE
var $collectionHolder = $('.clones');

jQuery(document).ready(function() {

    $collectionHolder.each(function(){
    	$(this).append('<li></li>');
    	$(this).data('index', $(this).find(':input').length);
    });
  
    $('a.more-one').on('click', function(e) {
        e.preventDefault();
        $ulClone = $(this).next('.clones');

        addTagForm($ulClone);
        
    });
});

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#" class="del-one">-</a>');
    $tagFormLi.append($removeFormA);

    $('.del-one').on('click', function(e) {
        e.preventDefault();

        $(this).parent().remove();
    });
}

function addTagForm($collectionHolder) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    
    var $newFormLi = $('<li class="col-md-12 col-sm-12 segura-li"></li>').append(newForm);
    $collectionHolder.find('li').last().before($newFormLi);
    addTagFormDeleteLink($newFormLi);
}

//checando quantos checks estão checkados para desativar ou não a função random no subject
checkAllChecks($('input[type="checkbox"]:checked').length);

$('input[type="checkbox"]').click( function(){
	checkAllChecks($('input[type="checkbox"]:checked').length);

	if ($('select#subject_type_randomStatus').prop('disabled') == true)
	{
		$('select#subject_type_randomStatus').val('inactive');
	}
});

function checkAllChecks(checks)
{
	if(checks > 1)
	{
		$('select#subject_type_randomStatus option[value="active"]').attr('disabled', false);
	} else {
		$('select#subject_type_randomStatus option[value="active"]').attr('disabled', true);
		$('select#subject_type_randomStatus').val('inactive');
	}
}