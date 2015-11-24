<?php

namespace Evo;
/**
 *	An effort to simplify the mailer class.
 *	Provides the ability to pass an array of variables
 *	that are then parsed before email sending.
 *	e.g.  this->vars( "firstName" => "Andrew" )
 *	would transform
 * 	Hello {{firstName}},  - to - Hello Andrew,
 * 	prior to email send.
 */
class Mailer
{
    private $contentType = 'text/plain';
    private $vars = array();
    private $content = '';
    private $headers = array();
    private $subject = '';
    private $to = '';
    private $from = '';

/**
 * Sets the content type for the email
 * @param [String] $type [content type of the email - text/plain or text/html]
 * @return [instance]
 */
    public function setContentType($type)
    {
        $this->contentType = $type;

        return $this;
    }
/**
 * Sets the content for the email
 * @param [String] $content [the actual email content]
 * @return [instance]
 */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }
/**
 * Provides the email object with an array of variables that can be used in the email
 * @param  [Array] $vars [key => value pairs for the email]
 * @return [instance]
 */
    public function vars($vars)
    {
        $this->vars = $vars;

        return $this;
    }
/**
 * Sets the email headers (if needed)
 * most of the necessary stuff is done for you
 * @param  [Array] $headers [headers as key => value pairs]
 * @return [instance]
 */
    public function headers($headers)
    {
        $this->headers = $headers;

        return $this;
    }
/**
 * Provides the email subject
 * @param  [String] $subject
 * @return [instance]
 */
    public function subject($subject)
    {
        $this->subject = $subject;

        return $this;
    }
/**
 * The recipients email address
 * @param  [String] $to [an email address]
 * @return [instance]
 */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }
/**
 * The "from" address - sets it as a correctly formatted header
 * @param  [String] $name  [The name the email should show it came from e.g. Burton Customer Service]
 * @param  [String] $email [The email the email should be attributed to e.g. service@burton.com]
 * @return [instance]
 */
    public function from($name, $email)
    {
        $this->from = 'From: ' . $name . ' <' . $email . '>' . "\n\r";

        return $this;
    }
   /**
    * returns the pass/fail status of the wp_mail
    * @return [type] [description]
    */
    public function getResponse()
    {
        return $this->response;
    }
  /**
   * What this has all been for - actually sends the email! Sets the $response property in the process.
   * @return [instance]
   */
    public function send()
    {
        # set Wordpress content type filter
        add_filter('wp_mail_content_type', function () {
            return $this->contentType;
        });
        # the first header should be the from address
        $headers = $this->from ? $this->from . '\n' : '';
        # build out the rest of the headers
        foreach ($this->headers as $key => $header) {
            $headers .= ucfirst($key) . ':' . $header . "\r\n";
        }

        $content = $this->content;
        $vars = $this->vars;
        # replace each var that is surrounded in mustache-style brackets
        foreach ($vars as $key => $val) {
            $splitContent = explode('{{' . $key . '}}', $content);
            $content = implode($val, $splitContent);
        }
        # use the wp_mail shortcut to send the email with Wordpress' settings
        $response = wp_mail($this->to, $this->subject, $content, $headers);

        $this->response = $response;

        return $this;
    }
}
