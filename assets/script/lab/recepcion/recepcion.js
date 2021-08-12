
var otblListRecepcion, otblListdetrecepcion, otblListProducto, otblListEtiquetasmuestras, otblBlancoviajero;
var varfdesde = '%', varfhasta = '%';


$(document).ready(function() {
    $('#tablab a[href="#tablab-list-tab"]').attr('class', 'disabled');
    $('#tablab a[href="#tablab-reg-tab"]').attr('class', 'disabled active');

    $('#tablab a[href="#tablab-list-tab"]').not('#store-tab.disabled').click(function(event){
        $('#tablab a[href="#tablab-list"]').attr('class', 'active');
        $('#tablab a[href="#tablab-reg"]').attr('class', '');
        return true;
    });
    $('#tablab a[href="#tablab-reg-tab"]').not('#bank-tab.disabled').click(function(event){
        $('#tablab a[href="#tablab-reg"]').attr('class' ,'active');
        $('#tablab a[href="#tablab-list"]').attr('class', '');
        return true;
    });
    
    $('#tablab a[href="#tablab-list"]').click(function(event){return false;});
    $('#tablab a[href="#tablab-reg"]').click(function(event){return false;});

    $('#txtFDesde,#txtFHasta,#mtxtFRecep').datetimepicker({
        format: 'DD/MM/YYYY',
        daysOfWeekDisabled: [0],
        locale:'es'
    });

    fechaAnioActual();
    $('#chkFreg').prop("checked", true);
    
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: baseurl+"lab/coti/ccotizacion/getcboclieserv",
        dataType: "JSON",
        async: true,
        success:function(result){
            $('#cboclieserv').html(result);
        },
        error: function(){
            alert('Error, No se puede autenticar por error');
        }
    });

    $('#frmRecepcion').validate({
        rules: {
            mtxtmproductoreal: {
                required: true,
            },
        },
        messages: {
            mtxtmproductoreal: {
                required: "Por Favor ingrese Nombre del Producto"
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
            var request = $.ajax({
                url:$('#frmRecepcion').attr("action"),
                type:$('#frmRecepcion').attr("method"),
                data:$('#frmRecepcion').serialize(),
                error: function(){
                    Vtitle = 'Error en Guardar!!!';
                    Vtype = 'error';
                    sweetalert(Vtitle,Vtype); 
                }
            });
            request.done(function( respuesta ) {
                var posts = JSON.parse(respuesta);
                
                $.each(posts, function() {
                    otblListProducto.ajax.reload(null,false);
                    Vtitle = 'La Recepcion esta Guardada!!!';
                    Vtype = 'success';
                    sweetalert(Vtitle,Vtype); 
                    $('#mbtnCCreaProduc').click();    
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

fechaAnioActual = function(){
    $("#txtFIni").prop("disabled",false);
    $("#txtFFin").prop("disabled",false);
        
    varfdesde = '';
    varfhasta = '';
    
    var fecha = new Date();		
    var fechatring1 = "01/01/" + (fecha.getFullYear() - 1) ;
    var fechatring2 = ("0" + fecha.getDate()).slice(-2) + "/" + ("0"+(fecha.getMonth()+1)).slice(-2) + "/" +fecha.getFullYear() ;

    $('#txtFDesde').datetimepicker('date', moment(fechatring1, 'DD/MM/YYYY') );
    $('#txtFHasta').datetimepicker('date', moment(fechatring2, 'DD/MM/YYYY') );
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

        
        var fecha = new Date();		
        var fechatring1 = "01/01/" + (fecha.getFullYear() - 1) ;
        var fechatring2 = ("0" + fecha.getDate()).slice(-2) + "/" + ("0"+(fecha.getMonth()+1)).slice(-2) + "/" +fecha.getFullYear() ;
        $('#txtFDesde').datetimepicker('date', fechatring1);
        $('#txtFHasta').datetimepicker('date', fechatring2);

    }else if($("#chkFreg").is(":checked") == false){ 
        $("#txtFIni").prop("disabled",true);
        $("#txtFFin").prop("disabled",true);
        
        varfdesde = '%';
        varfhasta = '%';

        fechaActual();
    }; 
});

$("#btnBuscar").click(function (){
    listarBusqueda();
});

listarBusqueda = function(){
    if(varfdesde != '%'){ varfdesde = $('#txtFIni').val(); }
    if(varfhasta != '%'){ varfhasta = $('#txtFFin').val(); } 
    
    var groupColumn = 0;   
    otblListRecepcion = $('#tblListRecepcion').DataTable({  
        'responsive'    : false,
        'bJQueryUI'     : true,
        'scrollY'     	: '400px',
        'scrollX'     	: true, 
        'paging'      	: true,
        'processing'  	: true,     
        'bDestroy'    	: true,
        'AutoWidth'     : false,
        'info'        	: true,
        'filter'      	: true, 
        'ordering'		: false,  
        'stateSave'     : true,
        'ajax'	: {
            "url"   : baseurl+"lab/recepcion/crecepcion/getbuscarrecepcion/",
            "type"  : "POST", 
            "data": function ( d ) {
                d.ccliente  = $('#cboclieserv').val();
                d.fini      = varfdesde; 
                d.ffin      = varfhasta;   
                d.descr     = $('#txtdescri').val(); 
                d.estado    = $('#cboestado').val(); 
                d.tieneot   = $('#cbotieneot').val(); 
            },     
            dataSrc : ''        
        },
        'columns'	: [
            {data: 'DCLIENTE'},
            {data: null, defaultContent: '', "className":"details-control col-xs"},
            {"orderable": false, "class" : "dt-body-center col-xs ", 
                render:function(data, type, row){            
                    if(row.SCOTIZACION == "S"){
                        varCerrar = '<a title="Editar" style="cursor:pointer; color:green;" onClick="selRecep(\'' + row.IDCOTIZACION + '\',\'' + row.NVERSION + '\',\'' + row.DCLIENTE + '\',\'' + row.NROCOTI + '\');"><span class="fas fa-edit fa-2x" aria-hidden="true"> </span> </a>'
                    }else{
                        varCerrar = '<a id="aCerrarCoti" href="'+row.IDCOTIZACION+'" nver="'+row.NVERSION+'" dclie="'+row.DCLIENTE+'" ncoti="'+row.NROCOTI+'" title="Recepción" style="cursor:pointer; color:red;"><span class="fas fa-folder-plus fa-2x" aria-hidden="true"> </span></a>'
                    };
                    return '<div class="text-center" >' +
                        varCerrar
                    '</div>';
                }
            },
            {data: 'NROCOTI'},
            {data: 'DFECHA'},
            {data: 'ENTREGAINFO'},
            {data: 'MONTOSINIGV', "class" : "col-s dt-body-right"},
            {data: 'ITOTAL', "class" : "col-s dt-body-right"},
            {data: 'ELABORADO'},
        ],  
        "columnDefs": [
            {
                "targets": [1], 
                "createdCell": function (td, cellData, rowData, row, col) {
                        if (rowData.TIENEOT == 'N') {
                            $(td).removeClass( 'details-control' );
                        }
                }
            },{
                "targets": [3], 
                "data": null, 
                "render": function(data, type, row) {
                    return '<div>'+
                    '    <p><a title="Cotizacion" style="cursor:pointer;" onclick="pdfCoti(\'' + row.IDCOTIZACION + '\',\'' + row.NVERSION + '\');"  class="pull-left">'+row.NROCOTI+'&nbsp;&nbsp;<i class="fas fa-file-pdf" style="color:#FF0000;"></i></a><p>' +
                    '</div>';
                }
            }
        ],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'all'} ).nodes();
            var last = null;
			var grupo;
 
            api.column([0], {} ).data().each( function ( ctra, i ) { 
                grupo = api.column(0).data()[i];
                if ( last !== ctra ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="8"><strong>'+ctra.toUpperCase()+'</strong></td></tr>'
                    ); 
                    last = ctra;
                }
            } );
        }
    }); 
    otblListRecepcion.column(0).visible( false );  

    $("#btnexcel").prop("disabled",false);  
};
/* DETALLE RECEPCION */
$('#tblListRecepcion tbody').on( 'click', 'td.details-control', function () {
    var tr = $(this).parents('tr');
    var row = otblListRecepcion.row(tr);
    var rowData = row.data();
    
        if ( row.child.isShown() ) {                    
            row.child.hide();
            tr.removeClass( 'details' );
        }
        else {
            otblListRecepcion.rows().every(function(){
                // If row has details expanded
                if(this.child.isShown()){
                    // Collapse row details
                    this.child.hide();
                    $(this.node()).removeClass('details');
                }
            })
            row.child( 
            '<table id="tblListdetrecepcion" class="display compact" style="width:100%; background-color:#D3DADF; padding-top: -10px; border-bottom: 2px solid black;">'+
            '<thead style="background-color:#FFFFFF;"><tr><th></th><th>Nro OT</th><th>Fecha OT</th><th>Archivo OT</th><th>Cargo Recepción</th><th>Etiqueta Muestra</th></tr></thead><tbody>' +
                '</tbody></table>').show();

                otblListdetrecepcion = $('#tblListdetrecepcion').DataTable({
                    "processing"  	: true,
                    'stateSave'     : true,
                    'bDestroy'      : true,
                    "bJQueryUI"     : true,
                    'scrollY'       : false,
                    'scrollX'       : true,
                    'scrollCollapse': false,
                    'AutoWidth'     : true,
                    'paging'        : false,
                    'info'          : false,
                    'filter'        : false,
                    "select"        : false, 
                    'ajax'        : {
                        "url"   : baseurl+"lab/recepcion/crecepcion/getlistdetrecepcion",
                        "type"  : "POST", 
                        "data": function ( d ) {
                            d.cinternocotizacion = rowData.IDCOTIZACION;
                            d.nversioncotizacion = rowData.NVERSION;
                        },     
                        dataSrc : ''        
                    },
                    'columns'     : [
                        {orderable: false,data: null, "class" : "col-s dt-body-right"},
                        {orderable: false,data: "nordentrabajo"},
                        {orderable: false,data: "fordentrabajo"},
                        {"orderable": false, "class" : "dt-body-center", 
                            render:function(data, type, row){  
                                return '<div>'+
                                '    <p><a title="OT" style="cursor:pointer;" onclick="pdfOT(\'' + row.cinternoordenservicio + '\');" class="pull-left"><i class="fas fa-file-pdf fa-2x" style="color:#FF0000;"></i></a><p>' +
                                '</div>';
                            }
                        },
                        {"orderable": false, "class" : "dt-body-center",  
                            render:function(data, type, row){  
                                return '<div>'+
                                '    <p><a title="Cargo" style="cursor:pointer;" onclick="pdfCargoOT(\'' + row.cinternoordenservicio + '\');" class="pull-left"><i class="fas fa-file-signature fa-2x" style="color:#0E2E93;"></i></a><p>' +
                                '</div>';
                            }
                        },
                        {"orderable": false, "class" : "dt-body-center",  
                            render:function(data, type, row){  
                                return '<div>'+
                                '    <p><a title="Etiqueta" style="cursor:pointer;" onClick="genEtiqueta(\'' + row.cinternoordenservicio + '\');"><span class="fas fa-receipt fa-2x" style="color:#DF6F3B;" aria-hidden="true"> </span> </a><p>' +
                                '</div>';
                            }
                        },       
                    ], 
                });
                // Enumeracion 
                otblListdetrecepcion.on( 'order.dt search.dt', function () { 
                    otblListdetrecepcion.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                      cell.innerHTML = i+1;
                      } );
                }).draw(); 

            tr.addClass('details');
        }
});

