<?php
function showContent($path){


   if ($handle = opendir($path))
   {
       //$up = substr($path, 0, (strrpos(dirname($path."/."),"/")));
       //echo "<tr><td colspan='2'><img src='style/up2.gif' width='16' height='16' alt='up'/> <a href='".$_SERVER['PHP_SELF']."?path=$up'>Up one level</a></td></tr>";

       while (false !== ($file = readdir($handle)))
       {
           if ($file != "." && $file != "..")
           {
               $fName = $file;
               $file = $path.'/'.$file;
               if(is_file($file)) {
                   echo "<tr><td><img src='style/file2.gif' width='16' height='16' alt='file'/> <a href='".$file."'>".$fName."</a></td>"
                            ."<td align='right'>".date ('d-m-Y H:i:s', filemtime($file))."</td>"
                            ."<td align='right'>".filesize($file)." bytes</td></tr>";
               } elseif (is_dir($file)) {
                   print "<tr><td colspan='2'><img src='style/dir2.gif' width='16' height='16' alt='dir'/> <a href='".$_SERVER['PHP_SELF']."?path=$file'>$fName</a></td></tr>";
               }
           }
       }

       closedir($handle);
   }	

}

if (isset($_POST['path'])){
	$actpath = isset($_POST['path']) ? $_POST['path'] : '.';	
} else {
	$actpath = isset($_GET['path']) ? $_GET['path'] : '.';	
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html>
<head>
   <title>File Browser</title>
   <link href="style/style.css" rel="stylesheet" type="text/css" />
   <style type="text/css">
<!--
.style2 {
	font-size: 36px;
	color: #666666;
}
-->
   </style>
</head>
<body>
    <div id="main">
      <div class="caption">FILE BROWSER</div>
      <div id="icon">&nbsp;</div>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="path">
        <table width="100%" height="92">
          <tr><td><div align="center"><br/>
              <span class="style2">Listing Course Contents </span></div></td>
          </tr>
        </table>  
      </form><br/>

      <div class="caption">ACTUAL PATH: <?php echo $actpath ?></div>
      <div id="icon2">&nbsp;</div>
      <div id="result">
        <table width="100%">
<?php
			showContent($actpath);        
?>
        </table>
     </div>
	<div id="source">File Browser 1.0</div>
    </div>
</body>   
