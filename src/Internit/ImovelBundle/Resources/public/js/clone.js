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

$('.del-one').on('click', function() {
    $(this).parent().prev().prev().parent().remove()
});

function addTagForm($collectionHolder) {
    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    
    var $newFormLi = $('<ul class="col-md-12 col-sm-12 segura-li"></ul>').append(newForm);
    $collectionHolder.find('li').last().before($newFormLi);
    addTagFormDeleteLink($newFormLi);
}