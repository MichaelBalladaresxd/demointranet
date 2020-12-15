
var otblListEquipos, otblListRecintos;
var vccliente = $('#hdnccliente').val(); 

$(document).ready(function() { 
    listEquipo();  
    listRecintos();   
});

listEquipo= function(){    

    otblListEquipos = $('#tblListEquipos').DataTable({  
        'responsive'    : false,
        'bJQueryUI'     : true,
        'scrollY'     	: '200px',
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
            "url"   : baseurl+"pt/cservcliente/getcocsechorequipo/",
            "type"  : "POST", 
            "data": function ( d ) {
                d.ccliente      = vccliente;
            },     
            dataSrc : ''        
        },
        'columns'	: [
            {
              "class"     :   "index",
              orderable   :   false,
              data        :   null,
              targets     :   0
            },
            {"orderable": false, data: 'TIPO'},
            {"orderable": false, data: 'NROEQUIPOS'},
            {"orderable": false, data: 'AREAEVAL'},
            {"orderable": false, data: 'VOLENFRIA'},
            {"orderable": false, 
                render:function(data, type, row){
                    return '<div>'+
                    '</div>'
                }
            },            
            {"orderable": false, 
                render:function(data, type, row){
                    return '<div>'+
                    '<a data-toggle="modal" title="Ver" style="cursor:pointer; color:#3c763d;" data-target="#modalVerequi" onClick="javascript:selEquipo(\''+row.IDPROPU+'\',\''+row.CODCLIENTE+'\',\''+row.NROPROPU+'\',\''+row.FECHPROPU+'\',\''+row.IDSERV+'\',\''+row.DETAPROPU+'\',\''+row.COSTOTOTAL+'\',\''+row.ESTPROPU+'\',\''+row.CONTACTO+'\',\''+row.OBSPROPU+'\',\''+row.SERVNEW+'\',\''+row.CLIPOTEN+'\',\''+row.IDUSUARIO+'\',\''+row.TIPOCOSTO+'\',\''+row.ARCHIVO+'\',\''+row.CODESTABLE+'\',\''+row.RUTA+'\',\''+row.NOMBARCH+'\');"><span class="fas fa-edit" aria-hidden="true"> </span> </a>'+
                    '</div>'
                }
            },             
            {"orderable": false, 
                render:function(data, type, row){
                    return '<div>'+
                    '<a data-toggle="modal" title="Ver" style="cursor:pointer; color:#3c763d;" data-target="#modalVerequi" onClick="javascript:selEquipo(\''+row.IDPROPU+'\',\''+row.CODCLIENTE+'\',\''+row.NROPROPU+'\',\''+row.FECHPROPU+'\',\''+row.IDSERV+'\',\''+row.DETAPROPU+'\',\''+row.COSTOTOTAL+'\',\''+row.ESTPROPU+'\',\''+row.CONTACTO+'\',\''+row.OBSPROPU+'\',\''+row.SERVNEW+'\',\''+row.CLIPOTEN+'\',\''+row.IDUSUARIO+'\',\''+row.TIPOCOSTO+'\',\''+row.ARCHIVO+'\',\''+row.CODESTABLE+'\',\''+row.RUTA+'\',\''+row.NOMBARCH+'\');"><span class="fas fa-edit" aria-hidden="true"> </span> </a>'+
                    '</div>'
                }
            },
        ]
    });   
    // Enumeracion 
    otblListEquipos.on( 'order.dt search.dt', function () { 
        otblListEquipos.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
          } );
    }).draw();  
};

listRecintos= function(){    

    otblListRecintos = $('#tblListRecintos').DataTable({  
        'responsive'    : false,
        'bJQueryUI'     : true,
        'scrollY'     	: '200px',
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
            "url"   : baseurl+"pt/cservcliente/getcocsechorrecinto/",
            "type"  : "POST", 
            "data": function ( d ) {
                d.ccliente      = vccliente;
            },     
            dataSrc : ''        
        },
        'columns'	: [
            {
              "class"     :   "index",
              orderable   :   false,
              data        :   null,
              targets     :   0
            },
            {"orderable": false, data: 'TIPO'},
            {"orderable": false, data: 'NRORECINTOS'},
            {"orderable": false, data: 'AREAEVAL'},
            {"orderable": false, data: 'VOLENFRIA'},
            {"orderable": false, 
                render:function(data, type, row){
                    return '<div>'+
                    '</div>'
                }
            },            
            {"orderable": false, 
                render:function(data, type, row){
                    return '<div>'+
                    '<a data-toggle="modal" title="Ver" style="cursor:pointer; color:#3c763d;" data-target="#modalVerequi" onClick="javascript:selEquipo(\''+row.IDPROPU+'\',\''+row.CODCLIENTE+'\',\''+row.NROPROPU+'\',\''+row.FECHPROPU+'\',\''+row.IDSERV+'\',\''+row.DETAPROPU+'\',\''+row.COSTOTOTAL+'\',\''+row.ESTPROPU+'\',\''+row.CONTACTO+'\',\''+row.OBSPROPU+'\',\''+row.SERVNEW+'\',\''+row.CLIPOTEN+'\',\''+row.IDUSUARIO+'\',\''+row.TIPOCOSTO+'\',\''+row.ARCHIVO+'\',\''+row.CODESTABLE+'\',\''+row.RUTA+'\',\''+row.NOMBARCH+'\');"><span class="fas fa-edit" aria-hidden="true"> </span> </a>'+
                    '</div>'
                }
            },             
            {"orderable": false, 
                render:function(data, type, row){
                    return '<div>'+
                    '<a data-toggle="modal" title="Ver" style="cursor:pointer; color:#3c763d;" data-target="#modalVerequi" onClick="javascript:selEquipo(\''+row.IDPROPU+'\',\''+row.CODCLIENTE+'\',\''+row.NROPROPU+'\',\''+row.FECHPROPU+'\',\''+row.IDSERV+'\',\''+row.DETAPROPU+'\',\''+row.COSTOTOTAL+'\',\''+row.ESTPROPU+'\',\''+row.CONTACTO+'\',\''+row.OBSPROPU+'\',\''+row.SERVNEW+'\',\''+row.CLIPOTEN+'\',\''+row.IDUSUARIO+'\',\''+row.TIPOCOSTO+'\',\''+row.ARCHIVO+'\',\''+row.CODESTABLE+'\',\''+row.RUTA+'\',\''+row.NOMBARCH+'\');"><span class="fas fa-edit" aria-hidden="true"> </span> </a>'+
                    '</div>'
                }
            },
        ]
    });   
    // Enumeracion 
    otblListRecintos.on( 'order.dt search.dt', function () { 
        otblListRecintos.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
          cell.innerHTML = i+1;
          } );
    }).draw();  
};



