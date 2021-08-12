
var otblconsseguiaacc, otbldetseguiaacc, otbllistaacc;
var varfdesde = '%', varfhasta = '%', varperiodo = '1';

$(document).ready(function() {
    $('#tabhallazgocalif a[href="#tabhallazgocalif-list-tab"]').attr('class', 'disabled');
    $('#tabhallazgocalif a[href="#tabhallazgocalif-det-tab"]').attr('class', 'disabled active');

    $('#tabhallazgocalif a[href="#tabhallazgocalif-list-tab"]').not('#store-tab.disabled').click(function(event){
        $('#tabhallazgocalif a[href="#tabhallazgocalif-list"]').attr('class', 'active');
        $('#tabhallazgocalif a[href="#tabhallazgocalif-det"]').attr('class', '');
        return true;
    });
    $('#tabhallazgocalif a[href="#tabhallazgocalif-det-tab"]').not('#bank-tab.disabled').click(function(event){
        $('#tabhallazgocalif a[href="#tabhallazgocalif-det"]').attr('class' ,'active');
        $('#tabhallazgocalif a[href="#tabhallazgocalif-list"]').attr('class', '');
        return true;
    });
    
    $('#tabhallazgocalif a[href="#tabhallazgocalif-list"]').click(function(event){return false;});
    $('#tabhallazgocalif a[href="#tabhallazgocalif-det"]').click(function(event){return false;});

    $('#divAnio').show();
    $('#divMes').show();
    $('#divDesde').hide();
    $('#divHasta').hide();

    $('#txtFDesde,#txtFHasta,#txtFInspeccion').datetimepicker({
        format: 'DD/MM/YYYY',
        daysOfWeekDisabled: [0],
        locale:'es',
        autoclose: true,
        todayBtn: true
    });

    fechaActual();

    /*LLENADO DE COMBOS*/         
        $.ajax({
            type: 'ajax',
            method: 'post',
            url: baseurl+"cglobales/getanios",
            dataType: "JSON",
            async: true,
            success:function(result)
            {
                $('#cboAnio').html(result);
            },
            error: function(){
              alert('Error, No se puede autenticar por error :: cboAnio');
            }
        });  

        $.ajax({
            type: 'ajax',
            method: 'post',
            url: baseurl+"cglobales/getmeses",
            dataType: "JSON",
            async: true,
            success:function(result)
            {
                $('#cboMes').html(result);
            },
            error: function(){
              alert('Error, No se puede autenticar por error :: cboMes');
            }
        }); 

        var paramscliente = {"ccliente" : $('#hdnCCliente').val()};
        
        $.ajax({
            type: 'ajax',
            method: 'post',
            url: baseurl+"oi/ctrlprovclie/cconsseguiaacc/getcboareaclie",
            dataType: "JSON",
            async: true,
            data: paramscliente,
            success:function(result)
            {
                $('#cboareaclie').html(result);
            },
            error: function(){
              alert('Error, No se puede autenticar por error :: cboareaclie');
            }
        });
});

fechaActual = function(){
    var fecha = new Date();		
    var fechatring = ("0" + fecha.getDate()).slice(-2) + "/" + ("0"+(fecha.getMonth()+1)).slice(-2) + "/" +fecha.getFullYear() ;

    $('#txtFDesde').datetimepicker('date', moment(fechatring, 'DD/MM/YYYY') );
    $('#txtFHasta').datetimepicker('date', moment(fechatring, 'DD/MM/YYYY') );

};
	
$('#txtFDesde').on('change.datetimepicker',function(e){	
    
    $('#txtFHasta').datetimepicker({
        format: 'DD/MM/YYYY',
        daysOfWeekDisabled: [0],
        locale:'es'
    });	

    var fecha = moment(e.date).format('DD/MM/YYYY');		
    
    $('#txtFHasta').datetimepicker('minDate', fecha);
    $('#txtFHasta').datetimepicker('date', fecha);

});

