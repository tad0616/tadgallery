<script type="text/javascript">

    $(document).ready(function(){
        make_option('m_csn_menu',0,0,<{if $def_csn|default:false}><{$def_csn}><{else}>0<{/if}>);
        make_option('csn_menu',0,0,<{if $def_csn|default:false}><{$def_csn}><{else}>0<{/if}>);
    });

    function make_option(menu_name , num , of_csn , def_csn){
        console.log("menu_name="+menu_name+" , num="+num+" , of_csn="+of_csn+" , def_cs="+def_csn);

        $('#'+menu_name+num).show();
            $.post('ajax_menu.php',  {'of_csn': of_csn , 'def_csn': def_csn} , function(data) {
            $('#'+menu_name+num).html("<option value=''>/</option>"+data);
        });

        $('.'+menu_name).change(function(){
            var menu_id= $(this).attr('id');
            var len=menu_id.length-1;
            var next_num = Number(menu_id.charAt(len))+1
            var next_menu = menu_name + next_num;
            var of_csn= $('#' + menu_id).val();

            console.log("menu_id=" + menu_id + " , len=" + len + " , next_num=" + next_num + " , next_menu=" + next_menu + " , of_csn=" +of_csn );

            $.post('ajax_menu.php',  {'of_csn': of_csn} , function(data) {
                if(data=="" || of_csn==''){
                    console.log("hide "+next_menu);
                    $('#'+next_menu).hide();
                }else{
                    console.log("data ="+data);
                    $('#'+next_menu).show();
                    $('#'+next_menu).html("<option value=''>/</option>"+data);
                }

            });
        });
    }

    function chk_csn(csn, new_csn){
        if(csn=="0" && new_csn==""){
            alert("<{$smarty.const._MD_TADGAL_NEED_CATE}>");
            return false;
        }else{
            return true;
        }
    }
</script>

<h2><{$smarty.const._MD_TADGAL_UPLOAD_PAGE}></h2>