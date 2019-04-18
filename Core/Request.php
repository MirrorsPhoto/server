<?php
namespace Core;
use Phalcon\Http\Request as HttpRequest;

class Request extends HttpRequest
{

	protected $postCache;

	protected $queryCache;

	/**
	 * @var bool
	 */
	private $isJson;

	/**
	 * Determine (and store in memory) whether or not the current request content-type is application/json or not
	 * @return boolean
	 */
	private function isApplicationJson()
	{
		if (is_null($this->isJson)) {
			$contentType = $this->getDI()->getShared('request')->getHeader('CONTENT_TYPE');

			$this->isJson = ($contentType && explode(';', $contentType)[0] === 'application/json');
		}

		return $this->isJson;
	}

	/**
	 * @param null $name
	 * @param null $filters
	 * @param null $defaultValue
	 * @param bool $notAllowEmpty
	 * @param bool $noRecursive
	 */
	public function getPut($name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false)
	{
		if (!is_array($this->_putCache)) {
			$this->_putCache = ($this->isApplicationJson()) ? (array)$this->getJsonRawBody() : $this->getRawBody();
		}

		return parent::getPut($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
	}

	/**
	 * @param null $name
	 * @param null $filters
	 * @param null $defaultValue
	 * @param bool $notAllowEmpty
	 * @param bool $noRecursive
	 */
	public function getPost($name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false)
	{
		if ($this->isApplicationJson()) {
			if (is_null($this->postCache)) {
				$this->postCache = (array)$this->getJsonRawBody();
			}

			return $this->getHelper($this->postCache, $name, $filters, $defaultValue, $notAllowEmpty, $notAllowEmpty);
		} else {
			return parent::getPost($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
		}
	}

	/**
	 * @param null $name
	 * @param null $filters
	 * @param null $defaultValue
	 * @param bool $notAllowEmpty
	 * @param bool $noRecursive
	 */
	public function getQuery($name = null, $filters = null, $defaultValue = null, $notAllowEmpty = false, $noRecursive = false)
	{
		if ($this->isApplicationJson()) {
			if (is_null($this->queryCache)) {
				$this->queryCache = (array)$this->getJsonRawBody();
			}

			return $this->getHelper($this->queryCache, $name, $filters, $defaultValue, $notAllowEmpty, $notAllowEmpty);
		} else {
			return parent::getQuery($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
		}
	}

	/**
	 * @param $name
	 * @return bool|void
	 */
	public function hasPost($name)
	{
		return array_key_exists($name, $this->getPost($name));
	}
}
