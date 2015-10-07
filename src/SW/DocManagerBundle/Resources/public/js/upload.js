var nbinput = 1;
var $collectionHolder;

$(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('#inputBlock');

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('.fileInput').length);

    $('#newInputButton').on('click', function(e) {
        
        if (nbinput < 5) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();
            // add a new tag form (see next code block)
            addTagForm($collectionHolder);
        } else {
            showDialog("#limitFileDialog");
        }
    });
});

function addTagForm($collectionHolder) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    var $newFormLi = $('<div></div>').append(newForm);
    
    var newBlock =$('<div class="row cells12 fileInput"></div>');
    
    $('input', $newFormLi).each(function(index, element){
        
        var name = element.name;
        var $block = $('<div class="input-control text full-size"></div>').append(element);
        
        if (contains(name, 'code') || contains(name, 'initials') || contains(name, 'date')) {
            $block = $('<div class="cell colspan2"></div>').append($block);
            if(contains(name, 'code')) {
                $('input', $block).val("DEEC2A");
            }
            if(contains(name, 'initials')) {
                $('input', $block).val("AM");
            }
            if(contains(name, 'date')) {
                $('input', $block).val("18.02.2015");
            }
        }
        
        if (contains(name, 'name')) {
            $block = $('<div class="cell colspan5"></div>').append($block);
        }
        
        if (contains(name, 'file')) {
            $block = $('<div class="input-control full-size input-file-custom button full-size bg-gray fg-white">Datei</div>').append(element);
            $block = $('<div class="cell colspan1"></div>').append($block);
        }
        
        newBlock.append($block);
        
    });

    newBlock.hide();
    newBlock.appendTo($("#inputBlock")).show("slow");
}

function contains(text, chartext) {
    
    return text.indexOf(chartext) > -1;
    
}