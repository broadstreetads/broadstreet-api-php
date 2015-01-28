<?php
/**
 * This is the PHP client for Broadstreet
 * @link http://broadstreetads.com
 * @author Broadstreet Ads <labs@broadstreetads.com>
 */

if(!class_exists('Broadstreet')):

/**
 * This is the PHP client and class for Broadstreet
 * It requires cURL 
 */
class Broadstreet
{
    const API_VERSION = '0';
    
    /**
     * The API Key used for auth
     * @var string
     */
    protected $accessToken = null;
    
    /**
     * The hostname to point at
     * @var string
     */
    protected $host = 'api.broadstreetads.com';
    
    /**
     * Use SSL? You should.
     * @var bool 
     */
    protected $use_ssl = true;
    
    /**
     * The constructor
     * @param string $access_token A user's access token
     * @param string $host The API endpoint host. Optional. Defaults to
     *  api.broadstreetads.com
     */
    public function __construct($access_token = null, $host = null, $secure = true)
    {
        if($host !== null)
        {
            $this->host = $host;
        }
        
        $this->accessToken = $access_token;
        $this->use_ssl     = $secure;
        
        /* Define cURL constants if needed */
        if(!defined('CURLOPT_POST'))
        {
            define('CURLOPT_POST', 47);
            define('CURLOPT_POSTFIELDS', 10015);
            define('CURLOPT_RETURNTRANSFER', 19913);
            define('CURLOPT_CUSTOMREQUEST', 10036);
            define('CURLINFO_HTTP_CODE', 2097154);
        }
    }

    
    /**
     * Advertisements section
     */
    
    /**
     * Create an advertisement
     * @param  int $network_id
     * @param  int $advertiser_id
     * @param  string $name
     * @param  string $type html|static Static refers to banner ads
     * @param  array  $params Could be:
     *   array('html' => 'Hello')
     *   array('image' => 'utl_to_image')
     *   array('image_base64' => 'base 64 image string')
     * @return mixed
     */
    public function createAdvertisement($network_id, $advertiser_id, $name, $type, $params = array())
    {
        $params = array('name' => $name, 'type' => $type) + $params;
        
        return $this->_post("/networks/$network_id/advertisers/$advertiser_id/advertisements", $params)->body->advertisement;
    }

    /**
     * Get information about a given advertisement
     * @param int $network_id
     * @param int $advertiser_id
     * @param int $advertisement_id
     * @return object
     */
    public function getAdvertisement($network_id, $advertiser_id, $advertisement_id)
    {
        return $this->_get("/networks/$network_id/advertisers/$advertiser_id/advertisements/$advertisement_id")
                    ->body->advertisement;
    }

    /**
     * Update an advertisement
     * @param string $name The name of the advertisement
     * @param string $type The type of advertisement
     * @return mixed
     */
    public function updateAdvertisement($network_id, $advertiser_id, $advertisement_id, $params = array())
    {
        return $this->_put("/networks/$network_id/advertisers/$advertiser_id/advertisements/$advertisement_id", $params)->body->advertisement;
    }

    /**
     * Delete an advertisement
     * @param string $name The name of the advertisement
     * @param string $type The type of advertisement
     * @return mixed
     * @todo
     */
    public function deleteAdvertisement($network_id, $advertiser_id, $advertisement_id)
    {        
        return $this->_delete("/networks/$network_id/advertisers/$advertiser_id/advertisements/$advertisement_id")->body;
    }
    
    /**
     * Get information about a given advertisement source
     * @param int $network_id
     * @param int $advertiser_id
     * @param int $advertisement_id
     * @return object
     */
    public function getAdvertisementSource($network_id, $advertiser_id, $advertisement_id)
    {   
        return $this->_get("/networks/$network_id/advertisers/$advertiser_id/advertisements/$advertisement_id/source")
                    ->body->source;
    }
    