function pulsarListarCoti(e) {
    if (e.keyCode === 13 && !e.shiftKey) {
        e.preventDefault();
        listarBusqueda();
    }
}  

pdfOT = function(cinternoordenservicio){
    window.open(baseurl+"lab/recepcion/crecepcion/pdfOrderServ/"+cinternoordenservicio);
};

pdfCargoOT = function(cinternoordenservicio){
    window.open(baseurl+"lab/recepcion/crecepcion/pdfCargoOT/"+cinternoordenservicio);
};

pdfCoti = function(idcoti,nversion){
    window.open(baseurl+"lab/coti/ccotizacion/pdfCoti/"+idcoti+"/"+nversion);
};

genEtiqueta = function(cinternoordenservicio){
    $('#modalEtiqueta').modal({show:true});
    
    $('#mhdncinternoordenservicio').val(cinternoordenservicio);
    listarEtiquetasmuestras(cinternoordenservicio);
};

fechaActualRecep = function(){
    var fecha = new Date();		
    var fechatring = ("0" + fecha.getDate()).slice(-2) + "/" + ("0"+(fecha.getMonth()+1)).slice(-2) + "/" +fecha.getFullYear() ;
    $('#mtxtFrecepcion').datetimepicker('date', moment(fechatring, 'DD/MM/YYYY') );
};

