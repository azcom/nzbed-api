<?PHP
/**
 * This example shows how XML_Serializer and XML_Unserializer
 * work together.
 *
 * A structure is serialized and later it's unserialized from the
 * resulting XML document.
 *
 * @author  Stephan Schmidt <schst@php.net>
 */
error_reporting(E_ALL);

require_once 'XML/Serializer.php';
require_once 'XML/Unserializer.php';
// this is just to get a nested object
$pearError = PEAR::raiseError('This is just an error object',123);

$options = array(
                    XML_SERIALIZER_OPTION_INDENT      => '    ',
                    XML_SERIALIZER_OPTION_LINEBREAKS  => "\n",
                    XML_SERIALIZER_OPTION_DEFAULT_TAG => 'unnamedItem',
                    XML_SERIALIZER_OPTION_TYPEHINTS   => true
                );

$foo = new stdClass();
$foo->value = 'My value';
$foo->error = $pearError;
$foo->xml   = array('This is' => 'cool');
$foo->resource = fopen(__FILE__, 'r');

$serializer = new XML_Serializer($options);
$result = $serializer->serialize($foo);

if ($result === true) {
    $xml = $serializer->getSerializedData();
}

echo '<pre>';
echo htmlspecialchars($xml);
echo '</pre>';

//  be careful to always use the ampersand in front of the new operator 
$unserializer = &new XML_Unserializer();

$status = $unserializer->unserialize($xml);    

if (PEAR::isError($status)) {
    echo 'Error: ' . $status->getMessage();
} else {
    $data = $unserializer->getUnserializedData();
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}
?>