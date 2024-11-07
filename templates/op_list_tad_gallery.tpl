<div class="row">
    <{if $photo|default:false}>
        <label class="checkbox col-sm-2">
        <input type="checkbox" id="clickAll" >
        <{$smarty.const._MA_TADGAL_SELECT_ALL}>
        </label>
    <{/if}>

    <div id="save_msg"></div>
</div>

<{if $photo|default:false}>
    <div class="row">
        <ul id="sortable">
        <{foreach from=$photo item=pic}>
            <li id="recordsArray_<{$pic.sn}>" class="thumb_func">
                <a href="javascript:check_one('p<{$pic.sn}>',true)" class="thumb">
                    <img data-src="<{$pic.photo_s}>" src="<{$pic.photo_s}>" <{if $pic.title|default:false}>alt="<{$pic.title}><{else}>sort:<{$pic.photo_sort}><{/if}>" title="sort:<{$pic.photo_sort}>">
                </a>
                <div style="font-size: 0.675rem; width:130px; word-break: break-all;">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input p<{$pic.db_csn}> photo" type="checkbox" name="pic[]" id="p<{$pic.sn}>" value="<{$pic.sn}>">
                        <label class="form-check-label" for="p<{$pic.sn}>" style="line-height: 1;">
                        <{if $pic.good=='1'}><i class="fa fa-star text-warning" aria-hidden="true"></i>
                        <{/if}>
                        <{$pic.filename}>
                        </label>
                    </div>
                </div>
            </li>
        <{/foreach}>
        </ul>
    </div>
    <script language="JavaScript">
        $().ready(function(){
            $(".thumb").thumbs();

            $("#sortable").sortable({ opacity: 0.6, cursor: 'move', update: function(){
                var order = $(this).sortable("serialize");
                $.post("save_sort.php", order, function(theResponse){
                    $("#save_msg").html(theResponse);
                });
            }
            });

            $("#clickAll").click(function() {
                var x = document.getElementById("clickAll").checked;
                if(x){
                    $(".photo").each(function() {
                        $(this).attr("checked", true);
                    });
                }else{
                    $(".photo").each(function() {
                        $(this).attr("checked", false);
                    });
                }
            });
        });


        function check_one(id_name,change){
            if(document.getElementById(id_name).checked && change){
                document.getElementById(id_name).checked = false;
            }else{
                document.getElementById(id_name).checked = true;
            }
        }
    </script>
<{else}>
    <div class="row">
        <div class="alert alert-warning" style="margin-top: 30px;"><{$smarty.const._MA_TADGAL_NEED_CATE}></div>
    </div>
<{/if}>
