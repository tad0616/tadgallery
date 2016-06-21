<{foreach from=$block.comment item=comment}>
  <ul <{if $block.showall=="0"}>style="height:2em;line-height:2em;overflow:hidden;"<{/if}>>
    <li>
      <{if $block.userinfo=="1"}>
        <a href="<{$xoops_url}>/userinfo.php?uid=<{$comment.uid}>"><{$comment.uid_name}></a>
        <a href="<{$xoops_url}>/modules/tadgallery/view.php?sn=<{$comment.nsn}>"><img src="<{$xoops_url}>/modules/tadgallery/images/image.png" hspace="4" align="absmiddle" alt="<{$comment.title}>" title="<{$comment.title}>"></a>
        :
      <{/if}>
      <a href="<{$xoops_url}>/modules/tadgallery/view.php?sn=<{$comment.nsn}>#comment<{$comment.com_id}>"><{$comment.txt}></a>
    </li>
  </ul>
<{/foreach}>
