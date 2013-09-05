(function($){$.fn.outlineLetters=function(options){var settings=$.extend({color:'#888',size:1,round:true},options);return this.each(function(){var $this=$(this);$this.css("position","relative");var html=$this.html();$this.html("");var xtop=-settings.size;var xleft=-settings.size;var diam=(settings.size*2+ 1);var rad=diam/2.0;var x=diam*diam;for(var i=0;i<x;i++)
{var c=0;if(settings.round)
{c=Math.sqrt(xleft*xleft+ xtop*xtop);}
if(c<=rad&&(xtop!=0||xleft!=0))
{$this.append("<div style=\"color: "+ settings.color+"; position: absolute; top: "+ xtop+"px; left: "+ xleft+"px;\">"+ html+"</div>");}
xleft++;if(xleft>settings.size)
{xtop++;xleft=-settings.size;}}
$this.append("<div style=\"position: absolute; top: 0px; left: 0px;\" class=\"txt\">"+ html+"</div>");if($this.css("text-align")=="center")
{var innerwidth=$this.children(".txt").width();$this.css("margin-left",-(innerwidth/2));$this.css("left","50%");}
else if($this.css("text-align")=="right")
{var innerwidth=$this.children(".txt").width();$this.css("left",$this.width()- innerwidth);}
if($this.css("vertical-align")=="middle")
{var innerheight=$this.children(".txt").height();$this.css("margin-top",-(innerheight/2));$this.css("top","50%");}
else if($this.css("vertical-align")=="bottom")
{var innerheight=$this.children(".txt").height();$this.css("top",$this.height()- innerheight);}});};})(jQuery);