--TEST--
Environment - magic_quotes_gpc on
--INI--
magic_quotes_gpc=Off
--POST--
a'b=a'b&aa'b[a'b]=a'b&&aa'b[a'bb][a'b]=a'b
--FILE--
<?php
require_once 'PHP/Compat/Environment/magic_quotes_gpc_on.php';
print_r($_POST);
?>
--EXPECT--
Array
(
    [a\'b] => a\'b
    [aa\'b] => Array
        (
            [a\'b] => a\'b
            [a\'bb] => Array
                (
                    [a\'b] => a\'b
                )

        )

)