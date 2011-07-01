<?php
class Akismet
{
	var $version = '0.2';
	var $wordPressAPIKey;
	var $blogURL;
	var $comment;
	var $apiPort;
	var $akismetServer;
	var $akismetVersion;
	// This prevents some potentially sensitive information from being sent accross the wire.
	var $ignore = array('HTTP_COOKIE', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED_HOST', 'HTTP_MAX_FORWARDS', 'HTTP_X_FORWARDED_SERVER', 'REDIRECT_STATUS', 'SERVER_PORT', 'PATH', 'DOCUMENT_ROOT', 'SERVER_ADMIN', 'QUERY_STRING', 'PHP_SELF');

	/**
	 *	@throws	Exception	An exception is thrown if your API key is invalid.
	 *	@param	string	Your WordPress API key.
	 *	@param	string	$blogURL			The URL of your blog.
	 */
	function Akismet($blogURL, $wordPressAPIKey)
	{
		global $HTTP_SERVER_VARS;
		$this->blogURL = $blogURL;
		$this->wordPressAPIKey = $wordPressAPIKey;
		// Set some default values
		$this->apiPort = 80;
		$this->akismetServer = 'rest.akismet.com';
		$this->akismetVersion = '1.1';
		// Start to populate the comment data
		$this->comment['blog'] = $blogURL;
		$this->comment['user_agent'] = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		$this->comment['referrer'] = $HTTP_SERVER_VARS['HTTP_REFERER'];
		// This is necessary if the server PHP5 is running on has been set up to run PHP4 and
		// PHP5 concurently and is actually running through a separate proxy al a these instructions:
		// http://www.schlitt.info/applications/blog/archives/83_How_to_run_PHP4_and_PHP_5_parallel.html
		// and http://wiki.coggeshall.org/37.html
		// Otherwise the user_ip appears as the IP address of the PHP4 server passing the requests to the
		// PHP5 one...
		$this->comment['user_ip'] = $HTTP_SERVER_VARS['REMOTE_ADDR'] != getenv('SERVER_ADDR') ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : getenv('HTTP_X_FORWARDED_FOR');
	}
	
	function check_api_key()
	{
		// Check to see if the key is valid
		$response = $this->http_post('key=' . $this->wordPressAPIKey . '&blog=' . $this->blogURL, $this->akismetServer, '/' . $this->akismetVersion . '/verify-key');
		if ($response[1] != 'valid')
		{
			// Whoops, no it's not. Akismet Spam Butcher will do whatever is appropriate in this scenario.
			return false;
		}
		else
		{
			// We're good, we can continue
			return true;
		}
	}

	function http_post($request, $host, $path)
	{
		global $board_config;
		$http_request  = "POST " . $path . " HTTP/1.1\r\n";
		$http_request .= "Host: " . $host . "\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n";
		$http_request .= "Content-Length: " . strlen($request) . "\r\n";
		$http_request .= "User-Agent: Akismet PHP5 Class " . $this->version . " | Akismet/1.11\r\n"; // @todo Probably should specify phpBB here...
		$http_request .= "\r\n";
		$http_request .= $request;

		$socketWriteRead = new SocketWriteRead($host, $this->apiPort, $http_request);
		$socketWriteRead->send();

		return explode("\r\n\r\n", $socketWriteRead->getResponse(), 2);
	}

	// Formats the data for transmission
	function getQueryString()
	{
		global $HTTP_SERVER_VARS;
		foreach($HTTP_SERVER_VARS as $key => $value)
		{
			if (!in_array($key, $this->ignore))
			{
				if ($key == 'REMOTE_ADDR')
				{
					$this->comment[$key] = $this->comment['user_ip'];
				}
				else
				{
					$this->comment[$key] = $value;
				}
			}
		}
		$query_string = '';
		foreach($this->comment as $key => $data)
		{
			$query_string .= $key . '=' . urlencode(stripslashes($data)) . '&';
		}
		return $query_string;
	}

	/**
	 *	Tests for spam.
	 *
	 *	Uses the web service provided by {@link http://www.akismet.com Akismet} to see whether or not the submitted comment is spam.  Returns a boolean value.
	 *
	 *	@return		bool	True if the comment is spam, false if not
	 */
	function isCommentSpam()
	{
		$response = $this->http_post($this->getQueryString(), $this->wordPressAPIKey . '.rest.akismet.com', '/' . $this->akismetVersion . '/comment-check');
		return ($response[1] == 'true');
	}

	/**
	 *	Submit spam that is incorrectly tagged as ham.
	 *
	 *	Using this function will make you a good citizen as it helps Akismet to learn from its mistakes.  This will improve the service for everybody.
	 */
	function submitSpam()
	{
		$this->http_post($this->getQueryString(), $this->wordPressAPIKey . '.' . $this->akismetServer, '/' . $this->akismetVersion . '/submit-spam');
	}

	/**
	 *	Submit ham that is incorrectly tagged as spam.
	 *
	 *	Using this function will make you a good citizen as it helps Akismet to learn from its mistakes.  This will improve the service for everybody.
	 */
	function submitHam()
	{
		$this->http_post($this->getQueryString(), $this->wordPressAPIKey . '.' . $this->akismetServer, '/' . $this->akismetVersion . '/submit-ham');
	}

	/**
	 *	To override the user IP address when submitting spam/ham later on
	 *
	 *	@param string $userip	An IP address.  Optional.
	 */
	function setUserIP($userip)
	{
		$this->comment['user_ip'] = $userip;
	}

	/**
	 *	To override the referring page when submitting spam/ham later on
	 *
	 *	@param string $referrer	The referring page.  Optional.
	 */
	function setReferrer($referrer)
	{
		$this->comment['referrer'] = $referrer;
	}

	/**
	 *	A permanent URL referencing the blog post the comment was submitted to.
	 *
	 *	@param string $permalink	The URL.  Optional.
	 */
	function setPermalink($permalink)
	{
		$this->comment['permalink'] = $permalink;
	}

	/**
	 *	The type of comment being submitted.
	 *
	 *	May be blank, comment, trackback, pingback, or a made up value like "registration" or "wiki".
	 */
	function setCommentType($commentType)
	{
		$this->comment['comment_type'] = $commentType;
	}

	/**
	 *	The name that the author submitted with the comment.
	 */
	function setCommentAuthor($commentAuthor)
	{
		$this->comment['comment_author'] = $commentAuthor;
	}

	/**
	 *	The email address that the author submitted with the comment.
	 *
	 *	The address is assumed to be valid.
	 */
	function setCommentAuthorEmail($authorEmail)
	{
		$this->comment['comment_author_email'] = $authorEmail;
	}

	/**
	 *	The URL that the author submitted with the comment.
	 */
	function setCommentAuthorURL($authorURL)
	{
		$this->comment['comment_author_url'] = $authorURL;
	}

	/**
	 *	The comment's body text.
	 */
	function setCommentContent($commentBody)
	{
		$this->comment['comment_content'] = $commentBody;
	}

	/**
	 *	Defaults to 80
	 */
	function setAPIPort($apiPort)
	{
		$this->apiPort = $apiPort;
	}

	/**
	 *	Defaults to rest.akismet.com
	 */
	function setAkismetServer($akismetServer)
	{
		$this->akismetServer = $akismetServer;
	}

	/**
	 *	Defaults to '1.1'
	 */
	function setAkismetVersion($akismetVersion)
	{
		$this->akismetVersion = $akismetVersion;
	}
}

/**
 *	Utility class used by Akismet
 *
 *  This class is used by Akismet to do the actual sending and receiving of data.  It opens a connection to a remote host, sends some data and the reads the response and makes it available to the calling program.
 *
 *  The code that makes up this class originates in the Akismet WordPress plugin, which is {@link http://akismet.com/download/ available on the Akismet website}.
 *
 *	N.B. It is not necessary to call this class directly to use the Akismet class.  This is included here mainly out of a sense of completeness.
 *
 *	@package	akismet
 *	@name		SocketWriteRead
 *	@version	0.1
 *  @author		Alex Potsides
 *  @link		http://www.achingbrain.net/
 */
class SocketWriteRead {
	var $host;
	var $port;
	var $request;
	var $response;
	var $responseLength;
	var $errorNumber;
	var $errorString;