    /**
     * Get a report for a given advertisement for the last x days
     * @param type $network_id
     * @param type $advertiser_id
     * @param type $advertisement_id
     * @param type $start_date
     * @param type $end_date
     * @return type 
     */
    public function getAdvertisementReport($network_id, $advertiser_id, $advertisement_id, $start_date = false, $end_date = false)
    {
        return $this->_get("/networks/$network_id/advertisers/$advertiser_id/advertisements/$advertisement_id/records", array(), array (
            'start_date' => $start_date,
            'end_date'   => $end_date
        ))->body->records;
    }

    /**
     * Create a proof
     * @param array $params 
     */
    public function createProof($params)
    {
        return $this->_post("/advertisements/proof", $params)->body->proof;
    }
    
    /**
     * The the update source of an advertisement
     * @param int $network_id
     * @param int $advertiser_id
     * @param int $advertisement_id
     * @param string $type
     * @param array $params
     * @return object 
     */
    public function setAdvertisementSource($network_id, $advertiser_id, $advertisement_id, $type, $params = array())
    {
        $params = array('type' => $type) + $params;

        return $this->_post("/networks/$network_id/advertisers/$advertiser_id/advertisements/$advertisement_id/source", $params)
                    ->body->advertisement;
    }

    /**
     * Advertiser section
     */

    /**
     * Get a list of advertisers this token has access to 
     * @param  int $network_id
     * @param  int $advertiser_id
     * @return mixed
     */
    public function getAdvertiser($network_id, $advertiser_id)
    {
        return $this->_get("/networks/$network_id/advertisers/$advertiser_id")->body->advertiser;
    }

    /**
     * Get a list of advertisers this token has access to 
     * @param  int $network_id
     * @return mixed
     */
    public function getAdvertisers($network_id)
    {
        return $this->_get("/networks/$network_id/advertisers")->body->advertisers;
    }

    /**
     * Create an advertiser
     * @param string $name The name of the advertiser
     * @return mixed
     */
    public function createAdvertiser($network_id, $name)
    {
        return $this->_post("/networks/$network_id/advertisers", array('name' => $name))->body->advertiser;
    }

    /**
     * Delete an advertiser
     * @param  int $network_id
     * @param  int $advertiser_id
     * @param  int $advertisement_id 
     * @return mixed
     */
    public function deleteAdvertiser($network_id, $advertiser_id, $advertisement_id)
    {        
        return $this->_delete("/networks/$network_id/advertisers/$advertiser_id")->body;
    }

    /**
     * Campaign section
     */
    
    /**
     * Create a campaign
     * @param int $network_id
     * @param int $advertiser_id
     * @param string $name The name of the campaign
     * @param array $params array('start_date' => null, 'end_date' => null)
     * @return mixed
     * @todo
     */
    public function createCampaign($network_id, $advertiser_id, $name, $params = array())
    {
        $params = array('name' => $name) + $params;
        return $this->_post("/networks/$network_id/advertisers/$advertiser_id/campaigns", $params)->body->campaign;
    }

    /**
     * Delete a campaign
     * @param int $network_id
     * @param int $advertiser_id
     * @param int $campaign_id
     * @return mixed
     * @todo
     */
    public function deleteCampaign($network_id, $advertiser_id, $campaign_id)
    {
        return $this->_delete("/networks/$network_id/advertisers/$advertiser_id/campaigns/$campaign_id")->body;
    }

    /**
     * Placements section
     */    
    
    /**
     * Create a placement
     * @param int $network_id
     * @param int $advertiser_id
     * @param int $campaign_id
     * @param array $params array('zone_id' => 1234, 'advertisement_id' => 5432)
     * @return mixed
     * @todo
     */
    public function createPlacement($network_id, $advertiser_id, $campaign_id, $params = array())
    {
        return $this->_post("/networks/$network_id/advertisers/$advertiser_id/campaigns/$campaign_id/placements", $params)->body;
    }

    /**
     * Create an advertiser
     * @param int $network_id
     * @param int $advertiser_id
     * @param int $campaign_id
     * @param int $placement_id
     * @return mixed
     * @todo
     */
    public function deletePlacement($network_id, $advertiser_id, $campaign_id, $placement_id)
    {
        return $this->_delete("/networks/$network_id/advertisers/$advertiser_id/campaigns/$campaign_id/placements/$placement_id")->body;
    }

