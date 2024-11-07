<{include file="db:tadgallery_cate_fancybox.tpl"}>

<!--工具列-->
<{$toolbar|default:''}>

<h2 style="display:none;">List Photos</h2>

<!--下拉選單及目前路徑-->
<div class="row">
    <div class="col-sm-<{if $author_option|default:false}>10<{else}>12<{/if}>">
        <{$path|default:''}>
    </div>
    <{if $author_option|default:false}>
        <div class="col-sm-2">
            <select onChange="location.href='index.php?<{if $smarty.get.csn|default:false}>csn=<{$smarty.get.csn|intval}>&<{/if}>show_uid=' + this.value" class="form-control" title="select author">
                <{$author_option|default:''}>
            </select>
        </div>
    <{/if}>
</div>

<!--相簿-->
<{if $only_thumb!="1"}>
    <{include file="db:tadgallery_albums.tpl"}>
<{/if}>

<!--說明-->
<{include file="db:tadgallery_content.tpl"}>
