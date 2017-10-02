<?php

namespace tests\AppBundle;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider roleAnnonymousUrlProvider
     */
    public function testRoleAnnonymousPages($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function roleAnnonymousUrlProvider()
    {
        return [
            ['/'],
        ];
    }

    /**
     * @dataProvider roleAdminUrlProvider
     */
    public function testRoleAdminPages($url)
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'root',
        ));
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function roleAdminUrlProvider()
    {
        return [
            ['/'],
        ];
    }
}