selRecep= function(IDCOTIZACION,NVERSION,DCLIENTE,NROCOTI){  
    
    $('#tablab a[href="#tablab-reg"]').tab('show'); 
    $('#frmRegCoti').trigger("reset");
    $('#hdnAccionregcoti').val('A'); 
    $('#mtxtidcotizacion').val(IDCOTIZACION); 
    $('#mtxtnroversion').val(NVERSION);
  
    document.querySelector('#lblclie').innerText = DCLIENTE;
    document.querySelector('#lblcoti').innerText = NROCOTI;
     
    recuperaListproducto(IDCOTIZACION,NVERSION);
    /*$('#btnParticiopantes').show();*/
};
  
$("body").on("click","#aCerrarCoti",function(event){
    event.preventDefault();
    
    IDCOTI = $(this).attr("href");
    NVERSION = $(this).attr("nver");
    DCLIENTE = $(this).attr("dclie");
    NROCOTI = $(this).attr("ncoti");
    
    Swal.fire({
        title: 'Confirmar Apertura de Recepcion ',
        text: "¿Está seguro de Aperturar de Recepcion?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Aperturarlo!'
    }).then((result) => {
        if (result.value) {
            $.post(baseurl+"lab/coti/ccotizacion/cerrarcotizacion/", 
            {
                idcotizacion    : IDCOTI,
                nversion        : NVERSION,
            },      
            function(data){     
                $('#tablab a[href="#tablab-reg"]').tab('show'); 
                $('#frmRegCoti').trigger("reset");
                $('#hdnAccionregcoti').val('A'); 
                $('#mtxtidcotizacion').val(IDCOTI); 
                $('#mtxtnroversion').val(NVERSION);
              
                document.querySelector('#lblclie').innerText = DCLIENTE;
                document.querySelector('#lblcoti').innerText = NROCOTI;
                listarBusqueda(); 
                recuperaListproducto(IDCOTI,NVERSION);
                Vtitle = 'Se Aperturo Correctamente';
                Vtype = 'success';
                sweetalert(Vtitle,Vtype);      
            });
        }
    }) 
});
   
