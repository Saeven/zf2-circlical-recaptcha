<?php

namespace CirclicalRecaptcha\Form\Validator;

use Zend\Validator\AbstractValidator;

class RecaptchaValidator extends AbstractValidator
{
    const NOT_ANSWERED = 'not_answered';
    const EXPIRED = 'expired';

    protected $messageTemplates = [
        'missing-input-secret' => 'The secret parameter is missing.',
        'invalid-input-secret' => 'The secret parameter is invalid or malformed.',
        'missing-input-response' => 'The response parameter is missing.',
        'invalid-input-response' => 'The response parameter is invalid or malformed.',
        self::NOT_ANSWERED => 'You must complete the challenge.',
        self::EXPIRED => 'Your form timed out, please try again.',
    ];


    private $secret;

    public function setSecret($key)
    {
        $this->secret = $key;
    }

    /**
     * @var bool skip checking the CAPTCHA
     */
    private $captchaBypassed = false;

    /**
     * Things like gherkin tests don't like CAPTCHAs.  This gives us a mechanism to disable them.
     *
     * @param bool $captchaBypassed
     */
    public function setCaptchaBypassed($captchaBypassed)
    {
        $this->captchaBypassed = $captchaBypassed;
    }

    /**
     * @return bool
     */
    public function isCaptchaBypassed()
    {
        return $this->captchaBypassed;
    }

    private $errorCodes = [];

    public function getErrorCodes()
    {
        return $this->errorCodes;
    }

    public static function getIP()
    {
        $ip = false;

        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Put the IP's into an array which we shall work with shortly.
            $ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = false;
            }

            $totalIPs = count($ips);
            for ($i = 0; $i < $totalIPs; $i++) {
                if (!preg_match("#^(10|172\.16|192\.168)\.#i", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }

        if (!$ip && !isset($_SERVER['REMOTE_ADDR'])) {
            return null;
        }

        return $ip ?: $_SERVER['REMOTE_ADDR'];
    }

    public function isValid($value)
    {

        if (!trim($value)) {
            $this->errorCodes[] = 'no-value-set';
            $this->error(self::NOT_ANSWERED);

            return false;
        }

        if ($this->captchaBypassed) {
            return true;
        }

        // https://www.google.com/recaptcha/api/siteverify
        $ip = self::getIP();
        $x = file_get_contents('https://www.google.com/recaptcha/api/siteverify?' . http_build_query([
                'secret' => $this->secret,
                'response' => $value,
                'remoteip' => $ip,
            ]));

        $json = json_decode($x, true);
        if (!$json['success']) {
            if (!empty($json['error-codes'])) {
                foreach ($json['error-codes'] as $r) {
                    $this->errorCodes[] = $r;
                    $this->error($r);
                }
            } else {
                $this->errorCodes[] = 'no-error-codes';
                $this->error(self::EXPIRED);
            }

            return false;
        }

        return true;
    }
}
