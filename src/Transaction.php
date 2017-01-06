<?php

namespace Evidos\SignHostAPIClientRest;

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
    public $Context; // Context must be a array or object.

    function __construct(
        $fileName,
        $seal = 1,
        $reference = null,
        $postbackUrl = null,
        $sendEmailNotifications = true,
        $signRequestMode = 2,
        $daysToExpire = 30,
        $context = null)
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
        $this->Context = $context;
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
        $daysToRemind = 15,
        $context = null)
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
        $signer->Context = $context;
        $this->Signers[] = $signer;
    }

    public function AddReceiver(
        $name,
        $email,
        $message,
        $language = null,
        $reference = null,
        $context = null)
    {
        $receiver = new Receiver();
        $receiver->Name = $name;
        $receiver->Email = $email;
        $receiver->Language = $language;
        $receiver->Message = $message;
        $receiver->Reference = $reference;
        $receiver->Context = $context;
        $this->Receivers[] = $receiver;
    }
}