$("body").on("click","#aAbrirCoti",function(event){
    event.preventDefault();
    
    IDCOTI = $(this).attr("href");
    NVERSION = $(this).attr("nver");
    
    Swal.fire({
        title: 'Confirmar Abrir',
        text: "¿Está seguro de Abrir la Cotizacion?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, abrirlo!'
    }).then((result) => {
        if (result.value) {
            $.post(baseurl+"lab/coti/ccotizacion/abrircotizacion/", 
            {
                idcotizacion    : IDCOTI,
                nversion        : NVERSION,
            },      
            function(data){     
                otblListCotizacion.ajax.reload(null,false); 
                Vtitle = 'Se Abrio Correctamente';
                Vtype = 'success';
                sweetalert(Vtitle,Vtype);      
            });
        }
    }) 
});

$('#btnRetornarLista').click(function(){
    $('#tablab a[href="#tablab-list"]').tab('show');  
    //$('#btnBuscar').click();
});

recuperaListproducto = function(IDCOTIZACION,NVERSION){
    otblListProducto = $('#tblListProductos').DataTable({         
        "processing"  	: true,
        "bDestroy"    	: true,
        "stateSave"     : true,
        "bJQueryUI"     : true,
        "scrollResize"  : true,
        "scrollY"     	: "400px",
        "scrollX"     	: true,
        "scrollCollapse": true, 
        'AutoWidth'     : true,
        "paging"      	: false,
        "info"        	: true,
        "filter"      	: true, 
        "ordering"		: false,
        "responsive"    : false,
        "select"        : false,  
        'ajax'	: {
            "url"   : baseurl+"lab/recepcion/crecepcion/getrecepcionmuestra/",
            "type"  : "POST", 
            "data": function ( d ) {
                d.idcoti    = IDCOTIZACION; 
                d.nversion  = NVERSION;  
            },     
            dataSrc : ''        
        },
        'columns'	: [
            {
                "className":        'details-control col-xxs',
                "orderable":        false,
                data:               'SPACE',
                "defaultContent":   '', 
                targets:            0
            },
            {"orderable": false, data: 'SPACE', targets: 1},
            {"orderable": false, data : 'NROORDEN', targets : 2},
            {"orderable": false, data: 'frecepcionmuestra', targets: 3, "class" : "col-s"},
            {"orderable": false, data: 'cmuestra', targets: 4},
            {"orderable": false, data: 'drealproducto', targets: 5, "class" : "col-l"},
            {"orderable": false, data: 'dpresentacion', targets: 6, "class" : "col-l"},
            {"orderable": false, data: 'dtemperatura', targets: 7},
            {"orderable": false, data: 'dcantidad', targets: 8},
            {"orderable": false, data: 'dproveedorproducto', targets: 9},
            {"orderable": false, data: 'dlote', targets: 10},
            {"orderable": false, data: 'fenvase', targets: 11},
            {"orderable": false, data: 'fmuestra', targets: 12},
            {"orderable": false, data: 'hmuestra', targets: 13},
            {"orderable": false, data: 'dobservacion', targets: 14},
            {"orderable": false, data: 'dotraobservacion', targets: 15},
            {"orderable": false, data: 'IDORDEN', targets: 16},
            {"orderable": false, data: 'FECHAORDEN', targets: 17},
        ],
        "columnDefs": [{
            "targets": [1],
            //"checkboxes": {
              //  'selectRow': true
            //}, 
            "createdCell": function (td, cellData, rowData, row, col) {
                    if (rowData.IDORDEN == '0') {
                        $(td).addClass('index select-checkbox');
                    }else{
                        $(td).removeClass('select-checkbox');
                    }
            },
            "orderable": false
        }
        ],
        "select": {
            style:    'multi',  
            selector: 'td:nth-child(2)'      
        },
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'all'} ).nodes();
            var last = null;
			var grupo;
 
            api.column([2], {} ).data().each( function ( ctra, i ) { 
                grupo = api.column(2).data()[i];                
                grupo1 = api.column(16).data()[i];             
                fechaot = api.column(17).data()[i];
                if ( last !== ctra ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="1">'+grupo1+'</td><td colspan="8"><strong> OT :: '+ctra.toUpperCase()+' :: '+fechaot+'</strong></td></tr>'
                    ); 
                    last = ctra;
                }
            } );
        },
    }); 

    otblListProducto.column(2).visible( false );  
    otblListProducto.column(16).visible( false ); 
    otblListProducto.column(17).visible( false );   
    // Enumeracion 
    otblListProducto.on( 'order.dt search.dt', function () { 
        otblListProducto.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
          } );
    }).draw();

    $('#tblListProductos tbody').on( 'click', 'tr.group', function () {
        var val = $(this).closest('tr').find('td:eq(0)').text();        
        var nro = $(this).closest('tr').find('td:eq(1)').text().substr(7,17);
        var fot = $(this).closest('tr').find('td:eq(1)').text().substr(27,11);
    
        $('#modalFechaOT').modal({show:true});
        seleOT(val,nro,fot);
        listarBlancoviajero(val);       
    } ); 
};
$('#tblListProductos tbody').on( 'click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    //var tr = $(this).parents('tr');
    var row = otblListProducto.row( tr );
    var rowData = row.data();
      
    //get index to use for child table ID
    var index = row.index();

    if ( row.child.isShown() ) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('details');
    }
    else {
        otblListProducto.rows().every(function(){
            // If row has details expanded
            if(this.child.isShown()){
                // Collapse row details
                this.child.hide();
                $(this.node()).removeClass('details');
            }
        })
        // Open this row
        row.child( 
           '<table class="display compact" id = "child_details' + index + '"  style="width:100%; background-color:#D3DADF; padding-top: -10px; border-bottom: 2px solid black;">'+
           '<thead style="background-color:#FFFFFF;"><tr><th></th><th>Codigo</th><th>Ensayo</th><th>Vias</th></tr></thead><tbody>' +
            '</tbody></table>').show();
        
        var childTable = $('#child_details' + index).DataTable({ 
            "processing"  	: true,
            "bDestroy"    	: true,
            "stateSave"     : true,
            "bJQueryUI"     : true,
            "scrollResize"  : true,
            "scrollY"     	: "400px",
            "scrollX"     	: true,
            "scrollCollapse": true, 
            'AutoWidth'     : true,
            "paging"      	: false,
            "info"        	: false,
            "filter"      	: false, 
            "ordering"		: false,
            "responsive"    : false,
            "select"        : false,
            'ajax'	: {
                "url"   : baseurl+"lab/coti/ccotizacion/getlistarensayo/",
                "type"  : "POST", 
                "data": function ( d ) {
                    d.idcoti    = rowData.cinternocotizacion; 
                    d.nversion  = rowData.nversioncotizacion;
                    d.idproduc  = rowData.nordenproducto;  
                },     
                dataSrc : ''        
            },
            'columns'	: [
                {"class" : "col-xs", "orderable": false, data : 'ENUMERAR', targets : 0},
                {"class" : "col-m", "orderable": false, data: 'CODIGO', targets: 1},
                {"class" : "col-l", "orderable": false, data: 'DENSAYO', targets: 2},
                {"class" : "col-xs", "orderable": false, data: 'NVIAS', targets: 3},
            ]
        });
        tr.addClass('details');
    }
});