$('input[type=radio][name=rFbuscar]').change(function() {
    if($('#rdPeriodo').prop('checked')){        
        $('#divAnio').show();
        $('#divMes').show();
        $('#divDesde').hide();
        $('#divHasta').hide();
        varfdesde = '%';
        varfhasta = '%';
        varperiodo = '1';
        $('#hrdbuscar').val('P'); 
    }else if ($('#rdFechas').prop('checked')){     
        $('#divAnio').hide();
        $('#divMes').hide();
        $('#divDesde').show();
        $('#divHasta').show();
        varfdesde = '';
        varfhasta = '';
        varperiodo = '0';
        $('#hrdbuscar').val('F'); 
    } 
});

$("#btnBuscar").click(function (){    
    parametros = paramListConsseguiaacc();
    getListConsseguiaacc(parametros);
    
});

paramListConsseguiaacc = function (){    
    var v_mes, v_anio

    if(varfdesde != '%'){ varfdesde = $('#txtFIni').val(); }
    if(varfhasta != '%'){ varfhasta = $('#txtFFin').val(); } 

    if(varperiodo != '0'){ 
        v_anio = $('#cboAnio').val(); 
        v_mes = $('#cboMes').val(); 
    }else{
        v_anio = 0;
        v_mes = 0;
    } 
     
    var parametros = {
        "ccliente"      : $('#hdnCCliente').val(),
        "anio"          : v_anio,
        "mes"           : v_mes,
        "fini"          : varfdesde,
        "ffin"          : varfhasta,
        "area"          : $('#cboareaclie').val(),
    };  

    return parametros;
    
};

getListConsseguiaacc = function(parametros){       
    otblconsseguiaacc = $('#tblconsseguiaacc').DataTable({
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
            "url"   : baseurl+"oi/ctrlprovclie/cconsseguiaacc/getconsseguiaacc",
            "type"  : "POST", 
            "data"  : parametros,     
            dataSrc : ''      
        },
        "columns"	: [
            {data: null, "class": "col-xxs"},
            {data: 'AREACLIENTE', "class": "col-l"},
            {data: 'NROPROVEEDOR', "class": "dt-body-center col-s"},
            {data: 'TOTALAC', "class": "dt-body-center col-sm"},
            {data: 'ACRECUPERADAS', "class": "dt-body-center col-sm"},
            {data: 'PTOTALACRE', "class": "dt-body-center col-sm"},
        ]
    });
    // Enumeracion 
    otblconsseguiaacc.on( 'order.dt search.dt', function () { 
        otblconsseguiaacc.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
        });
    } ).draw(); 
     
    $("#btnexcel").prop("disabled",false);
};

$('#tblconsseguiaacc tbody').on('dblclick', 'td', function () {
    var tr = $(this).parents('tr');
    var row = otblconsseguiaacc.row(tr);
    var rowData = row.data();
    $('#tabhallazgocalif a[href="#tabhallazgocalif-det"]').tab('show');
    parametros = paramListDetseguiaacc(rowData.CAREACLIENTE);
    getListDetseguiaacc(parametros);
} );

$('#btnRetornarLista').click(function(){
    $('#tabhallazgocalif a[href="#tabhallazgocalif-list"]').tab('show'); 
    $('#btnBuscar').click();
});

paramListDetseguiaacc = function (careacliente){    
    var v_mes, v_anio, v_ccliente

    v_ccliente = $('#hdnCCliente').val()

    if(varfdesde != '%'){ varfdesde = $('#txtFIni').val(); }
    if(varfhasta != '%'){ varfhasta = $('#txtFFin').val(); } 

    if(varperiodo != '0'){ 
        v_anio = $('#cboAnio').val(); 
        v_mes = $('#cboMes').val(); 
    }else{
        v_anio = 0;
        v_mes = 0;
    } 
        
    $('#hddnmdetccliente').val(v_ccliente);     
    $('#hddnmdetanio').val(v_anio); 
    $('#hddnmdetmes').val(v_mes);     
    $('#hddnmdetfini').val(varfdesde);    
    $('#hddnmdetffin').val(varfhasta);   
    $('#hddnmdetarea').val(careacliente);

    var parametros = {
        "ccliente"      : v_ccliente,
        "anio"          : v_anio,
        "mes"           : v_mes,
        "fini"          : varfdesde,
        "ffin"          : varfhasta,
        "area"          : careacliente,
    };  

    return parametros;
    
};

