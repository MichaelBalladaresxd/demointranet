
var otblListinspctrlprov, otblinspeccionprov;
var varfdesde = '%', varfhasta = '%';

$(document).ready(function() {
    $('#tabctrlprov a[href="#tabctrlprov-list-tab"]').attr('class', 'disabled');
    $('#tabctrlprov a[href="#tabctrlprov-det-tab"]').attr('class', 'disabled active');

    $('#tabctrlprov a[href="#tabctrlprov-list-tab"]').not('#store-tab.disabled').click(function(event){
        $('#tabctrlprov a[href="#tabctrlprov-list"]').attr('class', 'active');
        $('#tabctrlprov a[href="#tabctrlprov-det"]').attr('class', '');
        return true;
    });
    $('#tabctrlprov a[href="#tabctrlprov-det-tab"]').not('#bank-tab.disabled').click(function(event){
        $('#tabctrlprov a[href="#tabctrlprov-det"]').attr('class' ,'active');
        $('#tabctrlprov a[href="#tabctrlprov-list"]').attr('class', '');
        return true;
    });
    
    $('#tabctrlprov a[href="#tabctrlprov-list"]').click(function(event){return false;});
    $('#tabctrlprov a[href="#tabctrlprov-det"]').click(function(event){return false;});

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
        url: baseurl+"oi/ctrlprov/cinspctrolprov/getcboclieserv",
        dataType: "JSON",
        async: true,
        success:function(result)
        {
            $('#cboclieserv').html(result);
        },
        error: function(){
            alert('Error, No se puede autenticar por error = cboclieserv');
        }
    });
    /*$.ajax({
        type: 'ajax',
        method: 'post',
        url: baseurl+"oi/ctrlprov/cinspctrolprov/getcboestado",
        dataType: "JSON",
        async: true,
        success:function(result)
        {
            $('#cboestado').html(result);
        },
        error: function(){
            alert('Error, No se puede autenticar por error = cboestado');
        }
    })*/

    //
    $('#frmCreactrlprov').validate({
        rules: {
          cboregClie: {
            required: true,
          },
          cboregprovclie: {
            required: true,
          },
          cboregestable: {
            required: true,
          },
          cboregareaclie: {
            required: true,
          },
        },
        messages: {
          cboregClie: {
            required: "Por Favor escoja un Cliente"
          },
          cboregprovclie: {
            required: "Por Favor escoja un Proveedor"
          },
          cboregestable: {
            required: "Por Favor escoja un Establecimiento"
          },
          cboregareaclie: {
            required: "Por Favor escoja un Area"
          },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        },        
        submitHandler: function (form) {
            const botonEvaluar = $('#mbtnGCreactrl');
            var request = $.ajax({
                url:$('#frmCreactrlprov').attr("action"),
                type:$('#frmCreactrlprov').attr("method"),
                data:$('#frmCreactrlprov').serialize(),
                error: function(){
                    Vtitle = 'Error en Guardar!!!';
                    Vtype = 'error';
                    sweetalert(Vtitle,Vtype); 
                    objPrincipal.liberarBoton(botonEvaluar);
                },
                beforeSend: function() {
                    objPrincipal.botonCargando(botonEvaluar);
                }
            });
            request.done(function( respuesta ) {
                var posts = JSON.parse(respuesta);
                
                $.each(posts, function() {
                    $('#mhdnregIdinsp').val(this.cauditoriainspeccion);
                    
                    Vtitle = 'Inspección Guardada!!!';
                    Vtype = 'success';
                    sweetalert(Vtitle,Vtype); 
                    otblListinspctrlprovv.ajax.reload(null,false);   
                    objPrincipal.liberarBoton(botonEvaluar);    
                });
            });
            return false;
        }
    });
    $('#frmRegInsp').validate({
        rules: {
        },
        messages: {
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        },        
        submitHandler: function (form) {
            const botonEvaluar = $('#btnRegistrar');
            var request = $.ajax({
                url:$('#frmRegInsp').attr("action"),
                type:$('#frmRegInsp').attr("method"),
                data:$('#frmRegInsp').serialize(),
                error: function(){
                    Vtitle = 'Error en Guardar!!!';
                    Vtype = 'error';
                    sweetalert(Vtitle,Vtype); 
                    objPrincipal.liberarBoton(botonEvaluar);
                },
                beforeSend: function() {
                    objPrincipal.botonCargando(botonEvaluar);
                }
            });
            request.done(function( respuesta ) {
                var posts = JSON.parse(respuesta);
                
                $.each(posts, function() {
                    //$('#mhdnregIdinsp').val(this.cauditoriainspeccion);
                    
                    Vtitle = 'Inspección Guardada!!!';
                    Vtype = 'success';
                    sweetalert(Vtitle,Vtype);   
                    objPrincipal.liberarBoton(botonEvaluar);    
                });
            });
            return false;
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

$("#chkFreg").on("change", function () {
    if($("#chkFreg").is(":checked") == true){ 
        $("#txtFIni").prop("disabled",false);
        $("#txtFFin").prop("disabled",false);
        
        varfdesde = '';
        varfhasta = '';
    }else if($("#chkFreg").is(":checked") == false){ 
        $("#txtFIni").prop("disabled",true);
        $("#txtFFin").prop("disabled",true);
        
        varfdesde = '%';
        varfhasta = '%';
    }; 
});

$("#cboclieserv").change(function(){

    var select = document.getElementById("cboclieserv"), //El <select>
    value = select.value, //El valor seleccionado
    text = select.options[select.selectedIndex].innerText;
    document.querySelector('#lblCliente').innerText = text;

    var v_cboclieserv = $('#cboclieserv').val();
    var params = { "ccliente":v_cboclieserv };
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: baseurl+"oi/ctrlprov/cinspctrolprov/getcboprovxclie",
        dataType: "JSON",
        async: true,
        data: params,
        success:function(result){
            $("#cboprovxclie").html(result);  
            $('#cboprovxclie').val('').trigger("change");         
        },
        error: function(){
            alert('Error, No se puede autenticar por error = cboprovxclie');
        }
    });   
    
    /*if(v_cboclieserv != 0){
        $("#btnNuevo").prop("disabled",false);
    }else{
        $("#btnNuevo").prop("disabled",true);
    }*/
});

$("#cboprovxclie").change(function(){

    var v_cboprov = $('#cboprovxclie').val();
    var params = { "cproveedor":v_cboprov };
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: baseurl+"oi/ctrlprov/cinspctrolprov/getcbomaqxprov",
        dataType: "JSON",
        async: true,
        data: params,
        success:function(result){
            $("#cbomaqxprov").html(result);           
        },
        error: function(){
            alert('Error, No se puede autenticar por error = cbomaqxprov');
        }
    });    
});

