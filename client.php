<?php
	error_reporting(E_ALL ^ E_NOTICE);
	require_once("signhost.php");
	
	if(isset ($_POST["createNewTransaction"])) {
		$ondertekenen = new SignHost("https://api.signhost.com", $_POST["appName"], $_POST["appKey"], $_POST["apiKey"]);		
	
		$newTransaction = new Transaction($_FILES["file"]["name"], (bool)$_POST["seal"], $_POST["reference"], $_POST["postbackUrl"], $_POST["sendEmailNotifications"], $_POST["signRequestMode"], $_POST["daysToExpire"]);
		
		foreach($_POST["signers"] as $signer) {
			$newTransaction->AddSigner($signer["email"], $signer["mobile"], (bool)$signer["requireScribble"], (bool)$signer["requireEmailVerification"], (bool)$signer["requireSmsVerification"], (bool)$signer["sendSignRequest"], (bool)$signer["sendSignConfirmation"], $signer["signRequestMessage"], $signer["language"], $signer["scribbleName"], (bool)$signer["scribbleNameFixed"], $signer["reference"], $signer["returnUrl"], $signer["daysToRemind"]);
		}
		
		$transaction = $ondertekenen->CreateTransaction($newTransaction);
		$uploadResponse = $ondertekenen->UploadFileContent($transaction->File->Id, $_FILES["file"]["tmp_name"]);
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Ondertekenen.nl HTML Gateway</title>
<style type="text/css">
	td {
		border: 1px solid black;
		word-wrap: break-word;
	}
	th {
		text-align: left;
		border: 1px solid black;
	}
 </style>
</head>

<body>

	<h1>Ondertekenen.nl API</h1>
	
	<form method="post" enctype="multipart/form-data">
		
		<fieldset>
			<legend>Transaction</legend>
			<input type="text" name="appName" value="" /> APP Name<br />
			<input type="text" name="appKey" value="" /> APP Key<br />
			<input type="text" name="apiKey" value="" /> API Key<br />
			<input type="text" name="reference" value="" /> Reference<br />
			<input type="text" name="postbackUrl" value="" /> Postback URL<br />
			<input type="text" name="signRequestMode" value="2" /> Sign Request Mode<br />
			<input type="text" name="daysToExpire" value="30" /> Days To Expire<br />
			<input type="checkbox" name="sendEmailNotifications" value="1" checked />Send Email Notifications<br /><br />
			<div class="signerFieldsetContainer">
				
			</div>
			<p><a href="" class="addSignerLink">Add signer</a></p>
			<p>
				<input type="file" name="file"> <input type="checkbox" name="seal" value="1" checked />Seal
			</p>
			<input type="submit" name="createNewTransaction" value="Create" />
		</fieldset>
		
	</form>
	
	<fieldset id="signerFieldset" style="display: none;">
		<legend>Signer <span class="signerNumber">1</span></legend>
		<input type="text" name="signers[0][email]" value="" /> Email Receiver<br />
		<input type="text" name="signers[0][mobile]" value="" /> Mobile Receiver<br />
		<input type="text" name="signers[0][reference]" value="" /> Reference<br />
		<input type="text" name="signers[0][returnUrl]" value="http://ondertekenen.nl" /> Return URL<br />
		<input type="text" name="signers[0][scribbleName]" value="" /> Scribble Name<br />
		<input type="text" name="signers[0][daysToRemind]" value="15" /> Days To Remind<br />
		<p>
			<input type="checkbox" name="signers[0][requireScribble]" value="1" />Require Scribble<br />
			<input type="checkbox" name="signers[0][requireEmailVerification]" value="1" checked />Require Email Verification<br />
			<input type="checkbox" name="signers[0][requireSmsVerification]" value="1" />Require Sms Verification<br />
			<input type="checkbox" name="signers[0][sendSignRequest]" value="1" checked />Send Sign Request<br />
			<input type="checkbox" name="signers[0][sendSignConfirmation]" value="1" checked />Send Sign Confirmation<br />
			<input type="checkbox" name="signers[0][scribbleNameFixed]" value="1" />Scribble Name Fixed<br />
			Language: <select name="signers[0][language]">
				<option value="en-US">English</option>
				<option value="nl-NL">Nederlands</option>
				<option value="de-DE">Deutsch</option>
				<option value="fr-FR">Français</option>
				<option value="it-IT">Italiano</option>
				<option value="es-ES">Español</option>
			</select>
		</p>
		Sign Request Message:<br />
		<textarea name="signers[0][signRequestMessage]" rows="4" cols="50">This is a test sign request.</textarea>
	</fieldset>
	
	<?php 
		if(isset($newTransaction)) {
			var_dump($newTransaction);
		}
	?>
	
	<?php 
		if(isset($transaction)) {
			var_dump($transaction);
		}
	?>
	
	<?php 
		if(isset($uploadResponse)) {
			var_dump($uploadResponse);
		}
	?>
	
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script>
		
		var signerCounter = 0;
	
		$(function() {
			$('.addSignerLink').on('click', function(e) {
				e.preventDefault();
				
				signerCounter++;
				
				var fieldset = $('#signerFieldset').clone();
				
				$('.signerNumber', fieldset).html(signerCounter);
				
				$('input, textarea', fieldset).attr('name', function(index, attr) {
					return attr.replace(/\d+/, signerCounter-1);
				});
				
				fieldset.insertAfter('.signerFieldsetContainer:last').removeAttr('id').show();
				
				
				
			});
		});
	</script>
</body>

</html>