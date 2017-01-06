<?php

namespace Evidos\SignHostAPIClientRest;

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
    public $Context; // Context must be a array or object.
}