jQuery(document).ready(function($){/** Select Multiple Categories **/
   $('.ex-cat-wrap input:checkbox').on('change', function (e) {
       e.preventDefault();
       var chkbox = $(this).parents('.ex-cat-wrap').find('input:checkbox');//$('#ex-cat-wrap input:checkbox');
       var id = '';
       
       $.each( chkbox, function () {
           var oid = $(this).val(); 
           
           if($(this).attr('checked')) {
               id += oid;
               id += ','; 
           }
       });
       
       $(this).parents('.ex-cat-wrap').next('input:hidden').val(id).change();
   });
   
   });