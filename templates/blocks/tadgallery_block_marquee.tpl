<style type="text/css">
  .scroll_div {
    width: <{$block.width}>px;
    height: <{$block.height}>px;
    margin: 0 auto;
    overflow: hidden;
    white-space: nowrap;
  }

  .scroll_div img {
    height: <{$block.height}>px;
    margin: auto 0px
  }

  #scroll_begin, #scroll_end, #scroll_begin ul, #scroll_end ul, #scroll_begin ul li, #scroll_end ul li {
    display: inline;
  }
</style>

<script language="javascript">
  $(function() {
    var width=$('#scrolldiv').width();
    $('#scroll_div').css('width',width+'px');
  });

  function ScrollImgLeft(){
  var speed = '<{$block.speed}>';
  var scroll_begin = document.getElementById("scroll_begin");
  var scroll_end = document.getElementById("scroll_end");
  var scroll_div = document.getElementById("scroll_div");
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
<div id="scrolldiv">

  <div id="scroll_div" class="scroll_div">
    <div id="scroll_begin">
      <ul style="list-style: none;">
        <{foreach from=$block.pics item=photo}>
          <li>
            <a href="<{$xoops_url}>/modules/tadgallery/view.php?sn=<{$photo.photo_sn}>">
              <img src="<{$photo.pic_url}>" alt="<{$photo.title}>" title="<{$photo.title}>" />
            </a>
          </li>
        <{/foreach}>
      </ul>
    </div>
    <div id="scroll_end"></div>
  </div>
</div>
<!--gundong-->
<script type="text/javascript">ScrollImgLeft();</script>