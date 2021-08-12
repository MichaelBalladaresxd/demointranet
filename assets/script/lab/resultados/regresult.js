const objListaresult = {};
const objListaresultold = {};
var otblListServiciolab, otblListResultados;
var varfdesde = '%', varfhasta = '%';
var collapsedGroupsEq = {},collapsedGroupsEq1 = {},collapsedGroupsEq2 = {};


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

    $('#txtFDesde,#txtFHasta,#mtxtFRecep,#mtxtFanalisis').datetimepicker({
        format: 'DD/MM/YYYY',
        daysOfWeekDisabled: [0],
        locale:'es'
    });    

    $('#mtxtHanalisis').datetimepicker({
        format: 'LT'
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

    
    $('#frmIngresult').validate({
        rules: {
            mcboum: {
            required: true,
          },
        },
        messages: {
            mcboum: {
            required: "Por Favor escoja una UM"
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
            const botonEvaluar = $('#mbtnGIngresult');
            var request = $.ajax({
                url:$('#frmIngresult').attr("action"),
                type:$('#frmIngresult').attr("method"),
                data:$('#frmIngresult').serialize(),
                error: function(){
                    Vtitle = 'Error en Guardar!!!';
                    Vtype = 'error';
                    sweetalert(Vtitle,Vtype); 
                },
                beforeSend: function() {
                    objPrincipal.botonCargando(botonEvaluar);
                }
            });
            request.done(function( respuesta ) {
                var posts = JSON.parse(respuesta);
                
                $.each(posts, function() {                    
                    
                    Vtitle = 'Cotizacion Guardada!!!';
                    Vtype = 'success';
                    sweetalert(Vtitle,Vtype);   
                    objPrincipal.liberarBoton(botonEvaluar); 
                    
                    getListResultados(this.cinternoordenservicio);

                    $('#mbtnCIngresult').click();
                });
            });
            return false;
        }
    });
    
    $('#frmsetservicio').validate({
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
            const botonEvaluar = $('#btngrabarResult');
            var request = $.ajax({
                url:$('#frmsetservicio').attr("action"),
                type:$('#frmsetservicio').attr("method"),
                data:$('#frmsetservicio').serialize(),
                error: function(){
                    Vtitle = 'Error en Guardar!!!';
                    Vtype = 'error';
                    sweetalert(Vtitle,Vtype); 
                },
                beforeSend: function() {
                    objPrincipal.botonCargando(botonEvaluar);
                }
            });
            request.done(function( respuesta ) {
                var posts = JSON.parse(respuesta);
                if(respuesta == 'true') {   
                    Vtitle = 'Servicio Guardado!!!';
                    Vtype = 'success';
                    sweetalert(Vtitle,Vtype);   
                    objPrincipal.liberarBoton(botonEvaluar); 
                    //getListResultados(this.cinternoordenservicio);
                    //$('#mbtnCIngresult').click();
                };
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
    $('#mtxtFanalisis').datetimepicker('date', moment(fechatring, 'DD/MM/YYYY') );

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
    parametros = paramListarBusqueda();
    getListarBusqueda(parametros);
});

paramListarBusqueda = function (){    
    var v_mes, v_anio

    if(varfdesde != '%'){ varfdesde = $('#txtFIni').val(); }
    if(varfhasta != '%'){ varfhasta = $('#txtFFin').val(); } 
     
    var parametros = {
        "ccliente"      : $('#cboclieserv').val(),
        "buspor"        : $('#cbobuspor').val(), 
        "numero"        : $('#txtbuscarnro').val(), 
        "fini"          : varfdesde, 
        "ffin"          : varfhasta,   
        "prodmuestra"   : $('#txtdescri').val(), 
        "ensayo"        : $('#txtensayo').val(), 
        //"areaserv"      : $('#cboareaserv').val(),
    };  

    return parametros;
    
};

getListarBusqueda = function(){    

    otblListServiciolab = $('#tblListServiciolab').DataTable({ 
        "processing"  	: true,
        "bDestroy"    	: true,
        "stateSave"     : true,
        "bJQueryUI"     : true,
        "scrollResize"  : true,
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
            "url"   : baseurl+"lab/resultados/cregresult/getbuscaringresoresult",
            "type"  : "POST", 
            "data"  : parametros,     
            dataSrc : ''      
        }, 
        'columns'	: [
            {data: 'DCLIENTE'},
            {data: 'cinternoordenservicio'},
            {data: 'NROCOTI'},
            {data: 'ELABORADOPOR'},
            {data: 'NORDENTRABAJO'},
            {data: 'BLANCO', "className":"col-xxs"},
            {data: 'CODMUESTRA'},
            {data: 'REALPRODUCTO'},
            {data: 'BLANCO', "className":"col-xxs"},
            {data: 'TIPOENSAYO'},
            {data: 'CODENSAYO'},
            {data: 'ENSAYO'},
            {data: 'BLANCO'},
            {data: 'BLANCO'},
            {data: 'BLANCO', "className":"col-xxs"},
        ],
        rowGroup: {
            startRender : function ( rows, group, level ) {
                
                if ( level == 0 ) {
                    var varcclie = rows
                        .data()
                        .reduce( function (a, b) {
                            return b.ccliente;
                        }, 0) ; 

                    var collapsed = !!collapsedGroupsEq[varcclie];
        
                    rows.nodes().each(function (r) {
                        r.style.display = collapsed ? 'none' : '';
                    });   
              
                    
                    return $('<tr/>')
                    .append('<td colspan="8" style="cursor: pointer;">' + group + '</td>')
                    .attr('data-name', varcclie)
                    .toggleClass('collapsed', collapsed);

                } else if ( level == 1 ){
                    var varid = rows
                        .data()
                        .reduce( function (a, b) {
                            return b.cinternoordenservicio;
                        }, 0) ;

                    var collapsed = !!collapsedGroupsEq1[varid];
        
                    rows.nodes().each(function (r) {
                        r.style.display = collapsed ? 'none' : '';
                    }); 


                    return $('<tr/>')
                    .append('<td colspan="5" style="cursor: pointer;">' + group + '</td><td></td><td></td><td></td>')
                    .attr('data-name', varid)
                    .toggleClass('collapsed', collapsed);
                    
                } else if ( level == 2 ){
                    var varcod = rows
                        .data()
                        .reduce( function (a, b) {
                            return b.cinternoordenservicio+b.CODMUESTRA;
                        }, 0) ;

                    var collapsed = !!collapsedGroupsEq2[varcod];
        
                    rows.nodes().each(function (r) {
                        r.style.display = collapsed ? 'none' : '';
                    }); 
                    
                    return $('<tr/>')
                    .append('<td></td><td colspan="4" style="cursor: pointer;">' + group.toUpperCase() + '</td><td></td><td></td><td></td>')
                    .attr('data-name', varcod)
                    .toggleClass('collapsed', collapsed);
                }
                 
            },
            dataSrc: ["DCLIENTE","NROCOTI","REALPRODUCTO"]
        },
    }); 
    otblListServiciolab.column(0).visible( false ); 
    otblListServiciolab.column(1).visible( false ); 
    otblListServiciolab.column(2).visible( false ); 
    otblListServiciolab.column(3).visible( false );
    otblListServiciolab.column(4).visible( false );  
    otblListServiciolab.column(6).visible( false );  
    otblListServiciolab.column(7).visible( false );  
    otblListServiciolab.column(12).visible( true ); 
    otblListServiciolab.column(14).visible( true );  

    $("#btnexcel").prop("disabled",false);  
};

/* COMPRIMIR GRUPO 
$('#tblListServiciolab tbody').on('click', 'tr.dtrg-level-0', function () {      
    var name = $(this).data('name');
    collapsedGroupsEq[name] = !collapsedGroupsEq[name];
    otblListServiciolab.draw(true);
});
$('#tblListServiciolab tbody').on('click', 'tr.dtrg-level-1', function () {    
    var name = $(this).data('name');
    collapsedGroupsEq1[name] = !collapsedGroupsEq1[name];
    otblListServiciolab.draw(true);
}); */
$('#tblListServiciolab tbody').on('click', 'tr.dtrg-level-2', function () {    
    var name = $(this).data('name');
    collapsedGroupsEq2[name] = !collapsedGroupsEq2[name];
    otblListServiciolab.draw(true);
}); 

$('#tblListServiciolab tbody').on('dblclick', 'tr.dtrg-level-1', function () {
    var id = $(this).data('name');
    $('#tablab a[href="#tablab-reg"]').tab('show');
    verResultados(id);
} );

verResultados = function(id){
    var parametros = { 
        "cinternoordenservicio":id 
    };
    var request = $.ajax({
        type: 'ajax',
        method: 'post',
        url: baseurl+"lab/resultados/cregresult/getrecuperaservicio",
        dataType: "JSON",
        async: true,
        data: parametros,
        error: function(){
            alert('Error, no se puede cargar el servicio');
        }
    });      
    request.done(function( respuesta ) {            
        $.each(respuesta, function() { 

            $('#txtcliente').val(this.drazonsocial);
            $('#mtxtFanali').val(this.fanalisis);
            $('#mtxtHanali').val(this.hanalisis);
            $('#txtcotizacion').val(this.dcotizacion);
            $('#txtfcotizacion').val(this.fcotizacion);  
            $('#txtnroot').val(this.nordenservicio);  
            $('#txtfot').val(this.fordenservicio);  
            $('#txtidcotizacion').val(this.cinternocotizacion);  
            $('#txtnroversion').val(this.nversioncotizacion);  
            $('#txtidordenservicio').val(this.cinternoordenservicio); 
            $('#txtobserva').val(this.dobservacionresultados); 
            $('#mcbotipoinforme').val(this.ctipoinforme).trigger("change"); 
            $('#txttipoingreso').val(this.conttipoprod); 
                        
            
            if (this.conttipoprod > 0){                
                $('#divtblListResultados').show();           
                $('#divtblListResultadosold').hide();
                objListaresult.viewList(id,'%')
            }else{           
                $('#divtblListResultados').hide();           
                $('#divtblListResultadosold').show();
                objListaresultold.viewListold(id,'%')
            }

            cbotipoensayo(this.cinternoordenservicio);
                  
        });
    });
};

cbotipoensayo = function(vcinternoordenservicio){ 
    var params = { "cinternoordenservicio": vcinternoordenservicio};   
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: baseurl+"lab/resultados/cregresult/getcbotipoensayo",
        dataType: "JSON",
        async: true,
        data: params,
        success:function(result){
            $('#mcbotipoensayo').html(result);
        },
        error: function(){
            alert('Error, No se puede autenticar por error');
        }
    });
};

$("#mcbotipoensayo").change(function(){
    var v_idot = $('#txtidordenservicio').val();
    var v_tipoensayo = $('#mcbotipoensayo').val();
    var v_conttipoprod = $('#txttipoingreso').val();

    if (v_conttipoprod > 0){
        objListaresult.viewList(v_idot,v_tipoensayo)
    }else{           
        objListaresultold.viewListold(v_idot,v_tipoensayo)
    }
    
});

/* ******** */
/**
* Listar de servicio
*/

objListaresult.viewList = function (id) {        
    var parametros = {
        "cinternoordenservicio" : id,
    };
    $.ajax({
       url      :  baseurl+"lab/resultados/cregresult/getlistresultados",
       method   : "GET",
       data     : parametros, 
    }).done(function(data){
        $('#tblListResultados tbody').html(data)
        objListaresult.tableData()
        
        $("#tblListResultados thead .tabledit-edit-button").hide();
        $("#tblListResultados tbody .trcab .tabledit-edit-button").hide();
        $("#tblListResultados tbody .trtipo .tabledit-edit-button").hide();
    })
};
objListaresult.tableData = function () {
    $('#tblListResultados').Tabledit({
        url:'lab/resultados/cregresult/setresultados',
        eventType: 'dblclick',
        editButton: true,
        deleteButton: false,
        saveButton: false,
        autoFocus: false,
        hideIdentifier: true,
		restoreButton: false,
        buttons: {
            edit: {
                class: 'btn btn-sm btn-primary',
                html: '<span class="fas fa-pencil-alt"></span>',
                action: 'edit'
            }
        },
        columns:{
            identifier : [0,'ID'],
            editable:[[4, 'unidadmedida', '{"565":"-","561":"%","784":"% (Ac. acético)","776":"% (Ac. cítrico)","611":"% (Ac. Láctico)","A43":"% (Ác. málico)","612":"% (Ac. Sulfúrico)","781":"% (Al 15% Humedad)","B61":"% (Amoniaco)","B29":"% del extracto seco","E76":"% Vol.","743":"%(Ac. Oleico)","849":"%(Ac. Palmitico)","941":"%p/p","999":"%V","850":"(20ºC)","872":"(25º)","A44":"(g/100g) Base Seca","782":"(mg KOH)/g)","943":"\ Hg","938":"°D","940":"°Z","991":"µg retinol/100g","873":"µs/cm","880":"Ausencia / 100 ml","563":"Ausencia / Presencia","906":"Ausencia en 25g / Presencia en 25g","976":"Ausencia o Presencia / 25 g","977":"Ausencia o Presencia/100g","A57":"Ausencia o Presencia/1L ","A42":"B. cereus / superficie","989":"CIO2 - mg/L","988":"CIO3 - mg/L","773":"cm","982":"Coliform bacilli en 25 g","945":"Coliformes / 1 g","912":"cp","944":"CP (25ºC)","A82":"cps (20ºC)","A54":"cps (25ºC)","629":"cps (50ºC)","A55":"cps (65ºC)","A56":"cps (97ºC)","631":"cTs (100ºC)","J12":"dS/m","851":"E. coli / 1 g","968":"E. coli / 1 g","918":"E. coli / 100cm2","I67":"E. coli / 25 g","626":"E. coli / área","627":"E. coli / cm2","760":"E. coli / manos","837":"E. coli / superficie","613":"E. coli O 157:H7 / 25 g","A48":"E. coli O 157:H7 / 25 ml","E74":"E. coli O 157:H7/325 g","981":"Enterobacterias / 1 g","610":"Estéril / No Estéril","737":"g","961":"g / L","957":"g/100g","845":"g/100g (Límite de Cuantificación: 0.08)","974":"g/100ml","955":"g/115g","956":"g/130g","I91":"g/200mL","946":"g/Kg","B28":"g/L (Ác. acético)","A60":"g/m2","771":"g/ml","783":"g/ml (20ºC)","630":"g/ml (53ºC)","628":"g/ml (65ºC)","980":"g/paquete","954":"g/porción","993":"gr/dm3","A53":"gr/ml ( 97ºC)","A51":"gr/ml (25ºC)","A52":"gr/ml (65ºC)","775":"Kcal/100g","J55":"Kcal/100ml","I92":"Kcal/200mL","990":"Kg","E75":"Kg/m3","437":"Listeria / 100 Cm2","436":"Listeria / 25 g","I72":"Listeria / 25ml ","994":"Listeria / manos","438":"Listeria / superficie","E73":"Listeria monocytogenes/325 g","I79":"Listeria spp.","992":"Lt.","A58":"LUX","I65":"mcg/100g","I66":"mcgRE/g","744":"meq de Ácido/Kg","741":"meq Peróxido /Kg Aceite","934":"meq/Kg","J13":"meq/L","562":"meqPeroxido / Kg Grasa","939":"mg","960":"mg / 100 ml AA","888":"mg Ac Ascorbico/100g","J10":"mg Ac Ascorbico/100ml","I78":"mg Ácido sórbico","I68":"mg C3H6O3","886":"mg CaCO3/L","947":"mg Imidacloprid/Kg","772":"mg Nitrógeno Amoniacal/100g","887":"mg Nitrógeno Amoniacal/L","J16":"mg/100 gr (Ac. Sulfúrico)","963":"mg/100 mL","867":"mg/100g","I74":"mg/100ml","I88":"mg/200mL","I75":"mg/240ml","J15":"mg/dm2","625":"mg/Kg","618":"mg/L","619":"mgCaO3/L","J57":"miliequivalentes/Kg","972":"min. 10^7","774":"ml","A41":"ml/L","967":"mm","995":"mmHg","435":"N° / 100 Ml","785":"NBV/100g","J14":"ng/g","J11":"NMP / 100 g","054":"NMP / 100 ml","052":"NMP / g","053":"NMP / ml","620":"NTU","623":"NUO","624":"NUS","919":"ºBaumé","738":"ºBe","566":"ºBrix","840":"ºC","966":"ºHaugh","962":"Org / 100 mL","609":"Org/L","996":"ºZ","740":"pH (10ºBrix)","739":"pH Solución 50%","632":"Positivo/Negativo","839":"ppb","564":"ppm","E78":"ppm de Oleato de Sodio","848":"Presencia/Ausencia","969":"Pseudomonas aeruginosa / 1 g","I70":"Pseudomonas aeruginosa/ 1g","987":"Pseudomonas/100ml","I69":"Pulg-Hg","838":"S. aureus / superficie","060":"Salmonella / 100Cm2","A49":"Salmonella / 25 ml","058":"Salmonella / 25g","998":"Salmonella / 4 superficies","868":"Salmonella / 50 g","959":"Salmonella / 750 g","059":"Salmonella / área","B24":"Salmonella / cm2","745":"Salmonella / manos","780":"Salmonella / superficie","A59":"Salmonella sp./ 25g","E72":"Salmonella/325 g","878":"Samonella / 100 ml","846":"Shigella/25 g","970":"Staphylococcus aureus / 1 g","753":"Staphylococcus aureus / 100 cm2","752":"Staphylococcus aureus / área","879":"Staphylococcus aureus / manos","933":"Staphylococcus aureus / superficie ","971":"Streptococcus / 10 g","622":"UCV escala Pt/Co","434":"UFC / 15 Min","997":"UFC / 4 superficies","056":"UFC / área","055":"UFC / Cm2","049":"UFC / g ","983":"UFC / g (Est.)","057":"UFC / manos","050":"UFC / ml","985":"UFC / ml (Est.)","750":"UFC / placa 40 cm2","758":"UFC / placa 60 cm2","779":"UFC / superficie","051":"UFC/ 100 ml","A47":"UFC/ 100g","978":"UFC/ ml 35ºC/48 h R2A","608":"UFC/15","616":"UFP/ml","927":"ug/100g","I76":"ug/100ml","I73":"ug/200ml","I89":"ug/200mL","I77":"ug/240ml","964":"ug/g","770":"ug/Kg","I71":"ug/L","A84":"ug/mL","I64":"ugRE/g","742":"UI","913":"UI/100g","I90":"UI/200mL","621":"umho/cm","J56":"unidades kertesz","986":"V. cholerae/manos","942":"Vibrio cholerae / 100 cm2","617":"Vibrio cholerae / 25 g","909":"Vibrio parahaemolyticus / 25 g"}'],
                      [5,'condi_espe', '{"Ausencia": "Ausencia", "=": "=", "<": "<", ">": ">", "<=": "<=", ">=": ">="}'],[6,'valor_espe'],[7,'valexpo_espe'],[8,'condi_resul', '{"Ausencia": "Ausencia", "=": "=", "<": "<", ">": ">", "<=": "<=", ">=": ">="}'],[9,'valor_resul'],[10,'valexpo_resul'],[11,'sresultado', '{"N": "NO CONFORME", "C": "CONFORME", "NA": "NO APLICA", "AA": "ALTO EN AZUCAR", "AS": "ALTO EN SODIO", "GS": "ALTO EN GRASAS SATURADAS", "GT": "CONTIENE GRASAS TRANS"}']]
        },
        onSuccess: function(data, textStatus, jqXHR) {
            objListaresult.viewList
        },
    });
};


objListaresultold.viewListold = function (id) {  
    var parametros = {
        "cinternoordenservicio" : id,
    };
    $.ajax({
       url      :  baseurl+"lab/resultados/cregresult/getlistresultadosold",
       method   : "GET",
       data     : parametros, 
    }).done(function(data){
        $('#tblListResultadosold tbody').html(data)
        objListaresultold.tableDataold();
        
        $("#tblListResultadosold thead .tabledit-edit-button").hide();
        $("#tblListResultadosold tbody .trcab .tabledit-edit-button").hide();
        $("#tblListResultadosold tbody .trtipo .tabledit-edit-button").hide();
    })
};
objListaresultold.tableDataold = function () {
    $('#tblListResultadosold').Tabledit({
        url:'lab/resultados/cregresult/setresultadosold',
        eventType: 'dblclick',
        editButton: true,
        deleteButton: false,
        saveButton: false,
        autoFocus: false,
        hideIdentifier: true,
		restoreButton: false,
        buttons: {
            edit: {
                class: 'btn btn-sm btn-primary',
                html: '<span class="fas fa-pencil-alt"></span>',
                action: 'edit'
            }
        },
        columns:{
            identifier : [0,'ID'],
            editable:[[4, 'unidadmedida', '{"565":"-","561":"%","784":"% (Ac. acético)","776":"% (Ac. cítrico)","611":"% (Ac. Láctico)","A43":"% (Ác. málico)","612":"% (Ac. Sulfúrico)","781":"% (Al 15% Humedad)","B61":"% (Amoniaco)","B29":"% del extracto seco","E76":"% Vol.","743":"%(Ac. Oleico)","849":"%(Ac. Palmitico)","941":"%p/p","999":"%V","850":"(20ºC)","872":"(25º)","A44":"(g/100g) Base Seca","782":"(mg KOH)/g)","943":"\ Hg","938":"°D","940":"°Z","991":"µg retinol/100g","873":"µs/cm","880":"Ausencia / 100 ml","563":"Ausencia / Presencia","906":"Ausencia en 25g / Presencia en 25g","976":"Ausencia o Presencia / 25 g","977":"Ausencia o Presencia/100g","A57":"Ausencia o Presencia/1L ","A42":"B. cereus / superficie","989":"CIO2 - mg/L","988":"CIO3 - mg/L","773":"cm","982":"Coliform bacilli en 25 g","945":"Coliformes / 1 g","912":"cp","944":"CP (25ºC)","A82":"cps (20ºC)","A54":"cps (25ºC)","629":"cps (50ºC)","A55":"cps (65ºC)","A56":"cps (97ºC)","631":"cTs (100ºC)","J12":"dS/m","851":"E. coli / 1 g","968":"E. coli / 1 g","918":"E. coli / 100cm2","I67":"E. coli / 25 g","626":"E. coli / área","627":"E. coli / cm2","760":"E. coli / manos","837":"E. coli / superficie","613":"E. coli O 157:H7 / 25 g","A48":"E. coli O 157:H7 / 25 ml","E74":"E. coli O 157:H7/325 g","981":"Enterobacterias / 1 g","610":"Estéril / No Estéril","737":"g","961":"g / L","957":"g/100g","845":"g/100g (Límite de Cuantificación: 0.08)","974":"g/100ml","955":"g/115g","956":"g/130g","I91":"g/200mL","946":"g/Kg","B28":"g/L (Ác. acético)","A60":"g/m2","771":"g/ml","783":"g/ml (20ºC)","630":"g/ml (53ºC)","628":"g/ml (65ºC)","980":"g/paquete","954":"g/porción","993":"gr/dm3","A53":"gr/ml ( 97ºC)","A51":"gr/ml (25ºC)","A52":"gr/ml (65ºC)","775":"Kcal/100g","J55":"Kcal/100ml","I92":"Kcal/200mL","990":"Kg","E75":"Kg/m3","437":"Listeria / 100 Cm2","436":"Listeria / 25 g","I72":"Listeria / 25ml ","994":"Listeria / manos","438":"Listeria / superficie","E73":"Listeria monocytogenes/325 g","I79":"Listeria spp.","992":"Lt.","A58":"LUX","I65":"mcg/100g","I66":"mcgRE/g","744":"meq de Ácido/Kg","741":"meq Peróxido /Kg Aceite","934":"meq/Kg","J13":"meq/L","562":"meqPeroxido / Kg Grasa","939":"mg","960":"mg / 100 ml AA","888":"mg Ac Ascorbico/100g","J10":"mg Ac Ascorbico/100ml","I78":"mg Ácido sórbico","I68":"mg C3H6O3","886":"mg CaCO3/L","947":"mg Imidacloprid/Kg","772":"mg Nitrógeno Amoniacal/100g","887":"mg Nitrógeno Amoniacal/L","J16":"mg/100 gr (Ac. Sulfúrico)","963":"mg/100 mL","867":"mg/100g","I74":"mg/100ml","I88":"mg/200mL","I75":"mg/240ml","J15":"mg/dm2","625":"mg/Kg","618":"mg/L","619":"mgCaO3/L","J57":"miliequivalentes/Kg","972":"min. 10^7","774":"ml","A41":"ml/L","967":"mm","995":"mmHg","435":"N° / 100 Ml","785":"NBV/100g","J14":"ng/g","J11":"NMP / 100 g","054":"NMP / 100 ml","052":"NMP / g","053":"NMP / ml","620":"NTU","623":"NUO","624":"NUS","919":"ºBaumé","738":"ºBe","566":"ºBrix","840":"ºC","966":"ºHaugh","962":"Org / 100 mL","609":"Org/L","996":"ºZ","740":"pH (10ºBrix)","739":"pH Solución 50%","632":"Positivo/Negativo","839":"ppb","564":"ppm","E78":"ppm de Oleato de Sodio","848":"Presencia/Ausencia","969":"Pseudomonas aeruginosa / 1 g","I70":"Pseudomonas aeruginosa/ 1g","987":"Pseudomonas/100ml","I69":"Pulg-Hg","838":"S. aureus / superficie","060":"Salmonella / 100Cm2","A49":"Salmonella / 25 ml","058":"Salmonella / 25g","998":"Salmonella / 4 superficies","868":"Salmonella / 50 g","959":"Salmonella / 750 g","059":"Salmonella / área","B24":"Salmonella / cm2","745":"Salmonella / manos","780":"Salmonella / superficie","A59":"Salmonella sp./ 25g","E72":"Salmonella/325 g","878":"Samonella / 100 ml","846":"Shigella/25 g","970":"Staphylococcus aureus / 1 g","753":"Staphylococcus aureus / 100 cm2","752":"Staphylococcus aureus / área","879":"Staphylococcus aureus / manos","933":"Staphylococcus aureus / superficie ","971":"Streptococcus / 10 g","622":"UCV escala Pt/Co","434":"UFC / 15 Min","997":"UFC / 4 superficies","056":"UFC / área","055":"UFC / Cm2","049":"UFC / g ","983":"UFC / g (Est.)","057":"UFC / manos","050":"UFC / ml","985":"UFC / ml (Est.)","750":"UFC / placa 40 cm2","758":"UFC / placa 60 cm2","779":"UFC / superficie","051":"UFC/ 100 ml","A47":"UFC/ 100g","978":"UFC/ ml 35ºC/48 h R2A","608":"UFC/15","616":"UFP/ml","927":"ug/100g","I76":"ug/100ml","I73":"ug/200ml","I89":"ug/200mL","I77":"ug/240ml","964":"ug/g","770":"ug/Kg","I71":"ug/L","A84":"ug/mL","I64":"ugRE/g","742":"UI","913":"UI/100g","I90":"UI/200mL","621":"umho/cm","J56":"unidades kertesz","986":"V. cholerae/manos","942":"Vibrio cholerae / 100 cm2","617":"Vibrio cholerae / 25 g","909":"Vibrio parahaemolyticus / 25 g"}'],
                      [5,'despecificacion'],[6,'despecificacionexp'],[7,'dresultado'],[8,'dresultadoexp'],[9,'sresultado', '{"N": "NO CONFORME", "C": "CONFORME", "NA": "NO APLICA", "AA": "ALTO EN AZUCAR", "AS": "ALTO EN SODIO", "GS": "ALTO EN GRASAS SATURADAS", "GT": "CONTIENE GRASAS TRANS"}']]
        },
        onSuccess: function(data, textStatus, jqXHR) {
            objListaresultold.viewListold
        },
    });
};



/*
getListResultados = function(id){         
    var parametros = {
        "cinternoordenservicio" : id,
    };  

    var groupColumn = 0;
    otblListResultados = $('#tblListResultados').DataTable({ 
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
            "url"   : baseurl+"lab/resultados/cregresult/getlistresultados",
            "type"  : "POST", 
            "data"  : parametros,     
            dataSrc : ''      
        }, 
        'columns'	: [
            {data: 'BLANCO', "className":"col-xxs"},
            {data: 'tipoensayo'},
            {data: 'codmuestra'},
            {data: 'drealproducto'},
            {data: 'censayofs'},
            {data: 'densayo'},
            {data: 'unidadmedida'},
            {data: 'ESPECIFICACION'},
            {data: 'RESULTADO'},
            {data: 'sresultado'},
            {data: 'dinfensayo'},
            {data: 'BLANCO'},
        ],
        "drawCallback": function ( settings ) {      
            var api = this.api();
            var tableRows = api.rows( {page:'current'} ).nodes();

            var lastGroup = null;

            var groupName = null;
            var groupName01 = null;

            api.column([0], {} ).data().each( function ( ctra, i ) {
                groupName = api.column(2).data()[i];
                groupName01 = api.column(3).data()[i];

                if ( lastGroup !== groupName ) {
                    $(tableRows).eq( i ).before(
                        '<tr class="group"> <td> </td> <td>'+groupName.toUpperCase()+'</td> <td colspan="7">'+groupName01.toUpperCase()+'</td> </tr>'
                    ); 
                    lastGroup = groupName;
                 }

            } );
        }   
    }); 
    otblListResultados.column(2).visible(false); 
    otblListResultados.column(3).visible(false); 
    otblListResultados.column(10).visible(false); 
    // Enumeracion 
    otblListResultados.on( 'order.dt search.dt', function () { 
        otblListResultados.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
          } );
    }).draw();    
};

$('#tblListResultados tbody').on('dblclick', 'td', function () {
    var tr = $(this).parents('tr');
    var row = otblListResultados.row(tr);
    var rowData = row.data();

    if(rowData.cinternoordenservicio){
        $("#modalIngresult").modal('show');
        $('#frmIngresult').trigger("reset");        

        $('#mhdncinternoordenservicio').val(rowData.cinternoordenservicio);
        $('#mhdncinternocotizacion').val(rowData.cinternocotizacion);
        $('#mhdnnversioncotizacion').val(rowData.nversioncotizacion);
        $('#mhdnnordenproducto').val(rowData.nordenproducto);
        $('#mhdncmuestra').val(rowData.cmuestra);
        $('#mhdncensayo').val(rowData.censayo);
        $('#mhdnnviausado').val(rowData.nviausado);

        document.querySelector('#nomtipoensayo').innerText = rowData.tipoensayo;
        document.querySelector('#muestra').innerText = rowData.codmuestra+' '+rowData.drealproducto;

        $('#mtxtcodensayo').val(rowData.censayofs);
        $('#mtxtnomensayo').val(rowData.densayo);
        
        $.ajax({
            type: 'ajax',
            method: 'post',
            url: baseurl+"lab/resultados/cregresult/getcboum",
            dataType: "JSON",
            async: true,
            success:function(result){
                $('#mcboum').html(result);
                $('#mcboum').val(rowData.zctipounidadmedida).trigger("change");
            },
            error: function(){
                alert('Error, No se puede autenticar por error cboum');
            }
        });
        
        if(rowData.condi_espe == 'Ausencia'){
            ausencia();  
        }else if(rowData.condi_espe == '='){     
            igual(); 
        }else if(rowData.condi_espe == '<'){     
            mayor(); 
        }else if(rowData.condi_espe == '>'){     
            menor(); 
        }else if(rowData.condi_espe == '<='){     
            mayorigual(); 
        }else if(rowData.condi_espe == '>='){     
            menorigual(); 
        }

        $('#mtxtvalor_esp').val(rowData.valor_espe);
        $('#mcbosexponente_esp').val(rowData.esexpo_espe).trigger("change");
        $('#mtxtvalorexpo_esp').val(rowData.valexpo_espe);
        
        if(rowData.condi_resul == 'Ausencia'){
            ausencia_resul();  
        }else if(rowData.condi_resul == '='){     
            igual_resul(); 
        }else if(rowData.condi_resul == '<'){     
            mayor_resul(); 
        }else if(rowData.condi_resul == '>'){     
            menor_resul(); 
        }else if(rowData.condi_resul == '<='){     
            mayorigual_resul(); 
        }else if(rowData.condi_resul == '>='){     
            menorigual_resul(); 
        }
        
        $('#mtxtvalor_resul').val(rowData.valor_resul);
        $('#mcbosexponente_resul').val(rowData.esexpo_resul).trigger("change");
        $('#mtxtvalorexpo_resul').val(rowData.valexpo_resul);
        $('#mcboresultado').val(rowData.sresultado).trigger("change");
    }else{
        alert('ok');
    }
    
    

    //$('#mhdnidempleado').val(rowData.cinternoordenservicio);
});

ausencia=function(){
    $('#btncondi').html("Ausencia");
    $('#mhdncondi').val("Ausencia");
};
igual=function(){
    $('#btncondi').html("=");
    $('#mhdncondi').val("=");
};
mayor=function(){
    $('#btncondi').html("<");
    $('#mhdncondi').val("<");
};
menor=function(){
    $('#btncondi').html(">");
    $('#mhdncondi_').val(">");
};
mayorigual=function(){
    $('#btncondi').html("<=");
    $('#mhdncondi').val("<=");
};
menorigual=function(){
    $('#btncondi').html(">=");
    $('#mhdncondi').val(">=");
};

ausencia_resul=function(){
    $('#btncondi_resul').html("Ausencia");
    $('#mhdncondi_res').val("Ausencia");
};
igual_resul=function(){
    $('#btncondi_resul').html("=");
    $('#mhdncondi_res').val("=");
};
mayor_resul=function(){
    $('#btncondi_resul').html("<");
    $('#mhdncondi_res').val("<");
};
menor_resul=function(){
    $('#btncondi_resul').html(">");
    $('#mhdncondi_res').val(">");
};
mayorigual_resul=function(){
    $('#btncondi_resul').html("<=");
    $('#mhdncondi_res').val("<=");
};
menorigual_resul=function(){
    $('#btncondi_resul').html(">=");
    $('#mhdncondi_res').val(">=");
};

$('#modalIngresult').on('show.bs.modal', function (e) {

    

});
*/

$('#btnRetornarLista').click(function(){
    $('#tablab a[href="#tablab-list"]').tab('show');  
});
/*
$('#tblListResultados tbody').on( 'dblclick', 'tr.group', function () {     
    var idmuestra = $(this).find("td.groupOt:first-child").text().substr(0,3);
    var idot = $('#txtidordenservicio').val();
    window.open(baseurl+"lab/resultados/cregresult/pdfInformeMuestra/"+idot+"/"+idmuestra);
    
} ); 
*/