<?php namespace App\Component\Apache;

class Config
{
    // SSL Certificates
    const SSLCERT_MYPROJECTS_CRT    = '/vagrant/vagrant.d/etc/SSL_CERTS/myprojects.lh.crt';
    const SSLCERT_MYPROJECTS_KEY    = '/vagrant/vagrant.d/etc/SSL_CERTS/myprojects.lh.key';
    const SSLCERT_VAGRANT_CRT       = '/etc/pki/tls/certs/apache-selfsigned.crt';
    const SSLCERT_VAGRANT_KEY       = '/etc/pki/tls/private/apache-selfsigned.key';
}
