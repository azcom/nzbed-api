<?php
define('PHP_COMPAT_FILE_GET_CONTENTS_MAX_REDIRECTS', 5);

/**
 * Replace file_get_contents()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @license     LGPL - http://www.gnu.org/licenses/lgpl.html
 * @copyright   2004-2007 Aidan Lister <aidan@php.net>, Arpad Ray <arpad@php.net>
 * @link        http://php.net/function.file_get_contents
 * @author      Aidan Lister <aidan@php.net>
 * @author      Arpad Ray <arpad@php.net>
 * @version     $Revision: 280045 $
 * @internal    resource_context is only supported for PHP 4.3.0+ (stream_context_get_options)
 * @since       PHP 4.3.0
 * @require     PHP 4.0.0 (user_error)
 */
function php_compat_file_get_contents($filename, $incpath = false, $resource_context = null, $offset = -1, $maxlen = -1)
{
    if (is_resource($resource_context) && function_exists('stream_context_get_options')) {
        $opts = stream_context_get_options($resource_context);
    }
    
    $colon_pos = strpos($filename, '://');
    $wrapper = $colon_pos === false ? 'file' : substr($filename, 0, $colon_pos);
    $opts = (empty($opts) || empty($opts[$wrapper])) ? array() : $opts[$wrapper];

    $data = false;
    switch ($wrapper) {
    case 'http':
        $max_redirects = (isset($opts[$wrapper]['max_redirects'])
            ? $opts[$proto]['max_redirects']
            : PHP_COMPAT_FILE_GET_CONTENTS_MAX_REDIRECTS);
        for ($i = 0; $i < $max_redirects; $i++) {
            $data = php_compat_http_get_contents_helper($filename, $opts);
            if (is_array($contents)) {
                // redirected
                $filename = rtrim($contents[1]);
                $data = '';
                continue;
            }
            break 2;
        }
        user_error('redirect limit exceeded', E_USER_WARNING);
        return;
    case 'ftp':
    case 'https':
    case 'ftps':
    case 'socket':
        // tbc               
    }

    if (false === $data) {
        if (is_resource($resource_context) && version_compare(PHP_VERSION, '4.3.0', 'ge')) {
            $fh = fopen($filename, 'rb', $incpath, $resource_context);
        } else {
            $fh = fopen($filename, 'rb', $incpath);
        }
        if (false === $fh) {
            user_error('failed to open stream: No such file or directory',
                E_USER_WARNING);
            return false;
        }

        clearstatcache();
        if ($fsize = @filesize($filename)) {
            $data = fread($fh, $fsize);
        } else {
            $data = '';
            while (!feof($fh)) {
                $data .= fread($fh, 8192);
            }
        }

        fclose($fh);
    }
    
    if ($offset != -1) {
        $data = substr($data, $offset);
    }
    
    if ($maxlen != -1) {
        $data = substr($data, 0, $maxlen);
    }
    
    return $data;
}

/**
 * Performs HTTP requests
 *
 * @param string $filename
 *  the full path to request
 * @param array $opts
 *  an array of stream context options
 * @return mixed
 *  either the contents of the requested path (as a string),
 *  or an array where $array[1] is the path redirected to.
 */
function php_compat_http_get_contents_helper($filename, $opts)
{
    $path = parse_url($filename);
    if (!isset($path['host'])) {
        return '';
    }
    $port = isset($path['port']) ? $path['port'] : 80;
    $fp = fsockopen($path['host'], $port, $errno, $errstr, 4);
    if (!$fp) {
        return '';
    }
    if (!isset($path['path'])) {
        $path['path'] = '/';
    }
    
    $headers = array(
        'Host'      => $path['host'],
        'Conection' => 'close'
    );
    
    // enforce some options (proxy isn't supported) 
    $opts_defaults = array(
        'method'            => 'GET',
        'header'            => null,
        'user_agent'        => ini_get('user_agent'),
        'content'           => null,
        'request_fulluri'   => false
    );
        
    foreach ($opts_defaults as $key => $value) {
        if (!isset($opts[$key])) {
            $opts[$key] = $value;
        }
    }
    $opts['path'] = $opts['request_fulluri'] ? $filename : $path['path'];
    
    // build request
    $request = $opts['method'] . ' ' . $opts['path'] . " HTTP/1.0\r\n";

    // build headers
    if (isset($opts['header'])) {
        $optheaders = explode("\r\n", $opts['header']);
        for ($i = count($optheaders); $i--;) {
            $sep_pos = strpos($optheaders[$i], ': ');
            $headers[substr($optheaders[$i], 0, $sep_pos)] = substr($optheaders[$i], $sep_pos + 2);
        }
    }
    foreach ($headers as $key => $value) {
        $request .= "$key: $value\r\n";
    }
    $request .= "\r\n" . $opts['content'];
    
    // make request
    fputs($fp, $request);
    $response = '';
    while (!feof($fp)) {
        $response .= fgets($fp, 8192);
    }
    fclose($fp);    
    $content_pos = strpos($response, "\r\n\r\n");
    $headers = substr($response, 0, $content_pos);
    $content = substr($response, $content_pos + 4);
    
    $GLOBALS['http_response_header'] = explode("\r\n", $headers);
    
    // recurse for redirects
    if (preg_match('/^Location: (.*)$/mi', $headers, $matches)) {
        return $matches;
    }
    
    return $content;
}

// Define
if (!function_exists('file_get_contents')) {
    function file_get_contents($filename, $incpath = false, $resource_context = null, $offset = -1, $maxlen = -1)
    {
        return php_compat_file_get_contents($filename, $incpath, $resource_context);
    }
}

?>