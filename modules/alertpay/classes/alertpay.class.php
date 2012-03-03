<?php

/**
 * Alertpay Class
 */

class Alertpay extends PaymentGateway
{
	public $available_currencies = array(
		'AUD',  // Australian Dollar 
		'BGN',  // Bulgarian Lev
		'CAD',  // Canadian Dollar 
		'CHF',  // Swiss Franc
		'CZK',  // Czech Koruna
		'DKK',  // Danish Krone
		'EEK',  // Estonia Kroon
		'EUR',  // Euro
		'GBP',  // British Pounds
		'HKD',  // Hong Kong Dollar 
		'HUF',  // Hungarian Forint
		'INR',  // Indian Rupee
		'LTL',  // Lithuanian Litas
		'MYR',  // Malaysian Ringgit
		'MKD',  // Macedonian Denar
		'NOK',  // Norwegian Krone
		'NZD',  // New Zealand Dollar
		'PLN',  // Polish Zloty
		'RON',  // Romanian New Leu
		'SEK',  // Swedish Krona
		'SGD',  // Singapore Dollar
		'USD',  // U.S. Dollars
		'ZAR',  // South African Rand
	);

    /**
	 * Initialize the Alertpay gateway
	 *
	 * @param none
	 * @return void
	 */
	public function __construct()
	{
        parent::__construct();

        // Some default values of the class
		$this->gatewayUrl = 'https://www.alertpay.com/PayProcess.aspx';
		$this->ipnLogFile = 'alertpay.ipn_results.log';
	}

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    public function enableTestMode()
    {
        $this->testMode = TRUE;
        $this->gatewayUrl = 'http://sandbox.alertpay.com/sandbox/payprocess.aspx';
        $this->addField('ap_test', 1);
    }

    /**
	 * Validate the IPN notification
	 *
	 * @param none
	 * @return boolean
	 */
	public function validateIpn()
	{
		//The value is the url address of IPN V2 handler and the identifier of the token string 
		if (!$this->testMode)
			define("IPN_V2_HANDLER", "https://www.alertpay.com/ipn2.ashx");
		else
			define("IPN_V2_HANDLER", "https://sandbox.alertpay.com/sandbox/IPN2.ashx"); 
		define("TOKEN_IDENTIFIER", "token=");

		// get the token from Alertpay
		$token = urlencode($_POST['token']);

		//preappend the identifier string "token=" 
		$token = TOKEN_IDENTIFIER.$token;

		/**
		 * 
		 * Sends the URL encoded TOKEN string to the Alertpay's IPN handler
		 * using cURL and retrieves the response.
		 * 
		 * variable $response holds the response string from the Alertpay's IPN V2.
		 */
		
		$response = '';
		
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_URL, IPN_V2_HANDLER);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
		$response = curl_exec($ch);
	
		curl_close($ch);
		
		if(strlen($response) > 0)
		{
			if(urldecode($response) == "INVALID TOKEN")
			{
				// Invalid IPN transaction.  Check the log for details.
				$this->lastError = "The token is not valid";
				$this->logResults(false);
				return false;
			}
			else
			{
				//urldecode the received response from Alertpay's IPN V2
				$response = urldecode($response);
				//split the response string by the delimeter "&"
				$aps = explode("&", $response);

				foreach ($aps as $ap)
				{
					//put the IPN information into an associative array $info
					$ele = explode("=", $ap);
					$this->ipnData[$ele[0]] = $ele[1];
				}

				// Valid IPN transaction.
		 		$this->logResults(true);
		 		return true;

				
				//setting information about the transaction from the IPN information array
				/*$receivedMerchantEmailAddress = $info['ap_merchant'];
				$transactionStatus = $info['ap_status'];
				$testModeStatus = $info['ap_test'];
				$purchaseType = $info['ap_purchasetype'];
				$totalAmountReceived = $info['ap_totalamount'];
				$feeAmount = $info['ap_feeamount'];
				$netAmount = $info['ap_netamount'];
				$transactionReferenceNumber = $info['ap_referencenumber'];
				$currency = $info['ap_currency'];
				$transactionDate = $info['ap_transactiondate'];
				$transactionType = $info['ap_transactiontype'];
				
				//setting the customer's information from the IPN information array
				$customerFirstName = $info['ap_custfirstname'];
				$customerLastName = $info['ap_custlastname'];
				$customerAddress = $info['ap_custaddress'];
				$customerCity = $info['ap_custcity'];
				$customerState = $info['ap_custstate'];
				$customerCountry = $info['ap_custcountry'];
				$customerZipCode = $info['ap_custzip'];
				$customerEmailAddress = $info['ap_custemailaddress'];
				
				//setting information about the purchased item from the IPN information array
				$myItemName = $info['ap_itemname'];
				$myItemCode = $info['ap_itemcode'];
				$myItemDescription = $info['ap_description'];
				$myItemQuantity = $info['ap_quantity'];
				$myItemAmount = $info['ap_amount'];
				
				//setting extra information about the purchased item from the IPN information array
				$additionalCharges = $info['ap_additionalcharges'];
				$shippingCharges = $info['ap_shippingcharges'];
				$taxAmount = $info['ap_taxamount'];
				$discountAmount = $info['ap_discountamount'];
				
				//setting your customs fields received from the IPN information array
				$myCustomField_1 = $info['apc_1'];
				$myCustomField_2 = $info['apc_2'];
				$myCustomField_3 = $info['apc_3'];
				$myCustomField_4 = $info['apc_4'];
				$myCustomField_5 = $info['apc_5'];
				$myCustomField_6 = $info['apc_6'];*/
				
			}
		}
		else
		{
			// Invalid IPN transaction.  Check the log for details.
			$this->lastError = "Something is wrong, no response is received from Alertpay";
			$this->logResults(false);
			return false;
		}
	}
}