    /**
     * Network section
     */

    /**
     * Create a network
     * @param string $name The name of the network
     * @param array $params An array of options
     * @todo
     */
    public function createNetwork($name, $params = array())
    {
        $params['name'] = $name;
        return $this->_post("/networks", $params)->body->network;
    }

    
    /**
     * Get base account information for a network, including whether a card is
     *  on file, the cost of an import (in cents), etc
     * @param int $network_id 
     */
    public function getNetwork($network_id)
    {
        return $this->_get("/networks/$network_id")->body->network;
    }    
    
    /**
     * Get a list of networks this token has access to
     * @return array
     */
    public function getNetworks()
    {
        return $this->_get('/networks')->body->networks;
    }
    
    /**
     * Get a list of zones under a network
     * @param int $network_id
     * @return object
     */
    public function getNetworkZones($network_id)
    {
        return $this->_get("/networks/$network_id/zones")->body->zones;
    }

    /**
     * Zone section
     */

    /**
     * Create a zone
     * @param int $network_id
     * @param string $name The name of the zone
     * @param array $params array('alias' => 'optional_zone_alias', 'self_serve' => false, 'pricing_callback' => null)
     * @return mixed
     * @todo
     */
    public function createZone($network_id, $name, $params = array())
    {
        $params = array('name' => $name) + $params;
        return $this->_post("/networks/$network_id/zones", $params)->body->zone;
    }

    /**
     * Delete a zone
     * @param int $network_id
     * @param int $zone_id
     * @return mixed
     * @todo
     */
    public function deleteZone($network_id, $zone_id)
    {
        return $this->_delete("/networks/$network_id/zones/$zone_id")->body;
    }

    /**
     * User section
     */
    
    /**
     * Create a basic user
     * @param string $email 
     */
    public function createUser($email)
    {
        return $this->_post($email, $data);
    }

    /**
     * Log in to the API, get an access token back
     * @param string $username
     * @param string $password 
     */
    public function login($email, $password)
    {
        $params   = array('email' => $email, 'password' => $password);
        $response = $this->_post("/sessions", $params)->body->user;
        
        $this->accessToken = $response->access_token;
        
        # Store access token
        return $response;
    }
    
    /**
     * Register a new user
     * @param string $username
     * @param string $password 
     */
    public function register($email)
    {
        $params   = array('email' => $email);
        $response = $this->_post("/users", $params)->body->user;
        
        $this->accessToken = $response->access_token;
        
        # Store access token
        return $response;
    }


    /**
     * Misc section
     */    
    
    /**
     * Get a list of fonts supported by Broadstreet 
     */
    public function getFonts()
    {
        return $this->_get("/fonts")->body->fonts;
    }
    
    /**
     * Magically get back business data based off a seed URL
     * @param string $seed_url Facebook page URL
     * @param int    $network_id
     */
    public function magicImport($seed_url, $network_id)
    {
        return $this->_get("/networks/$network_id/import", array(), array('lookup' => $seed_url))->body;
    }

    
    /**
     * Gets a response from the server
     * @param string $uri
     * @param array $params
     * @param array $query_args
     * @return type
     * @throws Broadstreet_DependencyException 
     * @throws Broadstreet_AuthException 
     */
    protected function _get($uri, $params = array(), $query_args = array())
    {
        $url = $this->_buildRequestURL($uri, $query_args);

        # If the Wordpress HTTP library is loaded, use it
        if(function_exists('wp_remote_post'))
        {
            list($body, $status) = $this->_wpGet($url, $params);
        }
        else
        {
            # Fallback to cURL
            if(!function_exists('curl_exec'))
            {
                throw new Broadstreet_DependencyException("The cURL module must be installed");
            }
            
            list($body, $status) = $this->_curlGet($url, $params);
        }
        
        if($status == '403')
        {
            throw new Broadstreet_ServerException("Broadstreet API Auth Denied (HTTP 403)", @json_decode($body));
        }
        
        if($status == '500')
        {
            throw new Broadstreet_ServerException("Broadstreet API had a 500 error");
        }
        
        if($status[0] != '2')
        {
            throw new Broadstreet_ServerException("Server threw HTTP $status for call to $uri with cURL params " . print_r($params, true) . "; Response: " . $body, @json_decode($body));
        }

        return (object)(array('url' => $url, 'body' => @json_decode($body), 'status' => $status));
    }
    
