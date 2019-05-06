<?php
//phpcs:disable

namespace Core;
use Phalcon\Http\Request as HttpRequest;

class Request extends HttpRequest
{
	/**
	 * @var mixed[]
	 */
	protected $postCache;

	/**
	 * @var mixed[]
	 */
	protected $queryCache;

	/**
	 * @var bool
	 */
	private $isJson;

	/**
	 * Determine (and store in memory) whether or not the current request content-type is application/json or not
	 */
	private function isApplicationJson(): bool
	{
		if (is_null($this->isJson)) {
			$contentType = $this->getDI()->getShared('request')->getHeader('CONTENT_TYPE');

			$this->isJson = ($contentType && explode(';', $contentType)[0] === 'application/json');
		}

		return $this->isJson;
	}

	/**
	 * @param mixed $defaultValue
	 * @param mixed[] $filters
	 *
	 * @return mixed
	 */
	public function getPut(
		$name = null,
		$filters = null,
		$defaultValue = null,
		$notAllowEmpty = false,
		$noRecursive = false
	) {
		if (!is_array($this->_putCache)) {
			$this->_putCache = ($this->isApplicationJson()) ? (array) $this->getJsonRawBody() : $this->getRawBody();
		}

		return parent::getPut($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
	}

	/**
	 * @param mixed $defaultValue
	 * @param mixed[] $filters
	 *
	 * @return mixed
	 */
	public function getPost(
		$name = null,
		$filters = null,
		$defaultValue = null,
		$notAllowEmpty = false,
		$noRecursive = false
	) {
		if ($this->isApplicationJson()) {
			if (is_null($this->postCache)) {
				$this->postCache = (array) $this->getJsonRawBody();
			}

			return $this->getHelper($this->postCache, $name, $filters, $defaultValue, $notAllowEmpty, $notAllowEmpty);
		} else {
			return parent::getPost($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
		}
	}

	/**
	 * @param mixed $defaultValue
	 * @param mixed[] $filters
	 *
	 * @return mixed
	 */
	public function getQuery(
		$name= null,
		$filters = null,
		$defaultValue = null,
		$notAllowEmpty = false,
		$noRecursive = false
	) {
		if ($this->isApplicationJson()) {
			if (is_null($this->queryCache)) {
				$this->queryCache = (array) $this->getJsonRawBody();
			}

			return $this->getHelper($this->queryCache, $name, $filters, $defaultValue, $notAllowEmpty, $notAllowEmpty);
		} else {
			return parent::getQuery($name, $filters, $defaultValue, $notAllowEmpty, $noRecursive);
		}
	}

	public function hasPost($name): bool
	{
		return array_key_exists($name, $this->getPost($name));
	}
}
