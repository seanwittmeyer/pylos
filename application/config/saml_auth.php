<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Settings.
| -------------------------------------------------------------------------
*/
$config['settingsInfo'] = array (
        'sp' => array (
            'entityId' => site_url('auth/saml/metadata'),
            'assertionConsumerService' => array (
                'url' => site_url('auth/saml/acs'),
            ),
            'singleLogoutService' => array (
                'url' => site_url('auth/saml/sls'),
            ),
            //'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
        ),
        'idp' => array (
            //'entityId' => getenv('BUILDER_SAML_ENTITYMETADATA'),
            'entityId' => getenv('BUILDER_SAML_ENTITYID'),
            'singleSignOnService' => array (
                'url' => getenv('BUILDER_SAML_SINGLESIGNONSERVICE'),
            ),
            'singleLogoutService' => array (
                'url' => getenv('BUILDER_SAML_SINGLELOGOUTERVICE'),
            ),
            'x509cert' => getenv('BUILDER_SAML_X509CERT'),
        ),
    );
