<?php
/**
 * (c) Mantas Vaitkūnas <manvait@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MantasVaitkunas\VertexSmsPhpImplementation;

use Exception;

class Sender
{
    /** Curl timeout. */
    const CURLOPT_TIMEOUT = 3;

    const SERVICE_URL = 'https://api.vertexsms.com/sms';

    /** @var string Vertex API token. */
    private $apiToken;

    /** @var string Destination address (recipient).  */
    private $to;

    /** @var string	Source address (originator) */
    private $from;

    /**
     * @var string	Message body. If optional parameter udh is not used - length is not limited:
     * messages will be split automatically. Otherwise, max. text length depends on characters
     * used - see https://vertexsms.com/docs/sms.html#message-text-length for more info.
     */
    private $message;

    /**
     * @var string Where to transfer delivery report of each submitted message. Delivery report will contain the
     * same message ID, which is returned after submitting the message for sending.
     */
    private $dlrUrl;

    /**
     * @var string Change priority of sms.
     * Possible values:
     * high - use this for registration forms, password reminders and other important sms.
     * normal - default priority for sms'es
     * low - use this priority for mass messages
     */
    private $priority;

    /**
     * @var string Schedule message sending in specified time. Date and time must be set in ISO 8601 ex.:
     * 2015-01-01T13:00:00+02:00
     */
    private $scheduled;

    /**
     * @var string User Data Header of the message. Used to send long/concatenated messages.Each hex pair should be
     * prefixed with % - see examples.For more info on UDH - see hereand here for SMS concatenation.
     *
     * Attention:
     * If you specify udh - automatic message splitting will be disabled and you will also have to include field coding.
     */
    private $udh;

    /**
     * @var integer	Used to specify encoding of the message text. Possible values:
     * 0 - 7 Bits GSM7 Alphabet and more info here
     * 2 - UCS-2
     *
     * Attention:
     * If you specify coding - automatic message splitting will be disabled and you will also have to include field udh if the message exceeds character limit (160 - for text encoded with GSM-7; 70 - with UCS-2).
     */
    private $coding;

    /**
     * @var string	Use this to test API without sending real message to handset. Possible values:
     * 1 - Use this value to emulate success delivery
     * 2 - Use this value to emulate failed delivery
     */
    private $testMode;

    /**
     * @var integer	Use this to set time in seconds after which the message expires (is not attempted sent anymore).
     * Values can be in range [360, 432000].
     */
    private $expireIn;

    /**
     * Sender constructor.
     * @param string $apiToken
     */
    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @return string
     */
    protected function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    protected function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    protected function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    protected function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @return string
     */
    protected function getDlrUrl()
    {
        return $this->dlrUrl;
    }

    /**
     * @param string $dlrUrl
     */
    public function setDlrUrl($dlrUrl)
    {
        $this->dlrUrl = $dlrUrl;
    }

    /**
     * @return string
     */
    protected function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param string $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    protected function getScheduled()
    {
        return $this->scheduled;
    }

    /**
     * @param string $scheduled
     */
    public function setScheduled($scheduled)
    {
        $this->scheduled = $scheduled;
    }

    /**
     * @return string
     */
    protected function getUdh()
    {
        return $this->udh;
    }

    /**
     * @param string $udh
     */
    public function setUdh($udh)
    {
        $this->udh = $udh;
    }

    /**
     * @return int
     */
    protected function getCoding()
    {
        return $this->coding;
    }

    /**
     * @param int $coding
     */
    public function setCoding($coding)
    {
        $this->coding = $coding;
    }

    /**
     * @return string
     */
    protected function getTestMode()
    {
        return $this->testMode;
    }

    /**
     * @param string $testMode
     */
    public function setTestMode($testMode)
    {
        $this->testMode = $testMode;
    }

    /**
     * @return int
     */
    protected function getExpireIn()
    {
        return $this->expireIn;
    }

    /**
     * @param int $expireIn
     */
    public function setExpireIn($expireIn)
    {
        $this->expireIn = $expireIn;
    }

    /**
     * Sends SMS.
     *
     * @return mixed
     * @throws Exception
     */
    public function send()
    {
        if (is_null($this->getTo()) || is_null($this->getFrom()) || is_null($this->getMessage())) {
            throw new Exception('Parameters: $to, $from, $message must be set.');
        }
        $url = static::SERVICE_URL;
        $fields = array(
            'to' => $this->to,
            'from' => $this->from,
            'message' => $this->message,

            'dlrUrl' => $this->getDlrUrl(),
            'priority' => $this->getPriority(),
            'scheduled' => $this->getScheduled(),
            'testMode' => $this->getTestMode(),
            'expireIn' => $this->getExpireIn(),
        );

        if (!is_null($this->getUdh())) {
            $fields['udh'] = $this->getUdh();
        }
        if (!is_null($this->getCoding())) {
            $fields['coding'] = $this->getCoding();
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_TIMEOUT, static::CURLOPT_TIMEOUT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-VertexSMS-Token: ' . $this->getApiToken(),
            'Content-Type: application/json'
        ));

        $result = curl_exec($ch);
        if(curl_errno($ch))
        {
            throw new Exception('Curl error: ' . curl_error($ch), curl_errno($ch));
        }

        curl_close($ch);

        return $result;
    }
}
