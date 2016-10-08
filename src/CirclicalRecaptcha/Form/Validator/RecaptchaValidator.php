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


    private $error_codes = [];

    public function getErrorCodes()
    {
        return $this->error_codes;
    }

    public static function getIP()
    {
        $ip = false;

        if (!empty($_SERVER["HTTP_CLIENT_IP"]))
            $ip = $_SERVER["HTTP_CLIENT_IP"];

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Put the IP's into an array which we shall work with shortly.
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = false;
            }

            for ($i = 0; $i < count($ips); $i++) {
                if (!preg_match("#^(10|172\.16|192\.168)\.#i", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }

        if (!$ip && !isset($_SERVER['REMOTE_ADDR']))
            return null;

        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

    public function isValid($value)
    {

        if (!trim($value)) {
            $this->error_codes[] = 'no-value-set';
            $this->error(self::NOT_ANSWERED);

            return false;
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
                    $this->error_codes[] = $r;
                    $this->error($r);
                }
            } else {
                $this->error_codes[] = 'no-error-codes';
                $this->error(self::EXPIRED);
            }

            return false;
        }

        return true;
    }
}
