<?php
# This script parses a CSV and creates ads based on data in the CSV
# Usage (from root)
# Retrieve your network ID from your dashboard home page
# Retrieve your access token here: https://my.broadstreetads.com/access-token

# $ php test/csv.php [Network Id] [Access Token]
require dirname(__FILE__) . '/../src/Broadstreet.php';

if(count($argv) < 3) die("Supply an access token as the first parameter: php test/csv.php NETWORKID ACCESSTOKEN\n");

$network_id = $argv[1];
$token = $argv[2];

$api = new Broadstreet($token);

echo "Using network with id {$network_id} ...\n";

$advertiser = $api->createAdvertiser($network_id, 'CSV Example Advertiser ' . date('YmdHis'));

# Parse a CSV into an array of arrays
$csv = array_map('str_getcsv', file(dirname(__FILE__) . '/example.csv'));

# Loop through the rows and
for ($i = 0; $i < count($csv); $i++) {
    $creative_name  = $csv[$i][0];
    $creative_asset = $csv[$i][1];
    $creative_link  = $csv[$i][2];

    $advertisement = $api->createAdvertisement($network_id, $advertiser->id, $creative_name, 'static', array (
        'active_url' => $creative_asset,
        'destination' => $creative_link
    ));

    echo "Created advertisement with id {$advertisement->id}\nhttps://my.broadstreetads.com/networks/{$network_id}/advertisers/{$advertiser->id}/advertisements/$advertisement->id\n";
}