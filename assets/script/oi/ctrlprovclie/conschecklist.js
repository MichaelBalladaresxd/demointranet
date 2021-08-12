
var otblconschecklist, otbllistchecklist;
var varfdesde = '%', varfhasta = '%', varperiodo = '1';

$(document).ready(function() {
 
});

$("#btnBuscar").click(function (){    
   
    var parametros = {
        "ccliente"      : $('#hdnCCliente').val(),
    };  

    getListConschecklist(parametros);
    
});

getListConschecklist = function(param){       
    otblconschecklist = $('#tblconschecklist').DataTable({
        "processing"  	: true,
        "bDestroy"    	: true,
        "stateSave"     : true,
        "bJQueryUI"     : true,
        "scrollY"     	: "540px",
        "scrollX"     	: true, 
        'AutoWidth'     : true,
        "paging"      	: false,
        "info"        	: true,
        "filter"      	: true, 
        "ordering"		: false,
        "responsive"    : false,
        "select"        : true,
        "ajax"	: {
            "url"   : baseurl+"oi/ctrlprovclie/cconschecklist/getconschecklist",
            "type"  : "POST", 
            "data"  : param,     
            dataSrc : ''      
        },
        "columns"	: [
            {data: 'cchecklist', "class": "col-xs"},
            {data: 'dchecklist', "class": "col-lm"},
            {data: 'dservicio', "class": "col-m"},
            {data: 'dsistema', "class": "col-m"},
            {data: 'dnorma', "class": "col-sm"},
            {data: 'dsubnorma', "class": "col-m"},
            {data: 'suso', "class": "col-xxs"},
            {data: null,
                render:function(data, type, row){
                    return '<div style="text-align: center;">'+
                        '<a data-toggle="modal" title="Detalle" style="cursor:pointer; color:#3c763d;" data-target="#modalDetalle" onClick="javascript:verDetalle(\''+row.cchecklist+'\',\''+row.dchecklist+'\');"><span class="fas fa-tags fa-2x" aria-hidden="true"> </span> </a>'+
                    '</div>';
                }
            },
        ],  
    }); 
     
    $("#btnexcel").prop("disabled",false);
     
};

verDetalle = function(cchecklist,checklist){
    document.querySelector('#nameChkl').innerText = checklist; 

    $('#hddnmdetcchecklist').val(cchecklist); 
    $('#hddnmdetdchecklist').val(checklist); 
       
    var parametros = {
        "cchecklist"      : cchecklist,
    };  

    getListchecklist(parametros);
};

getListchecklist = function(param){       
    otbllistchecklist = $('#tbllistchecklist').DataTable({
        "processing"  	: true,
        "bDestroy"    	: true,
        "stateSave"     : true,
        "bJQueryUI"     : true,
        "scrollY"     	: "540px",
        "scrollX"     	: true, 
        'AutoWidth'     : true,
        "paging"      	: false,
        "info"        	: true,
        "filter"      	: true, 
        "ordering"		: false,
        "responsive"    : false,
        "select"        : true,
        "ajax"	: {
            "url"   : baseurl+"oi/ctrlprovclie/cconschecklist/getlistchecklist",
            "type"  : "POST", 
            "data"  : param,     
            dataSrc : ''      
        },
        "columns"	: [
            {data: 'DNUMERADOR', "class": "col-xs"},
            {data: 'DREQUISITO', "class": "col-xl"},
            {data: 'valor_maximo', "class": "col-s"},
            {data: 'DNORMATIVA', "class": "col-l"},
        ],  
    }); 
     
    $("#btnexcelDet").prop("disabled",false);
     
};