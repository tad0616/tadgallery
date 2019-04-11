<p>
<{$toolbar}>
</p>

<script type="text/javascript" src="class/jquery.bxslider/jquery.bxslider.js"></script>
<link rel="stylesheet" href="class/jquery.bxslider/jquery.bxslider.css" type="text/css" />

<{$fancybox_code}>

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

<{if $is360}>
  <link rel="stylesheet" href="class/pannellum/pannellum.css"/>
  <script type="text/javascript" src="class/pannellum/pannellum.js"></script>
  <style>
  #panorama {
      width: 100%;
      height: 400px;
  }
  </style>
<{/if}>


<{$del_js}>

<{$path}>
<div id="photo<{$sn}>"></div>

<div class="row" style="background-color:#FBFBFB;">
  <div class="col-sm-<{if $width > $height}>1<{else}>2<{/if}>" style="text-align:right;">
    <{if $back}><a href="view.php?sn=<{$back}>#photo<{$back}>"><img src="images/left.png" style="margin-top:100px;" alt="Back" title="Back"></a><{/if}>
  </div>
  <div class="col-sm-<{if $width > $height}>10<{else}>8<{/if}> text-center">

    <{if $title}>
      <h1><{$title}></h1>
    <{else}>
      <h1 class="sr-only" style="display: none;"><{$photo_l}></h1>
    <{/if}>
    <{if $is360}>
      <div id="panorama"></div>
      <script>
      pannellum.viewer('panorama', {
          "type": "equirectangular",
          "autoLoad":true,
          "panorama": "<{$photo_l}>"
      });
      </script>
    <{else}>
      <img src="<{$photo_l}>" style="max-width: 100%;" alt="<{$photo_l}> <{$title}>" title="<{$photo_l}> <{$title}>">
    <{/if}>
    <{if $is360}>
      <div id="panorama"></div>
      <script>
      pannellum.viewer('panorama', {
          "type": "equirectangular",
          "autoLoad":true,
          "panorama": "<{$photo_l}>"
      });
      </script>
    <{else}>
      <img src="<{$photo_l}>" style="max-width: 100%;" alt="<{$title}>" title="<{$title}>">
    <{/if}>
    <{if $description}>
      <div class="alert alert-info text-left">
        <{$description}>
      </div>
    <{/if}>

  </div>
  <div class="col-sm-<{if $width > $height}>1<{else}>2<{/if}>">
    <{if $next}><a href="view.php?sn=<{$next}>#photo<{$next}>"><img src="images/right.png" style="margin-top:100px;" alt="Next" title="Next"></a><{/if}>
  </div>
</div>

<div class="row" style="margin:20px 0px;">
  <div class="col-sm-6 text-left"><{$push}></div>
  <div class="col-sm-6 text-right">
    <div class="btn-group">
      <{if $sel_size}>
        <a href="<{$photo_l}>" target="_blank" title="<{$description}>" class="btn btn-xs btn-default"><i class="fa fa-search-plus"></i> L</a>

        <a href="<{$photo_m}>" target="_blank" title="<{$description}>" class="btn btn-xs btn-default"><i class="fa fa-search"></i> M</a>

        <a href="<{$photo_s}>" target="_blank" title="<{$description}>" class="btn btn-xs btn-default"><i class="fa fa-search-minus"></i> S</a>
      <{/if}>

      <{if $pic_toolbar}>
        <!--
        <{if $latitude}>
          <a href="gmap.php?latitude=<{$latitude}>&longitude=<{$longitude}>" class="fancybox fancybox.iframe btn btn-xs btn-success"><i class="fa fa-map-marker"></i> <{$smarty.const._MD_TADGAL_MAP}></a>
        <{/if}> -->

        <a href="exif.php?sn=<{$sn}>" class="fancybox fancybox.ajax btn btn-xs btn-info"><i class="fa fa-info-circle"></i> EXIF</a>

        <{if $show_del}>

          <{if $good}>
            <a href="view.php?op=good_del&sn=<{$sn}>" class="btn btn-xs btn-primary"><i class="fa fa-star-o"></i> <{$smarty.const._TADGAL_REMOVE_GOOD_PIC}></a>
          <{else}>
            <a href="view.php?op=good&sn=<{$sn}>" class="btn btn-xs btn-primary"><i class="fa fa-star"></i> <{$smarty.const._TADGAL_GOOD_PIC}></a>
          <{/if}>
          <a href="javascript:delete_tad_gallery_func(<{$sn}>)" title="{$smarty.const._TADGAL_DEL_PIC}>" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i> <{$smarty.const._TAD_DEL}></a>
          <a href="ajax.php?op=edit_photo&sn=<{$sn}>" title="<{$smarty.const._TAD_EDIT}>" class="fancybox fancybox.ajax btn btn-xs btn-warning "><i class="fa fa-pencil"></i> <{$smarty.const._TAD_EDIT}></a>
        <{/if}>
      <{/if}>

    </div>
  </div>
</div>


<{if $thumb_slider}>
  <div class="row">
    <div class="col-md-12">
      <div class="slider1">
        <{if $slides1}>
          <{foreach item=slide from=$slides1}>
            <div class="slide" onClick="location.href='view.php?sn=<{$slide.sn}>#photo<{$slide.sn}>'" onkeypress="location.href='view.php?sn=<{$slide.sn}>#photo<{$slide.sn}>'" style="cursor:pointer;height:80px;background:#000 url('<{$slide.thumb}>') no-repeat center top;background-size:cover;"></div>
          <{/foreach}>
        <{/if}>

        <{if $slides2}>
          <{foreach item=slide from=$slides2}>
            <div class="slide" onClick="location.href='view.php?sn=<{$slide.sn}>#photo<{$slide.sn}>'" onkeypress="location.href='view.php?sn=<{$slide.sn}>#photo<{$slide.sn}>'" style="cursor:pointer;height:80px;background:#000 url('<{$slide.thumb}>') no-repeat center top;background-size:cover;"></div>
          <{/foreach}>
        <{/if}>
      </div>
    </div>
  </div>
<{/if}>

<{$facebook_comments}>

<div class="row">
  <div class="col-md-12">
    <{$commentsnav}>
    <{$lang_notice}>


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