$('#tblListProductos tbody').on('dblclick', 'td', function () {
    var tr = $(this).parents('tr');
    var row = otblListProducto.row(tr);
    var rowData = row.data();
    $('#modalRecepcion').modal({show:true});
    selCotiprodu(rowData.cinternocotizacion,rowData.nversioncotizacion,rowData.nordenproducto,rowData.cmuestra,rowData.frecepcionmuestra,rowData.drealproducto,rowData.dpresentacion,rowData.dtemperatura,rowData.dcantidad,rowData.dproveedorproducto,rowData.dlote,rowData.fenvase,rowData.fmuestra,rowData.hmuestra,rowData.stottus,rowData.ntrimestre,rowData.zctipomotivo,rowData.careacliente,rowData.zctipoitem,rowData.dubicacion,rowData.dcondicion,rowData.dobservacion,rowData.dotraobservacion);
    

});

$('#modalFechaOT').on('shown.bs.modal', function (e) {  
});

seleOT = function(val,nro,fot){ 
    $('#frmFechaOT').trigger("reset");
    $('#frmGenConst').trigger("reset");

    $('#txtFOT,#txtFConstancia').datetimepicker({
        format: 'DD/MM/YYYY',
        daysOfWeekDisabled: [0],
        locale:'es'
    });    

    $('#txtHConstancia').datetimepicker({
        format: 'LT',
        datepicker: false,
        format: 'H:i',
        pickDate: false
    })

    $('#mhdncinternoordenservicio').val(val);
    $('#mhdncordenservicioconst').val(val);
    $('#mhdncordenservicioblancovia').val(val);
    $('#mhdnnroordenservicio').val(nro);
    $('#txtForden').val(fot);
    $('#mhdnAccionConst').val('N');
    
};

$('#frmFechaOT').submit(function(event){
    event.preventDefault();
    
    var request = $.ajax({
        url:$('#frmFechaOT').attr("action"),
        type:$('#frmFechaOT').attr("method"),
        data:$('#frmFechaOT').serialize(),
        error: function(){
            Vtitle = 'No se puede registrar por error';
            Vtype = 'error';
            sweetalert(Vtitle,Vtype);
        },
        success: function(data) { 
            Vtitle = 'Grabo Correctamente!!';
            Vtype = 'success';
            sweetalert(Vtitle,Vtype);        
            $('#mbtnCFechaOT').click();
        }
    });
});