$("#btnBuscar").click(function(){
    if(varfdesde != '%'){ varfdesde = $('#txtFDesde').val(); }
    if(varfhasta != '%'){ varfhasta = $('#txtFHasta').val(); } 
     
    var groupColumn = 0;   
    otblListinspctrlprov = $('#tblListinspctrlprov').DataTable({  
        'responsive'    : false,
        'bJQueryUI'     : true,
        'scrollY'     	: '400px',
        'scrollX'     	: true, 
        'paging'      	: true,
        'processing'  	: true,     
        'bDestroy'    	: true,
        'AutoWidth'     : true,
        'info'        	: true,
        'filter'      	: true, 
        'ordering'		: false,  
        'stateSave'     : true,
        'ajax'	: {
            "url"   : baseurl+"oi/ctrlprov/cinspctrolprov/getbuscarctrlprov/",
            "type"  : "POST", 
            "data": function ( d ) {
                d.ccliente      = $('#cboclieserv').val();
                d.fdesde        = varfdesde; 
                d.fhasta        = varfhasta;   
                d.cclienteprov  = $('#cboprovxclie').val();
                d.cclientemaq   = $('#cbomaqxprov').val();
                d.inspector     = $('#cusuario').val();
            },     
            dataSrc : ''        
        },
        'columns'	: [
            {"orderable": false, data: 'desc_gral', targets: 0,"visible": false},
            {"orderable": false, data: 'areacli', targets: 1,"visible": false},
            {"orderable": false, data: 'lineaproc', targets: 2,"visible": false},
            {"orderable": false, data: 'periodo', "class" : "col-s", targets: 3},
            {"orderable": false, data: 'destado', "class" : "col-sm", targets: 4},
            {"orderable": false, data: 'finspeccion', "class" : "col-s", targets: 5},
            {"orderable": false, data: 'dinformefinal', "class" : "col-sm", targets: 6},
            {"orderable": false, data: 'resultado', "class" : "col-sm", targets: 7},
            {responsivePriority: 1, "orderable": false, 
                render:function(data, type, row){
                    return '<div>'+
                        '<a title="Checklist" style="cursor:pointer; color:#3c763d;" onClick="javascript:selInspe(\''+row.cauditoriainspeccion+'\',\''+row.finspeccion+'\',\''+row.cchecklist+'\',\''+row.cmodeloinforme+'\',\''+row.fservicio+'\');"><span class="fas fa-th-list fa-2x" aria-hidden="true"> </span> </a>'+
                    '</div>'
                }
            }
        ],  
		"columnDefs": [
            { "targets": [0], "visible": false },
		],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'all'} ).nodes();
            var last = null;
			var grupo, grupo01;
 
            api.column([0], {} ).data().each( function ( ctra, i ) {                
                grupo = api.column(1).data()[i];
                grupo01 = api.column(2).data()[i];
                if ( last !== ctra ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="10" class="subgroup"><strong>'+ctra.toUpperCase()+'</strong></td></tr>'+
                        '<tr class="group"><td colspan="10">Area : '+grupo+'<tab><tab>Linea : '+grupo01+'</td></tr>'
                    ); 
                    last = ctra;
                }
            } );
        } 
    }); 
    otblListinspctrlprov.column( 1 ).visible( false );   
    otblListinspctrlprov.column( 2 ).visible( false );
});

