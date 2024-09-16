<{if $block.pics|default:false}>
  <{foreach from=$block.pics item=photo}>
  	<a href="<{$photo.link}>" data-photo="<{$photo.pic_url}>" data-sn="<{$photo.photo_sn}>" style="display: block; float: left; margin: <{$block.margin}>px; text-decoration: none;" <{$photo.fancy_class}> title="<{$photo.photo_sn}>">
      <div style="border: 1px solid #D0D0D0; width :<{$block.width}>px; height: <{$block.height}>px; background-image: url('<{$photo.pic_url}>'); background-position: top center;	background-repeat: no-repeat; background-size: <{$block.bgsize}>; cursor:pointer;"></div>
      <{if $block.show_txt|default:false}>
        <div style='width: <{$block.width}>px; <{$block.style}>'><{$photo.pic_txt}></div>
      <{else}>
        <span class="sr-only visually-hidden">photo-<{$photo.photo_sn}></span>
      <{/if}>
    </a>
  <{/foreach}>
  <div style="clear:both;"></div>
<{/if}>


<{include file="$xoops_rootpath/modules/tadgallery/templates/blocks/colorbox.tpl"}>