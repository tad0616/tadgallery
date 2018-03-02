<script>
  $(function(){
    $('.tad_gallery_colorbox_<{$block.view_csn}>').colorbox({rel:'group', photo:true, maxWidth:'100%', maxHeight:'100%', title: function(){
        var sn = $(this).attr('title');
        return '<a href="<{$xoops_url}>/modules/tadgallery/view.php?sn=<{$block.sn}>#photo<{$block.sn}>" target="_blank"><{$smarty.const._MB_TADGAL_VIEW_PHOTO}></a>';
      }});
  });
</script>