//selInspe = function(cauditoriainspeccion,desc_gral,areacli,lineaproc,cusuarioconsultor,periodo,fservicio,cnorma,csubnorma,cchecklist,cmodeloinforme,cvalornoconformidad,cformulaevaluacion,ccriterioresultado,dcomentario,zctipoestadoservicio,destado,ccliente){
    selInspe = function(cauditoriainspeccion,finspeccion,cchecklist,cmodeloinforme,fservicio){
    $('#tabctrlprov a[href="#tabctrlprov-det"]').tab('show'); 
    
    $('#frmRegInsp').trigger("reset");

    $('#mtxtidinsp').val(cauditoriainspeccion); 
    $('#mhdnAccioninsp').val('A');
    //$('#mhdnccliente').val(ccliente);  

    //iniInspe(cusuarioconsultor,'T',cnorma,csubnorma,cchecklist,ccliente,cvalornoconformidad,cmodeloinforme,cformulaevaluacion,ccriterioresultado);

    /*$('#mtxtinspdatos').val(desc_gral); 
    $('#mtxtinsparea').val('AREÁ : '+areacli); 
    $('#mtxtinsplinea').val('LINEA : '+lineaproc); 
    
    $('#cboinspperiodo').val(periodo);
    $('#mtxtinspcoment').val(dcomentario);
    $('#mhdnzctipoestado').val(zctipoestadoservicio);
    $('#txtinspestado').val(destado);*/

    listChecklist(cauditoriainspeccion,fservicio);
    
    $('#txtFInspeccion').datetimepicker({
        format: 'DD/MM/YYYY',
        daysOfWeekDisabled: [0],
        locale:'es',
        autoclose: true,
        todayBtn: true
    });
    if(fservicio == '01/01/1900'){        
        $('#txtFInsp').val('Sin Fecha');
    }else{
        $('#txtFInsp').val(fservicio);
    }

};
/*
iniInspe = function(cusuarioconsultor,consultsreg,cnorma,csubnorma,cchecklist,ccliente,cvalornoconformidad,cmodeloinforme,cformulaevaluacion,ccriterioresultado){
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: baseurl+"oi/ctrlprov/cinspctrolprov/getcbosistemaip",
        dataType: "JSON",
        async: true,
        success:function(result)
        {
            $('#cboinspsistema').html(result);
            //$('#cboinspsistema').val(cnorma).trigger("change");
            document.getElementById("cboinspsistema").value = cnorma;
        },
        error: function(){
            alert('Error, No se puede autenticar por error = cboinspsistema');
        }
    });
    var paramsrubro = { "cnorma":cnorma};
        $.ajax({
            type: 'ajax',
            method: 'post',
            url: baseurl+"oi/ctrlprov/cinspctrolprov/getcborubroip",
            dataType: "JSON",
            async: true,
            data: paramsrubro,
            success:function(result)
            {
                $('#cboinsprubro').html(result);
                document.getElementById("cboinsprubro").value = csubnorma;
            },
            error: function(){
                alert('Error, No se puede autenticar por error = cboinsprubro');
            }
        }); 
    
    
    var paramschecklist = { 
        "cnorma":cnorma,
        "csubnorma":csubnorma,
        "ccliente":ccliente,
    };
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: baseurl+"oi/ctrlprov/cinspctrolprov/getcbochecklist",
        dataType: "JSON",
        async: true,
        data: paramschecklist,
        success:function(result)
        {
            $('#cboinspcchecklist').html(result);
            document.getElementById("cboinspcchecklist").value = cchecklist;
        },
        error: function(){
            alert('Error, No se puede autenticar por error = cboinspcchecklist');
        }
    });
};
*/
$("#cboinspsistema").change(function(){
    var v_cnorma = $( "#cboinspsistema option:selected").attr("value");
    
    var paramsrubro = { "cnorma":v_cnorma};
        $.ajax({
            type: 'ajax',
            method: 'post',
            url: baseurl+"oi/ctrlprov/cinspctrolprov/getcborubroip",
            dataType: "JSON",
            async: true,
            data: paramsrubro,
            success:function(result)
            {
                $('#cboinsprubro').html(result);
            },
            error: function(){
                alert('Error, No se puede autenticar por error = cboinsprubro');
            }
        });  
});

