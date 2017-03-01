<{includeq file="db:tadgallery_list_header.tpl"}>

<link rel="stylesheet" href="<{$xoops_url}>/modules/tadgallery/class/justifiedGallery/justifiedGallery.min.css" type="text/css" media="all" />
<script type="text/javascript" src="<{$xoops_url}>/modules/tadgallery/class/justifiedGallery/jquery.justifiedGallery.min.js"></script>

<!--相片-->
<{if $photo}>
  <div class="row">
    <div class="col-sm-12">
      <div id="tg_container">
        <{foreach item=photo from=$photo}>
          <div>
            <{if $photo.is360}>
              <a href="360.php?sn=<{$photo.sn}>&file=<{$photo.photo_l}>" title="<{$photo.sn}>" rel="pic_group" class="photo_link Photo360">
                <img alt="<{$photo.title}>"  src="<{$photo.photo_m}>" />
              </a>
            <{else}>
              <a href="<{$photo.photo_l}>" title="<{$photo.sn}>" rel="pic_group" class="photo_link Photo">
                <img alt="<{$photo.title}>" src="<{$photo.photo_m}>" />
              </a>
            <{/if}>
            <div class="caption row">
              <div class="col-sm-3 text-left">
                <{if $photo.photo_del}>
                  <button onclick="javascript:delete_tad_gallery_func(<{$photo.sn}>)" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></button>
                <{/if}>
              </div>
              <div class="col-sm-6 text-center">
                <{if $photo.is360}>
                  <i class="fa fa-street-view"></i>
                <{/if}>
                <{$photo.title}>
              </div>
              <div class="col-sm-3 text-right">
                <{if $photo.photo_edit}>
                  <button href="ajax.php?op=edit_photo&sn=<{$photo.sn}>" class="btn btn-xs btn-warning fancybox.ajax editbtn"><{$smarty.const._TAD_EDIT}></button>
                <{/if}>
              </div>
            </div>
          </div>
        <{/foreach}>

      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(function(){
      $('.photo_link').colorbox({rel:'pic_group',photo:true,maxWidth:'100%',maxHeight:'100%', title: function(){
        var sn = $(this).attr('title');
        return '<a href="view.php?sn=' + sn + '#photo' + sn + '" target="_blank"><{$smarty.const._MD_TADGAL_VIEW_PHOTO}></a>';
      }});
    });


    $('.Photo360').colorbox({rel:'group', iframe:true, width:"90%", height:"90%", maxWidth:'100%', maxHeight:'100%', title: function(){
        var sn = $(this).attr('title');
        return '<a href="view.php?sn=' + sn + '#photo' + sn + '" target="_blank"><{$smarty.const._MD_TADGAL_VIEW_PHOTO}></a>';
      }});

    function delete_tad_gallery_func(sn){
      var sure = window.confirm('<{$smarty.const._TAD_DEL_CONFIRM}>');
      if (!sure)  return;
      //location.href="ajax.php?op=delete_tad_gallery&sn=" + sn;
      $.post("ajax.php", { op: "delete_tad_gallery", sn: sn },
        function(data) {
        $('#item_photo_'+sn).remove();
      });
    }

    $("#tg_container").justifiedGallery({
      "rowHeight" : 120,
      "sizeRangeSuffixes" : {
        "lt100":"",
        "lt240":"",
        "lt320":"",
        "lt500":"",
        "lt640":"",
        "lt1024":""
      },
      "usedSizeRange" : "lt240",
      "margins" : 1,
      "extension": ".<{$extension}>",
    });
  </script>
<{elseif $csn}>
  <div class="alert alert-danger">
    <{$smarty.const._MD_TADGAL_EMPTY}>
  </div>
<{/if}>