<{$toolbar|default:''}>

<script type="text/javascript" src="class/jquery.bxslider/jquery.bxslider.js"></script>
<link rel="stylesheet" href="class/jquery.bxslider/jquery.bxslider.css" type="text/css">

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    $(document).ready(function(){
        $('.slider1').bxSlider({
            slideWidth: 100,
            minSlides: 2,
            maxSlides: 12,
            slideMargin: 10
        });
    });
</script>

<{if $is360|default:false}>
    <link rel="stylesheet" href="class/pannellum/pannellum.css">
    <script type="text/javascript" src="class/pannellum/pannellum.js"></script>
    <style>
    #panorama {
        width: 100%;
        height: 400px;
    }
    </style>
<{/if}>


<{$del_js|default:''}>

<{$path|default:''}>
<div id="photo<{$sn|default:''}>"></div>
<h2 style="display:none;">Photo</h2>

<div class="row" style="background-color:#FBFBFB;">
  <div class="col-sm-<{if $width > $height}>1<{else}>2<{/if}>" style="text-align:right;">
    <{if $back|default:false}><a href="view.php?sn=<{$back|default:''}>"><img src="images/left.png" style="margin-top:100px;" alt="Back" title="Last photo"></a><{/if}>
  </div>
  <div class="col-sm-<{if $width > $height}>10<{else}>8<{/if}> text-center">

    <{if $title|default:false}>
      <h1><{$title|default:''}></h1>
    <{else}>
      <h1 class="sr-only visually-hidden" style="display: none;"><{$photo_l|default:''}></h1>
    <{/if}>
    <{if $is360|default:false}>
      <div id="panorama"></div>
      <script>
      pannellum.viewer('panorama', {
          "type": "equirectangular",
          "autoLoad":true,
          "panorama": "<{$photo_l|default:''}>"
      });
      </script>
    <{else}>
      <img src="<{$photo_l|default:''}>" style="max-width: 100%;" alt="<{$photo_l|default:''}> <{$title|default:''}>" title="<{$photo_l|default:''}> <{$title|default:''}>">
    <{/if}>
    <{if $description|default:false}>
      <div class="alert alert-info text-left text-start">
        <{$description|default:''}>
      </div>
    <{/if}>

  </div>
  <div class="col-sm-<{if $width > $height}>1<{else}>2<{/if}>">
    <{if $next|default:false}><a href="view.php?sn=<{$next|default:''}>"><img src="images/right.png" style="margin-top:100px;" alt="Next" title="Next photo"></a><{/if}>
  </div>
</div>

<div class="row" style="margin:20px 0px;">
  <div class="col-sm-4 text-left text-start"><{$push|default:''}></div>
  <div class="col-sm-8 text-right text-end">
    <div class="btn-group">
      <{if $sel_size|default:false}>
        <a href="<{$photo_l|default:''}>" target="_blank" title="<{$description|default:''}>" class="btn btn-sm btn-default btn-secondary"><i class="fa fa-search-plus"></i> L</a>

        <a href="<{$photo_m|default:''}>" target="_blank" title="<{$description|default:''}>" class="btn btn-sm btn-default btn-secondary"><i class="fa fa-search"></i> M</a>

        <a href="<{$photo_s|default:''}>" target="_blank" title="<{$description|default:''}>" class="btn btn-sm btn-default btn-secondary"><i class="fa fa-search-minus"></i> S</a>
      <{/if}>

      <{if $pic_toolbar|default:false}>
        <{if $latitude|default:false}>
          <a href="gmap.php?latitude=<{$latitude|default:''}>&longitude=<{$longitude|default:''}>" class="fancybox fancybox.ajax btn btn-sm btn-success"><i class="fa fa-map-marker"></i> <{$smarty.const._MD_TADGAL_MAP}></a>
        <{/if}>
        <a href="exif.php?sn=<{$sn|default:''}>" class="fancybox fancybox.ajax btn btn-sm btn-info"><i class="fa fa-info-circle"></i> EXIF</a>

        <{if $show_del|default:false}>

          <{if $good|default:false}>
            <a href="view.php?op=good_del&sn=<{$sn|default:''}>" class="btn btn-sm btn-primary"><i class="fa fa-star-o"></i> <{$smarty.const._TADGAL_REMOVE_GOOD_PIC}></a>
          <{else}>
            <a href="view.php?op=good&sn=<{$sn|default:''}>" class="btn btn-sm btn-primary"><i class="fa fa-star"></i> <{$smarty.const._TADGAL_GOOD_PIC}></a>
          <{/if}>
          <a href="javascript:delete_tad_gallery_func(<{$sn|default:''}>)" title="{$smarty.const._TADGAL_DEL_PIC}>" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}></a>
          <a href="ajax.php?op=edit_photo&sn=<{$sn|default:''}>" title="<{$smarty.const._TAD_EDIT}>" class="fancybox fancybox fancybox.ajax btn btn-sm btn-warning "><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}></a>
        <{/if}>
      <{/if}>

    </div>
  </div>
</div>


<{if $thumb_slider|default:false}>
    <div class="slider1">
      <{if $slides1|default:false}>
        <{foreach from=$slides1 item=slide}>
          <div class="slide" onClick="location.href='view.php?sn=<{$slide.sn}>#photo<{$slide.sn}>'" onkeypress="location.href='view.php?sn=<{$slide.sn}>#photo<{$slide.sn}>'" style="cursor:pointer;height:80px;background: url('<{$slide.thumb}>') center center/cover no-repeat #252a44;"></div>
        <{/foreach}>
      <{/if}>

      <{if $slides2|default:false}>
        <{foreach from=$slides2 item=slide}>
          <div class="slide" onClick="location.href='view.php?sn=<{$slide.sn}>#photo<{$slide.sn}>'" onkeypress="location.href='view.php?sn=<{$slide.sn}>#photo<{$slide.sn}>'" style="cursor:pointer;height:80px;background: url('<{$slide.thumb}>') center center/cover no-repeat #252a44;"></div>
        <{/foreach}>
      <{/if}>
    </div>
<{/if}>

<div class="row">
  <div class="col-md-12">
    <{$commentsnav|default:''}>
    <{$lang_notice|default:''}>


    <div style="margin: 3px; padding: 3px;">
    <!-- start comments loop -->
    <{if $comment_mode == "flat"}>
      <{include file="db:system_comments_flat.html"}>
    <{elseif $comment_mode == "thread"}>
      <{include file="db:system_comments_thread.html"}>
    <{elseif $comment_mode == "nest"}>
      <{include file="db:system_comments_nest.html"}>
    <{/if}>
    <!-- end comments loop -->
    </div>
  </div>
</div>