$('#frmGenConst').submit(function(event){
    event.preventDefault();
    
    var request = $.ajax({
        url:$('#frmGenConst').attr("action"),
        type:$('#frmGenConst').attr("method"),
        data:$('#frmGenConst').serialize(),
        error: function(){
            Vtitle = 'No se puede registrar por error';
            Vtype = 'error';
            sweetalert(Vtitle,Vtype);
        },
        success: function(data) { 
            Vtitle = 'Grabo Correctamente!!';
            Vtype = 'success';
            sweetalert(Vtitle,Vtype);        
            $('#mbtnCFechaOT').click();
        }
    });
});

$('#modalRecepcion').on('shown.bs.modal', function (e) {
    
    $('#mtxtFRecep').datetimepicker({
        format: 'DD/MM/YYYY',
        daysOfWeekDisabled: [0],
        locale:'es'
    });

    $("#mtxtregnumcoti").prop({readonly:true});  
    
    $('#divExtra').hide();
});

selCotiprodu = function(cinternocotizacion,nversioncotizacion,nordenproducto,cmuestra,frecepcionmuestra,drealproducto,dpresentacion,dtemperatura,dcantidad,dproveedorproducto,dlote,fenvase,fmuestra,hmuestra,stottus,ntrimestre,zctipomotivo,careacliente,zctipoitem,dubicacion,dcondicion,dobservacion,dotraobservacion){
    $('#frmRecepcion').trigger("reset");
    $('#mhdnAccionRecepcion').val('A');
    
    $('#mhdnidcotizacion').val(cinternocotizacion); 
    $('#mhdnnroversion').val(nversioncotizacion);
    $('#mhdnnordenproducto').val(nordenproducto);

    $('#mtxtmcodigo').val(cmuestra);
    
    if(frecepcionmuestra == 'null'){
        fechaActualRecep();
    }else{
        $('#mtxtFrecepcion').val(frecepcionmuestra);
    }
    
    $('#mtxtmproductoreal').val(drealproducto);
    $('#mtxtmpresentacion').val(dpresentacion);
    $('#mtxttemprecep').val(dtemperatura);
    $('#mtxtcantmuestra').val(dcantidad);    
    $('#mtxtproveedor').val(dproveedorproducto);
    $('#mtxtnrolote').val(dlote);
    
    if(fenvase == 'null'){
        $('#mtxtFenvase').val('');
    }else{
        $('#mtxtFenvase').val(fenvase);
        $('#mtxtFenvase').datetimepicker({
            format: 'DD/MM/YYYY',
            daysOfWeekDisabled: [0],
            locale:'es'
        });
    }
    
    if(fmuestra == 'null'){
        $('#mtxtFmuestra').val('');
    }else{
        $('#mtxtFmuestra').val(fmuestra);
        $('#mtxtFmuestra').datetimepicker({
            format: 'DD/MM/YYYY',
            daysOfWeekDisabled: [0],
            locale:'es'
        });
    }
    
    if(hmuestra == 'null'){
        $('#mtxthmuestra').val('');
    }else{
        $('#mtxthmuestra').val(hmuestra);
        $('#mtxthmuestra').datetimepicker({
            format: 'hh:mm A',
            locale:'es',
            stepping: 15
        });
    }
    
    $('#mcbotottus').val(stottus);
    $('#mcbomonitoreo').val(ntrimestre);
    $('#mcbomotivo').val(zctipomotivo);
    $('#mcboarea').val(careacliente);
    $('#mcboitem').val(zctipoitem);
    $('#mtxtubicacion').val(dubicacion);
    $('#mtxtestado').val(dcondicion);
    $('#mtxtObserva').val(dobservacion);
    $('#mtxtObsotros').val(dotraobservacion);
};

$('#btngenerarOT').click(function(){
    event.preventDefault();
    var table = $('#tblListProductos').DataTable();
    var seleccionados = table.rows({ selected: true });

    var IDPRODUCTO;
    var varsot = 'N';
    var varidot = null;
    var CUSU = $('#mtxtcusuario').val();

    var res;

    Swal.fire({
        title: 'Confirmar Generar OT',
        text: "¿Está seguro de Generar su OT?",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, generarlo!'
    }).then((result) => {
        if (result.value) { 
                        
            seleccionados.every(function(key,data){
                IDCOTIZACION = this.data().cinternocotizacion;
                NVERSION = this.data().nversioncotizacion;
                IDPRODUCTO = this.data().nordenproducto;
                IDMUESTRA = this.data().cmuestra;

                var_NROORDEN = this.data().NROORDEN;
                
                if(var_NROORDEN == ''){
                    if(varsot == 'N'){
                        varidot = generarResult(IDCOTIZACION,NVERSION,IDPRODUCTO,IDMUESTRA,CUSU,varidot,varsot);  
                        varsot = 'S';
                    }else{
                        varidot = generarResult(IDCOTIZACION,NVERSION,IDPRODUCTO,IDMUESTRA,CUSU,varidot,varsot);
                    }
                     
                }else{
                    alert("La muestra "+IDMUESTRA+", Seleccionada ya tiene OT");
                    return;
                }                
            });
            generarOTMensajeOK();
        }
    })
}); 
generarResult = function(IDCOTIZACION,NVERSION,IDPRODUCTO,IDMUESTRA,CUSU,IDOT,VARSOT){  
         
    $.post(baseurl+"lab/recepcion/crecepcion/setordentrabajoresult", 
    {
        cinternocotizacion    : IDCOTIZACION,
        nversioncotizacion    : NVERSION,
        nordenproducto  : IDPRODUCTO,
        cmuestra  : IDMUESTRA,
        cusuario : CUSU,
        cinternoordenservicio : IDOT,
        varsot : VARSOT,
    },  
    function(data){ 
        var c = JSON.parse(data);
        $.each(c,function(i,item){ 
            return item.id; 
        })      
    });
    
};
generarOTMensajeOK = function(){ 
    otblListProducto.ajax.reload(null,false); 
    Vtitle = 'Se Genero Correctamente';
    Vtype = 'success';
    sweetalert(Vtitle,Vtype);
};
/*generarOT = function(IDCOTIZACION,NVERSION,CUSU){      
    $.post(baseurl+"lab/recepcion/crecepcion/setordentrabajo", 
    {
        cinternocotizacion    : IDCOTIZACION,
        nversioncotizacion    : NVERSION,
        cusuario : CUSU,
    },  
    function(data){ 
        var c = JSON.parse(data);
        $.each(c,function(i,item){ 
            return item.id; 
        })      
    });
}*/

