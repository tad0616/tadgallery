<{if $albums|default:false}>
    <script language="JavaScript">
        $().ready(function(){
            $(".thumb").thumbs();

            $('.AlbumCate').hover(function(){$(this).children('.btn').css('display','inline')},function(){$(this).children('.btn').css('display','none')});
            $('.AlbumCate').animate({boxShadow: '0 0 8px #D0D0D0'});
        });
    </script>
    <{assign var="i" value=0}>
    <{assign var="total" value=1}>

    <{foreach from=$albums item=album}>

        <{if $i==0}>
            <div class="row" style="margin:10px auto;">
        <{/if}>

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

            <div class="col-sm-3" id="item_album_<{$album.csn}>">
                <div class="thumbnail">
                    <div class="AlbumCate" style="background:black url(images/cadenas.png) center center ;">
                        <form action="index.php" method="post" style="margin-top:80px;">
                            <div class="input-append">
                                <input class="col-sm-9" name="passwd" id="appendedInputButton" type="password">
                                <input type="hidden" name="csn" value="<{$album.csn}>">
                                <button class="btn" type="submit">Go</button>
                            </div>
                        </form>
                        <div style="font-size:1em;font-weight:bold;color:#FFFFCC;position:absolute;bottom:2px;left:10px;z-index:2;text-shadow: 1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 0px -1px 0 #000, 0px 1px 0 #000, -1px 0px 0 #000, 1px 0px 0 #000;"><{$album.title}></div>
                    </div>
                </div>
            </div>
        <{else}>
            <div class="col-sm-3" id="item_album_<{$album.csn}>">
                <div class="thumbnail">
                    <div class="AlbumCate">
                        <a href="<{$xoops_url}>/modules/tadgallery/index.php?csn=<{$album.csn}>" style="display:block; width:100%;height:100%; background: url('<{$album.cover_pic}>') center center / cover no-repeat #252a44;">
                            <div style="font-size: 1em; font-weight:normal; color:#FFFFFF; position:absolute; bottom:2px; left:10px; text-shadow: 1px 1px 0 #000, -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 0px -1px 0 #000, 0px 1px 0 #000, -1px 0px 0 #000, 1px 0px 0 #000;"><{$album.title}></div>
                        </a>
                        <{if $album.album_del|default:false}>
                            <a href="javascript:delete_tad_gallery_cate_func(<{$album.csn}>)" class="btn btn-sm btn-danger" style="position:absolute;bottom:2px;left:2px;display:none;"><i class="fa fa-trash" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
                        <{/if}>

                        <{if $album.album_edit|default:false}>
                            <a href="<{$xoops_url}>/modules/tadgallery/ajax.php?op=edit_album&csn=<{$album.csn}>" class="btn btn-sm btn-warning fancybox fancybox.ajax editbtn" style="position:absolute;bottom:2px;right:2px;display:none;z-index:2;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>  <{$smarty.const._TAD_EDIT}></a>
                        <{/if}>
                    </div>
                </div>
            </div>
        <{/if}>

        <{assign var="i" value=$i+1}>
        <{if $i == 4 || $total==$count}>
            </div>
            <{assign var="i" value=0}>
        <{/if}>
        <{assign var="total" value=$total+1}>
    <{/foreach}>

    <hr>
<{/if}>