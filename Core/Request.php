<?php
namespace Core;
use Phalcon\Http\Request as HttpRequest;
/**
 * Written to work with Phalcon 2.0.8
 */
class Request extends HttpRequest
{
	protected $_postCache;
	protected $_queryCache;
	private $_isJson;
	/**
	 * Determine (and store in memory) whether or not the current request content-type is application/json or not
	 * @return boolean
	 */
	private function isApplicationJson()
	{
		if(is_null($this->_isJson)){
			$this->_isJson = (($contentType = $this->getDI()->getShared('request')->getHeader('CONTENT_TYPE')) && explode(';', $contentType)[0] === 'application/json');
		}
		return $this->_isJson;
	}
	public function getPut($name = NULL, $filters = NULL, $defaultValue = NULL, $notAllowEmpty = FALSE, $noRecursive = FALSE)
	{
		if(!is_array($this->_putCache)){
			$this->_putCache = ($this->isApplicationJson()) ? (array)$this->getJsonRawBody() : $this->getRawBody();
		}
		return parent::getPut($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
	}
	public function getPost($name = NULL, $filters = NULL, $defaultValue = NULL, $notAllowEmpty = FALSE, $noRecursive = FALSE)
	{
		if($this->isApplicationJson()){
			if(is_null($this->_postCache)){
				$this->_postCache = (array)$this->getJsonRawBody();
			}
			return $this->getHelper($this->_postCache, $name, $filters, $defaultValue, $notAllowEmpty, $notAllowEmpty);
		} else {
			return parent::getPost($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
		}
	}
	public function getQuery($name = NULL, $filters = NULL, $defaultValue = NULL, $notAllowEmpty = FALSE, $noRecursive = FALSE)
	{
		if($this->isApplicationJson()){
			if(is_null($this->_queryCache)){
				$this->_queryCache = (array)$this->getJsonRawBody();
			}
			return $this->getHelper($this->_queryCache, $name, $filters, $defaultValue, $notAllowEmpty, $notAllowEmpty);
		} else {
			return parent::getQuery($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
		}
	}
	public function hasPost($name)
	{
		return array_key_exists($name, $this->getPost($name));
	}
}