
<{if $cate.adm|default:false}>
  <{$jeditable_set|default:''}>
<{/if}>

<h2 class="d-inline-block" style="margin-right:2rem;"><{$cate.title}></h2>

<{if $cate.adm|default:false}>
  <a href="javascript:delete_tad_gallery_cate_func(<{$cate.csn}>)" class="btn btn-sm btn-danger" ><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>

  <a href="<{$xoops_url}>/modules/tadgallery/ajax.php?op=edit_album&csn=<{$cate.csn}>" class="btn btn-sm btn-warning fancybox fancybox.ajax editbtn" ><i class="fa fa-pencil" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a>
<{/if}>

<{if $cate.adm|default:false || $cate.content|default:false}>
<div class="well card card-body bg-light m-1" id="content" style="line-height: 1.8; white-space: pre-wrap; word-break: break-all;"><{$cate.content|default:false}></div>
<{/if}>
