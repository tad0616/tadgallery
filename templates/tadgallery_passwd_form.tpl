<div class="row">
  <div class="col-sm-9"><{$toolbar}></div>
  <div class="text-right col-sm-3">
    <select onChange="location.href='index.php?show_uid=' + this.value" class="col-sm-12">
      <{$author_option}>
    </select>
  </div>
</div>

<div class="row">
  <div class="col-sm-3">
    <select onChange="location.href='index.php?csn=' + this.value" class="col-sm-12">
      <{$cate_option}>
    </select>
  </div>
  <div class="col-sm-9">
    <{$path}>
  </div>
</div>

<div class="well">
  <form action="index.php" method="post" style="display:inline;">
    <h3><{$title}></h3>
    <div class="input-append">
      <input class="col-sm-9" name="passwd" id="appendedInputButton" type="password" value="">
      <input type="hidden" name="csn" value="<{$csn}>">
      <button class="btn" type="submit">Go</button>
    </div>
  </form>
</div>