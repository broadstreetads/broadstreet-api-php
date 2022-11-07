# Broadstreet PHP API Client

This is an API client for Broadstreet Ads. Usage of this client requires that you have an account with Broadstreet, and have an [access token](https://my.broadstreetads.com/access-token).

## Example Usage

    $network_id    = '12345'; // Something you have access to
    $advertiser_id = '12345'; // And advertiser under that network
    $access_token  = 'your access token here'; // see https://my.broadstreetads.com/access-token
    
    try
    {
        $client = new Broadstreet($access_token);
        
        /* Create an ad */
        $ad = $client->createAdvertisement($network_id, $advertiser_id, 'New HTML Ad!', 'html', array (
            'html' => '<script>alert("everybody loves these")</script>'
        ));

        $ad = $client->createAdvertisement($network_id, $advertiser_id, 'New Banner Ad from Local File!', 'static', array (
            'active_base64' => base64_encode(file_get_contents('banner.png'))
        ));

        $ad = $client->createAdvertisement($network_id, $advertiser_id, 'New Ad from Remote!', 'static', array (
            'active_url' => 'https://placehold.jp/300x250.png'
        ));
        
        /* Print ad code */
        echo $ad->html;
    }
    catch(Exception $ex)
    {
        echo "Whoops, there was a problem connecting to Broadstreet:" . $ex->__toString();
    }

## License

TBD
