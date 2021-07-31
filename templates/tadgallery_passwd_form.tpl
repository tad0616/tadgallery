<div class="row">
  <div class="col-sm-9"><{$toolbar}></div>
  <div class="text-right col-sm-3">
    <select onChange="location.href='index.php?<{if $smarty.get.csn}>csn=<{$smarty.get.csn|intval}>&<{/if}>showshow_uid=' + this.value" class="form-control" title="select author">
      <{$author_option}>
    </select>
  </div>
</div>

<div class="row">
  <div class="col-sm-3">
    <select onChange="location.href='index.php?csn=' + this.value" class="form-control" title="select cate">
      <{$cate_option}>
    </select>
  </div>
  <div class="col-sm-9">
    <{$path}>
  </div>
</div>

<div class="well card card-body bg-light m-1">
  <form action="index.php" method="post" style="display:inline;">
    <h3><{$title}></h3>
    <div class="input-append">
      <input class="col-sm-9" name="passwd" id="appendedInputButton" type="password" value="">
      <input type="hidden" name="csn" value="<{$csn}>">
      <button class="btn" type="submit">Go</button>
    </div>
  </form>
</div>