<?php

namespace DidUngar\RedisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 * @Template()
 */
class AdminController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
	$oRedis = new \Redis();
	$oRedis->connect('localhost');
	$count = $oRedis->dbSize();
	echo "Redis has $count keys\n";
	if ( !empty($_POST) ) {
		switch(@$_POST['act']) {
			case 'get' :
				var_dump($_POST['key']);
				var_dump($oRedis->get($_POST['key']));
				break;
			case 'set' :
				var_dump($_POST['key']);
				var_dump($_POST['value']);
				var_dump($oRedis->set($_POST['key'], $_POST['value']));
				break;
		}
	}
	echo '<form action="" method="POST"><input type="hidden" name="act" value="get" /><input name="key" /><input type="submit" /></form>';
	echo '<form action="" method="POST"><input type="hidden" name="act" value="set" /><input name="key" /><input name="value" /><input type="submit" /></form>';
	exit;
        return [];
    }
    /**
     * @Route("/keys")
     * @Template()
     */
    public function keysAction()
    {
        $oRedis = new \Redis();
        $oRedis->connect('localhost');
        $keys = $oRedis->keys('*');
	$lstKeys = [];
	foreach($keys as $key) {
		$lstKeys[$key] = [
			'key' => $key,
			'value' => $oRedis->get($key),
			'ttl' => $oRedis->ttl($key),
		];
	}

	var_dump($lstKeys);
        exit;
        return [];
    }
    /**
     * @Route("/info")
     * @Template()
     */
    public function infoAction()
    {
        $oRedis = new \Redis();
        $oRedis->connect('localhost');
        var_dump($oRedis->info());
        exit;
        return [];
    }
}