	/**
	 *	@param	string	$host			The host to send/receive data.
	 *	@param	int		$port			The port on the remote host.
	 *	@param	string	$request		The data to send.
	 *	@param	int		$responseLength	The amount of data to read.  Defaults to 1160 bytes.
	 */
	function SocketWriteRead($host, $port, $request, $responseLength = 1160)
	{
		$this->host = $host;
		$this->port = $port;
		$this->request = $request;
		$this->responseLength = $responseLength;
		$this->errorNumber = 0;
		$this->errorString = '';
	}

	/**
	 *  Sends the data to the remote host.
	 *
	 * @throws	An exception is thrown if a connection cannot be made to the remote host.
	 */
	function send()
	{
		$this->response = '';
		$fs = fsockopen($this->host, $this->port, $this->errorNumber, $this->errorString, 3);
		if ($this->errorNumber != 0)
		{
			trigger_error('Error connecting to host: ' . $this->host . ' Error number: ' . $this->errorNumber . ' Error message: ' . $this->errorString, E_USER_ERROR);
		}
		if ($fs !== false)
		{
			@fwrite($fs, $this->request);
			while(!feof($fs))
			{
				$this->response .= fgets($fs, $this->responseLength);
			}
			fclose($fs);
		}
	}

	/**
	 *  Returns the server response text
	 *
	 *  @return	string
	 */
	function getResponse()
	{
		return $this->response;
	}

	/**
	 *	Returns the error number
	 *
	 *	If there was no error, 0 will be returned.
	 *
	 *	@return int
	 */
	function getErrorNumner()
	{
		return $this->errorNumber;
	}

	/**
	 *	Returns the error string
	 *
	 *	If there was no error, an empty string will be returned.
	 *
	 *	@return string
	 */
	function getErrorString()
	{
		return $this->errorString;
	}
}
