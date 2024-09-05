<{$cate_fancybox_code|default:''}>
<script>
  $(function(){
    $('.PhotoCate').hover(function(){$(this).children('.btn').css('display','inline')},function(){$(this).children('.btn').css('display','none')});
    $('.PhotoCate').animate({boxShadow: '0 0 8px #D0D0D0'});
  });

</script>
