<?php
/**
 * Represents an email message
 *
 * PHP version 5.4
 *
 * @category   PitchBlade
 * @package    Mail
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2013 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    1.0.0
 */
namespace PitchBlade\Mail;

use PitchBlade\Mail\Deliverable,
    PitchBlade\Mail\Address;

/**
 * Represents an email message
 *
 * @category   PitchBlade
 * @package    Mail
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 */
class Message implements Deliverable
{
    /**
     * @var \PitchBlade\Mail\Address The emailaddress of the sender
     */
    private $fromAddress;

    /**
     * @var \PitchBlade\Mail\Address The reply to emailaddress
     */
    private $replyToAddress;

    /**
     * @var array The emailaddress(es) of the carbon copy
     */
    private $ccAddresses = [];

    /**
     * @var array The emailaddress(es) of the blind carbon copy
     */
    private $bccAddresses = [];

    /**
     * @var string The subject of the email
     */
    private $subject;

    /**
     * @var string The plaintext content of the email
     */
    private $plainTextBody;

    /**
     * @var string The HTML content of the email
     */
    private $htmlBody;

    /**
     * @var string The boundary used in the content and the headers
     */
    private $boundary;

    /**
     * @var string The character set used
     */
    private $charset = 'iso-8859-1';

    /**
     * Creates instance
     *
     * @param \PitchBlade\Mail\Address $fromAddress The from address
     * @param string                   $subject     The subject of the mail
     * @param string                   $charset     The charset the message uses
     */
    public function __construct(Address $fromAddress, $subject, $charset = 'iso-8859-1')
    {
        $this->fromAddress       = $fromAddress;
        $this->replyToAddress    = $fromAddress;
        $this->subject           = $subject;
        $this->charset           = $charset;
        $this->boundary          = md5(date('r', time()));
    }

    /**
     * Sets a custom reply-to address
     *
     * @param \PitchBlade\Mail\Address $address The reply-to address
     */
    public function setReplyTo(Address $address)
    {
        $this->replyToAddress = $address;
    }

    /**
     * Adds a CC emailaddress
     *
     * @param \PitchBlade\Mail\Address $address The recipient address
     */
    public function addCc(Address $address)
    {
        $this->ccAddresses[] = $address;
    }

    /**
     * Adds a BCC emailaddress
     *
     * @param \PitchBlade\Mail\Address $address The recipient address
     */
    public function addBcc(Address $address)
    {
        $this->bccAddresses[] = $address;
    }

    /**
     * Adds the plain text version of the body of the email
     *
     * @param string $text The text of the body
     */
    public function setPlainTextBody($text)
    {
        $this->plainTextBody = $text;
    }

    /**
     * Adds the HTML version of the body of the email
     *
     * @param string $html The HTML of the body
     */
    public function setBodyHtml($html)
    {
        $this->htmlBody = $html;
    }

    /**
     * Builds the headers using all the options specified
     *
     * @return array The headers
     */
    public function getHeaders()
    {
        $headers = [
            'From'         => $this->fromAddress->getRfcAddress(),
            'Reply-To'     => $this->replyToAddress->getRfcAddress(),
            'Content-Type' => 'multipart/alternative; boundary="PHP-alt-' . $this->boundary . '"',
        ];

        if (!empty($this->ccAddresses)) {
            $headers['Cc'] = $this->addRecipientsHeader($this->ccAddresses);
        }

        if (!empty($this->bccAddresses)) {
            $headers['Bcc'] = $this->addRecipientsHeader($this->bccAddresses);
        }

        return $headers;
    }

    /**
     * Generates a header containing recipients
     *
     * @param array $collection A list of emailaddresses
     *
     * @return null|string The header containing all the emailaddresses
     */
    private function addRecipientsHeader(array $collection)
    {
        $addresses = [];
        foreach ($collection as $address) {
            $addresses[] = $address->getRfcAddress();
        }

        return implode(', ', $addresses);
    }

    /**
     * Builds the mail body (the actual message to be send)
     *
     * return string The message body
     */
    public function getMessageBody()
    {
        $message = '';

        if ($this->plainTextBody !== null) {
            $message.= '--PHP-alt-' . $this->boundary . "\r\n";
            $message.= 'Content-Type: text/plain; charset="' . $this->charset . '"' . "\r\n";
            $message.= 'Content-Transfer-Encoding: 7bit' . "\r\n\r\n";
            $message.= $this->plainTextBody ."\r\n\r\n";
        }

        if ($this->htmlBody !== null) {
            $message.= '--PHP-alt-' . $this->boundary . "\r\n";
            $message.= 'Content-Type: text/html; charset="' . $this->boundary . '"' . "\r\n";
            $message.= 'Content-Transfer-Encoding: 7bit' . "\r\n\r\n";
            $message.= $this->htmlBody . "\r\n\r\n";
        }

        return $message . '--PHP-alt-' . $this->boundary . '--' . "\r\n";
    }
}
