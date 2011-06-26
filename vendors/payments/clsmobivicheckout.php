<?php
/*
 * ============================================================================
 * Mobivi Checkout library
 * Copyright 2008 (C) by Viet Phu - Mobivi
 * ============================================================================
 */
// http.php is an opensource HTTP client to access Web site pages
// get it from http://www.phpclasses.org/browse/package/3.html
include("clshttp.php");
/*
 * This class encapsulate a Checkout Request (a Shopping Cart)
 * to be sent to Mobivi.
 */
class MobiviCheckoutRequest {

	var $SerialID;
	var $ReturnURL;
	var $DoneURL;
	var $ValidUntil;
	var $InvoiceID;
	var $InvoiceFrom;
	var $InvoiceTo;
	var $InvoiceDescription;
	var $InvoiceAmount;
	var $InvoiceTax;
	var $InvoiceItems;

	function MobiviCheckoutRequest() {
		$this->InvoiceItems = array();
	}

	function add_invoice_item($name, $unitprice, $quantity, $taxable = NULL, $itemid = NULL, $description = NULL) {
		$newItem = array(
			"ItemID" => $itemid,
			"Name" => $name,
			"UnitPrice" => $unitprice,
			"Quantity" => $quantity,
			"Description" => $description,
			"Taxable" => $taxable
		);
		$this->InvoiceItems[] = $newItem;
	}

	function serialize() {
		$xw = new xmlWriter();
		$xw->openMemory();
		$xw->startDocument('1.0', 'UTF-8');
		$xw->startElement('CheckoutInfo');
		$xw->startElement('Invoice');
		$xw->writeAttribute('SerialID', $this->SerialID);
		if ($this->InvoiceID)
			$xw->writeElement('InvoiceID', $this->InvoiceID);
		if ($this->From)
			$xw->writeElement('From', $this->From);
		if ($this->To)
			$xw->writeElement('To', $this->To);
		if ($this->Description)
			$xw->writeElement('Description', $this->Description);
		$xw->startElement('Items');
		foreach ($this->InvoiceItems as $invoiceItem) {
			$xw->startElement('Item');
			if ($invoiceItem["ItemID"])
				$xw->writeElement('ItemID', $invoiceItem["ItemID"]);
			$xw->writeElement('Name', $invoiceItem["Name"]);
			$xw->writeElement('UnitPrice', $invoiceItem["UnitPrice"]);
			$xw->writeElement('Quantity', $invoiceItem["Quantity"]);
			if ($invoiceItem["Description"])
				$xw->writeElement('Description', $invoiceItem["Description"]);
			if ($invoiceItem["Taxable"])
				$xw->writeElement('Taxable', 'true');
			else
				$xw->writeElement('Taxable', 'false');
			$xw->endElement(); // Item
		}
		$xw->endElement(); // Items
		if ($this->Tax)
			$xw->writeElement('Tax', $this->Tax);
		$xw->writeElement('Amount', $this->Amount);
		$xw->endElement(); // Invoice
		if ($this->ReturnURL)
			$xw->writeElement('ReturnURL', $this->ReturnURL);
		if ($this->DoneURL)
			$xw->writeElement('DoneURL', $this->DoneURL);
		if ($this->ValidUntil)
			$xw->writeElement('ReturnURL', $this->ValidUntil);
		$xw->endElement();
		return $xw->outputMemory(true);
	}

