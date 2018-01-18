<?php

namespace ActiveCampaign\Api\V1;

use ActiveCampaign\Api\V1\Exceptions\InvalidArgumentException;

/**
 * Class ActiveCampaign
 */
class ActiveCampaign extends Connector
{

    /**
     * @var
     */
    public $url_base;

    /**
     * @var
     */
    public $url;

    /**
     * @var
     */
    public $api_key;

    /**
     * @var
     */
    public $track_email;

    /**
     * @var
     */
    public $track_actid;

    /**
     * @var
     */
    public $track_key;

    /**
     * @var int
     */
    public $version = 1;

    /**
     * @var string
     */
    public $curl_response_error = "";

    /**
     * ActiveCampaign constructor.
     *
     * @param        $url
     * @param        $api_key
     * @param string $api_user
     * @param string $api_pass
     */
    public function __construct($url, $api_key, $api_user = "", $api_pass = "")
    {
        $this->url_base = $this->url = $url;
        $this->api_key = $api_key;
        parent::__construct($url, $api_key, $api_user, $api_pass);
    }

    /**
     * Set the version on the url
     *
     * @param $version
     */
    public function version($version)
    {
        $this->version = (int)$version;
        if ($version == 2) {
            $this->url_base = $this->url_base . "/2";
        }
    }

    /**
     * Make api calls
     *
     * @param       $path
     * @param array $post_data
     *
     * @return mixed
     */
    public function api($path, $post_data = array())
    {
        // IE: "contact/view"
        $components = explode("/", $path);
        $component = $components[0];

        if (count($components) > 2) {
            // IE: "contact/tag/add?whatever"
            // shift off the first item (the component, IE: "contact").
            array_shift($components);
            // IE: convert to "tag_add?whatever"
            $method_str = implode("_", $components);
            $components = array($component, $method_str);
        }

        if (preg_match("/\?/", $components[1])) {
            // query params appended to method
            // IE: contact/edit?overwrite=0
            $method_arr = explode("?", $components[1]);
            $method = $method_arr[0];
            $params = $method_arr[1];
        } else {
            // just a method provided
            // IE: "contact/view
            if (isset($components[1])) {
                $method = $components[1];
                $params = "";
            } else {
                return "Invalid method.";
            }
        }

        // adjustments
        if ($component == "list") {
            // reserved word
            $component = "list_";
        } elseif ($component == "branding") {
            $component = "design";
        } elseif ($component == "sync") {
            $component = "contact";
            $method = "sync";
        } elseif ($component == "singlesignon") {
            $component = "auth";
        }

        // "contact" becomes "Contact"
        $class = ucwords($component);

        $add_tracking = false;
        if ($class == "Tracking") {
            $add_tracking = true;
        }
        if ($class == "Tags") {
            $class = "Tag";
        }

        // add the FQCN
        $class = "\\ActiveCampaign\\Api\\V1\\" . $class;
        if (! class_exists($class)) {
            throw new InvalidArgumentException("The class $class does not exist. This class was called from the path $path.");
        }

        $class = new $class($this->version, $this->url_base, $this->url, $this->api_key);

        $class->set_curl_timeout($this->get_curl_timeout());
        $class->set_curl_connect_timeout($this->get_curl_connect_timeout());

        if ($add_tracking) {
            $class->track_email = $this->track_email;
            $class->track_actid = $this->track_actid;
            $class->track_key = $this->track_key;
        }

        if ($method == "list") {
            // reserved word
            $method = "list_";
        }

        $response = $class->$method($params, $post_data);
        return $response;
    }
}
