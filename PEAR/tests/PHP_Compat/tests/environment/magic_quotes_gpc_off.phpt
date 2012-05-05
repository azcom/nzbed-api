--TEST--
Environment - magic_quotes_gpc off
--INI--
magic_quotes_gpc=On
--POST--
a'b=a'b&aa'b[a'b]=a'b&&aa'b[a'bb][a'b]=a'b
--FILE--
<?php
require_once 'PHP/Compat/Environment/magic_quotes_gpc_off.php';
print_r($_POST);
?>
--EXPECT--
Array
(
    [a'b] => a'b
    [aa'b] => Array
        (
            [a'b] => a'b
            [a'bb] => Array
                (
                    [a'b] => a'b
                )

        )

)