$("#cboinsprubro").change(function(){
    var v_csubnorma = $( "#cboinsprubro option:selected").attr("value");
    var v_cnorma = $('#cboinspsistema').val();
    var v_ccliente = $('#mhdnccliente').val();
    var paramschecklist = { 
        "cnorma":v_cnorma,
        "csubnorma":v_csubnorma,
        "ccliente":v_ccliente,
    };
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: baseurl+"oi/ctrlprov/cinspctrolprov/getcbochecklist",
        dataType: "JSON",
        async: true,
        data: paramschecklist,
        success:function(result)
        {
            $('#cboinspcchecklist').html(result);
        },
        error: function(){
            alert('Error, No se puede autenticar por error = cboinspcchecklist');
        }
    });
});

$("#cboinspcchecklist").change(function(){
    var v_cchecklist = $( "#cboinspcchecklist option:selected").attr("value");
    var paramsform= { "cchecklist":v_cchecklist};
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: baseurl+"oi/ctrlprov/cinspctrolprov/getcboinspformula",
        dataType: "JSON",
        async: true,
        data: paramsform,
        success:function(result)
        {
            $('#cboinspformula').html(result);
        },
        error: function(){
            alert('Error, No se puede autenticar por error = cboinspformula');
        }
    });
});


$('#btnRetornarLista').click(function(){
    $('#tabctrlprov a[href="#tabctrlprov-list"]').tab('show');  
    $('#btnBuscar').click();
});

listChecklist = function(varcauditoria,varfservicio){
      alert(varcauditoria);
      alert(varfservicio);
    otblinspeccionprov = $('#tblinspeccionprov').DataTable({  
        'responsive'    : false,
        'bJQueryUI'     : true,
        'scrollY'     	: '400px',
        'scrollX'     	: true, 
        'paging'      	: false,
        'processing'  	: true,     
        'bDestroy'    	: true,
        'AutoWidth'     : true,
        'info'        	: true,
        'filter'      	: true, 
        'ordering'		: false,  
        'stateSave'     : true,
        'ajax'	: {
            "url"   : baseurl+"oi/ctrlprov/cinspctrolprov/getlistachecklist/",
            "type"  : "POST", 
            "data": function ( d ) {
                d.cauditoriainspeccion      = varcauditoria;
                d.fservicio        = varfservicio; 
            },     
            dataSrc : ''        
        },
        'columns'	: [
            {"orderable": false, data: 'dnumeradorpadre', targets: 0},
            {"orderable": false, data: 'dnumerador', targets: 1},
            {"orderable": false, data: 'drequisito', targets: 2},
            {"orderable": false, data: 'nvalormaxrequisito', targets: 3},
            {"orderable": false, data: 'nvalorrequisito', targets: 4},
            {"orderable": false, data: 'noconformidad', targets: 5},
            {"orderable": false, data: 'existeHallazgo', targets: 6},
            {"orderable": false, data: 'dhallazgo', targets: 7},
            {responsivePriority: 1, "orderable": false, 
                render:function(data, type, row){
                    if(row.dnumeradorpadre==''){
                         return '<div>'+  
                        '&nbsp; &nbsp;'+
                        '<a href="#" data-original-title="Registrar" data-toggle="modal" data-target="#modalChecklist" onClick="selChecklist(\''+row.idinspecciontienda+'\',\''+row.iditem+'\',\''+row.cchecklist+'\',\''+row.CREQUISITOCHECKLIST+'\',\''+row.idclaseareatienda+'\',\''+row.CVALORREQUISITO+'\',\''+row.DHALLAZGO+'\',\''+row.NVALORREQUISITO+'\');"><i class="fa fa-edit fa-3x" data-original-title="Registrar" data-toggle="tooltip"></i></a>'+
                        
                    '</div>'  
                    }else{
                        return '';
                    }
                    
                }
            }
        ],  
		"columnDefs": [
            { "targets": [0], "visible": true },
		],
    }); 
};

selChecklist = function(idinspecciontienda,iditem,idclaseareatienda){
    console.log('idclaseareatienda', idclaseareatienda)
    console.log('iditem', iditem)
    console.log('idinspecciontienda', idinspecciontienda)
    $("#ModalItemList").modal('show');
}

