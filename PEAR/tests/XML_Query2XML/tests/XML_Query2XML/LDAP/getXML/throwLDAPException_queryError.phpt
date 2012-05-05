--TEST--
XML_Query2XML_Driver_LDAP::preprocessQuery(): check for XML_Query2XML_ConfigException - $query: array expected, string given
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
    require_once 'XML/Query2XML.php';
    require_once dirname(dirname(__FILE__)) . '/ldap_init.php';

    try {
        $query2xml = XML_Query2XML::factory($ldap);
        $dom = $query2xml->getXML(
            array(
                'base' => 'ou=people,dc=example,dc=com',
                'filter' => '(object class=inetOrgPerson)'
            ),
            array(
                'rootTag' => 'persons',
                'rowTag' => 'person',
                'idColumn' => 'cn',
                'elements' => array(
                    'cn',
                    'sn',
                    'mail'
                )
            )
        );
        $dom->formatOutput = true;
        print $dom->saveXML();
    } catch (XML_Query2XML_LDAPException $e) {
        echo get_class($e) . ': ' . $e->getMessage();
    } catch (XML_Query2XML_LDAP2Exception $e) {
        echo str_replace('LDAP2', 'LDAP', get_class($e)) . ': ' . str_replace('net_ldap2_error', 'net_ldap_error', $e->getMessage());
    }
?>
--EXPECT--
XML_Query2XML_LDAPException: [sql]: Could not run LDAP search query: [net_ldap_error: message="LDAP_FILTER_ERROR((object class=inetOrgPerson)): LDAP_FILTER_ERROR" code=87 mode=return level=notice prefix="" info=""]
