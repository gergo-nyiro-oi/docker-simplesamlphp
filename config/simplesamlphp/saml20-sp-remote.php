<?php
/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

function load_metadata() {
    $metadata = [];

    if (getenv('SIMPLESAMLPHP_SP_ENTITY_ID') && getenv('SIMPLESAMLPHP_SP_ASSERTION_CONSUMER_SERVICE')) {
        $metadata[getenv('SIMPLESAMLPHP_SP_ENTITY_ID')] = [
            'AssertionConsumerService' => getenv('SIMPLESAMLPHP_SP_ASSERTION_CONSUMER_SERVICE'),
            'SingleLogoutService' => getenv('SIMPLESAMLPHP_SP_SINGLE_LOGOUT_SERVICE'),
        ];
    }

    if (!file_exists('/var/www/simplesamlphp/metadata/sp.json'))
        return $metadata;

    $json = file_get_contents('/var/www/simplesamlphp/metadata/sp.json');
    $data = json_decode($json, true);

    if (is_null($data) || !is_array($data))
        return $metadata;


    foreach ($data as $entity_id => $entity_data) {
        if (!array_key_exists("AssertionConsumerService", $entity_data))
            throw new UnexpectedValueException("SP metadata is not set for $entity_id");

        $metadata[$entity_id] = $entity_data;
    }
    return $metadata;
}

$metadata = load_metadata();