getListDetseguiaacc = function(param){       
    otbldetseguiaacc = $('#tbldetseguiaacc').DataTable({
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
            "url"   : baseurl+"oi/ctrlprovclie/cconsseguiaacc/getdetseguiaacc",
            "type"  : "POST", 
            "data"  : param,     
            dataSrc : ''      
        },
        "columns"	: [
            {data: null, "class": "col-xxs"},
            {data: 'PROVEEDOR', "class": "col-xl"},
            {data: 'LINEACLIENTE', "class": "col-lm"},
            {data: 'NROINFORME', "class": "col-sm"},
            {data: 'RESULTADO', "class": "dt-body-right col-sm"},
            {data: 'FACEPTACORRECTIVA', "class": "dt-body-center col-s"},
            {data: 'ACRECUPERADAS', "class": "dt-body-center col-s"},
            {data: 'ACPORREALIZAR', "class": "dt-body-center col-s"},
            {data: 'TOTALAC', "class": "dt-body-center col-s"},
            {"orderable": false, 
              render:function(data, type, row){ 
                var veraacc, ubicaaacc;
                ubicaaacc = row.DUBICACIONFILESERVERAC   
                
                if(row.DUBICACIONFILESERVERAC == null || ubicaaacc.length == 0 )  {
                    veraacc = ' <a data-toggle="modal" title="Listado" style="cursor:pointer; color:#3c763d;" data-target="#modalAACC" onClick="javascript:listarAACC(\''+row.CAUDITORIAINSPECCION+'\',\''+row.FSERVICIO+'\');"class="btn btn-outline-secondary btn-sm hidden-xs hidden-sm"><span class="far fa-window-maximize" aria-hidden="true"> </span> Listado</a>'+
                        ' &nbsp; &nbsp;'
                } else {
                    veraacc = ' <a title="Archivo" style="cursor:pointer; color:#1646ec;" href="'+baseurl+'FTPfileserver/Archivos/'+row.DUBICACIONFILESERVERAC+'" target="_blank" class="btn btn-outline-secondary btn-sm hidden-xs hidden-sm"><span class="fas fa-download" aria-hidden="true"> </span> Archivo</a>'+
                        ' &nbsp; &nbsp;'
                }

                return  '<div>'+veraacc+
                  '</div>'  
              }
            },
        ]
    });
    // Enumeracion 
    otbldetseguiaacc.on( 'order.dt search.dt', function () { 
        otbldetseguiaacc.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
        });
    } ).draw(); 
     
    $("#btnexcelDet").prop("disabled",false);
     
};

listarAACC = function (cauditoriainspeccion, fservicio){    
    parametros = paramListaacc(cauditoriainspeccion, fservicio);
    getListaacc(parametros);
}

paramListaacc = function (cauditoriainspeccion, fservicio){    
         
    var parametros = {
        "CAUDITORIAINSPECCION" : cauditoriainspeccion,
        "FSERVICIO"          : fservicio,
    };  

    return parametros;
    
};

getListaacc = function(param){       
    otbllistaacc = $('#tbllistaacc').DataTable({
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
            "url"   : baseurl+"oi/ctrlprovclie/cconsseguiaacc/getaacc",
            "type"  : "POST", 
            "data"  : param,     
            dataSrc : ''      
        },
        "columns"	: [
            {data: 'dnumerador', "class": "col-s"},
            {data: 'drequisito', "class": "col-lm"},
            {data: 'sexcluyente', "class": "col-xs"},
            {data: 'tipohallazgo', "class": "col-sm"},
            {data: 'dhallazgotext', "class": "col-lm"},
            {data: 'daccioncorrectiva', "class": "col-lm"},
            {data: 'dresponsablecliente', "class": "col-sm"},
            {data: 'fcorrectiva', "class": "col-s"},
            {data: 'saceptaraccioncorrectiva', "class": "col-xs"},
            {data: 'dobservacion', "class": "col-xm"},
            {data: 'svalor', "class": "col-xs"},
        ]
    });
    // Enumeracion 
    otbldetseguiaacc.on( 'order.dt search.dt', function () { 
        otbldetseguiaacc.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
        });
    } ).draw(); 
     
};