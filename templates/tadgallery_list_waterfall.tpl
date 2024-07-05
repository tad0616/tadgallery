<{include file="db:tadgallery_list_header.tpl"}>

<style>
.tg_item {
  width:200px;
  margin: 8px;
  float: left;
  border:none;
  background-color:rgb(254,254,254);
}
.outline{
  color:rgb(32,32,32);
  font-size: 0.92em;
  display:block;
  margin:0px;
  padding:15px 4px;
  border-top:1px solid #D0D0D0;
  border-bottom:1px solid #D0D0D0;
  text-align:center;
}
.photo_description{
  font-size: 0.75em;
  line-height:1.8;
  color:rgb(48,48,48);
  padding:6px;
}
.photo_info{
  font-size: 0.75em;
  color:rgb(128,128,128);
  border-top:1px solid #D0D0D0;
  padding:3px 6px;
}
</style>
<h2 style="display:none;">List Photos</h2>

<!--相片-->
<{if $photo}>
    <div id="tg_container">
      <{foreach from=$photo item=pic}>
        <{if $pic.photo_m}>
          <div class="tg_item" id="item_photo_<{$pic.sn}>">
            <div class="show_photo" style="position:relative">
              <{if $pic.is360}>
                <a href="360.php?sn=<{$pic.sn}>&file=<{$pic.photo_l}>" title="<{$pic.sn}>" class="Photo360" data-author="<{$pic.author}>">
                  <img src="<{$pic.photo_m}>" alt="photo sn <{$pic.sn}>" class="rounded img-responsive img-fluid" data-corner="top 5px">
                  <span class="sr-only visually-hidden">photo:<{$pic.sn}></span>
                </a>
              <{else}>
                <a href="<{$pic.photo_l}>" title="<{$pic.sn}>" class="Photo" data-author="<{$pic.author}>">
                  <img src="<{$pic.photo_m}>"  alt="photo sn <{$pic.sn}>"  class="rounded img-responsive img-fluid" data-corner="top 5px">
                  <span class="sr-only visually-hidden">photo:<{$pic.sn}></span>
                </a>
              <{/if}>
              <{if $pic.photo_del}>
                <a href="javascript:delete_tad_gallery_func(<{$pic.sn}>)" class="btn btn-sm btn-danger" style="position:absolute;bottom:2px;left:2px;display:none;"><{$smarty.const._TAD_DEL}></a>
              <{/if}>

              <{if $pic.photo_edit}>
                <a href="ajax.php?op=edit_photo&sn=<{$pic.sn}>" class="btn btn-sm btn-warning fancybox fancybox.ajax editbtn" style="position:absolute;bottom:2px;right:2px;display:none;"><{$smarty.const._TAD_EDIT}></>
              <{/if}>
            </div>

            <div <{if $pic.title}>class="outline"<{/if}>>
              <{if $pic.is360}>
                <i class="fa fa-street-view"></i>
              <{/if}>
              <a href="view.php?sn=<{$pic.sn}>" id="title<{$pic.sn}>"><{$pic.title}></a>
            </div>

            <div <{if $pic.description}>class="photo_description"<{/if}> id="description<{$pic.sn}>">
              <{$pic.description}>
            </div>

            <div>
              <div class="col-sm-4 photo_info">
                <i class="icon-user"></i><{$pic.counter}>
              </div>
              <div class="col-sm-8 photo_info text-right text-end">
                <{$pic.DateTime}>
              </div>
            </div>
          </div>
        <{/if}>
      <{/foreach}>

    </div>
    <{$bar}>

  <script type="text/javascript" src="<{$xoops_url}>/modules/tadgallery/class/jquery.masonry.min.js"></script>
  <script type="text/javascript" src="<{$xoops_url}>/modules/tadgallery/class/jquery.corner.js"></script>
  <script type="text/javascript" src="<{$xoops_url}>/modules/tadgallery/class/jquery.animate-shadow.js"></script>


  <script>
   $(function(){

    $('.Photo').colorbox({rel:'group',photo:true,maxWidth:'100%',maxHeight:'100%', title: function(){
        var sn = $(this).attr('title');
        var author= $(this).data('author');
        return '<a href="view.php?sn=' + sn + '#photo' + sn + '" target="_blank"><{$smarty.const._MD_TADGAL_VIEW_PHOTO}></a> post by '+ author;
      }});


    $('.Photo360').colorbox({rel:'group', iframe:true, width:"90%", height:"90%", maxWidth:'100%', maxHeight:'100%', title: function(){
        var sn = $(this).attr('title');
        var author= $(this).data('author');
        return '<a href="view.php?sn=' + sn + '#photo' + sn + '" target="_blank"><{$smarty.const._MD_TADGAL_VIEW_PHOTO}></a> post by '+ author;
      }});

     var $container = $('#tg_container');
     $container.imagesLoaded(function(){
       $container.masonry({
         itemSelector : '.tg_item',
         isFitWidth:true,
         isAnimated: true
       });
     });

    $(".editbtn").fancybox({
      afterClose : function() {
        $('#tg_container').masonry('reload');
        return;
      }
    });

    $('.show_photo').hover(function(){$(this).children('.btn').css('display','inline')},function(){$(this).children('.btn').css('display','none')});
    $('.tg_item').animate({boxShadow: '0 0 8px #D0D0D0'});
    $('.rounded').corner("round 4px");
  });

  function delete_tad_gallery_func(sn){
    var sure = window.confirm('<{$smarty.const._TAD_DEL_CONFIRM}>');
    if (!sure)  return;
    //location.href="ajax.php?op=delete_tad_gallery&sn=" + sn;
    $.post("ajax.php", { op: "delete_tad_gallery", sn: sn },
      function(data) {
      $('#item_photo_'+sn).remove();
      $('#tg_container').masonry('reload');
    });
  }
  </script>

<{elseif $csn}>
  <div class="alert alert-danger">
    <{$smarty.const._MD_TADGAL_EMPTY}>
  </div>
<{/if}>