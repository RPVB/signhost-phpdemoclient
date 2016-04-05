<?php

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

class Transaction
{
	public $File;
	public $Seal;
	public $Signers;
	public $Reference;
	public $PostbackUrl;
	public $SendEmailNotifications;
	public $SignRequestMode;
	public $DaysToExpire;
	
	function __construct(
			$fileName,
			$seal = 1,
			$reference = null,
			$postbackUrl = null,
			$sendEmailNotifications = true,
			$signRequestMode = 2,
			$daysToExpire = 30)
	{
		$this->File = new File();
		$this->File->Name = $fileName;
		$this->Seal = $seal;
		$this->Signers = array();
		$this->Reference = $reference;
		$this->PostbackUrl = $postbackUrl;
		$this->SendEmailNotifications = $sendEmailNotifications;
		$this->SignRequestMode = $signRequestMode;
		$this->DaysToExpire = $daysToExpire;
	}
	
	public function AddSigner(
			$email,
			$mobile = null,
			$iban = null,
			$requireScribble = true,
			$requireScribbleName = false, // If a scribble is not required, do we at least need the signers name?
			$requireEmailVerification = true,
			$requireSmsVerification = true,
			$requireIdealVerification = true,
			$sendSignRequest = true,
			$sendSignConfirmation = true,
			$signRequestMessage = null,
			$language = null,
			$scribbleName = null,
			$scribbleNameFixed = true,
			$reference = null,
			$returnUrl = null,
			$daysToRemind = 15)
	{
		$signer = new  Signer();
		$signer->Email = $email;
		$signer->Mobile = $mobile;
		$signer->Iban = $iban;
		$signer->RequireScribble = $requireScribble;
		$signer->RequireScribbleName = $requireScribbleName;
		$signer->RequireEmailVerification = $requireEmailVerification;
		$signer->RequireSmsVerification = $requireSmsVerification;
		$signer->RequireIdealVerification = $requireIdealVerification;
		$signer->SendSignRequest = $sendSignRequest;
		$signer->SendSignConfirmation = $sendSignConfirmation;
		$signer->SignRequestMessage = $signRequestMessage;
		$signer->Language = $language;
		$signer->ScribbleName = $scribbleName;
		$signer->ScribbleNameFixed = $scribbleNameFixed;
		$signer->Reference = $reference;
		$signer->ReturnUrl = $returnUrl;
		$signer->DaysToRemind = $daysToRemind;
		$this->Signers[] = $signer;
	}
	
	public function AddReceiver(
			$name,
			$email,
			$message,
			$language = null,
			$reference = null)
	{
		$receiver = new Receiver();
		$receiver->Name = $name;
		$receiver->Email = $email;
		$receiver->Language = $language;
		$receiver->Message = $message;
		$receiver->Reference = $reference;
		$this->Receivers[] = $receiver;
	}
}

class File
{
	public $Name;
}

class Signer
{
	public $Email;
	public $Mobile;
	public $Iban;
	public $RequireScribble;
	public $RequireScribbleName;
	public $RequireEmailVerification;
	public $RequireSmsVerification;
	public $RequireIdealVerification;
	public $SendSignRequest;
	public $SendSignConfirmation;
	public $SignRequestMessage;
	public $Language;
	public $ScribbleName;
	public $ScribbleNameFixed;
	public $Reference;
	public $ReturnUrl;
	public $DaysToRemind;
}

class Receiver
{
	public $Name;
	public $Email;
	public $Message;
	public $Language;
	public $Reference;
}

class AccessToken
{
	public $AccessToken;
	public $ExpiresIn;
	public $Username;
	
	function __construct($accessToken, $expiresIn, $username) {
		$this->AccessToken = $accessToken;
		$this->ExpiresIn = $expiresIn;
		$this->Username = $username;
	}
}

?>
