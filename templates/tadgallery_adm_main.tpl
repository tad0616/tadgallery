<div class="container-fluid">

  <{$formValidator_code|default:''}>
  <form action="main.php" method="post" id="myForm" name="form1" class="form-horizontal" role="form">
    <div class="row">
      <div class="col-sm-3">
        <div style="height: 300px; overflow: auto;">
          <{$ztree_code|default:''}>
        </div>

        <{if $now_op!="tad_gallery_cate_form"}>
          <style>
            #sortable { list-style-type: none;  }
            #sortable li { list-style-type: none; height: 150px; margin: 6px; float:left}
            .thumb_func{background-color: rgb(234,234,234); padding: 4px;}
          </style>

          <script language="JavaScript">
            $().ready(function(){
              $(".thumb").thumbs();
              $("#sortable").sortable({ opacity: 0.6, cursor: 'move', update: function(){
                var order = $(this).sortable("serialize");
                $.post("save_sort.php", order, function(theResponse){
                  $("#save_msg").html(theResponse);
                });
              }
              });

              $("#clickAll").click(function() {
                var x = document.getElementById("clickAll").checked;
                if(x){
                  $(".photo").each(function() {
                    $(this).attr("checked", true);
                  });
                }else{
                 $(".photo").each(function() {
                     $(this).attr("checked", false);
                 });
                }
              });


            });


            function check_one(id_name,change){
              if(document.getElementById(id_name).checked && change){
                document.getElementById(id_name).checked = false;
              }else{
                document.getElementById(id_name).checked = true;
              }
            }
          </script>
          <div class="d-grid gap-2" style="margin-bottom: 30px;">
            <a href="main.php?op=tad_gallery_cate_form" class="btn btn-info btn-block"><{$smarty.const._MA_TADGAL_ADD_CATE}></a>
          </div>

          <h3><{$smarty.const._MA_TADGAL_THE_ACT_IS}></h3>
          <div class="form-group row mb-3">
              <label class="col-sm-3 radio">
                <input type="radio" name="op" value="del" id="del">
                <{$smarty.const._TAD_DEL}>
              </label>

              <label class="col-sm-4 radio">
                <input type="radio" name="op" value="add_good" id="add_good">
                <{$smarty.const._MA_TADGAL_ADD_GOOD}>
              </label>

              <label class="col-sm-4 radio">
                <input type="radio" name="op" value="del_good" id="del_good">
                <{$smarty.const._MA_TADGAL_DEL_GOOD}>
              </label>
          </div>

          <div class="form-group row mb-3">
            <label class="col-sm-4 radio">
              <input type="radio" name="op" value="move" id="move">
              <{$smarty.const._MA_TADGAL_MOVE_TO}>
            </label>
            <div class="col-sm-8">
              <select name="new_csn" onChange="check_one('move',false)"  class="form-control" title="select cate">
                <{$option|default:''}>
              </select>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-sm-4 radio">
              <input type="radio" name="op" value="add_title" id="add_title">
              <{$smarty.const._MA_TADGAL_ADD_TITLE}>
            </label>

            <div class="col-sm-8">
              <input type="text" name="add_title"  class="form-control" onClick="check_one('add_title',false)" onkeypress="check_one('add_title',false)">
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-sm-4 radio">
              <input type="radio" name="op" value="add_description" id="add_description">
              <{$smarty.const._MA_TADGAL_ADD_DESCRIPTION}>
            </label>

            <div class="col-sm-8">
              <textarea name="add_description"  class="form-control" onClick="check_one('add_description',false)" onkeypress="check_one('add_description',false)"></textarea>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-sm-4 radio">
              <input type="radio" name="op" value="remove_tag" id="remove_tag">
              <{$smarty.const._MA_TADGAL_REMOVE_TAG}>
            </label>
          </div>

          <div class="form-group row mb-3">
            <label class="col-sm-4 radio">
              <input type="radio" name="op" value="add_tag" id="add_tag">
              <{$smarty.const._MA_TADGAL_TAG}>
            </label>

            <div class="col-sm-8">
              <input type="text" name="new_tag" class="form-control" placeholder="<{$smarty.const._MA_TADGAL_TAG_TXT}>" onClick="check_one('add_tag',false)" onkeypress="check_one('add_tag',false)">
            </div>
          </div>

          <div class="form-group row mb-3">
            <{$tag_select|default:''}>
          </div>

          <input type="hidden" name="csn" value="<{$csn|default:''}>">
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-block"><{$smarty.const._MA_TADGAL_GO}></button>
          </div>
        <{/if}>
      </div>

      <div class="col-sm-9">
        <{if $csn!="" }>
          <div class="row">
            <div class="col-sm-4">
              <h1>
                <{$cate.title}>
                <small>
                    <{if $mode_select=="good"}>
                      <{$smarty.const._MA_TADGAL_LIST_NORMAL}>
                    <{else}>
                      <{$smarty.const._MA_TADGAL_LIST_GOOD}>
                    <{/if}>
                </small>
              </h1>
            </div>
            <div class="col-sm-8 text-right text-end">
              <div style="margin-top: 10px;">
                <div class="btn-group">
                  <{if $now_op!="tad_gallery_cate_form"}>

                    <{if $mode_select=="good"}>
                      <a href='main.php?op=chg_mode&mode=good#gallery_top' class='btn btn-sm btn-warning'><{$smarty.const._MA_TADGAL_LIST_GOOD}></a>
                    <{else}>
                      <a href='main.php?op=chg_mode&mode=normal#gallery_top' class='btn btn-sm btn-warning'><{$smarty.const._MA_TADGAL_LIST_NORMAL}></a>
                    <{/if}>

                    <{if $link_to_cate|default:false}>
                      <a href='../index.php?csn=<{$cate.csn}>' class='btn btn-sm btn-info'><{$link_to_cate|default:''}></a>
                    <{/if}>

                    <a href="main.php?op=re_thumb&csn=<{$cate.csn}>" class="btn btn-sm btn-success"><{$smarty.const._MA_TADGAL_RE_CREATE_THUMBNAILS_ALL}></a>
                    <a href="main.php?op=re_thumb&kind=m&csn=<{$cate.csn}>" class="btn btn-sm btn-success"><{$smarty.const._MA_TADGAL_RE_CREATE_THUMBNAILS_M}></a>
                    <a href="main.php?op=re_thumb&kind=s&csn=<{$cate.csn}>" class="btn btn-sm btn-success"><{$smarty.const._MA_TADGAL_RE_CREATE_THUMBNAILS_S}></a>
                    <a href="javascript:delete_tad_gallery_cate_func(<{$cate.csn}>);" class="btn btn-sm btn-danger <{if $cate_count.$csn > 0}>disabled<{/if}>"><{$smarty.const._TAD_DEL}></a>
                    <a href="main.php?op=tad_gallery_cate_form&csn=<{$cate.csn}>" class="btn btn-sm btn-warning"><{$smarty.const._TAD_EDIT}></a>
                  <{/if}>
                </div>
              </div>
            </div>
          </div>
          <{if $cate.content|default:false}>
            <div class="alert alert-info">
              <{$cate.content}>
            </div>
          <{/if}>
        <{else}>
          <div class="row">
            <div class="col-sm-4">
              <h3>
                <{if $gallery_list_mode=="good"}>
                  <{$smarty.const._MA_TADGAL_LIST_GOOD}>
                <{else}>
                  <{$smarty.const._MA_TADGAL_LIST_NORMAL}>
                <{/if}>
              </h3>
            </div>
            <div class="col-sm-8 text-right text-end">
              <div style="margin-top: 10px;">
                <div class="btn-group">
                  <{if $now_op!="tad_gallery_cate_form"}>

                    <{if $mode_select=="good"}>
                      <a href='main.php?op=chg_mode&mode=good#gallery_top' class='btn btn-sm btn-warning'><{$smarty.const._MA_TADGAL_LIST_GOOD}></a>
                    <{else}>
                      <a href='main.php?op=chg_mode&mode=normal#gallery_top' class='btn btn-sm btn-warning'><{$smarty.const._MA_TADGAL_LIST_NORMAL}></a>
                    <{/if}>

                    <{if $link_to_cate|default:false}>
                      <a href='../index.php?csn=<{$cate.csn}>' class='btn btn-sm btn-info'><{$link_to_cate|default:''}></a>
                    <{/if}>
                  <{/if}>
                </div>
              </div>
            </div>
          </div>
        <{/if}>

        <{if $now_op=="tad_gallery_cate_form"}>
          <style>
            .csn_menu,.of_csn_menu,.m_csn_menu,.p_csn_menu{
              width: 130px;
              padding: 4px;
              border: 1px solid gray;
              font-size: 0.75em;
            }
          </style>

          <script>
            $(document).ready(function(){
              <{if $path_arr|default:false}>
                <{foreach from=$path_arr key=i item=sn}>
                  make_option('of_csn_menu','<{$i|default:''}>','<{$sn.of_csn}>','<{$sn.def_csn}>');
                <{/foreach}>
              <{else}>
                make_option('of_csn_menu',0,0,0);
              <{/if}>
            });

            function make_option(menu_name , num , of_csn , def_csn){
              $('#'+menu_name+num).show();
              $.post('../ajax_menu.php',  {'of_csn': of_csn , 'def_csn': def_csn, 'chk_view': 0, 'chk_up': 0} , function(data) {
                $('#'+menu_name+num).html("<option value=''>/</option>"+data);
              });

              $('.'+menu_name).change(function(){
              var menu_id= $(this).attr('id');
              var len=menu_id.length-1;
              var next_num = Number(menu_id.charAt(len))+1
                var next_menu = menu_name + next_num;
                $.post('../ajax_menu.php',  {'of_csn': $('#'+menu_id).val(), 'chk_view': 0, 'chk_up': 0} , function(data) {
                  if(data==""){
                    $('#'+next_menu).hide();
                  }else{
                    $('#'+next_menu).show();
                    $('#'+next_menu).html("<option value=''>/</option>"+data);
                  }
                });
              });
            }
          </script>

          <div class="form-group row mb-3">
            <label class="col-sm-2 col-form-label text-sm-right">
              <{$smarty.const._MA_TADGAL_OF_CSN}>
            </label>
            <div class="col-sm-10">
              <select name="of_csn_menu[0]" id="of_csn_menu0" class="of_csn_menu" ><option value=''></option></select>
              <select name="of_csn_menu[1]" id="of_csn_menu1" class="of_csn_menu" style="display: none;"></select>
              <select name="of_csn_menu[2]" id="of_csn_menu2" class="of_csn_menu" style="display: none;"></select>
              <select name="of_csn_menu[3]" id="of_csn_menu3" class="of_csn_menu" style="display: none;"></select>
              <select name="of_csn_menu[4]" id="of_csn_menu4" class="of_csn_menu" style="display: none;"></select>
              <select name="of_csn_menu[5]" id="of_csn_menu5" class="of_csn_menu" style="display: none;"></select>
              <select name="of_csn_menu[6]" id="of_csn_menu6" class="of_csn_menu" style="display: none;"></select>
            </div>
          </div>


          <div class="form-group row mb-3">
            <label class="col-sm-2 col-form-label text-sm-right">
              <{$smarty.const._MA_TADGAL_TITLE}>
            </label>
            <div class="col-sm-4">
              <input type="text" name="title" class="validate[required] form-control" value="<{$title|default:''}>" placeholder="<{$smarty.const._MA_TADGAL_TITLE}>">
            </div>

            <label class="col-sm-2 col-form-label text-sm-right">
              <{$smarty.const._MA_TADGAL_PASSWD}>
            </label>
            <div class="col-sm-4">
              <input type="text" name="passwd" class="form-control" value="<{$passwd|default:''}>" placeholder="<{$smarty.const._MA_TADGAL_PASSWD_DESC}>">
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-sm-2 col-form-label text-sm-right">
              <{$smarty.const._MA_TADGAL_EDIT_CATE_CONTENT}>
            </label>
            <div class="col-sm-10">
              <textarea name="content" class="form-control"><{$content|default:''}></textarea>
            </div>
          </div>

          <div class="form-group row mb-3">
            <label class="col-sm-2 col-form-label text-sm-right">
              <{$smarty.const._MA_TADGAL_ENABLE_GROUP}>
            </label>
            <div class="col-sm-4">
              <{$enable_group|default:''}>
            </div>

            <label class="col-sm-2 col-form-label text-sm-right">
              <{$smarty.const._MA_TADGAL_ENABLE_UPLOAD_GROUP}>
            </label>
            <div class="col-sm-4">
              <{$enable_upload_group|default:''}>
            </div>
          </div>


          <div class="form-group row mb-3">
            <label class="col-sm-2 col-form-label text-sm-right">
              <{$smarty.const._MA_TADGAL_CATE_SHOW_MODE}>
            </label>
            <div class="col-sm-4">
              <select name="show_mode" class="form-control" size=6>
                <{$cate_show_option|default:''}>
              </select>
            </div>


            <{if $csn|default:false}>
              <label class="col-sm-2 col-form-label text-sm-right">
                <{$smarty.const._MA_TADGAL_COVER}>
              </label>

              <div class="col-sm-2">
                <img src="<{$cover_default|default:''}>" id="pic" class="rounded img-fluid" alt="<{$smarty.const._MA_TADGAL_COVER}>" style="margin-top: 20px;">
              </div>

              <div class="col-sm-2">
                <select class="form-control" name="cover" size=6 onChange="document.getElementById('pic').src='<{$smarty.const.XOOPS_URL}>/uploads/tadgallery/'+ this.value" title="select cover">
                  <{$cover_select|default:''}>
                </select>
              </div>
            <{/if}>
          </div>

          <div class="form-group row mb-3">
            <div class="text-center">
              <input type="hidden" name="sort" value="<{$sort|default:''}>">
              <input type="hidden" name="csn" value="<{$csn|default:''}>">
              <input type="hidden" name="op" value="<{$op|default:''}>">
              <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
            </div>
          </div>
        <{else}>
          <div class="row">
            <{if $photo|default:false}>
              <label class="checkbox col-sm-2">
                <input type="checkbox" id="clickAll" >
                <{$smarty.const._MA_TADGAL_SELECT_ALL}>
              </label>
            <{/if}>

            <div id="save_msg"></div>
          </div>

          <{if $photo|default:false}>
            <div class="row">
              <ul id="sortable">
                <{foreach from=$photo item=pic}>
                  <li id="recordsArray_<{$pic.sn}>" class="thumb_func">
                    <a href="javascript:check_one('p<{$pic.sn}>',true)" class="thumb">
                      <img data-src="<{$pic.photo_s}>" src="<{$pic.photo_s}>" <{if $pic.title|default:false}>alt="<{$pic.title}><{else}>sort:<{$pic.photo_sort}><{/if}>" title="sort:<{$pic.photo_sort}>">
                    </a>
                    <div style="font-size: 0.8rem; width:130px; word-break: break-all;">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input p<{$pic.db_csn}> photo" type="checkbox" name="pic[]" id="p<{$pic.sn}>" value="<{$pic.sn}>">
                          <label class="form-check-label" for="p<{$pic.sn}>" style="line-height: 1;">
                            <{if $pic.good=='1'}><i class="fa fa-star text-warning" aria-hidden="true"></i>
                            <{/if}>
                            <{$pic.filename}>
                          </label>
                        </div>
                    </div>
                  </li>
                <{/foreach}>
              </ul>
            </div>
          <{else}>
            <div class="row">
              <div class="alert alert-warning" style="margin-top: 30px;"><{$smarty.const._MA_TADGAL_NEED_CATE}></div>
            </div>
          <{/if}>
        <{/if}>
      </div>
    </div>
  </form>
</div>