listarEtiquetasmuestras = function(vcinternoordenservicio){
    otblListEtiquetasmuestras = $('#tblListEtiquetasmuestras').DataTable({         
        "processing"  	: true,
        "bDestroy"    	: true,
        "stateSave"     : true,
        "bJQueryUI"     : true,
        "scrollResize"  : true,
        "scrollY"     	: "400px",
        "scrollX"     	: true,
        "scrollCollapse": true, 
        'AutoWidth'     : true,
        "paging"      	: false,
        "info"        	: false,
        "filter"      	: false, 
        "ordering"		: false,
        "responsive"    : false,
        "select"        : false,  
        'ajax'	: {
            "url"   : baseurl+"lab/recepcion/crecepcion/getetiquetasmuestras/",
            "type"  : "POST", 
            "data": function ( d ) {
                d.cinternoordenservicio    = vcinternoordenservicio;  
            },     
            dataSrc : ''        
        },
        'columns'	: [
            {data: 'CMUESTRA'},
            {data: 'SPACE'},
            {data : 'COPIA'},
        ],
        "columnDefs": [{
            "targets": [1],
            "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).addClass('index select-checkbox');
            },
            "orderable": false
        }
        ],
        "select": {
            style:    'multi',  
            selector: 'td:nth-child(2)'      
        }
    });
}; 

/*table = otblListEtiquetasmuestras;
table.MakeCellsEditable({
    "onUpdate": myCallbackFunction
});

function myCallbackFunction(updatedCell, updatedRow, oldValue) {
    console.log("The new value for the cell is: " + updatedCell.data());
    console.log("The old value for that cell was: " + oldValue);
    console.log("The values for each cell in that row are: " + updatedRow.data());
}*/

$('#mbtnPrint').click(function(){ 
    var table = $('#tblListEtiquetasmuestras').DataTable();
    var seleccionados = table.rows({ selected: true }).indexes();

    var array1 = table.cells(seleccionados, [0]).data().toArray();
    var array2 = table.cells(seleccionados, [2]).data().toArray();
    var plainArray = [array1, array2];

    var vcinternoordenservicio = $('#mhdncinternoordenservicio').val();
    
    var form = getForm(baseurl+"lab/recepcion/crecepcion/pdfEtiquetagen", "_blank", plainArray, vcinternoordenservicio, "post");
    
    document.body.appendChild(form);
    form.submit();
    form.parentNode.removeChild(form);
});

listarBlancoviajero = function(vcinternoordenservicio){
    
    //$('#tblBlancoviajero').DataTable().destroy();
    //$('#tblBlancoviajero tbody').empty();

    otblBlancoviajero = $('#tblBlancoviajero').DataTable({   
        "processing"  	: true,
        "bDestroy"    	: true,
        "stateSave"     : true,
        "bJQueryUI"     : true,
        "scrollResize"  : true,
        "scrollY"     	: "300px",
        "scrollX"     	: false,
        "scrollCollapse": true, 
        'AutoWidth'     : true,
        "paging"      	: false,
        "info"        	: false,
        "filter"      	: false, 
        "ordering"		: false,
        "responsive"    : false,
        "select"        : false, 
        //"serverSide"    : true,   
        'ajax'	: {
            "url"   : baseurl+"lab/recepcion/crecepcion/getlistblancoviajero/",
            "type"  : "POST", 
            "data": function ( d ) {
                d.cinternoordenservicio    = vcinternoordenservicio;  
            },     
            dataSrc : ''        
        },
        'columns'	: [
            {data: 'BK', "class" : "col-s"},
            {data: 'LOTE', "class" : "col-sm"},                     
            {"orderable": false, 
                render:function(data, type, row){
                    return '<div>' +
                            '<a id="aDelBlanco" href="'+row.IDBLANCOVIAJERO+'" title="Eliminar" style="cursor:pointer; color:#FF0000;"><span class="fas fa-trash-alt fa-2x" aria-hidden="true"> </span></a>'+
                            '</div>' ; 
                }
            }  
        ]
    });
};
    
