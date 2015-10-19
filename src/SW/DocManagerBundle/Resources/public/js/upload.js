var nbinput = 1;
var $collectionHolder;

$(function() {
    
    var $alreadyUsed = $("#alreadyExistVar").html();
    
    if ($alreadyUsed) {
        showDialog("#some-exists-dialog");
    }
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

$("#confirmOverride").click(function(){
    
    $("#upload-block form").submit();
    
});

function changeInputFile(element) {
    
    var lineInput = $(element).parent().parent().parent();
    var filename = $(element).val();
    var n = filename.lastIndexOf('.');
    var format = filename.substring(n + 1);
    
    $('.input-format', lineInput).val(format);
    
    $(element).parent().addClass("bg-green");
    $(element).parent().removeClass("bg-gray");
}

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
    
    var newBlock =$('<div id="lineInput_' + (index+1) + '" class="row cells12 fileInput"></div>');
    
    $('input', $newFormLi).each(function(index, element){
                
        var name = element.name;
        var $block = $('<div class="input-control text full-size"></div>').append(element);
               
        if (contains(name, 'category') || contains(name, 'creator') || contains(name, 'nameAlreadyUsed'))
            return;
        
        if (contains(name, 'initials') || contains(name, 'date')) {
            $block = $('<div class="cell colspan2"></div>').append($block);
            if(contains(name, 'initials')) {
                $('input', $block).val("AM");
            }
            if(contains(name, 'date')) {
                $('input', $block).val($('#sw_docmanagerbundle_uploadsession_documents_0_date').val());
            }
        } else if (contains(name, 'name')) {
            $block = $('<div class="cell colspan4"></div>').append($block);
        } else if (contains(name, 'format') || contains(name, 'code')) {
            $block = $('<div class="cell colspan1"></div>').append($block);
            if(contains(name, 'code')) {
                $('input', $block).val($('#sw_docmanagerbundle_uploadsession_documents_0_code').val());
                $('input', $block).attr("readonly","readonly");
            }            
            if(contains(name, 'format')) {
                $('input', $block).attr("class",'input-format');
            }
        } else if (contains(name, 'file')) {
            $('input', $block).attr("onchange",'changeInputFile(this)');
            $block = $('<div class="input-control full-size input-file-custom button full-size bg-gray fg-white">Datei</div>').append(element);
            $block = $('<div class="cell colspan1"></div>').append($block);
        } else {
            return true;
        }
        
        newBlock.append($block);
        
    });

    var $blockRemove = '<div class="cell colspan1 v-align-middle padding10">';
    $blockRemove += '<button onclick="removeLine(lineInput_' + (index+1) + ')" class="removeInputButton button mini-button cycle-button">-</button>';
    $blockRemove += '</div>';
    newBlock.append($blockRemove);
    newBlock.hide();
    newBlock.appendTo($("#inputBlock")).show("slow");
    
    $("#sw_docmanagerbundle_uploadsession_weiter").prop(
        'disabled', ($("#inputBlock").children().length < 1));
}

function contains(text, chartext) {
    
    return text.indexOf(chartext) > -1;
    
}

function removeLine(id) {
    
    $(id).remove();
    $("#sw_docmanagerbundle_uploadsession_weiter").prop(
            'disabled', ($("#inputBlock").children().length < 1));
    
}