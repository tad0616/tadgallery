<div style="display:block;width:<{$block.width}>px;height:<{$block.height}>px;">
  <ul class="imageBox<{$block.view_csn}>" style="list-style-type: none;">
    <{foreach from=$block.pics item=p}>
      <li style="list-style-type: none;">
        <div style="width: <{$block.width}>px; height: <{$block.height}>px; background-image: url('<{$p.pic_url}>'); background-position: center center; background-repeat: no-repeat; background-color: white; background-size: contain;"></div>
      </li>
    <{/foreach}>
  </ul>
</div>