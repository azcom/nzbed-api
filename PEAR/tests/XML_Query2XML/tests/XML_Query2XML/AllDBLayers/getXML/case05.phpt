--TEST--
XML_Query2XML::getXML(): Case05
--SKIPIF--
<?php require_once dirname(dirname(__FILE__)) . '/skipif.php'; ?>
--FILE--
<?php
require_once 'XML/Query2XML.php';
require_once dirname(dirname(__FILE__)) . '/db_init.php';
$query2xml = XML_Query2XML::factory($db);
$dom = $query2xml->getXML(
    "SELECT
         *
     FROM
         customer c
         LEFT JOIN sale s ON c.customerid = s.customer_id
         LEFT JOIN album al ON s.album_id = al.albumid
         LEFT JOIN artist ar ON al.artist_id = ar.artistid
     ORDER BY
         c.customerid,
         s.saleid,
         al.albumid,
         ar.artistid",
    array(
        'rootTag' => 'music_store',
        'rowTag' => 'customer',
        'idColumn' => 'customerid',
        'elements' => array(
            'customerid',
            'first_name',
            'last_name',
            'email',
            'sales' => array(
                'rootTag' => 'sales',
                'rowTag' => 'sale',
                'idColumn' => 'saleid',
                'elements' => array(
                    'saleid',
                    'timestamp',
                    'date' => '#Callbacks::getFirstWord()',
                    'time' => '#Callbacks::getSecondWord()',
                    'album' => array(
                        'rootTag' => '',
                        'rowTag' => 'album',
                        'idColumn' => 'albumid',
                        'elements' => array(
                            'albumid',
                            'title',
                            'published_year',
                            'comment',
                            'artist' => array(
                                'rootTag' => '',
                                'rowTag' => 'artist',
                                'idColumn' => 'artistid',
                                'elements' => array(
                                    'artistid',
                                    'name',
                                    'birth_year',
                                    'birth_place',
                                    'genre'
                                ) //artist elements
                            ) //artist array
                        ) //album elements
                    ) //album array
                ) //sales elements
            ) //sales array
        ) //root elements
    ) //root
); //getXML method call

$root = $dom->firstChild;
$root->setAttribute('date_generated', '2005-08-23T14:52:50');

header('Content-Type: application/xml');

$dom->formatOutput = true;
print $dom->saveXML();

class Callbacks
{
    function getFirstWord($record)
    {
        return substr($record['timestamp'], 0, strpos($record['timestamp'], ' '));
    }
    
