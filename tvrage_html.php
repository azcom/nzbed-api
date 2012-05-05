<?php

/**************************************************
 * NZBed
 * Copyright (c) 2008 Harry Bragg
 * tiberious.org
 * Module: tvrage
 **************************************************
 *
 * Full GPL License: <http://www.gnu.org/licenses/gpl.txt>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
 */

require_once( 'HTTP/Request.php' );

class tvrage
{
	/**
	 * Lists the definitions for tvrage website
	 *
	 * @var array of regular expressions
	 * @access public
	 */
	var $_def 		 = array(
		'url' => array(
			'search' => 'http://www.tvrage.com/search.php?search=%s',
			'episodeList' => 'http://www.tvrage.com/%s/episode_list/%d',
			'show' => 'http://www.tvrage.com/%s/',
			'episode' => 'http://www.tvrage.com/%s/episodes/%s/'		
		),
		'findShow'				=> "/<a  href='http:\/\/www.tvrage.com\/([^']+)' >([^<]+)<\/a>/i",
		'findEpisodeFromSeries' => "/<a href=\'.+\/(\d+)\/0*%dx%02d\'>.+<\/a>/i",
		'findEpisodeFromName'   => "/<a href=\'.+\/(\d+)\/0*\d+x\d+\'>.*?%s.*?<\/a>/i",
        'show'         => array(
            'name'              => '/<h5 class=\'nospace\'><a name=\'summary\'>&nbsp;<\/a>"(.+?)" Summary<\/h5>/i', 
            'genre'             => '/<tr><td width=\'\d+\' valign=\'top\'><b>Genre: <\/b><\/td><td>(.+)<\/td>/i',
            'classification'    => '/<tr><td width=\'\d+\' valign=\'top\'><b>Classification: <\/b><\/td><td>(.+)<\/td>/i',
            'status'            => '/<tr><td width=\'\d+\' valign=\'top\'><b>Status: <\/b><\/td><td>(.+)<\/td>/i',
            'episodes'          => '/<tr><td width=\'\d+\' valign=\'top\'><b>(First|Last|Latest|Next) Episode: <\/b><\/td><td>(?:<table cellspacing=\'0\' cellpadding=\'0\'><tr><td valign=\'top\'>)?<a\s+href=\'.+\'>(?:\d+): (\d+)x(\d+) \| (.+)<\/a>(?:\s\(([a-z0-9\/]+)\))?/i',
            'airtime'           => '/<tr><td width=\'\d+\' valign=\'top\'><b>Airs on: <\/b><\/td><td>(.+) \((.+)\)<\/td>/i',
        ),
		'episode' 	=> array(
			'title'             => '/<b>Title: <\/b><\/td><td [^>]+>(.+)<\/td>/i',
			'epnum'             => '/<b>Episode Number: <\/b><\/td><td [^>]+>(\d+)<\/td>/i',
            'series'            => '/<b>Episode #: <\/b><\/td><td [^>]+>0?(\d+)x\d+<\/td>/i',
            'sepnum'            => '/<b>Episode #: <\/b><\/td><td [^>]+>\d+x(\d+)<\/td>/i',
			'prodnum'           => '/<b>Production Number: <\/b><\/td><td [^>]+>(\d+)<\/td>/i',
			'airdate'           => '/<b>Original Airdate: <\/b><\/td><td [^>]+>(.+)<\/td>/i'
		),
		'findEpisodeFromDate'   => "/<td width='\d+' align='center' class='b1'><table align='center' cellspacing='0'><tr>\s+<td width='15' align='left'>%02d<\/td><td>\/<\/td>\s+<td width='10'>%s<\/td><td>\/<\/td>\s+<td width='10'>%d<\/td><\/table><\/td>\s+<td style='padding-left: 6px;' class='b1'>\s*<a href='.+\/(\d+)\/0*([0-9]+)x([0-9]+)'>(.+)<\/a>\s*<\/td>/i",
		'command' => array(
			'showEp'			=> '/^(.+) s?(\d+)(?:x|e)(\d+)/i',
			'showEpName'		=> '/^(.+), (.+)$/i'
		),
		'error' => '/Unknown Error/i',
		'regex' => array(
			'epID' => '/\/(\d+)$/i',
			'showID' => '/^shows\/id(?:-|\s)(\d+)$/i',
		),
	);

	var $debug = false;

	/*****************************************************
	 * Main functions
	 *****************************************************/
	
