--TEST--
XML Beautifier - Bug #5591: Undefined variable notice when parsing DOCTYPE
--FILE--
<?php
/*
 * The bug report complains of a Notice being printed,
 * but I cannot duplicate it here.  
 *
 * Note that this test case fails on PHP5 because 
 * the XML and DOCTYPE tags are not being included in 
 * the output.  That problem is already reported in 
 * Bug #5450.  This test case should begin passing 
 * after #5450 is fixed.
 *
 * Note also that I'm not sure if the 5-char indention
 * of the DOCTYPE attributes is correct behavior.
 */

error_reporting(E_ALL);
require_once 'XML/Beautifier.php';

/*
 * XML is from http://www.samalyse.com/ln/0015.php
 */
$xml = <<<EOF
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html  xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<meta http-equiv="Content-Language" content="fr"/>
<meta name="Description" content="Samalyse SARL - Solutions informatiques"/>
<meta name="Keywords" content="informatique,linux,d.veloppement,gestion,audio,r.seau,maintenance"/>
<meta name="Author" content="Olivier Guilyardi"/>
<meta name="Revisit-after" content="5 days"/>
<meta name="Robots" content="all"/>
<link rel="stylesheet" href="/css/samalyse.css.php?rand=8044&amp;r_bar_size=17" type="text/css" />

<title> Samalyse </title>

</head>

<body>
<div style="background: white; "><a name="top" href="/index.php"><img src="/pico/logo4.gif" alt="Samalyse" border="0" width="558" height="60" /></a></div>
</body>
</html>
EOF;

$fmt = new XML_Beautifier();
echo $fmt->formatString($xml);
?>
--EXPECT--
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type" />
        <meta content="fr" http-equiv="Content-Language" />
        <meta content="Samalyse SARL - Solutions informatiques" name="Description" />
        <meta content="informatique,linux,d.veloppement,gestion,audio,r.seau,maintenance" name="Keywords" />
        <meta content="Olivier Guilyardi" name="Author" />
        <meta content="5 days" name="Revisit-after" />
        <meta content="all" name="Robots" />
        <link href="/css/samalyse.css.php?rand=8044&amp;r_bar_size=17" rel="stylesheet" type="text/css" />
        <title>Samalyse</title>
    </head>
    <body>
        <div style="background: white; ">
            <a href="/index.php" name="top">
                <img alt="Samalyse" border="0" height="60" src="/pico/logo4.gif" width="558" />
            </a>
        </div>
    </body>
</html>
