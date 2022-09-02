<?php

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (is_null($data) || !is_array($data))
    throw new UnexpectedValueException("Invalid SP metadata: $json");

foreach ($data as $entity_id => $entity_data) {
    if (!array_key_exists("AssertionConsumerService", $entity_data))
        throw new UnexpectedValueException("SP metadata is not set; entity_id=$entity_id");
}

file_put_contents("/var/www/simplesamlphp/metadata/sp.json", $json);
