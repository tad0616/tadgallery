<{if !empty($cate.content)}>
  <{if $cate.adm|default:false}>
    <{$jeditable_set}>
  <{/if}>
  <h2><{$cate.title}></h2>
  <div class="well card card-body bg-light m-1" id="content" style="line-height: 1.8; white-space: pre-wrap; word-break: break-all;"><{$cate.content}></div>
<{elseif !empty($cate.adm)}>
  <{$jeditable_set}>
  <h2><{$cate.title}></h2>
  <div class="well card card-body bg-light m-1" id="content" style="line-height: 1.8; white-space: pre-wrap; word-break: break-all;"></div>
<{/if}>
