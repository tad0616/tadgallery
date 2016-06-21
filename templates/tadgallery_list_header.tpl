<{includeq file="db:tadgallery_cate_fancybox.tpl"}>

<!--工具列-->
<{$toolbar}>

<!--下拉選單及目前路徑-->
<div class="row">
  <div class="col-md-10">
    <{$path}>
  </div>
  <div class="col-md-2">
    <select onChange="location.href='index.php?show_uid=' + this.value" class="form-control">
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
