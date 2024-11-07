<div class="container-fluid">

  <{$formValidator_code|default:''}>

  <form action="main.php" method="post" id="myForm" name="form1" class="form-horizontal" role="form">
    <div class="row">
      <div class="col-sm-3">
        <div style="height: 300px; overflow: auto;">
          <{$ztree_code|default:''}>
        </div>

        <{if $photo|default:false}>
          <{include file="$xoops_rootpath/modules/tadgallery/templates/sub_batch_tools.tpl"}>
        <{/if}>
      </div>

      <div class="col-sm-9">
          <div class="row">
            <div class="col-sm-4">
              <h1>
                <{if $csn}>
                  <a href='../index.php?csn=<{$cate.csn}>' class="my-title" title="<{$link_to_cate|default:''}>"><{$cate.title}> <i class="fa fa-caret-square-o-right" aria-hidden="true"></i>
                  </a>
                <{/if}>

                <{if $photo|default:false}>
                  <{if $mode_select=="good"}>
                      <a href='main.php?op=chg_mode&mode=good&csn=<{$cate.csn}>' class='btn btn-sm btn-info'><{$smarty.const._MA_TADGAL_CHANGE|sprintf:$smarty.const._MA_TADGAL_LIST_NORMAL:$smarty.const._MA_TADGAL_LIST_GOOD}></a>
                  <{else}>
                      <a href='main.php?op=chg_mode&mode=normal&csn=<{$cate.csn}>' class='btn btn-sm btn-warning'><{$smarty.const._MA_TADGAL_CHANGE|sprintf:$smarty.const._MA_TADGAL_LIST_GOOD:$smarty.const._MA_TADGAL_LIST_NORMAL}></a>
                  <{/if}>
                <{/if}>
              </h1>
            </div>
            <div class="col-sm-8 text-right text-end">
                <div class="btn-group mt-3">
                  <{if $now_op=="list_tad_gallery"}>
                    <{include file="$xoops_rootpath/modules/tadgallery/templates/sub_adm_toolbar.tpl"}>
                  <{/if}>
                </div>
            </div>
          </div>

          <{if $cate.content|default:false}>
            <div class="alert alert-info">
              <{$cate.content}>
            </div>
          <{/if}>

          <{include file="$xoops_rootpath/modules/tadgallery/templates/op_`$now_op`.tpl"}>
      </div>
    </div>
  </form>
</div>