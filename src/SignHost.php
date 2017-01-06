<?php

namespace Evidos\SignHostAPIClientRest;

class SignHost
{
	public $ApiUrl;
	public $AppName;
	public $AppKey;
	public $ApiKey;
	public $SharedSecret;

	function __construct($apiUrl, $appName, $appKey, $apiKey, $sharedSecret = null)
	{
		$this->ApiUrl = $apiUrl;
		$this->AppName = $appName;
		$this->AppKey = $appKey;
		$this->ApiKey = $apiKey;
		$this->SharedSecret = $sharedSecret;
	}

	public function CreateTransaction($transaction) {
		$ch = curl_init($this->ApiUrl. "/api/transaction");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($transaction));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Application: APPKey ". $this->AppName. " ". $this->AppKey, "Authorization: APIKey ". $this->ApiKey));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
		$responseJson = curl_exec($ch);
		return json_decode($responseJson);
	}

	public function UploadFileContent($fileId, $filePath) {
		$fh = fopen($filePath, 'r');
		$ch = curl_init($this->ApiUrl. "/api/file/". $fileId);
		curl_setopt($ch, CURLOPT_PUT, 1);
		curl_setopt($ch, CURLOPT_INFILE , $fh);
		curl_setopt($ch, CURLOPT_INFILESIZE , filesize($filePath));
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/pdf", "Application: APPKey ". $this->AppName. " ". $this->AppKey, "Authorization: APIKey ". $this->ApiKey));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);

		$response = curl_exec($ch);

		fclose($fh);
		return $response;
	}

	public function ValidateChecksum($masterTransactionId, $fileId, $status, $checksum) {
		return sha1($masterTransactionId. "|". $fileId. "|". $status. "|". $this->SharedSecret) == $checksum;
	}
}
