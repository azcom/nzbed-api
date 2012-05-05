<?PHP
/**
 * XML_Beautifier example 7
 *
 * This example shows to to change the treatment
 * of data section
 *
 * @author	Stephan Schmidt <schst@php.net>
 */
	error_reporting( E_ALL );

    require_once 'XML/Beautifier.php';

    $fmt = new XML_Beautifier( array( 'removeLineBreaks' => false ) );
    $result = $fmt->formatFile('test3.xml');

    echo "<h3>Original file</h3>";
    echo "<pre>";
    echo htmlspecialchars(implode("",file('test3.xml')));
    echo "</pre>";
        
    echo    "<br><br>";
    
    echo "<h3>Beautified output</h3>";
    echo "<pre>";
    echo htmlspecialchars($result);
    echo "</pre>";
?>
