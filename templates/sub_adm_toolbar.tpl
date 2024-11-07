<{if $csn}>
    <a href="main.php?op=re_thumb&csn=<{$csn}>" class="btn btn-sm btn-success"><{$smarty.const._MA_TADGAL_RE_CREATE_THUMBNAILS_ALL}></a>
    <a href="main.php?op=re_thumb&kind=m&csn=<{$csn}>" class="btn btn-sm btn-success"><{$smarty.const._MA_TADGAL_RE_CREATE_THUMBNAILS_M}></a>
    <a href="main.php?op=re_thumb&kind=s&csn=<{$csn}>" class="btn btn-sm btn-success"><{$smarty.const._MA_TADGAL_RE_CREATE_THUMBNAILS_S}></a>
    <a href="javascript:delete_tad_gallery_cate_func(<{$csn}>);" class="btn btn-sm btn-danger <{if $cate_count.$csn > 0}>disabled<{/if}>"><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
    <a href="main.php?op=tad_gallery_cate_form&csn=<{$csn}>" class="btn btn-sm btn-warning"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a>
<{/if}>