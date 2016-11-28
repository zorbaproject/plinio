<?php
header('Content-Type: text/html; charset=utf-8');


print "<h1>Minimal CMS for storytelling</h1>";
print ' <script src="ckeditor/ckeditor.js"></script>';

$filename = $_GET["filename"];
if ($filename == "") $filename = "index";

$htmlpage = $filename.".html";
$cssfile = $filename."_style.css";
$headsrc = $filename."_head_script.js";


$headscript = file_get_contents($headsrc);






//We start to write the HTML code of the storytelling

if ($_POST["editor0"] != "" || $_POST["title"] != "" || $_POST["topbar"] || $_POST["pageheader"]) {

$htmlcode = '
<html>
  <head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>'.$_POST["title"].'</title>

    <meta content="">
    <base target="_blank">  <!--Invia automaticamente tutti i link ad una nuova scheda-->
    
     <link rel="stylesheet" type="text/css" href="'.$cssfile.'"/>
     
<script>'.$headscript.'</script>

</head>
<body>';

$htmlcode .= $_POST["topbar"];


$htmlcode .= '<div > '.$_POST["pageheader"].' <script>
checkScroll();
sharer();
</script>
</div>';

$htmlcode .= '<div id="all-text" name="all-text" >';


//here we go with the text of the article

foreach ($_POST as $key => $textarea){

if (substr($key,0,6) == "editor"){

if ($_POST[$key] != ""){
$htmlcode .= '<div class="'.$_POST["S".$key].'">';
$htmlcode .= $_POST[$key];
$htmlcode .= '</div>';
}

}
}

//end of the text of the article

$htmlcode .= '</div>

</body>
</html>';



file_put_contents($htmlpage, $htmlcode);
//file_put_contents("indexNEW.html", $htmlcode);

$dt = date('d/m/Y H:i:s');
print 'Last saved on '.$dt;

}







//This is the form for editing the article


//We try to read the actual page, if it exists
$oldhtml = file_get_contents($htmlpage);


$title = $_POST["title"];
if ($title == "") {
$titlepos = strpos($oldhtml,'<title>')+7;
$title = substr($oldhtml, $titlepos,strpos($oldhtml,'</title>')-$titlepos);
}
if ($title == "") $title = "MySite - This is my article";


$topbar = file_get_contents($filename."_topbar.html");

$pageheader = file_get_contents($filename."_pageheader.html");


$csscode = file_get_contents($cssfile);
$styles_list = array();
$i = 0;
while ( $i !== false) {

$ni = strpos($csscode,'.', $i);
if ($ni === false) break;
$i = $ni;

$ni = strpos($csscode,' ', $i);
if ($ni === false) break;

if ($csscode[$ni+1] == '{') $styles_list[] = substr($csscode, $i+1, ($ni-$i-1));

$i = $ni;

}


print '<form action="CMS.php?filename='.$filename.'"  method="post" id="uploadform" name="uploadform" enctype="multipart/form-data">';
print 'Title of the page: <input type="text" name="title" value="'.$title.'"><br>';
print 'Top bar: <textarea name="topbar" rows="6" cols="100">'.$topbar.'</textarea> <br>';
print 'Header: <textarea name="pageheader" rows="6" cols="100">'.$pageheader.'</textarea> <br>';
print '<hr>';


$beginning = '<div id="all-text" name="all-text" >';

if (strpos($oldhtml, $beginning) !== false) {

$oldtext = substr($oldhtml, strpos($oldhtml,$beginning)+strlen($beginning));


$block = 0;
$i = 0;
while ( $i !== false) {

$ni = strpos($oldtext,'<div class=', $i);
if ($ni === false) break;
$i = $ni;

$ni = strpos($oldtext,'</div>', $i);
if ($ni === false) break;

$tagend = strpos($oldtext,'>', $i) +1;
$tmptext = substr($oldtext, $tagend, ($ni-$tagend));
$tmpstyle = substr($oldtext, strpos($oldtext,'"', $i)+1, ($tagend-3-(strpos($oldtext,'"', $i+1))));


print addElement($block, $styles_list, $tmptext, $tmpstyle);

$i = $ni;
$block++;

}


print addElement($block, $styles_list, "", "");

$tempId = 'TMP'.$block;


print "<a href=\"javascript:var element = document.getElementById('ELEMENT".$block."'); element.setAttribute('style', 'display: block;');\">Add new element</a> <br>";


print '<input type="submit" value="Save"></form>';

}



function addElement($block, $styles_list, $tmptext, $tmpstyle){

$newelem = '<div id="ELEMENT'.$block.'">';

if ($tmpstyle == "" && $tmptext == "") $newelem = '<div id="ELEMENT'.$block.'" style="display: none">';

$newelem .= 'Type of element: <select name="Seditor'.$block.'" id="Seditor'.$block.'" >';
foreach ($styles_list as $i => $value) {
    if ($tmpstyle == $value) $newelem .= '<option selected="selected" value="'.$value.'">'.$value.'</option>';
    if ($tmpstyle != $value) $newelem .= '<option value="'.$value.'">'.$value.'</option>';
}
$newelem .= '</select><br>';

$newelem .= '<textarea name="editor'.$block.'" id="editor'.$block.'" rows="6" cols="100">'.$tmptext.'</textarea> <br>';
$newelem .= "<a href=\"javascript:CKEDITOR.replace( 'editor".$block."' );\">View in CKEditor</a> <br>";
$newelem .= "<a href=\"javascript:var element = document.getElementById('ELEMENT".$block."'); element.parentNode.removeChild(element);\">Delete element</a> <br>";

$newelem .= '<hr></div>';

return $newelem;

}






?>