$("body").on("click","#aDelBlanco",function(event){
    event.preventDefault();
    idblancoviajero = $(this).attr("href");
    
    $.post(baseurl+"lab/recepcion/crecepcion/delblancoviajero", 
    {
        idblancoviajero   : idblancoviajero,
    },      
    function(data){   	
        otblBlancoviajero.ajax.reload(null,false); 
        $('#btnNuevobv').click(); 	
    });
}); 

$('#tblBlancoviajero tbody').on('click', 'td', function () {
    var tr = $(this).parents('tr');
    var row = otblBlancoviajero.row(tr);
    var rowData = row.data();
    $('#frmBlancovia').trigger("reset");

    $('#mhdnAccionblancovia').val('A');
    $('#mhdnidblancoviajero').val(rowData.IDBLANCOVIAJERO);
    $('#mhdncordenservicioblancovia').val(rowData.CINTERNOORDENSERVICIO);
    $('#cbobk').val(rowData.BK).change();
    $('#mtxtlote').val(rowData.LOTE);

});

$('#frmBlancovia').submit(function(event){
    event.preventDefault();

    $.ajax({
        url:$('#frmBlancovia').attr("action"),
        type:$('#frmBlancovia').attr("method"),
        data:$('#frmBlancovia').serialize(),
        success: function (respuesta){            
            otblBlancoviajero.ajax.reload(null,false);
            Vtitle = 'Se Grabo Correctamente';
            Vtype = 'success';
            sweetalert(Vtitle,Vtype);
            $('#btnNuevobv').click();  
        },
        error: function(){
            Vtitle = 'No se puede Grabar por error';
            Vtype = 'error';
            sweetalert(Vtitle,Vtype); 
        }
    });
});
$('#btnNuevobv').click(function(){
    $('#frmBlancovia').trigger("reset");
    $('#mhdnidblancoviajero').val(''); 
    $('#mhdnAccionblancovia').val('N');
    $('#cbobk').val('0').change();
});

$('#addRow1').on( 'click', function () {
    var vcinternoordenservicio = $('#mhdncordenservicioblancovia').val();

    event.preventDefault();
        
    $.post(baseurl+"lab/recepcion/crecepcion/setblancoviajero", 
    {
        cinternoordenservicio    : vcinternoordenservicio,
        accion : 'N'
    },  
    function(data){ 
        var c = JSON.parse(data);
        $.each(c,function(i,item){ 
            otblBlancoviajero.ajax.reload(null,false); 
        })      
    });
} );

$('#tblBlancoviajero1').on('draw.dt', function(){
    $('#tblBlancoviajero').Tabledit({
     url:baseurl+"lab/recepcion/crecepcion/setblancoviajero/",
     //eventType: 'dblclick',
     editButton: true,
     deleteButton: false,
     dataType:'json',
     columns:{
      identifier : [[2, 'IDBLANCOVIAJERO'],[3, 'IDBLANCOVIAJERO']],
      editable:[[0, 'BK', '{"PBS":"PBS","BPW":"BPW","D/E":"D/E","APC":"APC","OGYE":"OGYE","OXFORD":"OXFORD","MYP":"MYP","VRBA":"VRBA","XLD":"XLD","OTROS":"OTROS"}'],[1, 'LOTE']]
     },
     restoreButton:false,
    });
});


function getForm(url, target, values, idvalor, method) {
    function grabValues(x) {
      var path = [];
      var depth = 0;
      var results = [];
  
      function iterate(x) {
        switch (typeof x) {
          case 'function':
          case 'undefined':
          case 'null':
            break;
          case 'object':
            if (Array.isArray(x))
              for (var i = 0; i < x.length; i++) {
                path[depth++] = i;
                iterate(x[i]);
              }
            else
              for (var i in x) {
                path[depth++] = i;
                iterate(x[i]);
              }
            break;
          default:
            results.push({
              path: path.slice(0),
              value: x
            })
            break;
        }
        path.splice(--depth);
      }
      iterate(x);
      return results;
    }
    var form = document.createElement("form");
    form.method = method;
    form.action = url;
    form.target = target;
  
    var values = grabValues(values);
    
    for (var j = 0; j < values.length; j++) {
      var input = document.createElement("input");
      input.type = "hidden";
      input.value = values[j].value;
      input.name = values[j].path[0];
      for (var k = 1; k < values[j].path.length; k++) {
        input.name += "[" + values[j].path[k] + "]";
      }
      form.appendChild(input);
    }

    var inputid = document.createElement("input");
    inputid.type = "hidden";
    inputid.value = idvalor;
    inputid.name = "id";
    form.insertBefore(inputid,input);

    var inputlen = document.createElement("input");
    inputlen.type = "hidden";
    inputlen.value = values.length/2;
    inputlen.name = "len";
    form.insertBefore(inputlen,inputid);
    
    return form;
  }