	/**
	 * Get URL
	 *
	 * @param string $url - url to get
	 * @return contents of the page
	 * @access public
	 */
	function getUrl( $url )
	{
		if ( $this->debug ) printf( "  Get URL: %s\n", $url );
		$req =& new HTTP_Request( );
		$req->setMethod(HTTP_REQUEST_METHOD_GET);
		$req->setURL( $url, array( 'timeout' => '30', 'readTimeout' => 30, 'allowRedirects' => true ) );
		$request = $req->sendRequest();
		if (PEAR::isError($request)) {
			unset( $req, $request );
			return false;
		} else {
			$body = $req->getResponseBody();
			unset( $req, $request );
			return $body;
		}
	}
	
	/**
	 * look for a show
	 *
	 * @param string $query - Show search query
	 * @return int - tvrage.com showid
	 * @access public
	 */
	function findShow( $query, $ignoreCache = false )
	{
		// temp fix
		$ignoreCache = true;

		if ( $this->debug ) printf("findShow( query:%s, ignoreCache:%d )\n", $query, $ignoreCache );

		global $api;
		
		$res = $api->db->select( '*', 'tvrage_search', array('search' => $query ), __FILE__, __LINE__ );
		
		$nRows = $api->db->rows( $res );
		
		// check the cache
		if ( $nRows >= 1 )
        {
            $row = $api->db->fetch( $res );
            if ( ( $row->ftvrageShowID != '') && ( $row->ftvrageShowID != 0 ) )
            {
				if ( $this->debug ) printf( " using forced ID: %s ", $row->ftvrageShowID );
                return $row->ftvrageShowID;   
            }
            else if ( ( mt_rand(1, 100) <= (100 * 0.9) ) &&
			     ( $ignoreCache == false ) )
            {
		if ( $this->debug ) printf( " using cached ID: %s ", $row->tvrageShowID );
                return $row->tvrageShowID; 
            }
        }
                 
		// find tv show
		//$url = sprintf( $this->_def['url']['search'], urlencode( strtolower( $query ) ) );
		$url = sprintf( $this->_def['url']['search'], urlencode( $query ) );
		if ( ( $page = $this->getUrl( $url ) ) !== false )
		{
			if ( preg_match($this->_def['findShow'], $page, $tvurl) )
			{
				if ( $this->debug ) printf(" found a show\n" );
				if ( $this->debug ) var_dump( $tvurl );
/*
				if ( $nRows >= 1 )
					$api->db->update( 'tvrage_search', array( 'tvrageShowID' => $tvurl[1] ), array( 'search' => $query ), __FILE__, __LINE__ );
				else
					$api->db->insert( 'tvrage_search', array( 'tvrageShowID' => $tvurl[1], 'search' => $query ), __FILE__, __LINE__ );
*/
				return $tvurl[1];
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	function getFShow( $query, $ignoreCache = false )
	{
		if ( ( $showID = $this->findShow( $query, $ignoreCache ) ) !== false )
		{				
			return $this->getShow( $showID, $ignoreCache );
		}
		else
			return false;
	}
	
	/**
	 * @param string $tvin - tvrage showID
	 * @return array - Show information
	 * @access public
	 */
	function getShow( $showID, $ignoreCache = false, $tvID = false )
	{
		// temp fix
		$ignoreCache = true;		

		if ( $this->debug ) printf("getShow( showID:%s, ignoreCache:%d )\n", $showID, $ignoreCache );

		global $api;

		$res = $api->db->select( '*', 'tvrage_show', array( 'tvrageShowID' => $showID ), __FILE__, __LINE__ );
		
		$nRows = $api->db->rows( $res );
		
		if ( $nRows >= 1 )
			$row = $api->db->fetch( $res );
	
		// check cache
		if ( ( $nRows >= 1 ) &&
		     ( mt_rand(1, 100) <= (100 * 0.9) ) &&
			 ( $ignoreCache == false ) )
		{
			if ( $this->debug ) printf(" using tv show cache\n");
			return $row;
		}
			
		$url = sprintf( $this->_def['url']['show'], $showID );
		if ( ( $page = $this->getUrl( $url ) ) !== false )
		{
			if ( preg_match( $this->_def['error'], $page ) )
			{
				if ( $this->debug ) printf(" error getting tv show\n");
				return false;
			}
					
			preg_match( $this->_def['show']['name'], $page, $name );
			preg_match( $this->_def['show']['genre'], $page, $genre );
			preg_match( $this->_def['show']['classification'], $page, $class );
			
			$show = array(
				'tvrageShowID' => $showID,
				'name' => $api->stringDecode( $name[1] ),
				'genre' => $api->stringDecode( $genre[1] ),
				'class' => $api->stringDecode( $class[1] ),
				'url' => substr(
					sprintf( $this->_def['url']['show'], $showID ),
					0, -1 )
			);

			if ( $this->debug ) var_dump( $show );

			if ( empty( $show['name'] ) )
			{
				if ( $nRows >= 1 )
				{
					return $row;
				}
				return false;
			}
				
			if ( $nRows >= 1 )
			{
				if ( !empty( $row->nzbName ) )
					$show['nzbName'] = rtrim( $row->nzbName );
				if ( !empty( $row->nzbGenre ) )
					$show['nzbGenre'] = $row->nzbGenre;
				$show['usenetToTvrage'] = $row->usenetToTvrage;
				$show['tvrageToNewzbin'] = $row->tvrageToNewzbin;
			}

/*			
			if ( $nRows >= 1 )
			{
				$api->db->update( 'tvrage_show', $show, array( 'showID' => $row->showID ), __FILE__, __LINE__ );
			}
			else
				$api->db->insert( 'tvrage_show', $show, __FILE__, __LINE__ );
*/						
			return (object)$show;
		} else {
			return false;
		}

	}

	/**
	 * look for a episode
	 *
	 * @param string showID - tvrage.com showid
	 * @param int $series - Series Number
	 * @param int $episode - Episode in $series number
	 * @return int - episodeID
	 * @access public
	 */
	function findEpisode( $showID, $series, $episode )
	{	
		if ( $this->debug ) printf("findEpisode( showID:%s, series:%d, episode:%d )\n", $showID, $series, $episode );

		// download series page
		$url = sprintf( $this->_def['url']['episodeList'], $showID, ltrim($series, '0') );
		$tmp = $this->getUrl( $url );
		
		// get episode id
		if ( preg_match( sprintf( $this->_def['findEpisodeFromSeries'], $series, $episode ), $tmp, $epid ) )
		{
			if ( $this->debug ) var_dump( $epid );
			return $epid[1];
		}
		else
		{
			return false;
		}	
	}

    /**
     * @param string $showid - tvrage showid
     * @param int $date - date to start looking on 
     * @return array - array of Episode information
     * @access public
     */    
    function findEpisodeFromDate( $showid, $date, $ignoreCache = false )
    {
		$ignoreCache = true;

		if ( $this->debug ) printf("findEpisodeFromDate( showid:%s, date:%d, ignoreCache:%d )\n", $showid, $date, $ignoreCache );	

        global $api;

        $url = sprintf('http://www.tvrage.com/%s/episode_list/all', $showid );
        $multiep = $this->getUrl($url);
        
        $reg = sprintf( $this->_def['findEpisodeFromDate'], date( 'd', $date ), date( 'M', $date ), date( 'Y', $date ) );
        if ( preg_match($reg, $multiep, $matches) )
        {
			if ( $this->debug ) var_dump( $matches );
            return $matches[1];        
        }
        else
        {
            return false;   
        }
    }

    
	/**
	 * @param string $show - tvrage showID
	 * @param int $id - episodeID
	 * @return array - Episode information
	 * @access public
	 */	
	function getEpisode( $showID, $series, $episode, $ignoreCache = false )
	{
		$ignoreCache = true;

		if ( $this->debug ) printf("getEpisode( showID:%s, series:%d, episode:%d, ignoreCache:%d )\n", $showID, $series, $episode, $ignoreCache );

		global $api;

		$res = $api->db->select( '*', 'tvrage_episode', array( 'tvrageShowID' => $showID, 'series' => $series, 'episode' => $episode ), __FILE__, __LINE__ );
		
		$nRows = $api->db->rows( $res );
/*		
		if ( $nRows >= 1 )
		{
			$row = $api->db->fetch( $res );
			$id = $row->tvrageEpisodeID;

			if ( $this->debug ) printf(" using id from cache: %d\n", $id );
		}
		else
		{
*/
			$id = $this->findEpisode( $showID, $series, $episode );
/*
		}
*/
	
		// check cache
		if ( ( $nRows >= 1 ) &&
		     ( mt_rand(1, 100) <= (100 * 0.9) ) &&
			 ( $ignoreCache == false ) )
		{
			if ( $this->debug ) printf(" using episode cache\n" );
			return $row;
		}
	
		$url = sprintf( $this->_def['url']['episode'], $showID, $id );
		
		if ( ( $page = $this->getUrl( $url ) ) !== false )
		{
			if ( preg_match( $this->_def['error'], $page ) )
			{
				if ( $this->debug ) printf(" tvrage page error\n" );
				return false;
			}		
			preg_match( $this->_def['episode']['title'], $page, $title );
			preg_match( $this->_def['episode']['series'], $page, $series );
			preg_match( $this->_def['episode']['sepnum'], $page, $sepnum );
			preg_match( $this->_def['episode']['airdate'], $page, $adate );
				
			$ep = array(
				'tvrageEpisodeID' => $id,
				'tvrageShowID' => $showID,
				'title' => $api->stringDecode( $title[1] ),
				'series' => $series[1],
				'episode' => $sepnum[1],
                'date' => strtotime( $adate[1] ),
				'url' => sprintf( 'http://www.tvrage.com/%s/episodes/%d/%dx%02d/', $showID, $id, $series[1], $sepnum[1] ) );
			
			if ( $this->debug ) var_dump( $ep );
	
			if ( empty( $ep['title'] ) )
			{
				if ( $nRows >= 1 )
				{
					return $row;
				}
				else
				{
					return false;
				}
			}				

/*			if ( $nRows >= 1 )
				$api->db->update( 'tvrage_episode', $ep, array( 'episodeID' => $row->episodeID ), __FILE__, __LINE__ );
			else
				$api->db->insert( 'tvrage_episode', $ep, __FILE__, __LINE__ );
*/						
			return (object)$ep;
		} else {
			return false;
		}
	}

	function getDateEpisode( $showID, $date, $ignoreCache = false )
	{
		if ( ( $epID = $this->findEpisodeFromDate( $showID, $date ) ) !== false )
		{
			return $this->getIDEpisode( $showID, $epID, $ignoreCache );
		}
		else
			return false;
	}

	/**
	 * @param string $show - tvrage showID
	 * @param int $id - episodeID
	 * @return array - Episode information
	 * @access public
	 */	
	function getIDEpisode( $showID, $episodeID, $ignoreCache = false )
	{
		$ignoreCache = true;

		if ( $this->debug ) printf( "getIDEpisode( showID:%s, episodeID:%d, ignoreCache:%d )\n", $showID, $episodeID, $ignoreCache );

		global $api;

		$res = $api->db->select( '*', 'tvrage_episode', array( 'tvrageShowID' => $showID, 'tvrageEpisodeID' => $episodeID ), __FILE__, __LINE__ );
		
		$nRows = $api->db->rows( $res );
		
		$id = $episodeID;
	
		// check cache
		if ( ( $nRows >= 1 ) &&
		     ( mt_rand(1, 100) <= (100 * 0.9) ) &&
			 ( $ignoreCache == false ) )
		{
			if ( $this->debug ) printf(" using episode cache\n" );			

			$row = $api->db->fetch( $res );
			return $row;
		}
	
		$url = sprintf( $this->_def['url']['episode'], $showID, $id );
		
		if ( ( $page = $this->getUrl( $url ) ) !== false )
		{
			if ( preg_match( $this->_def['error'], $page ) )
			{
				if ( $nRows >= 1 )
				{
					return $row;
				}
				else
				{
					return false;
				}
			}		
			preg_match( $this->_def['episode']['title'], $page, $title );
			preg_match( $this->_def['episode']['series'], $page, $series );
			preg_match( $this->_def['episode']['sepnum'], $page, $sepnum );
			preg_match( $this->_def['episode']['airdate'], $page, $adate );
				
			$ep = array(
				'tvrageEpisodeID' => $id,
				'tvrageShowID' => $showID,
				'title' => $api->stringDecode( $title[1] ),
				'series' => $series[1],
				'episode' => $sepnum[1],
                'date' => strtotime( $adate[1] ),
				'url' => sprintf( 'http://www.tvrage.com/%s/episodes/%d/%dx%02d/', $showID, $id, $series[1], $sepnum[1] ) );

			if ( $this->debug ) var_dump( $ep );
				
			if ( empty( $ep['title'] ) )
			{
				if ( $nRows >= 1 )
				{
					return $row;
				}
				else
				{
					return false;
				}
			}				
/*
			if ( $nRows >= 1 )
				$api->db->update( 'tvrage_episode', $ep, array( 'episodeID' => $row->episodeID ), __FILE__, __LINE__ );
			else
				$api->db->insert( 'tvrage_episode', $ep, __FILE__, __LINE__ );
*/						
			return (object)$ep;
		} else {
			return false;
		}
	}
	
}

?>
