<?php

/**
 * Class CRM_SMS_Provider_Dummysms
 */
class cpsms_handler extends CRM_SMS_Provider {

    /**
     * provider details
     * @var	string
     */
    protected $_providerInfo = array();
    protected $_id = 0;
    

    const MESSAGE_DIRECTION_OUTBOUND = 1;
    const MESSAGE_DIRECTION_INBOUND = 2;

    /**
     * We only need one instance of this object. So we use the singleton
     * pattern and cache the instance in this variable
     *
     * @var object
     * @static
     */
    static private $_singleton = array();

    /**
     * Constructor
     * @return void
     */
    function __construct($provider, $skipAuth = TRUE) {
        // Instantiate the dummysms client
        $this->provider = $provider;
    }

    /**
     * singleton function used to manage this object
     *
     * @return object
     * @static
     *
     */
    static function &singleton($providerParams = array(), $force = FALSE) {
        if (isset($providerParams['provider'])) {
            $providers = CRM_SMS_BAO_Provider::getProviders(NULL, array('name' => $providerParams['provider']));
            $providerID = empty($providers) ? 0 : current($providers)['id'];
        } else {
            $providerID = CRM_Utils_Array::value('provider_id', $providerParams);
        }
        $skipAuth = $providerID ? FALSE : TRUE;
        $cacheKey = (int) $providerID;

        if (!isset(self::$_singleton[$cacheKey]) || $force) {
            $provider = array();
            if ($providerID) {
                $provider = CRM_SMS_BAO_Provider::getProviderInfo($providerID);
            }
            self::$_singleton[$cacheKey] = new cpsms_handler($provider, $skipAuth);
        }
        return self::$_singleton[$cacheKey];
    }

    /**
     * Send an SMS Message to a log file
     *
     * @param array the message with a to/from/text
     *
     * @return mixed SID on success or PEAR_Error object
     * @access public
     */
    function send($recipients, $header, $message, $jobID = NULL, $userID = NULL) {
        $id = date('YmdHis');
        try {
            $country = '45';
            $recipient = str_replace(' ', "", $recipients);
            if (str_starts_with($recipient, '+')) {
                $recipient = str_replace('+', "", $recipient, 1);
                $country = "";
            } else if (strlen($recipient) === 8) {
                $recipient = '45' . $recipient;
            }
            $this->sendSms($recipient, $message, $this->provider['api_params']['from'], $id);

            $this->createActivity($id, $message, $header, $jobID, $userID);
        } catch (Exception $e) {
            return PEAR::raiseError($e->getMessage(), $e->getCode(), PEAR_ERROR_RETURN);
        }
        return $id;
    }

    function sendSms($to, $message, $from, $reference) {
        $curl = curl_init();
        $post = [
            'to' => $to,
            'message' => $message,
            'from' => $from,
            'reference' => $reference,
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.cpsms.dk/v2/send",
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic " . $this->provider['password'])
            ),
        );

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($httpCode != 200) {

        }
    }

    function inbound($from_number, $content, $id = NULL) {
        if (!isset($id)) {
            $id = date('YmdHis');
        }
        return parent::processInbound($from_number, $content, NULL, $id);
    }

}
