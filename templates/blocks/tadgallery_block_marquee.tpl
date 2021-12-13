<{if $block.pics}>
  <style type="text/css">
    .scroll_div {
      width: <{$block.width}>px;
      height: <{$block.height}>px;
      margin: 0 auto;
      overflow: hidden !important;
      white-space: nowrap;
    }

    .scroll_div img {
      height: <{$block.height}>px;
      margin: auto 0px;

    }

    #scroll_begin<{$block.view_csn}>, #scroll_end<{$block.view_csn}>, #scroll_begin<{$block.view_csn}> ul, #scroll_end<{$block.view_csn}> ul, #scroll_begin<{$block.view_csn}> ul li, #scroll_end<{$block.view_csn}> ul li {
      display: inline;
    }
  </style>

  <script language="javascript">
    $(function() {
      var width=$('#scrolldiv<{$block.view_csn}>').width();
      $('#scroll_div<{$block.view_csn}>').css('width',width+'px');
    });

    function ScrollImgLeft<{$block.view_csn}>(){
    var speed = '<{$block.speed}>';
    var scroll_begin = document.getElementById("scroll_begin<{$block.view_csn}>");
    var scroll_end = document.getElementById("scroll_end<{$block.view_csn}>");
    var scroll_div = document.getElementById("scroll_div<{$block.view_csn}>");
    scroll_end.innerHTML=scroll_begin.innerHTML
      function Marquee(){
        if(scroll_end.offsetWidth-scroll_div.scrollLeft<=0)
          scroll_div.scrollLeft-=scroll_begin.offsetWidth
        else
          scroll_div.scrollLeft++
      }
    var MyMar=setInterval(Marquee,speed)
      scroll_div.onmouseover=function() {clearInterval(MyMar)}
      scroll_div.onmouseout=function() {MyMar=setInterval(Marquee,speed)}
    }
  </script>

  <!--gundong-->
  <div id="scrolldiv<{$block.view_csn}>">

    <div id="scroll_div<{$block.view_csn}>" class="scroll_div">
      <div id="scroll_begin<{$block.view_csn}>">
        <ul style="list-style: none;">
          <{foreach from=$block.pics item=photo}>
            <li>
              <a href="<{$photo.link}>" data-photo="<{$photo.pic_url}>" data-sn="<{$photo.photo_sn}>" <{$photo.fancy_class}>>
                <img src="<{$photo.pic_url}>" alt="<{$photo.photo_title}>" <{if $photo.photo_title}>title="<{$photo.photo_title}>"<{/if}>>
                <span class="sr-only visually-hidden">photo-<{$photo.photo_sn}></span>
              </a>
            </li>
          <{/foreach}>
        </ul>
      </div>
      <div id="scroll_end<{$block.view_csn}>"></div>
    </div>
  </div>
  <!--gundong-->
  <script type="text/javascript">ScrollImgLeft<{$block.view_csn}>();</script>
  <{includeq file="$xoops_rootpath/modules/tadgallery/templates/blocks/colorbox.tpl"}>
<{/if}>