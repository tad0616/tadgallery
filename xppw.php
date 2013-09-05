<?php
include_once "header.php";

/*
    XPPubWiz.php
        http://tim.digicol.de/xppubwiz/
    Authors:
        Tim Strehle <tim@digicol.de>
        Andr? Basse <andre@digicol.de>
    =====================================================================
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or (at
    your option) any later version.

    This program is distributed in the hope that it will be useful, but
    WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
    General Public License for more details.
    =====================================================================

    Technical information can be found here:
        http://msdn.microsoft.com/library/default.asp?url=/library/en-us/shellcc/platform/shell/programmersguide/shell_basics/shell_basics_extending/publishing_wizard/pubwiz_intro.asp
        http://www.zonageek.com/code/misc/wizards/
*/

// General configuration

$protocol = 'http';
if (isset($_SERVER[ 'HTTPS' ]))
  if ($_SERVER[ 'HTTPS' ] == 'on')
    $protocol .= 's';

$cfg = array(
    'wizardheadline'    => to_utf8(_MD_TADGAL_WIZARD_HEADLINE),
    'wizardbyline'      => to_utf8(_MD_TADGAL_WIZARD_BYLINE),
    'finalurl'          => XOOPS_URL."/modules/tadgallery/uploads.php#ui-tabs-2",
    'registrykey'       => strtr($_SERVER[ 'HTTP_HOST' ], '.:', '__'),
    'wizardname'        => to_utf8(sprintf(_MD_TADGAL_WIZARDNAME,$xoopsConfig['sitename'])),
    'wizarddescription' => to_utf8(sprintf(_MD_TADGAL_WIZARDDESC,$xoopsConfig['sitename']))
    );


// Determine page/step to display, as this script contains a four-step wizard:
// "login", "options", "check", "upload" (+ special "reg" mode, see below)

$allsteps = array( 'login', 'options', 'check', 'upload', 'reg' );

$step = 'login';

if (isset($_REQUEST[ 'step' ]))
  if (in_array($_REQUEST[ 'step' ], $allsteps))
    $step = $_REQUEST[ 'step' ];


// Special registry file download mode:
// Call this script in your browser and set ?step=reg to download a .reg file for registering
// your server with the Windows XP Publishing Wizard

if ($step == 'reg')  {

    $reg='Windows Registry Editor Version 5.00' . "\n\n" .
    '[HKEY_CURRENT_USER\\Software\\Microsoft\\Windows\\CurrentVersion\\Explorer\\PublishingWizard\\PublishingWizard\\Providers\\' . $cfg[ 'registrykey' ] . ']' . "\n" .
    '"displayname"="' . $cfg[ 'wizardname' ] . '"' . "\n" .
    '"description"="' . $cfg[ 'wizarddescription' ] . '"' . "\n" .
    '"href"="' . $protocol . '://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'PHP_SELF' ] . '"' . "\n" .
    '"icon"="' . $protocol . '://' . $_SERVER[ 'HTTP_HOST' ] . dirname($_SERVER[ 'PHP_SELF' ]) . '/favicon.ico"';
		if(is_utf8($reg)){
	    $reg=iconv("UTF-8","Big5",$reg);
		}
		header('Content-Type: application/octet-stream; name="tadGallery_pubwiz.reg"');
	  header('Content-disposition: attachment; filename="tadGallery_pubwiz.reg"');
		echo $reg;


    exit;
  }



// Send no-cache headers

header('Expires: Mon, 26 Jul 2002 05:00:00 GMT');              // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: no-cache="set-cookie", private');       // HTTP/1.1
header('Pragma: no-cache');                                    // HTTP/1.0


// Start session

session_name('phpxppubwiz');

// Send character set header
header('Content-Type: text/html; charset=UTF-8');


// Set maximum execution time to unlimited to allow large file uploads

set_time_limit(0);

?>
<html>
<head>
<meta http-equiv='content-type' content='text/html; charset=utf8'>
<title>XP Publishing Wizard Server Script</title>
<style type="text/css">

body,a,p,span,td,th,input,select,textarea {
    font-family:verdana,arial,helvetica,geneva,sans-serif,serif;
    font-size:10px;
}

</style>
</head>
<body>


<?php

// Variables for the XP wizard buttons

$WIZARD_BUTTONS = 'false,true,false';
$ONBACK_SCRIPT  = '';
$ONNEXT_SCRIPT  = '';


// Check page/step

if (empty($xoopsUser))
  $step = 'login';
elseif ($step == 'login')
  $step = 'options';

