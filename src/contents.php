<?php

include("config.php");
html_head("Contents", "web/css/lincoln.css");
include_once("lib/xmlDbConnection.class.php");

print "<body>";

include("web/html/header.html");

// use settings from config file
//$args = $exist_args;
$exist_args{"debug"} = false;

$xmldb = new xmlDbConnection($exist_args);

/*
$query = 'for $a in input()/TEI.2/:text/body/div1 
let $bibl := $a/head/bibl  
return <div> {$a/@id} {$bibl} </div> sort by (@id)';
*/
/*$query = 'for $a in input()/TEI.2
let $auth := $a/teiHeader/fileDesc/titleStmt/author
let $body := $a/:text/body
return <div> {$auth}
{for $div1 in $body/div1 return <div1>{$div1/@id}{$div1/head/bibl}</div1> } </div> sort by (author)'; */

// query for all volumes -- eXist
$query = 'declare namespace tei="http://www.tei-c.org/ns/1.0";
for $a in /tei:TEI
let $auth := $a/tei:teiHeader/tei:fileDesc/tei:titleStmt/tei:author
let $body := $a/tei:text/tei:body
order by $auth
return <div>
{$auth}
{for $div1 in $body/tei:div1
return
<div1>
{$div1/@xml:id}
{$div1/tei:head/tei:bibl}
</div1>}
</div>';

$maxdisplay = "57"; //show all the sermons
$position = "1"; //start here


$xmldb->xquery($query, $position, $maxdisplay);

print '<div class="content">  
          <h2>Contents</h2>';
print "<hr>";
$xsl_file = "xslt/contents.xsl";


$xmldb->xslTransform($xsl_file); 
$xmldb->printResult(); 

print "</div>";

include("web/html/footer.html");
?> 
   
  </div>
 <?php include("web/html/google-tracklinc.xml"); ?>
</body>
</html>
