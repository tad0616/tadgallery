<{if $block.pics}>
  <style type="text/css">
  /* Scroller Box */
  #scroller_container<{$block.view_csn}> {
   height: <{$block.height}>px;
   overflow: hidden;
  }
  /* Scoller Box */

  /* CSS Hack Safari */
  #dummy {;# }

  #scroller_container<{$block.view_csn}> {
   overflow: auto;
  }
  /* Scroller Box */
  .scroller_container_blur{
      background-color: #ccc; /*shadow color*/
      color: inherit;
      margin-left: 4px;
      margin-top: 4px;
      margin-right: 8px;
  }

  .scroller_container_shadow,
  .scroller_container_shadow_content{
      position: relative;
      bottom: 2px;
      right: 2px;
  }

  .scroller_container_shadow{
      background-color: #666; /*shadow color*/
      color: inherit;
  }

  .scroller_container_shadow_content{
      background-color: #fff; /*background color of content*/
      color: #000; /*text color of content*/
      border: 1px solid #cfcfcf; /*border color*/
      padding: 4px 2px;
      text-align:center;
  }
  </style>


  <div id="scroller_container<{$block.view_csn}>_w"></div>
  <div id="scroller_container<{$block.view_csn}>">
    <div class="<{$block.direction}> jscroller2_speed-<{$block.speed}> jscroller2_mousemove">
     <{foreach from=$block.pics item=p}>
      <div class="scroller_container_blur">
        <div class="scroller_container_shadow">
          <div class="scroller_container_shadow_content">
            <a href="<{$xoops_url}>/modules/tadgallery/view.php?sn=<{$p.photo_sn}>"><img src="<{$p.pic_url}>" alt="<{$p.pic_txt}>" title="<{$p.photo_title}>" style="width:100%;"></a>
            <div style="padding:4px;margin-top:4px;font-size: 0.6875em;color:#606060"><{$p.photo_title}></div>
            <{$p.description}>
          </div>
        </div>
      </div>
     <{/foreach}>
    </div>
  </div>
<{/if}>