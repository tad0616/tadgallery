<{if $block.pics}>
  <style type="text/css" media="all">
    .list_carousel {
      position: relative;
    }
    .list_carousel ul {
      margin: 0;
      padding: 0;
      list-style: none;
      display: block;
    }
    .list_carousel li {
      padding: 0;
      margin: 0px;
      display: block;
      float: left;
    }
    .list_carousel.responsive {
      width: auto;
      margin-left: 0;
    }
    .clearfix {
      float: none;
      clear: both;
    }
    a.tadgallery_carousel_prev, a.tadgallery_carousel_next {
      background: url('<{$xoops_url}>/modules/tadgallery/images/miscellaneous_sprite.png') no-repeat transparent;
      width: 45px;
      height: 50px;
      display: block;
      position: absolute;
      top: 45px;
    }
    a.tadgallery_carousel_prev {			left: -22px;
              background-position: 0 0; }
    a.tadgallery_carousel_prev:hover {		background-position: 0 -50px; }
    a.tadgallery_carousel_prev.disabled {	background-position: 0 -100px !important;  }
    a.tadgallery_carousel_next {			right: -22px;
              background-position: -50px 0; }
    a.tadgallery_carousel_next:hover {		background-position: -50px -50px; }
    a.tadgallery_carousel_next.disabled {	background-position: -50px -100px !important;  }
    a.tadgallery_carousel_prev.disabled, a.tadgallery_carousel_next.disabled {
      cursor: default;
    }

    a.tadgallery_carousel_prev span, a.tadgallery_carousel_next span {
      display: none;
    }
  </style>
  <script type="text/javascript">
  $(function() {
    $('#carousel<{$block.view_csn}>').carouFredSel({
      <{$block.vertical}>
      width: '100%',
      height: <{$block.vertical_height}>,
      <{$block.scroll}>
      prev	: {
        button	: "#foo2_tadgallery_carousel_prev<{$block.view_csn}>",
        key		: "left"
      },
      next	: {
        button	: "#foo2_tadgallery_carousel_next<{$block.view_csn}>",
        key		: "right"
      },
      <{if $block.staytime}>
      auto: <{$block.staytime}>,
      <{/if}>
      mouseWheel: true
    });
  });

  </script>
  <div class="list_carousel">
    <ul id="carousel<{$block.view_csn}>">
      <{foreach from=$block.pics item=p}>
      <li>
        <a href="<{$p.link}>" data-photo="<{$p.pic_url}>" data-sn="<{$p.photo_sn}>" <{$p.fancy_class}>>
          <img src="<{$p.pic_url}>" alt="<{$p.pic_txt}>" <{if $p.photo_title}>title="<{$p.photo_title}>"<{/if}> style="<{if $p.direction=="1"}>width:<{$p.width}>px;<{else}>height:<{$p.height}>px;<{/if}>margin:0px 2px;"></a>
      </li>
      <{/foreach}>
    </ul>
    <div class="clearfix"></div>
  	<a class="tadgallery_carousel_prev" id="foo2_tadgallery_carousel_prev<{$block.view_csn}>" href="#"><span>prev</span></a>
  	<a class="tadgallery_carousel_next" id="foo2_tadgallery_carousel_next<{$block.view_csn}>" href="#"><span>next</span></a>
  </div>
  <{includeq file="$xoops_rootpath/modules/tadgallery/templates/blocks/colorbox.tpl"}>
<{/if}>