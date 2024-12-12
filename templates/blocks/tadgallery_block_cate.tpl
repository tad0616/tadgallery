<{if $block.display_mode=="title"}>
  <ul>
    <{foreach item=album from=$block.albums}>
      <li>
        <a href="<{$xoops_url}>/modules/tadgallery/index.php?csn=<{$album.csn}>">
          <{$album.title}>
        </a>
        <{if $album.dir_counter|default:false}>
          <i class='fa fa-folder-open'></i> <{$album.dir_counter}>
        <{/if}>
        <{if $album.file_counter|default:false}>
          <i class='fa fa-image'></i> <{$album.file_counter}>
        <{/if}>
      </li>
    <{/foreach}>
  </ul>
<{elseif $block.display_mode=="content"}>
  <{foreach item=album from=$block.albums}>
    <div class="row">
      <div class="col-sm-3">

        <{if $album.album_lock|default:false}>
          <script type="text/javascript">
            $(document).ready(function(){
              $("#pass_col_<{$album.csn}>").hide();
                $(".GalleryCate").click(function(){
                $("#cate_pass_title_<{$album.csn}>").hide();
                $("#pass_col_<{$album.csn}>").show();
              });
            });
          </script>
          <div class="thumbnail">
            <div class="AlbumCate" style="background:black url('<{$xoops_url}>/modules/tadgallery/images/cadenas.png') center center ;">
                <form action="<{$xoops_url}>/modules/tadgallery/index.php" method="post" style="margin-top:80px;">
                  <div class="input-append">
                    <input class="col-sm-9" name="passwd" id="appendedInputButton" type="password">
                    <input type="hidden" name="csn" value="<{$album.csn}>">
                    <button class="btn btn-primary" type="submit">Go</button>
                  </div>
                </form>
                <div style="font-size:1em;font-weight:bold;color:#FFFFCC;position:absolute;bottom:2px;left:10px;z-index:2;text-shadow: 1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 0px -1px 0 #000, 0px 1px 0 #000, -1px 0px 0 #000, 1px 0px 0 #000;"><{$album.title}></div>
            </div>
          </div>
        <{else}>
          <div class="thumbnail">
            <div class="AlbumCate">
              <a href="<{$xoops_url}>/modules/tadgallery/index.php?csn=<{$album.csn}>" style="display:block; width:100%;height:100%; background: url('<{$album.cover_pic}>') center center / cover no-repeat #252a44;">
                <div style="font-size: 1em; font-weight:normal; color:#FFFFFF; position:absolute; bottom:2px; left:10px; text-shadow: 1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 0px -1px 0 #000, 0px 1px 0 #000, -1px 0px 0 #000, 1px 0px 0 #000;"><{$album.title}>(<{$album.file_counter}>)</div>
              </a>
            </div>
          </div>
        <{/if}>
      </div>
      <div class="col-sm-9">

        <h3 style="display:inline">
          <a href="<{$xoops_url}>/modules/tadgallery/index.php?csn=<{$album.csn}>">
            <{$album.title}>
          </a>
        </h3>

        <span>
          <{if $album.dir_counter|default:false}>
            <i class='fa fa-folder-open'></i> <{$album.dir_counter}>
          <{/if}>
          <{if $album.file_counter|default:false}>
            <i class='fa fa-image'></i> <{$album.file_counter}>
          <{/if}>
        </span>


        <div style="<{$block.content_css}>">
          <{$album.content}>
        </div>
      </div>
    </div>
  <{/foreach}>
<{else}>
  <script language="JavaScript">
    $().ready(function(){
      $('.AlbumCate').hover(function(){$(this).children('.btn').css('display','inline')},function(){$(this).children('.btn').css('display','none')});
      $('.AlbumCate').animate({boxShadow: '0 0 8px #D0D0D0'});
    });

  </script>
  <div class="row">
    <{foreach item=album from=$block.albums}>
      <{if $album.file_counter or $album.dir_counter}>
        <{if $album.album_lock|default:false}>
          <div class="col-sm-<{$block.col}>" id="item_album_<{$album.csn}>" style="margin:10px auto;">
            <script type="text/javascript">
              $(document).ready(function(){
                $("#pass_col_<{$album.csn}>").hide();
                  $(".GalleryCate").click(function(){
                  $("#cate_pass_title_<{$album.csn}>").hide();
                  $("#pass_col_<{$album.csn}>").show();
                });
              });
            </script>
            <div class="card">
              <div class="AlbumCate" style="background:black url('<{$xoops_url}>/modules/tadgallery/images/cadenas.png') center center ;">
                  <form action="<{$xoops_url}>/modules/tadgallery/index.php" method="post" style="margin-top:80px;">
                    <div class="input-append">
                      <input class="col-sm-9" name="passwd" id="appendedInputButton" type="password">
                      <input type="hidden" name="csn" value="<{$album.csn}>">
                      <button class="btn btn-secondary" type="submit">Go</button>
                    </div>
                  </form>
                  <div style="font-size:1em;font-weight:bold;color:#FFFFCC;position:absolute;bottom:2px;left:10px;z-index:2;text-shadow: 1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 0px -1px 0 #000, 0px 1px 0 #000, -1px 0px 0 #000, 1px 0px 0 #000;"><{$album.title}></div>
              </div>
            </div>
          </div>
        <{else}>
          <div class="col-sm-<{$block.col}>" id="item_album_<{$album.csn}>" style="margin:10px auto;">
            <div class="card">
              <div class="AlbumCate">
                <a href="<{$xoops_url}>/modules/tadgallery/index.php?csn=<{$album.csn}>" style="display:block; width:100%;height:100%; background: url('<{$album.cover_pic}>') center center / cover no-repeat #252a44;">
                  <div style="font-size: 1em; font-weight:normal; color:#FFFFFF; position:absolute; bottom:2px; left:10px; text-shadow: 1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 0px -1px 0 #000, 0px 1px 0 #000, -1px 0px 0 #000, 1px 0px 0 #000;"><{$album.title}>(<{$album.file_counter}>)</div>
                </a>
              </div>
            </div>
          </div>
        <{/if}>
      <{/if}>
    <{/foreach}>
  </div>


<{/if}>

<div class="text-right text-end">
<a href="<{$xoops_url}>/modules/tadgallery/index.php" class="badge badge-info badge-pill bg-info text-white">more...</a>
</div>