if ($step == 'check')
  if (! (isset($_REQUEST[ 'manifest' ]) && isset($_REQUEST[ 'dir' ])))
    $step = 'options';

if ($step == 'check')
  if (($_REQUEST[ 'manifest' ] == '') || ($_REQUEST[ 'dir' ] == ''))
    $step = 'options';

if ($step == 'check')
  if (! isset($_REQUEST[ 'dir' ]))
    $step = 'options';


// 第一步：登入表單

if ($step == 'login')  {
		if(eregi("2.0.",XOOPS_VERSION)){
			$action="checklogin20.php";
		}else{
			$action="checklogin.php";
		}
    

    $main= "<center>
		<h3>".sprintf(_MD_TADGAL_INPUT_TITLE,$xoopsConfig['sitename'])."</h3>
    <form  id='login' action='{$action}' method='post'>
    <table>
		<tr><td>"._MD_TADGAL_INPUT_ID."</td><td>
		<input name='uname' size='12' value='' maxlength='25' type='text'></td></tr>
		<tr><td>"._MD_TADGAL_INPUT_PASS."</td><td>
    <input name='pass' size='12' maxlength='32' type='password'></td></tr>
    </table>
    <input name='xoops_redirect' value='{$protocol}://{$_SERVER[ 'HTTP_HOST' ]}{$_SERVER['PHP_SELF']}?step=options' type='hidden'>
    <input name='op' value='login' type='hidden'>
		</form></center>";
		
		echo to_utf8($main);


    $ONNEXT_SCRIPT  = 'login.submit();';
    $ONBACK_SCRIPT  = 'window.external.FinalBack();';
    $WIZARD_BUTTONS = 'true,true,false';
  }


//第二步：選擇目錄（或選項）

if ($step == "options")  {
	//檢查有無發佈權限
	//$p=chk_cate_post_power();
	$p=tadgallery::chk_cate_power("upload");
	if(sizeof($p)>0 and $xoopsUser){
		$post_dir="<input type='radio' id='dir' name='dir' value='"._TADGAL_UP_IMPORT_DIR."' checked>"._TADGAL_UP_IMPORT_DIR."";
	}else{
    $post_dir=_MD_TADGAL_NO_POST_POWER;
	}

    $main= "<form method='post' id='options' action='{$protocol}://{$_SERVER[ 'HTTP_HOST' ]}{$_SERVER['PHP_SELF']}'>
    <center>
    <h3>"._MD_TADGAL_SELECT_DIR."</h3>
    $post_dir
    

    </center>

    <input type='hidden' name='step' value='check' />
    <input type='hidden' name='manifest' value='' />

    <script>

    function docheck()
    { var xml = window.external.Property('TransferManifest');
      options.manifest.value = xml.xml;
      options.submit();
    }

    </script>

    </form>
    ";
    
    echo to_utf8($main);


   $ONNEXT_SCRIPT  = "docheck();";
   $WIZARD_BUTTONS = "false,true,false";
  }

?>

<div id="content"/>

</div>

<?php

// 步驟三：檢查檔案，準備上傳

