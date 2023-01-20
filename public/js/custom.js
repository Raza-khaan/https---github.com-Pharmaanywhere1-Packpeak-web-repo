//multi-delete,multi-archive
$(document).ready(function () {
    if(parseInt($('#multidelete').length)>0)
    {
        var modelName=$('#multidelete').data('model');
        if(typeof(modelName)  === "undefined" || modelName.length<1){
            $('.box-body').prepend('<div class="alertMsg alert alert-info alert-dismissible">\
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Information!</strong> Model name is not defined.</div>');
            return false;
        }

        // $('#multidelete').find('thead>tr').prepend('<th style="width: 50px;">\
        // <i title="Un-Archive selected" data-title="unarchive" data-url="unarchiveAll" style="display:none;cursor:pointer;" id="unarchive_all" class="fa fa-file-archive-o text-blue multiFunc"></i><br/>\
        // <i title="Archive selected" data-title="archive" data-url="archiveAll"  style="display:none;cursor:pointer;" id="archive_all" class="fa fa-archive text-green multiFunc"></i><br/>\
        // <i title="Delete selected" data-title="delete" data-url="deleteAll"  style="display:none;cursor:pointer;" id="delete_all" class="fa fa-trash text-danger multiFunc"></i><br/><input type="checkbox" name="select_all" id="select_all"></th>');
        
        $('#multidelete').find('thead>tr').prepend('<th style="width: 50px;">\
        <i title="Archive selected" data-title="archive" data-url="archiveAll"  style="display:none;cursor:pointer;" id="archive_all" class="fa fa-archive text-green multiFunc"></i>&nbsp\
        <i title="Delete selected" data-title="delete" data-url="deleteAll"  style="display:none;cursor:pointer;" id="delete_all" class="fa fa-trash text-danger multiFunc"></i><br/>Select<input type="checkbox" name="select_all" id="select_all"></th>');
        
        $('#multidelete').dataTable({ 
            lengthChange: true,
            language: {
                 // search: '<i class="fa fa-search"></i>',
                  searchPlaceholder: "search",
                 },
  
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options
            columnDefs: [ {
              orderable: false,
              sorting: false,
              className: 'select-checkbox',
              targets:   0
              } ],
              select: {
                  style:    'os',
                  selector: 'td:first-child'
              },
              dom: '<"top"if>Brt<"bottom"p>l',
              // dom: 'f<>Brtpl',
              buttons: [
                 
                  {
                  extend: 'collection',
                  text: 'Export',
                  buttons: [
                      'copy',
                      'excel',
                      'csv',
                      'pdf',
                      'print'
                  ]
                  },
              ],
               //select: true,
          });
          
    
        $('#select_all').on('change',function(){
        
            if(this.checked){
                $('.chk').each(function(){
                    $('#delete_all,#archive_all,#unarchive_all').show();
                    this.checked = true;
                });
            }else{
                $('#delete_all,#archive_all,#unarchive_all').hide();
                $('.chk').each(function(){
                    this.checked = false;
                });
            }
        });
        
        $('.chk').on('click',function(){
            
            if($('.chk:checked').length>0){
                $('#delete_all,#archive_all,#unarchive_all').show();
            }else{
                $('#delete_all,#archive_all,#unarchive_all').hide();
            }
            if($('.chk:checked').length == $('.chk').length){
                
                $('#select_all').prop('checked',true);
            }else{
            
                $('#select_all').prop('checked',false);
            }
        });

        $('.multiFunc').click(function(){
            $(".alertMsg").remove(); 
           // alert($(this).data('url'));return false;
            let deleteIds=[];
            $('.chk:checked').each(function(k,v){
                deleteIds.push($(v).val());
            });

            if(deleteIds.length<1)
            {    
               
                $('.box-body').prepend('<div class="alertMsg alert alert-info alert-dismissible">\
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Information!</strong> Please select records.</div>');
                return false; 
            }else{
                var _this = $(this);
                bootbox.confirm({
                    message: "Do you want to "+_this.data('title')+" these records ?",
                    size: 'small',
                    buttons: {
                        confirm: {
                            label: '<i class="fa fa-check"></i> Confirm',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancel',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        
                        //console.log('This was logged in the callback: ' + _this.data('url'));
                        
                        if(result){
                            $.ajax({
                                type: "POST",
                                url: "./"+_this.data('url')+"/"+modelName,
                                data: {'deleteIds':deleteIds},
                                headers: {
                                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                                },
                                success: function(result){
                                
                                    if(result=='200'){
                                        //if(_this.data('title')=='delete'){

                                            $('.chk:checked').closest('tr').fadeOut().remove();
                                        //}
                                        
                                        $('.box-body').prepend('<div class="alertMsg alert alert-success alert-dismissible">\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> Records '+_this.data('title')+' successfully.</div>');
                                    }
                                    else{ 
                                    
                                        $('.box-body').prepend('<div class="alertMsg alert alert-danger alert-dismissible">\
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Failure!</strong> Please try again, something went wrong.</div>');
                                    }
            
                                    
                                }
                            });
                            window.setTimeout(function () { 
                                $(".alertMsg").fadeOut(); 
                            }, 4000); 
                        }
                    }
                });

                
            }
        });
    }

    if(parseInt($('#tbDatatable').length)>0)
    {
        var modelName=$('#tbDatatable').data('model');
        if(typeof(modelName)  === "undefined" || modelName.length<1){
            $('.box-body').prepend('<div class="alertMsg alert alert-info alert-dismissible">\
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Information!</strong> Model name is not defined.</div>');
            return false;
        }

        // $('#multidelete').find('thead>tr').prepend('<th style="width: 50px;">\
        // <i title="Un-Archive selected" data-title="unarchive" data-url="unarchiveAll" style="display:none;cursor:pointer;" id="unarchive_all" class="fa fa-file-archive-o text-blue multiFunc"></i><br/>\
        // <i title="Archive selected" data-title="archive" data-url="archiveAll"  style="display:none;cursor:pointer;" id="archive_all" class="fa fa-archive text-green multiFunc"></i><br/>\
        // <i title="Delete selected" data-title="delete" data-url="deleteAll"  style="display:none;cursor:pointer;" id="delete_all" class="fa fa-trash text-danger multiFunc"></i><br/><input type="checkbox" name="select_all" id="select_all"></th>');
        
       
        $('#tbDatatable').dataTable({ 
            "dom": 'Bfrtip',
            "responsive": true,
            "bSort": true,
            "lengthChange": true,
            
            'order': [[1, 'asc']],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options
            "buttons": [ 
                
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'pageLength','colvis'
            ],
            
        });
    }

})
