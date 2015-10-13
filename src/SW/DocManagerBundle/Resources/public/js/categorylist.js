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