    function getSecondWord($record)
    {
        return substr($record['timestamp'], strpos($record['timestamp'], ' ') + 1);
    }
}
?>
--EXPECT--
<?xml version="1.0" encoding="UTF-8"?>
<music_store date_generated="2005-08-23T14:52:50">
  <customer>
    <customerid>1</customerid>
    <first_name>Jane</first_name>
    <last_name>Doe</last_name>
    <email>jane.doe@example.com</email>
    <sales>
      <sale>
        <saleid>1</saleid>
        <timestamp>2005-05-25 16:32:00</timestamp>
        <date>2005-05-25</date>
        <time>16:32:00</time>
        <album>
          <albumid>1</albumid>
          <title>New World Order</title>
          <published_year>1990</published_year>
          <comment>the best ever!</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
      <sale>
        <saleid>11</saleid>
        <timestamp>2005-05-25 16:23:00</timestamp>
        <date>2005-05-25</date>
        <time>16:23:00</time>
        <album>
          <albumid>2</albumid>
          <title>Curtis</title>
          <published_year>1970</published_year>
          <comment>that man's got somthin' to say</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
    </sales>
  </customer>
  <customer>
    <customerid>2</customerid>
    <first_name>John</first_name>
    <last_name>Doe</last_name>
    <email>john.doe@example.com</email>
    <sales>
      <sale>
        <saleid>2</saleid>
        <timestamp>2005-06-05 12:56:00</timestamp>
        <date>2005-06-05</date>
        <time>12:56:00</time>
        <album>
          <albumid>1</albumid>
          <title>New World Order</title>
          <published_year>1990</published_year>
          <comment>the best ever!</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
      <sale>
        <saleid>16</saleid>
        <timestamp>2005-06-05 12:56:12</timestamp>
        <date>2005-06-05</date>
        <time>12:56:12</time>
        <album>
          <albumid>3</albumid>
          <title>Shaft</title>
          <published_year>1972</published_year>
          <comment>he's the man</comment>
          <artist>
            <artistid>2</artistid>
            <name>Isaac Hayes</name>
            <birth_year>1942</birth_year>
            <birth_place>Tennessee</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
    </sales>
  </customer>
  <customer>
    <customerid>3</customerid>
    <first_name>Susan</first_name>
    <last_name>Green</last_name>
    <email>susan.green@example.com</email>
    <sales>
      <sale>
        <saleid>3</saleid>
        <timestamp>2005-07-10 11:03:00</timestamp>
        <date>2005-07-10</date>
        <time>11:03:00</time>
        <album>
          <albumid>1</albumid>
          <title>New World Order</title>
          <published_year>1990</published_year>
          <comment>the best ever!</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
      <sale>
        <saleid>12</saleid>
        <timestamp>2005-07-10 11:56:00</timestamp>
        <date>2005-07-10</date>
        <time>11:56:00</time>
        <album>
          <albumid>2</albumid>
          <title>Curtis</title>
          <published_year>1970</published_year>
          <comment>that man's got somthin' to say</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
    </sales>
  </customer>
  <customer>
    <customerid>4</customerid>
    <first_name>Victoria</first_name>
    <last_name>Alt</last_name>
    <email>victory.alt@example.com</email>
    <sales>
      <sale>
        <saleid>4</saleid>
        <timestamp>2005-07-10 10:03:00</timestamp>
        <date>2005-07-10</date>
        <time>10:03:00</time>
        <album>
          <albumid>1</albumid>
          <title>New World Order</title>
          <published_year>1990</published_year>
          <comment>the best ever!</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
      <sale>
        <saleid>17</saleid>
        <timestamp>2005-07-10 10:03:32</timestamp>
        <date>2005-07-10</date>
        <time>10:03:32</time>
        <album>
          <albumid>3</albumid>
          <title>Shaft</title>
          <published_year>1972</published_year>
          <comment>he's the man</comment>
          <artist>
            <artistid>2</artistid>
            <name>Isaac Hayes</name>
            <birth_year>1942</birth_year>
            <birth_place>Tennessee</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
    </sales>
  </customer>
  <customer>
    <customerid>5</customerid>
    <first_name>Will</first_name>
    <last_name>Rippy</last_name>
    <email>will.wippy@example.com</email>
    <sales>
      <sale>
        <saleid>5</saleid>
        <timestamp>2005-07-10 13:03:00</timestamp>
        <date>2005-07-10</date>
        <time>13:03:00</time>
        <album>
          <albumid>1</albumid>
          <title>New World Order</title>
          <published_year>1990</published_year>
          <comment>the best ever!</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
      <sale>
        <saleid>13</saleid>
        <timestamp>2005-07-10 13:12:00</timestamp>
        <date>2005-07-10</date>
        <time>13:12:00</time>
        <album>
          <albumid>2</albumid>
          <title>Curtis</title>
          <published_year>1970</published_year>
          <comment>that man's got somthin' to say</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
    </sales>
  </customer>
  <customer>
    <customerid>6</customerid>
    <first_name>Tim</first_name>
    <last_name>Raw</last_name>
    <email>tim.raw@example.com</email>
    <sales>
      <sale>
        <saleid>6</saleid>
        <timestamp>2005-07-10 14:03:00</timestamp>
        <date>2005-07-10</date>
        <time>14:03:00</time>
        <album>
          <albumid>1</albumid>
          <title>New World Order</title>
          <published_year>1990</published_year>
          <comment>the best ever!</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
      <sale>
        <saleid>18</saleid>
        <timestamp>2005-07-10 14:03:52</timestamp>
        <date>2005-07-10</date>
        <time>14:03:52</time>
        <album>
          <albumid>3</albumid>
          <title>Shaft</title>
          <published_year>1972</published_year>
          <comment>he's the man</comment>
          <artist>
            <artistid>2</artistid>
            <name>Isaac Hayes</name>
            <birth_year>1942</birth_year>
            <birth_place>Tennessee</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
    </sales>
  </customer>
  <customer>
    <customerid>7</customerid>
    <first_name>Nick</first_name>
    <last_name>Fallow</last_name>
    <email>nick.fallow@example.com</email>
    <sales>
      <sale>
        <saleid>7</saleid>
        <timestamp>2005-07-10 15:03:00</timestamp>
        <date>2005-07-10</date>
        <time>15:03:00</time>
        <album>
          <albumid>1</albumid>
          <title>New World Order</title>
          <published_year>1990</published_year>
          <comment>the best ever!</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
      <sale>
        <saleid>14</saleid>
        <timestamp>2005-07-10 15:09:00</timestamp>
        <date>2005-07-10</date>
        <time>15:09:00</time>
        <album>
          <albumid>2</albumid>
          <title>Curtis</title>
          <published_year>1970</published_year>
          <comment>that man's got somthin' to say</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
    </sales>
  </customer>
  <customer>
    <customerid>8</customerid>
    <first_name>Ed</first_name>
    <last_name>Burton</last_name>
    <email>ed.burton@example.com</email>
    <sales>
      <sale>
        <saleid>8</saleid>
        <timestamp>2005-07-10 16:03:00</timestamp>
        <date>2005-07-10</date>
        <time>16:03:00</time>
        <album>
          <albumid>1</albumid>
          <title>New World Order</title>
          <published_year>1990</published_year>
          <comment>the best ever!</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
      <sale>
        <saleid>19</saleid>
        <timestamp>2005-07-10 16:03:01</timestamp>
        <date>2005-07-10</date>
        <time>16:03:01</time>
        <album>
          <albumid>3</albumid>
          <title>Shaft</title>
          <published_year>1972</published_year>
          <comment>he's the man</comment>
          <artist>
            <artistid>2</artistid>
            <name>Isaac Hayes</name>
            <birth_year>1942</birth_year>
            <birth_place>Tennessee</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
    </sales>
  </customer>
  <customer>
    <customerid>9</customerid>
    <first_name>Jack</first_name>
    <last_name>Woo</last_name>
    <email>jack.woo@example.com</email>
    <sales>
      <sale>
        <saleid>9</saleid>
        <timestamp>2005-07-10 18:03:00</timestamp>
        <date>2005-07-10</date>
        <time>18:03:00</time>
        <album>
          <albumid>1</albumid>
          <title>New World Order</title>
          <published_year>1990</published_year>
          <comment>the best ever!</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
      <sale>
        <saleid>15</saleid>
        <timestamp>2005-07-10 18:49:00</timestamp>
        <date>2005-07-10</date>
        <time>18:49:00</time>
        <album>
          <albumid>2</albumid>
          <title>Curtis</title>
          <published_year>1970</published_year>
          <comment>that man's got somthin' to say</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
    </sales>
  </customer>
  <customer>
    <customerid>10</customerid>
    <first_name>Maria</first_name>
    <last_name>Gonzales</last_name>
    <email>maria.gonzales@example.com</email>
    <sales>
      <sale>
        <saleid>10</saleid>
        <timestamp>2005-07-10 19:03:00</timestamp>
        <date>2005-07-10</date>
        <time>19:03:00</time>
        <album>
          <albumid>1</albumid>
          <title>New World Order</title>
          <published_year>1990</published_year>
          <comment>the best ever!</comment>
          <artist>
            <artistid>1</artistid>
            <name>Curtis Mayfield</name>
            <birth_year>1920</birth_year>
            <birth_place>Chicago</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
      <sale>
        <saleid>20</saleid>
        <timestamp>2005-07-10 19:03:50</timestamp>
        <date>2005-07-10</date>
        <time>19:03:50</time>
        <album>
          <albumid>3</albumid>
          <title>Shaft</title>
          <published_year>1972</published_year>
          <comment>he's the man</comment>
          <artist>
            <artistid>2</artistid>
            <name>Isaac Hayes</name>
            <birth_year>1942</birth_year>
            <birth_place>Tennessee</birth_place>
            <genre>Soul</genre>
          </artist>
        </album>
      </sale>
    </sales>
  </customer>
</music_store>
