function showDialog(id){
        var dialog = $(id).data('dialog');
        dialog.open();
}

function closeDialog(id){
    var dialog = $(id).data('dialog');
    dialog.close();
}

function fillTable(documents) {
    
    var $url = $("#documents-table").attr("data-url").replace("/-1", "");
    var $table = $('<table class="table"></table>');
    thead ='<thead>';
    thead += '<tr class="row cells7">';
    thead += '<th class="cell sortable-column">Name</th>';
    thead += '<th class="cell sortable-column">Datum</th>';
    thead += '<th class="cell sortable-column">Bezeichnung</th>';
    thead += '<th class="cell sortable-column">Von</th>';
    thead += '</tr>';
    thead += '</thead>';
    
    $table.append(thead);
    
    documents.forEach(function(document){
        //var format = document.format === undefined ? '' : document.format;       
        var $td = '<td class="cell"><span class="'+document.thumbs+'"></span> ' + document.name + '.' + document.format + '</td>';
        $td += '<td class="cell">' + document.date + '</td>';
        $td += '<td class="cell">' + document.code + '</td>';
        $td += '<td class="cell">' + document.creator + '</td>';
        $td += '<td class="cell"><span class="edit-document mif-pencil" onclick=\'editDocument(' + document.id + ', \"' + document.name + '\", \"' + document.date + '\", \"' + document.code + '\", \"' + document.creator + '\")\' data-id="' + document.id + '"></span></td>';
        $td += '<td class="cell"><a target="_blank" href="' + $url + '/' + document.id + '"><span class="mif-search"></span></a></td>';
        $td += '<td class="cell"><span class="delete-document mif-bin" onclick=\'deleteDocument(' + document.id + ', \"' + document.name + '\")\' data-id="' + document.id + '"></span></td>';
        
        $newRow = $('<tr class="row cells7"></tr>').append($td);
        
        $table.append($newRow);
        
    });
    
    $("#documents-table").empty();
    $("#documents-table").append($table);
    
}