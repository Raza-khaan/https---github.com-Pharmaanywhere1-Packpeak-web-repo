<?php

namespace App\Helpers;
use App\Models\Admin\Subscription;

Class Helper {

	public static function smsSendToMobile($phoneNo, $msg) {
		require base_path() . '/vendor/twilio/sdk/src/Twilio/autoload.php';
		$account_sid = env('SMS_SID');
		$auth_token = env('SMS_TOKEN');
		$twilio_number = env('SMS_MOBILE');
		$client = new \Twilio\Rest\Client($account_sid, $auth_token);
		// echo $phoneNo;
		// echo $twilio_number;
		//  die;
		$resp = $client->messages->create(
			$phoneNo,
			array(
				'from' => $twilio_number,
				'body' => $msg,
			)
		);

		return $resp;
		// dd($resp->properties);  die;
	}

	public static function menus() {
		//return ;
		$menus = [];

		if (session()->has('phrmacy')) {
			$subcription = Subscription::find(session()->get('phrmacy')->subscription);

			$parentMenus = $subcription->menus;
			$subMenus = collect($subcription->submenus->all())->groupBy('menu_id');
// dd($subMenus[2]);
			if ($parentMenus->count()) {
				foreach ($parentMenus as $parentMenu) {
					//print_r($parentMenu->count());
					$menus[$parentMenu->id]['menu'] = $parentMenu;
					if ($parentMenus->count() && $subMenus->has($parentMenu->id)) {
						$menus[$parentMenu->id]['submenu'] = $subMenus[$parentMenu->id];
					} else {
						$menus[$parentMenu->id]['submenu'] = [];
					}

				}

			}
			// dd($menus[2]['submenu']->name);
			// print_r($menus);

		}
		// foreach($menus as $menu){
		//     print_r($menu['submenu']);
		// }
		//dd('hello');
		return $menus;
	}

	public static function mysql_escape($inp) {
		if (is_array($inp)) {
			return array_map(__METHOD__, $inp);
		}

		if (!empty($inp) && is_string($inp)) {
			return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
		}

		return $inp;
	}
}

?>
