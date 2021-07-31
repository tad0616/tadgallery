<{if $block.pics}>
  <style type="text/css">
    ul#animated-portfolio-block<{$block.view_csn}>{
      list-style-type: none;
    }
    ul#animated-portfolio-block<{$block.view_csn}> li{
      list-style: none;
    }
  </style>

  <ul id="animated-portfolio-block<{$block.view_csn}>">
    <{foreach from=$block.pics item=p}>
      <li>
        <a href="<{$xoops_url}>/modules/tadgallery/view.php?sn=<{$p.photo_sn}>">
          <img src="<{$p.pic_url}>" alt="view <{$p.photo_title}>" style="width:100%">
        </a>
      </li>
    <{/foreach}>
  </ul>
<{/if}>