	/*
	 * Read the Array Struct return by xml_parse_into_struct
	 * return MobiviCheckoutRequest object (with Invoice information) if OK
	 * return NULL otherwise
	 */
	function read_invoice_array_struct($arrStruct, &$offset) {
		$tag = $arrStruct[$offset];
		if ($tag["tag"] != "INVOICE" || $tag["type"] != "open")
			return NULL;
		// parse the struct
		$struct_count = count($arrStruct);
		for ($nIndex = $offset + 1; $nIndex < $struct_count; $nIndex++) {
			$tag = $arrStruct[$nIndex];
			if ($tag["type"] == "cdata") // ignore NewLine character
				continue;
			elseif ($tag["tag"] == "ITEMS") { // start Items parsing
				if ($tag["type"] != "open" || isset($item))
					return NULL;
				$items = array();
				$item = NULL;
				for (++$nIndex; $nIndex < $struct_count; $nIndex++) {
					$tag = $arrStruct[$nIndex];
					if ($tag["type"] == "cdata") // ignore NewLine character
						continue;elseif (is_null($item)) {
						if ($tag["type"] == "open") {
							if ($tag["tag"] == "ITEM") { // start parsing an Item
								$item = array();
							} else
								return NULL;
						} elseif ($tag["type"] == "close") {
							if ($tag["tag"] == "ITEMS") { // stop Items parsing
								break;
							} else
								return NULL;
						} elseif ($tag["type"] == "complete") // ignore unknow Items/CompleteTag
							continue;
						else
							return NULL;
					} elseif ($tag["type"] == "close") { // stop parsing an Item
						if ($tag["tag"] == "ITEM") { // add item to $items list
							$items[] = $item;
							$item = NULL;
						} else // never happen because of no open tag
							return $tag["tag"] . $tag["type"] . $item;
					} elseif ($tag["type"] != "complete")
						return NULL;
					elseif (!isset($tag["value"])) // ignore null Item/CompleteTag
						continue;
					elseif ($tag["tag"] == "ITEMID")
						$item["ItemID"] = $tag["value"];
					elseif ($tag["tag"] == "NAME")
						$item["Name"] = $tag["value"];
					elseif ($tag["tag"] == "DESCRIPTION")
						$item["Description"] = $tag["value"];
					elseif ($tag["tag"] == "UNITPRICE")
						$item["UnitPrice"] = $tag["value"];
					elseif ($tag["tag"] == "QUANTITY")
						$item["Quantity"] = $tag["value"];
					elseif ($tag["tag"] == "TAXABLE")
						$item["Taxable"] = $tag["value"];
					else // ignore unknown Item/CompleteTag
						continue;
				} // end for
				if (count($items) == 0 || $tag["tag"] != "ITEMS" || $tag["type"] != "close")
					return NULL;
			} elseif ($tag["type"] == "close") { // stop parsing invoice
				if ($tag["tag"]) {
					$offset = $nIndex;
					break;
				} else
					return NULL;
			} elseif ($tag["type"] != "complete") { // stop at unknown Invoice/NonCompleteTag
				return NULL;
			} elseif (!isset($tag["value"])) // ignore empty Invoice/CompleteTag
				continue;
			elseif ($tag["tag"] == "AMOUNT")
				$amount = $tag["value"];
			elseif ($tag["tag"] == "INVOICEID")
				$invoice_id = $tag["value"];
			elseif ($tag["tag"] == "FROM")
				$from = $tag["value"];
			elseif ($tag["tag"] == "TO")
				$to = $tag["value"];
			elseif ($tag["tag"] == "DESCRIPTION")
				$description = $tag["value"];
			elseif ($tag["tag"] == "TAX")
				$tax = $tag["value"];
			else // ignore unknown Invoice/CompleteTag
				continue;
		}
		// return object
		if (!isset($amount) || !isset($items))
			return NULL;
		$mbvRequest = new MobiviCheckoutRequest();
		$mbvRequest->InvoiceAmount = $amount;
		$mbvRequest->InvoiceItems = $items;
		if (isset($from))
			$mbvRequest->InvoiceFrom = $from;
		if (isset($to))
			$mbvRequest->InvoiceTo = $to;
		if (isset($invoice_id))
			$mbvRequest->InvoiceID = $invoice_id;
		if (isset($description))
			$mbvRequest->InvoiceDescription = $description;
		if (isset($tax))
			$mbvRequest->InvoiceTax = $tax;
		return $mbvRequest;
	}
}

/*
 * This class encapsulate a New Order Notification message
 * sent from Mobivi.
 */
class MobiviNewOrder {
	var $CheckoutRequest;
	var $TransactionID;
	var $State;
	var $SerialID;

