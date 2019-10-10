<?php

namespace CirclicalRecaptcha\Form\Validator;

use Zend\Validator\AbstractValidator;

class RecaptchaValidator extends AbstractValidator
{
    public const NOT_ANSWERED = 'not_answered';

    public const EXPIRED = 'expired';

    public const ERROR_MISSING_SECRET = 'missing-input-secret';

    public const ERROR_INVALID_SECRET = 'invalid-input-secret';

    public const ERROR_MISSING_INPUT_RESPONSE = 'missing-input-response';

    public const ERROR_INVALID_INPUT_RESPONSE = 'invalid-input-response';

    public const ERROR_CONNECTION_FAILED = 'connection-failed';

    private static $GOOGLE_REQUEST_URL = 'https://www.google.com/recaptcha/api/siteverify';

    protected $messageTemplates = [
        self::ERROR_MISSING_SECRET => 'The secret parameter is missing.',
        self::ERROR_INVALID_SECRET => 'The secret parameter is invalid or malformed.',
        self::ERROR_MISSING_INPUT_RESPONSE => 'The response parameter is missing.',
        self::ERROR_INVALID_INPUT_RESPONSE => 'The response parameter is invalid or malformed.',
        self::NOT_ANSWERED => 'You must complete the challenge.',
        self::EXPIRED => 'Your form timed out, please try again.',
        self::ERROR_CONNECTION_FAILED => 'The captcha could not be verified, please try again.',
    ];


    private $secret;

    private $errorCodes;

    private $captchaBypassed;

    private $requestUrl;

    private $responseTimeout;

    private $challengeTimestamp;

    private $challengeVerificationTimestamp;


    public function __construct(string $secret, int $responseTimeout, $options = null)
    {
        parent::__construct($options);

        $this->secret = $secret;
        $this->responseTimeout = $responseTimeout;
        $this->errorCodes = [];
        $this->captchaBypassed = false;

    }

    public function setCaptchaBypassed(bool $captchaBypassed): void
    {
        $this->captchaBypassed = $captchaBypassed;
    }

    public function isCaptchaBypassed(): bool
    {
        return $this->captchaBypassed;
    }

    public function getErrorCodes(): array
    {
        return $this->errorCodes;
    }

    public static function getIP(): ?string
    {
        $ipAddress = false;

        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ipAddress = $_SERVER["HTTP_CLIENT_IP"];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Put the IP's into an array which we shall work with shortly.
            $ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ipAddress) {
                array_unshift($ips, $ipAddress);
                $ipAddress = false;
            }

            $totalIPs = count($ips);
            for ($i = 0; $i < $totalIPs; $i++) {
                if (!preg_match("#^(10|172\.16|192\.168)\.#i", $ips[$i])) {
                    $ipAddress = $ips[$i];
                    break;
                }
            }
        }

        if (!$ipAddress && !isset($_SERVER['REMOTE_ADDR'])) {
            return null;
        }

        return $ipAddress ?: $_SERVER['REMOTE_ADDR'];
    }

    public function isValid($value): bool
    {
        if ($this->captchaBypassed) {
            return true;
        }

        if (!trim($value)) {
            $this->errorCodes[] = 'no-value-set';
            $this->error(self::NOT_ANSWERED);

            return false;
        }

        // https://www.google.com/recaptcha/api/siteverify
        $ipAddress = self::getIP();
        $this->requestUrl = static::$GOOGLE_REQUEST_URL . '?' . http_build_query([
                'secret' => $this->secret,
                'response' => $value,
                'remoteip' => $ipAddress,
            ]);

        try {
            $googleResponse = @file_get_contents($this->requestUrl);
            if( $googleResponse === false ){
                throw new \ErrorException('Site could not be reached');
            }

            $json = json_decode($googleResponse, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->errorCodes[] = self::ERROR_INVALID_INPUT_RESPONSE;
                $this->error(self::ERROR_INVALID_INPUT_RESPONSE);
            }
        } catch (\ErrorException $errorException) {
            $this->errorCodes[] = self::ERROR_CONNECTION_FAILED;
            $this->error(self::ERROR_CONNECTION_FAILED);
            return false;
        }

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

        if ($json['challenge_ts']) {
            $this->challengeVerificationTimestamp = time();
            $this->challengeTimestamp = $json['challenge_ts'];
            $challengeTime = strtotime($json['challenge_ts']);
            if ($challengeTime > 0 && ($this->challengeVerificationTimestamp - $challengeTime) > $this->responseTimeout) {
                $this->errorCodes[] = 'no-error-codes';
                $this->error(self::EXPIRED);

                return false;
            }
        }

        return true;
    }
}
