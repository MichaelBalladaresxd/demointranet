
var otblconscuadcompara;
var varfdesde = '%', varfhasta = '%', varperiodo = '1';

$(document).ready(function() {

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
            url: baseurl+"oi/ctrlprovclie/cconscuadcompara/getcboprovxclie",
            dataType: "JSON",
            async: true,
            data: paramscliente,
            success:function(result)
            {
                $('#cboProveedor').html(result);
            },
            error: function(){
              alert('Error, No se puede autenticar por error :: cboProveedor');
            }
        });
        
        $.ajax({
            type: 'ajax',
            method: 'post',
            url: baseurl+"oi/ctrlprovclie/cconscuadcompara/getcboareaclie",
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
    }else if ($('#rdFechas').prop('checked')){     
        $('#divAnio').hide();
        $('#divMes').hide();
        $('#divDesde').show();
        $('#divHasta').show();
        varfdesde = '';
        varfhasta = '';
        varperiodo = '0';
    } 
});

$("#btnBuscar").click(function (){    
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
        "cclienteprov"  : $('#cboProveedor').val(),
        "area"          : $('#cboareaclie').val(),
    };  

    getListConscuadcompara(parametros);
    
});

getListConscuadcompara = function(param){       
    otblconscuadcompara = $('#tblconscuadcompara').DataTable({
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
            "url"   : baseurl+"oi/ctrlprovclie/cconscuadcompara/getconscuadcompara",
            "type"  : "POST", 
            "data"  : param,     
            dataSrc : ''      
        },
        "columns"	: [
            {data: null, "class": "col-xxs"},
            {data: 'FECHASERVICIO', "class": "col-s"},
            {data: 'PROVEEDOR', "class": "col-m"},
            {data: 'DESTABLE', "class": "col-l"},
            {data: 'LINEA', "class": "col-m"},
            {data: 'AREA', "class": "col-sm"},
            {data: 'PUNTUACIONACTUAL', "class": "dt-body-center col-sm"},
            {data: 'PUNTUACIONANTERIOR', "class": "dt-body-center col-sm"},
            {data: 'PUNTUACIONANTERIOR1', "class": "dt-body-center col-sm"},
            {data: 'PUNTUACIONANTERIOR2', "class": "dt-body-center col-sm"},
            {data: 'PUNTUACIONANTERIOR3', "class": "dt-body-center col-sm"},
        ],  
    });
    // Enumeracion 
    otblconscuadcompara.on( 'order.dt search.dt', function () { 
        otblconscuadcompara.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
        });
    } ).draw(); 
     
};