    /**
     * Issue a network request using the built-in Wordpress libraries
     *  Intended for use within Wordpress for extra portability
     * @param string $url
     * @param array $params cURL options. Limited support
     * @return array(body, status_code)
     */
    protected function _wpGet($url, $params = array())
    {
        $params = array (
            'method'      => 'GET',
            'timeout'     => 25,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true
        );
        
        # Handle POST Requests
        if(isset($params[CURLOPT_POST]))
        {
            $params['method'] = 'POST';
            $params['body']   = $params[CURLOPT_POSTFIELDS];
        }
        
        # Handle PUT
        if(isset($params[CURLOPT_CUSTOMREQUEST])
            && $params[CURLOPT_CUSTOMREQUEST] == 'PUT')
        {
            $params['method'] = 'PUT';
            $params['body']   = $params[CURLOPT_POSTFIELDS];
        }
        
        $body     = '{}';
        $status   = false;
        $response = @wp_remote_post($url, $params);
        
        if(isset($response['response'])
                && isset($response['body'])
                && isset($response['response']['code']))
        {
            $body   = $response['body'];
            $status = (string)$response['response']['code'];
        }
        
        return array($body, $status);
    }
    
    /**
     * Issue a network request using cURL
     * @param string $url
     * @param array  $params
     * @return array(body, status_code)
     */
    protected function _curlGet($url, $params = array())
    {
        $curl_handle = curl_init($url);
        $params    += array(CURLOPT_RETURNTRANSFER => true);

        curl_setopt_array($curl_handle, $params);

        $body   = curl_exec($curl_handle);
        $status = (string)curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        
        return array($body, $status);
    }
    
    
    /**
     * POST data to the server
     * @param string $uri
     * @param array $data Assoc. array of post data
     * @return mixed
     */
    protected function _post($uri, $data)
    {
        return $this->_get($uri, array(                                        
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $data)
        );
    }
    
    /**
     * PUT data to the server
     * @param string $uri
     * @param array $data Assoc. array of post data
     * @return mixed
     */
    public function _put($uri, $data = false, $params = array())
    {
        $data    = http_build_query($data);

        $params = array (
                        CURLOPT_CUSTOMREQUEST => 'PUT',
                        CURLOPT_POSTFIELDS    => $data
                        ) + $params;
        
        $result = $this->_get($uri, $params);
        
        return $result;
    }   

    /**
     * DELETE data on the server
     * @param string $uri
     * @return mixed
     */
    public function _delete($uri)
    {
        $params = array (CURLOPT_CUSTOMREQUEST => 'DELETE');
        
        $result = $this->_get($uri, $params);
        
        return $result;
    }   
    
    /**
     * Build a valid request URL from the URI given and the API key
     * @param string $uri
     * @return string 
     */
    protected function _buildRequestURL($uri, $query_args = array())
    {
        $uri      = ltrim($uri, '/');

        return ($this->use_ssl ? 'https://' : 'http://')
                . $this->host
                . '/api/'
                . self::API_VERSION
                . '/'
                . $uri
                . (count($query_args) ? '?' . http_build_query($query_args) : '')
                . (count($query_args) ? '&' : '?')
                . ($this->accessToken ? "access_token={$this->accessToken}" : '');
    }
}

class Broadstreet_GeneralException extends Exception {}
class Broadstreet_DependencyException extends Broadstreet_GeneralException {}
class Broadstreet_AuthException extends Broadstreet_GeneralException {}
class Broadstreet_ServerException extends Broadstreet_GeneralException {
    /**
     * The error object
     * @var object 
     */
    public $error;
    public function __construct($message, $error = '') {
        $this->error = $error;
        parent::__construct($message);
    }
}

endif;
