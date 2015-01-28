<?php

require '../src/Broadstreet.php';

if(count($argv) < 2) die("Supply an access token as the first parameter: php full.php acdef...\n");

$token = $argv[1];

$api = new Broadstreet($token);

$network = $api->createNetwork('API Test');

echo "Created network with id {$network->id} ...\n";

$zone = $api->createZone($network->id, 'Api Test Zone');

echo "Created zone with id {$zone->id} ...\n";

$advertiser = $api->createAdvertiser($network->id, 'API Test Advertiser');

echo "Created advertiser with id {$advertiser->id} ...\n";

$advertisement = $api->createAdvertisement($network->id, $advertiser->id, 'API Test Advertisement', 'html', array (
	'html' => "<h1>API Test Advertisement</h1><script>document.write('On advertiser {$advertiser->id}');</script>"
));

echo "Created advertisement with id {$advertisement->id} ...\n";

$campaign = $api->createCampaign($network->id, $advertiser->id, 'API Test Campaign', $params = array (
	'start_date' => '2014-01-01 00:00:00',
	'end_date' => '2017-01-01 00:00:00'
));

echo "Created campaign with id {$campaign->id} ...\n";

$campaign_del = $api->createCampaign($network->id, $advertiser->id, 'API Test Campaign: To be deleted', $params = array (
	'start_date' => '2014-01-01 00:00:00',
	'end_date' => '2017-01-01 00:00:00'
));

echo "Created campaign to be deleted with id {$campaign_del->id} ...\n";

$api->deleteCampaign($network->id, $advertiser->id, $campaign_del->id);

echo "Deleted campaign with id {$campaign_del->id} ...\n";

$placement = $api->createPlacement($network->id, $advertiser->id, $campaign->id, $params = array (
	'advertisement_id' => $advertisement->id,
	'zone_id' => $zone->id
));

echo "Created placement with id {$placement->id} ...\n";

$placement_del = $api->createPlacement($network->id, $advertiser->id, $campaign->id, $params = array (
	'advertisement_id' => $advertisement->id,
	'zone_id' => $zone->id
));

echo "Created placement to be deleted with id {$placement_del->id} ...\n";

$api->deletePlacement($network->id, $advertiser->id, $campaign->id, $placement_del->id);

echo "Deleted placement with id {$campaign_del->id} ...\n";

echo "Done\n";