	/*
	 * Read the Array Struct return by xml_parse_into_struct
	 * return MobiviNewOrder object if OK
	 * return NULL otherwise
	 */
	function read_array_struct($arrStruct) {
		$tag = $arrStruct[0];
		if ($tag["tag"] != "NEWORDERNOTIFICATION")
			return NULL;
		if (isset($tag["attributes"]) && isset($tag["attributes"]["SERIALID"]))
			$serial_id = $tag["attributes"]["SERIALID"];
		else
			return NULL;
		// parse the struct
		$struct_count = count($arrStruct); // start parsing NewOrder
		for ($nIndex = 1; $nIndex < $struct_count; $nIndex++) {
			$tag = $arrStruct[$nIndex];
			if ($tag["type"] == "open") {
				if ($tag["tag"] == "INVOICE") {
					$invoice = MobiviCheckoutRequest::read_invoice_array_struct($arrStruct, $nIndex);
					if ($invoice == NULL)
						return NULL;
				} else
					return NULL; // stop at unknown OpenTag
			} elseif ($tag["type"] == "close") {
				if ($tag["tag"] == "NEWORDERNOTIFICATION") // stop parsing NewOrder
					break;
				else
					return NULL;
			} elseif ($tag["type"] == "cdata") // ignore NewLine character
				continue;
			elseif ($tag["type"] != "complete") // stop at unknown Tag
				return NULL;
			elseif (!isset($tag["value"])) // ignore EmptyCompleteTag
				continue;
			elseif ($tag["tag"] == "STATE")
				$xtran_state = $tag["value"];
			else if ($tag["tag"] == "TRANSACTIONID")
				$xtran_id = $tag["value"];
			else // ignore unknown CompleteTag
				continue;
		}
		// return object
		if (!isset($xtran_id) || !isset($xtran_state) || !isset($invoice))
			return NULL;
		$newOrder = new MobiviNewOrder();
		$newOrder->SerialID = $serial_id;
		$newOrder->TransactionID = $xtran_id;
		$newOrder->State = $xtran_state;
		$newOrder->CheckoutRequest = $invoice;
		return $newOrder;
	}
}

/*
 * This class encapsulate a Transaction State Change Notification message
 * sent from Mobivi.
 */
class MobiviTransactionStateChange {
	var $TransactionID;
	var $State;
	var $SerialID;

	/*
	 * Read the Array Struct return by xml_parse_into_struct
	 * return MobiviTransactionStateChange object if OK
	 * return NULL otherwise
	 */
	function read_array_struct($arrStruct) {
		$tag = $arrStruct[0];
		if ($tag["tag"] != "TRANSACTIONSTATECHANGENOTIFICATION")
			return NULL;
		if (isset($tag["attributes"]) && isset($tag["attributes"]["SERIALID"]))
			$serial_id = $tag["attributes"]["SERIALID"];
		else
			return NULL;
		// parse the struct
		$struct_count = count($arrStruct);
		for ($nIndex = 1; $nIndex < $struct_count; $nIndex++) { // start parsing
			$tag = $arrStruct[$nIndex];
			if ($tag[type] == "close") {
				if ($tag["tag"] == "TRANSACTIONSTATECHANGENOTIFICATION") // stop parsing
					break;
				else // stop at unknown CloseTag
					return NULL;
			} elseif ($tag["type"] == "cdata") // igore NewLine character in the RootTag
				continue;elseif ($tag["type"] != "complete") { // stop at NonCompleteTag
				return NULL;
			} elseif (!isset($tag["value"])) // ignore empty CompleteTag
				continue;elseif ($tag["tag"] == "STATE") {
				$xtran_state = $tag["value"];
			} elseif ($tag["tag"] == "TRANSACTIONID")
				$xtran_id = $tag["value"];
		}
		if (!isset($xtran_state) || !isset($xtran_id))
			return NULL;
		// return object
		$stateChange = new MobiviTransactionStateChange();
		$stateChange->TransactionID = $xtran_id;
		$stateChange->State = $xtran_state;
		$stateChange->SerialID = $serial_id;
		return $stateChange;
	}
}

/*
 * This class implement Mobivi Checkout API
 * - To use this class you must call read_verify_cert() first
 * - Call parse() to parse as many MobiviNotificationMessage as you want
 * - Call dispose() when you are done with the object
 */
class MobiviNotification {
	var $pubkey;

	/*
	 * open the X509 certificate file
	 * read the certificate
	 * return
	 *  - TRUE if success
	 *  - FALSE otherwise
	 */
	function read_verify_cert($path) {
		$this->pubkey = NULL;
		$fp = fopen($path, "r");
		if (!$fp)
			return FALSE;
		$filedata = fread($fp, 8192);
		fclose($fp);
		$this->pubkey = openssl_get_publickey($filedata);
		if ($this->pubkey == FALSE)
			$this->pubkey = NULL;
		return $this->pubkey != NULL;
	}

	/*
	 * release allocated resources
	 */
	function dispose() {
		if ($this->pubkey != NULL)
			openssl_free_key($this->pubkey);
	}

