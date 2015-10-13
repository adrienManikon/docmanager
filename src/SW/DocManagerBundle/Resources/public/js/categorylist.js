var idcategories = [-1, -1, -1, -1];

$(function(){
        
    $(".select-tools").each(function(){
                
        $(".rows-select", this).change(function(){
            
            position = $(this).attr('position');
            idcategories[position] = $("option:selected",this).val();
            
            showList();
        });
        
        $(".category-radio").click(function(){
            
            position = $(this).attr('position');
            idcategories[position] = $(this).val();
            
            showList();
            
        });
        
    });
    
    var status = $('#status').html();
    
    if (status === 'success') {
        showDialog("#success");
    } else if (status === 'failed'){
        showDialog("#alert");
    }
    
});
    
function showList() {
    $.ajax({
        type: "POST",
        url: "/web/app_dev.php/list",
        dataType: "json",
        data: {
            'idcategories': idcategories
        },
        beforeSend: function() {
            $("#documents-list").hide();
            $("#loading").show();
        },
        success: function(response) {
            console.log(response);
            fillTable(response.documents);
            $("#loading").hide();
            $("#documents-list").show("slow");
        },
        error: function() {
            $("#loading").hide();
        }
    });
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