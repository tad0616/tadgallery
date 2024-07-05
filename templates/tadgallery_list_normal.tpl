<{include file="db:tadgallery_list_header.tpl"}>

<!--相片-->
<h2 style="display:none;">List Photos</h2>
<{if $photo}>
    <div id="tg_container">
        <{foreach from=$photo item=pic}>
            <div class='PhotoCate' id="PhotoCate_<{$pic.sn}>">
            <{if $pic.is360}>
                <a class='Photo360' href="360.php?sn=<{$pic.sn}>&file=<{$pic.photo_l}>" id="item_photo_<{$pic.sn}>" title="<{$pic.sn}>"  data-author="<{$pic.author}>">
                    <div style="width:125px; height:100px; background: white url('<{$pic.photo_m}>') no-repeat center center; cursor: pointer; margin: 0px auto; background-size: cover;" class="show_photo">
                        <span class="fa-stack">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-street-view fa-stack-1x fa-inverse"></i>
                        </span>
                    </div>
            <{else}>
                <a class="Photo" id="item_photo_<{$pic.sn}>" title="<{$pic.sn}>" data-photo="<{$pic.photo_l}>" data-author="<{$pic.author}>"  alt="<{$pic.author}>" href="<{$pic.photo_l}>">
                    <div style="width:125px; height:100px; background: white url('<{$pic.photo_s}>') no-repeat center center; cursor: pointer; margin: 0px auto; background-size: cover;" class="show_photo"><span class="sr-only visually-hidden">photo-<{$pic.sn}></span>
                    </div>
            <{/if}>

                <div class="pic_title2"><{$pic.title}></div>
                <span class="sr-only visually-hidden">photo:<{$pic.sn}></span>
            </a>
            <{if $pic.photo_del}>
                <button onclick="javascript:delete_tad_gallery_func(<{$pic.sn}>)" class="btn btn-sm btn-danger" style="position:absolute;bottom:2px;left:2px;display:none;"><{$smarty.const._TAD_DEL}></button>
            <{/if}>

            <{if $pic.photo_edit}>
                <button href="ajax.php?op=edit_photo&sn=<{$pic.sn}>" class="btn btn-sm btn-warning fancybox fancybox.ajax editbtn" style="position:absolute;bottom:2px;right:2px;display:none;"><{$smarty.const._TAD_EDIT}></button>
            <{/if}>
            </div>
        <{/foreach}>
    </div>

    <div class="clearfix"></div>

    <div class="bar">
      <{$bar}>
    </div>


    <script type="text/javascript" src="<{$xoops_url}>/modules/tadgallery/class/jquery.animate-shadow.js"></script>

    <script>
        $(function(){
            $('.Photo').colorbox({rel:'group', photo:true, maxWidth:'100%', maxHeight:'100%', title: function(){
                var author= $(this).data('author');
                var sn = $(this).attr('title');
                return '<a href="view.php?sn=' + sn + '#photo' + sn + '" target="_blank"><{$smarty.const._MD_TADGAL_VIEW_PHOTO}></a> post by '+ author;
                }});

                $('.Photo360').colorbox({rel:'group', iframe:true, width:"90%", height:"90%", maxWidth:'100%', maxHeight:'100%', title: function(){
                var author= $(this).data('author');
                var sn = $(this).attr('title');
                return '<a href="view.php?sn=' + sn + '#photo' + sn + '" target="_blank"><{$smarty.const._MD_TADGAL_VIEW_PHOTO}></a> post by '+ author;
                }});
            });

        function delete_tad_gallery_func(sn){
            var sure = window.confirm('<{$smarty.const._TAD_DEL_CONFIRM}>');
            if (!sure)  return;
            //location.href="ajax.php?op=delete_tad_gallery&sn=" + sn;
            $.post("ajax.php", { op: "delete_tad_gallery", sn: sn },
                function(data) {
                $('#PhotoCate_'+sn).remove();
            });
        }
    </script>


<{elseif $csn}>
    <div class="alert alert-danger">
        <{$smarty.const._MD_TADGAL_EMPTY}>
    </div>
<{/if}>