	/*
	 * This function can be called as static method
	 * return MobiviOrder, or MobiviTransactionStateChange
	 *     if the message is valid
	 * return NULL
	 *     otherwise
	 */
	function parse($msg, $enc) {
		if (!isset($msg) || !isset($enc))
			return NULL; // "message or encsig missing";

		// decode the message
		$message = base64_decode($msg);
		$encsig = base64_decode($enc);
		if ($message == FALSE || $encsig == FALSE)
			return NULL; // "failed to decode";

		// verify the message
		if (openssl_verify($message, $encsig, $this->pubkey) != 1)
			return NULL; // "failed to verify";

		// parse the message
		$parser = xml_parser_create();
		if (!$parser)
			return NULL; // "failed to create xml parser";

		$ret = NULL;
		if (xml_parse_into_struct($parser, $message, $tags) == 1) {
			$root_tag = $tags[0];
			if ($root_tag != NULL && $root_tag["type"] == "open") {
				if ($root_tag["tag"] == "NEWORDERNOTIFICATION") {
					$ret = MobiviNewOrder::read_array_struct($tags);
				} else if ($root_tag["tag"] == "TRANSACTIONSTATECHANGENOTIFICATION") {
					$ret = MobiviTransactionStateChange::read_array_struct($tags);
				}
			}
		}
		xml_parser_free($parser);
		return $ret;
	}
}

/*
 * This class implement Mobivi Checkout API
 * - To use this class you must call read_private_key() first
 * - Call send() to send as many MobiviCheckoutRequest as you want
 * - Call dispose() when you are done with the object
 */
class MobiviCheckout {

	var $privkey; // Merchant private key
	var $CheckoutURL;

	function MobiviCheckout($checkouturl) {
		$this->CheckoutURL = $checkouturl;
	}

	/*
	 * open the private key file
	 * read the private key
	 * return
	 *  - TRUE if success
	 *  - FALSE otherwise
	 */
	function read_private_key($path, $passphase = NULL) {
		$this->privkey = NULL;
		$fp = fopen($path, "r");
		if (!$fp)
			return FALSE;
		$filedata = fread($fp, 8192);
		fclose($fp);
		$this->privkey = openssl_get_privatekey($filedata, $passphase);
		if ($this->privkey == FALSE)
			$this->privkey = NULL;
		return $this->privkey != NULL;
	}

	/*
	 * release allocated resources
	 */
	function dispose() {
		if ($this->privkey)
			openssl_free_key($this->privkey);
	}

	/*
	 * send a checkout request to Mobivi
	 * return TRUE:
	 *     $redirect_url contains the URL to redirect customer
	 * retrun FALSE:
	 *     $redirect_url contains the error message
	 */
	function send($account, $request, &$redirect_url) {
		$request_xml = $request->serialize();

		$signed = openssl_sign($request_xml, $request_sig, $this->privkey);
		if (!$signed) {
			$redirect_url = "cannot sign the message: " . $request_xml;
			return FALSE;
		}

		$post_data = Array();
		$post_data["merchant_account"] = $account;
		$post_data["checkout"] = base64_encode($request_xml);
		$post_data["encsig"] = base64_encode($request_sig);

		$http = new http_class;
		$http->timeout = 0;
		$http->data_timeout = 0;
		$http->debug = 0;
		$http->html_debug = 1;
		$error = $http->GetRequestArguments($this->CheckoutURL, $arguments);
		$arguments["RequestMethod"] = "POST";
		$arguments["PostValues"] = $post_data;
		$arguments["Referer"] = $_SERVER['PHP_SELF'];
		$error = $http->Open($arguments);
		$body = NULL;
		if ($error == "") {
			$error = $http->SendRequest($arguments);
			if ($error == "") {
				$error = $http->ReadReplyBody($body, 8192);
			}
			$http->Close();
		}

		if (strlen($error)) {
			$redirect_url = $error;
			return FALSE;
		} else {
			$parser = xml_parser_create();
			if (!$parser) {
				$redirect_url = "cannot create parser";
				return FALSE;
			}
			$ret = FALSE;
			$redirect_url = $body;
			if (xml_parse_into_struct($parser, $body, $tags) == 1) {
				foreach ($tags as $tag) {
					if ($tag["tag"] == "REDIRECTURL" && $tag["type"] == "complete") {
						$redirect_url = $tag["value"];
						$ret = TRUE;
						break;
					}
				}
			} else {
				$redirect_url = $body;
			}
			xml_parser_free($parser);
			return $ret;
		}
	}
}
?>
