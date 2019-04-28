<{includeq file="db:tadgallery_cate_fancybox.tpl"}>

<link rel="stylesheet" href="class/pannellum/pannellum.css">
<script type="text/javascript" src="class/pannellum/pannellum.js"></script>
<style>
#panorama {
  width: 100%;
  height: 400px;
}
</style>

<!--工具列-->
<{$toolbar}>

<h1 class="sr-only" style="display: none;">All Photos</h1>

<!--下拉選單及目前路徑-->
<div class="row">
    <div class="col-sm-10">
        <{$path}>
    </div>
    <div class="col-sm-2">
        <select onChange="location.href='index.php?<{if $smarty.get.csn}>csn=<{$smarty.get.csn|intval}>&<{/if}>show_uid=' + this.value" class="form-control">
            <{$author_option}>
        </select>
    </div>
</div>

<!--相簿-->
<{if $only_thumb!="1"}>
    <{includeq file="db:tadgallery_albums.tpl"}>
<{/if}>

<!--說明-->
<{includeq file="db:tadgallery_content.tpl"}>
