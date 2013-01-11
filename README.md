# Broadstreet PHP API Client

This is an API client for Broadstreet Ads. Usage of this client requires that you have an account with Broadstreet, and have an access token.

## Example Usage

    $network_id    = '12345'; // Something you have access to
    $advertiser_id = '12345'; // And advertiser under that network
    $access_token  = 'your access token here';
    
    try
    {
        $client = new Broadstreet($access_token);
        
        /* Create an ad */
        $ad = $client->createAdvertisement($network_id, $advertiser_id, 'New Ad!', 'text', array (
            'default_text' => 'This is the message'
        ));
        
        /* Print ad code */
        echo $ad->html;
    }
    catch(Exception $ex)
    {
        echo "Whoops, there was a problem connecting to Broadstreet:" . $ex__toString();
    }

## License

TBD
