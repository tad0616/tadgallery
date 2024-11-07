<{include file="db:tadgallery_list_header.tpl"}>

<h2 style="display:none;">List Photos</h2>

<!--相片-->
<{if $photo|default:false}>
    <div id="tg_container">
      <{foreach from=$photo item=pic}>
        <{if $pic.photo_m|default:false}>
          <div class="tg_item" id="item_photo_<{$pic.sn}>">
            <div class="show_photo" style="position:relative">
              <{if $pic.is360|default:false}>
                <a href="360.php?sn=<{$pic.sn}>&file=<{$pic.photo_l}>" title="<{$pic.sn}>" class="Photo360" data-author="<{$pic.author}>">
                  <img src="<{$pic.photo_m}>" alt="photo sn <{$pic.sn}>" class="img-responsive img-fluid" data-corner="top 5px">
                  <span class="sr-only visually-hidden">photo:<{$pic.sn}></span>
                </a>
              <{else}>
                <a href="<{$pic.photo_l}>" title="<{$pic.sn}>" class="Photo" data-author="<{$pic.author}>">
                  <img src="<{$pic.photo_m}>"  alt="photo sn <{$pic.sn}>"  class="img-responsive img-fluid" data-corner="top 5px">
                  <span class="sr-only visually-hidden">photo:<{$pic.sn}></span>
                </a>
              <{/if}>

              <{if $pic.photo_del|default:false}>
                <a href="javascript:delete_tad_gallery_func(<{$pic.sn}>)" class="btn btn-sm btn-danger" style="position:absolute;bottom:2px;left:2px;display:none;"><i class="fa fa-trash" aria-hidden="true"></i></a>
              <{/if}>

              <{if $pic.photo_edit|default:false}>
                <a href="ajax.php?op=edit_photo&sn=<{$pic.sn}>" class="btn btn-sm btn-warning fancybox fancybox.ajax editbtn" style="position:absolute;bottom:2px;right:2px;display:none;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
              <{/if}>
            </div>

            <div <{if $pic.title|default:false}>class="outline"<{/if}>>
              <{if $pic.is360|default:false}>
                <i class="fa fa-street-view"></i>
              <{/if}>
              <a href="view.php?sn=<{$pic.sn}>" id="title<{$pic.sn}>"><{$pic.title}></a>
            </div>

            <div <{if $pic.description|default:false}>class="photo_description"<{/if}> id="description<{$pic.sn}>">
              <{$pic.description}>
            </div>

            <div class="photo_info text-center">
              <span class="badge badge-info bg-info">
                <{$pic.counter}>
              </span>
              <{$pic.DateTime}>
            </div>
          </div>
        <{/if}>
      <{/foreach}>

    </div>

    <div class="bar">
    <{$bar|default:''}>
    </div>


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
    <{$smarty.const._MD_TADGAL_EMPTY|sprintf:$csn}>
  </div>
<{/if}>