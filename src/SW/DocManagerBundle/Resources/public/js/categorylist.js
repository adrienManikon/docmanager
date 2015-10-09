var idcategories = [-1, -1, -1, -1];

$(function(){
    
    var documentListDisplayed = false;    
    
    $(".select-tools").each(function(){
        
        $(".rows-select option", this).hide();
        value = $(".header-select select option:first-child", this).val();
        
        $("."+value, this).show();
                
        $(".header-select select", this).change(function(){
            
            value = $( "option:selected", this).val();
            $(".rows-select option", this.parentNode.parentNode).hide();
            $("."+value, this.parentNode.parentNode).show();
            
        });
        
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
        $td += '<td><span class="mif-pencil"></span></td>';
        $td += '<td><span class="mif-search"></span></td>';
        
        $newRow = $('<tr></tr>').append($td);
        
        $table.append($newRow);
        
    });
    
    $("#documents-table").empty();
    $("#documents-table").append($table);
    
}