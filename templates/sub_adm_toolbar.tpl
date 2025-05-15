<{if $csn|default:false}>
    <a href="main.php?op=re_thumb&csn=<{$csn|default:''}>" class="btn btn-sm btn-success"><i class="fa-solid fa-images"></i><{$smarty.const._MA_TADGAL_RE_CREATE_THUMBNAILS_ALL}></a>
    <a href="main.php?op=re_thumb&kind=m&csn=<{$csn|default:''}>" class="btn btn-sm btn-success"><i class="fa-solid fa-image"></i><{$smarty.const._MA_TADGAL_RE_CREATE_THUMBNAILS_M}></a>
    <a href="main.php?op=re_thumb&kind=s&csn=<{$csn|default:''}>" class="btn btn-sm btn-success"><i class="fa-regular fa-image"></i><{$smarty.const._MA_TADGAL_RE_CREATE_THUMBNAILS_S}></a>
    <a href="javascript:delete_tad_gallery_cate_func(<{$csn|default:''}>);" class="btn btn-sm btn-danger <{if $cate_count.$csn > 0}>disabled<{/if}>"><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._MA_TADGAL_DEL_CATE}></a>
    <a href="main.php?op=tad_gallery_cate_form&csn=<{$csn|default:''}>" class="btn btn-sm btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a>
<{/if}>