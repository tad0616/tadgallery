<{include file="db:tadgallery_list_header.tpl"}>

<link rel="stylesheet" href="<{$xoops_url}>/modules/tadgallery/class/justifiedGallery/justifiedGallery.min.css" type="text/css" media="all">
<script type="text/javascript" src="<{$xoops_url}>/modules/tadgallery/class/justifiedGallery/jquery.justifiedGallery.min.js"></script>

<!--相片-->
<h2 style="display:none;">List Photos</h2>
<{if $photo|default:false}>
    <div id="tg_container">
      <{foreach from=$photo item=pic}>
        <div>
          <{if $pic.is360|default:false}>
            <a href="360.php?sn=<{$pic.sn}>&file=<{$pic.photo_l}>" title="<{$pic.sn}>" rel="pic_group" class="photo_link Photo360" data-author="<{$pic.author}>">
              <img alt="<{$pic.sn}>"  src="<{$pic.photo_m}>"><span class="sr-only visually-hidden"><{$pic.title}></span>
            </a>
          <{else}>
            <a href="<{$pic.photo_l}>" title="<{$pic.sn}>" rel="pic_group" class="photo_link Photo" data-author="<{$pic.author}>">
              <img alt="<{$pic.sn}>" src="<{$pic.photo_m}>"><span class="sr-only visually-hidden"><{$pic.title}></span>
            </a>
          <{/if}>
          <div class="caption row">
            <div class="col-sm-3 text-left text-start">
              <{if $pic.photo_del|default:false}>
                <button onclick="javascript:delete_tad_gallery_func(<{$pic.sn}>)" class="btn btn-sm btn-danger"><{$smarty.const._TAD_DEL}></button>
              <{/if}>
            </div>
            <div class="col-sm-6 text-center">
              <{if $pic.is360|default:false}>
                <i class="fa fa-street-view"></i>
              <{/if}>
              <{$pic.title}>
            </div>
            <div class="col-sm-3 text-right text-end">
              <{if $pic.photo_edit|default:false}>
                <button href="ajax.php?op=edit_photo&sn=<{$pic.sn}>" class="btn btn-sm btn-warning fancybox fancybox.ajax editbtn"><{$smarty.const._TAD_EDIT}></button>
              <{/if}>
            </div>
          </div>
        </div>
      <{/foreach}>

    </div>
    <{$bar|default:''}>

  <script type="text/javascript">
    $(function(){
      $('.photo_link').colorbox({rel:'pic_group',photo:true,maxWidth:'100%',maxHeight:'100%', title: function(){
        var sn = $(this).attr('title');
        var author= $(this).data('author');
        return '<a href="view.php?sn=' + sn + '#photo' + sn + '" target="_blank"><{$smarty.const._MD_TADGAL_VIEW_PHOTO}></a> post by '+ author;
      }});
    });


    $('.Photo360').colorbox({rel:'group', iframe:true, width:"90%", height:"90%", maxWidth:'100%', maxHeight:'100%', title: function(){
        var sn = $(this).attr('title');
        var author= $(this).data('author');
        return '<a href="view.php?sn=' + sn + '#photo' + sn + '" target="_blank"><{$smarty.const._MD_TADGAL_VIEW_PHOTO}></a> post by '+ author;
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
      "extension": ".<{$extension|default:''}>",
    });
  </script>
<{elseif $csn}>
  <div class="alert alert-danger">
    <{$smarty.const._MD_TADGAL_EMPTY}>
  </div>
<{/if}>