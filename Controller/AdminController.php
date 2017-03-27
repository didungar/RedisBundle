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

	protected function actionForm() {
        	if ( !empty($_POST) ) {
			$oRedis = new \Redis();
			$oRedis->connect('localhost');
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
				case 'delete' :
					var_dump($_POST['key']);
					var_dump($oRedis->delete($_POST['key']));
					break;
        	        }
        	}
	}

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
	$oRedis = new \Redis();
	$oRedis->connect('localhost');
        $this->actionForm();
	return [
		'keys_count' => $oRedis->dbSize(),
	];
    }
    /**
     * @Route("/keys")
     * @Template()
     */
    public function keysAction()
    {
        $oRedis = new \Redis();
        $oRedis->connect('localhost');
	$this->actionForm();
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
        return [
		'lstKeys' => $lstKeys,
	];
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
