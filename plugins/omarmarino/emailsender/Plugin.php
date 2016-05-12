<?php namespace OmarMarino\EmailSender;

use Backend;
use System\Classes\PluginBase;
use Event;
use OmarMarino\EmailSender\Models\Deal;
use DB;

/**
 * EmailSender Plugin Information File
 */
class Plugin extends PluginBase
{


    public function boot() {
	 Event::listen('rainlab.user.registered', function($account) {
		$deal1 = Deal::find(1);
		$deal2 = Deal::find(2);
		$theUser = Db::table('users')->where('email', $account['email'])->first();
		$timestamp = strtotime($theUser->created_at);
		$registration_date = date("F jS, Y", $timestamp);

		//Send the email
		$url = 'https://api.sendgrid.com/';
		$user = '******';
		$pass = '******';
		
		$json_string = array(
  			'filters' => array('templates' => array('settings' => array('enable' => 1, 'template_id' => 'aee63675-ce8d-4c46-9948-93141e040b80'))),
			   'sub' => array(
				'-email-' => array($theUser->email),
				'-registration_date-' => array(strval($registration_date)),
				'-url_image1-' => array($deal1->image),
				'-url_image2-' => array($deal2->image),
				'-title1-' => array($deal1->title),
				'-title2-' => array($deal2->title),
				'-description1-' => array($deal1->description),
				'-description2-' => array($deal2->description),
				'-address1-' => array($deal1->address),
				'-address2-' => array($deal2->address),
				'-url1-' => array($deal1->url),
				'-url2-' => array($deal2->url),
				'-site1-' => array($deal1->site),
				'-site2-' => array($deal2->site),
			),
  			'to' => array($theUser->email),
  			'category' => 'test_category'
		);

		$params = array(
    			'api_user'  => $user,
    			'api_key'   => $pass,
    			'x-smtpapi' => json_encode($json_string),
    			'to'        => 'example3@sendgrid.com',
 			    'html'      => '<h1>Some deals for you</h1>',
    			'text'      => 'Some deals for you',
  			   'subject'   => 'testing from curl',
    			'from'      => 'omarinho@gmail.com',
  		);


		$request =  $url.'api/mail.send.json';
		$session = curl_init($request);
		curl_setopt ($session, CURLOPT_POST, true);
		curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_SSLVERSION, 6);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($session);
		curl_close($session);
	});
    }


    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'EmailSender',
            'description' => 'It allows users to send an e-mail to all users Utilizing Sendgrid template engine and the Sendgrid API',
            'author'      => 'Omar Yesid Marino',
            'icon'        => 'icon-envelope'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'OmarMarino\EmailSender\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'omarmarino.emailsender.some_permission' => [
                'tab' => 'EmailSender',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'emailsender' => [
                'label'       => 'Email Sender',
                'url'         => Backend::url('omarmarino/emailsender/users'),
                'icon'        => 'icon-envelope',
                'order'       => 500,
            ]

        ];
    }

}