if ($step == "check")
  { /* Now we're embedding the HREFs to POST to into the transfer manifest.

    The original manifest sent by Windows XP looks like this:

    <transfermanifest>
        <filelist>
            <file id="0" source="C:\pic1.jpg" extension=".jpg" contenttype="image/jpeg" destination="pic1.jpg" size="530363">
                <metadata>
                    <imageproperty id="cx">1624</imageproperty>
                    <imageproperty id="cy">2544</imageproperty>
                </metadata>
            </file>
            <file id="1" source="C:\pic2.jpg" extension=".jpg" contenttype="image/jpeg" destination="pic2.jpg" size="587275">
                <metadata>
                    <imageproperty id="cx">1960</imageproperty>
                    <imageproperty id="cy">3008</imageproperty>
                </metadata>
            </file>
        </filelist>
    </transfermanifest>

    We will add a <post> child to each <file> section, and an <uploadinfo> child to the root element.
    */

    // stripslashes if the evil "magic_quotes_gpc" are "on" (hint by Juan Valdez <juanvaldez123@hotmail.com>)

    if (ini_get('magic_quotes_gpc') == '1')
      $manifest = stripslashes($_REQUEST[ 'manifest' ]);
    else
      $manifest = $_REQUEST[ 'manifest' ];

    $parser = xml_parser_create();

    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

    $xml_ok = xml_parse_into_struct($parser, $manifest, $tags, $index);

    $manifest = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";

    foreach ($tags as $i => $tag)
      { if (($tag[ 'type' ] == 'open') || ($tag[ 'type' ] == 'complete'))
          { if ($tag[ 'tag' ] == 'file')
              $filedata = array(
                'id'                => -1,
                'source'            => '',
                'extension'         => '',
                'contenttype'       => '',
                'destination'       => '',
                'size'              => -1,
                'imageproperty_cx'  => -1,
                'imageproperty_cy'  => -1
                );

            $manifest .= '<' . $tag[ 'tag' ];

            if (isset($tag[ 'attributes' ]))
              foreach ($tag[ 'attributes' ] as $key => $value)
                { $manifest .= ' ' . $key . '="' . $value . '"';

                  if ($tag[ 'tag' ] == 'file')
                    $filedata[ $key ] = $value;
                }

            if (($tag[ 'type' ] == 'complete') && (! isset($tag[ 'value' ])))
              $manifest .= '/';

            $manifest .= '>';

            if (isset($tag[ 'value' ]))
              { $manifest .= htmlspecialchars($tag[ 'value' ]);

                if ($tag[ 'type' ] == 'complete')
                  $manifest .= '</' . $tag[ 'tag' ] . '>';

                if (($tag[ 'tag' ] == 'imageproperty') && isset($tag[ 'attributes' ]))
                  if (isset($tag[ 'attributes' ][ 'id' ]))
                    $filedata[ 'imageproperty_' . $tag[ 'attributes' ][ 'id' ] ] = $tag[ 'value' ];
              }
          }
        elseif ($tag[ 'type' ] == 'close')
          { if ($tag[ 'tag' ] == 'file')
              { $protocol = 'http';
                if (isset($_SERVER[ 'HTTPS' ]))
                  if ($_SERVER[ 'HTTPS' ] == 'on')
                    $protocol .= 's';

                $manifest .= 
                    '<post href="' . $protocol . '://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'PHP_SELF' ] . '" name="userfile">' .
                    '    <formdata name="MAX_FILE_SIZE">10000000</formdata>' .
                    '    <formdata name="step">upload</formdata>' .
                    '    <formdata name="todir">' . htmlspecialchars($_REQUEST[ 'dir' ]) . '</formdata>';

                foreach ($filedata as $key => $value)
                  $manifest .= '<formdata name="' . $key . '">' . htmlspecialchars($value) . '</formdata>';

                $manifest .= '</post>';
              }
            elseif ($tag[ 'level' ] == 1)
              $manifest .= '<uploadinfo><htmlui href="' . $cfg[ 'finalurl' ] . '"/></uploadinfo>';

            $manifest .= '</' . $tag[ 'tag' ] . '>';
          }
      }

    // Check whether we created well-formed XML ...

    if (xml_parse_into_struct($parser,$manifest,$tags,$index) >= 0)
      { ?>

        <script>

        var newxml = '<?php echo str_replace('\\', '\\\\', $manifest); ?>';
        var manxml = window.external.Property('TransferManifest');

        manxml.loadXML(newxml);

        window.external.Property('TransferManifest') = manxml;
        window.external.SetWizardButtons(true,true,true);

        content.innerHtml = manxml;
        window.external.FinalNext();

        </script>

        <?php
      }
  }


// Step 4: This page will be called once for every file upload

if ($step == 'upload')
  { if (isset($_FILES) && isset($_REQUEST[ 'todir' ]) && isset($_REQUEST[ 'destination' ]))
      if (isset($_FILES[ 'userfile' ]) && ($_REQUEST[ 'todir' ] != '') && ($_REQUEST[ 'destination' ] != ''))
        if (file_exists($_REQUEST[ 'todir' ]))
          if (is_dir($_REQUEST[ 'todir' ]))
            { $filename = $_REQUEST[ 'todir' ] . '/' . $_REQUEST[ 'destination' ];

              if (! file_exists($filename))
                move_uploaded_file($_FILES[ 'userfile' ][ 'tmp_name' ], $filename);
            }
  }

?>

<script>

function OnBack()
{ <?php echo $ONBACK_SCRIPT; ?>
}

function OnNext()
{ <?php echo $ONNEXT_SCRIPT; ?>
}

function OnCancel()
{ // Don't know what this is good for:
  content.innerHtml+='<br>OnCancel';
}

function window.onload()
{ window.external.SetHeaderText("<?php echo strtr($cfg[ 'wizardheadline' ], '"', "'"); ?>","<?php echo strtr($cfg[ 'wizardbyline' ], '"', "'"); ?>");
  window.external.SetWizardButtons(<?php echo $WIZARD_BUTTONS; ?>);
}

</script>

</body>
</html>
