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
    thead += '<tr>';
    thead += '<th class="sortable-column sort-desc">Name</th>';
    thead += '<th class="sortable-column sort-desc">Datum</th>';
    thead += '<th class="sortable-column">Bezeichnung</th>';
    thead += '<th class="sortable-column">Von</th>';
    thead += '</tr>';
    thead += '</thead>';
    
    $table.append(thead);
    
    documents.forEach(function(document){
                
        var $td = '<td><span class="mif-file-pdf"></span> ' + document.name + '</td>';
        $td += '<td>' + document.date + '</td>';
        $td += '<td>' + document.code + '</td>';
        $td += '<td>' + document.creator + '</td>';
        $td += '<td><span class="edit-document mif-pencil" onclick=\'editDocument(' + document.id + ', \"' + document.name + '\", \"' + document.date + '\", \"' + document.code + '\", \"' + document.creator + '\")\' data-id="' + document.id + '"></span></td>';
        $td += '<td><a target="_blank" href="' + $url + '/' + document.id + '"><span class="mif-search"></span></a></td>';
        $td += '<td><span class="delete-document mif-bin" onclick=\'deleteDocument(' + document.id + ', \"' + document.name + '\")\' data-id="' + document.id + '"></span></td>';
        
        $newRow = $('<tr></tr>').append($td);
        
        $table.append($newRow);
        
    });
    
    $("#documents-table").empty();
    $("#documents-table").append($table);
    
}