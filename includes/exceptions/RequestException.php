<?php

class RequestException extends \Exception {
	
	private $failedRequestMessage;

	/**
	 * @param string message	Response error message from the server.
	 *
	 * Set the failure message for this exception.
	 */
	public function setFailedMessage($message) {
		$this->failedRequestMessage = $message;
		$this->message = sprintf('An unexpected problem occurred with the API request. Some causes include: invalid JSON or XML returned. Here is the actual response from the server: ---- %s', $message);
	}

	/**
	 * @return string	Response error message from the server.
	 *
	 * Get the failure message for this exception.
	 */
	public function getFailedMessage() {
		return $this->failedRequestMessage;
	}
}
