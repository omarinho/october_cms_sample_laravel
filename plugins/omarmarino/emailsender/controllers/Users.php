<?php namespace OmarMarino\EmailSender\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use DB;
use OmarMarino\EmailSender\Models\Deal;
use RainLab\User\Models\User;
use Event;

class Users extends Controller
{
    
    public $bodyClass = 'compact-container';

    public function __construct() {
        parent::__construct();
        BackendMenu::setContext('OmarMarino.EmailSender', 'emailsender', 'users');
	// We create a couple of test deals if not  crated yet
	$howManyDeals = Deal::all()->count();
	if ($howManyDeals == 0) {
		//First deal
		$deal = new Deal;
		$deal->title = 'Bella Gioia';
		$deal->description = 'Get a $5 certificate for only $2';
		$deal->address = '209 4th Ave.';
		$deal->site = 'Restaurant.com';
		$deal->url = 'http://www.restaurant.com/';
		$deal->image = 'http://www.8coupons.com/image/deal/14835763/featured';
		$deal->save();
		//Second deal
                $deal = new Deal;
                $deal->title = "Hermie's Salon";
		$deal->description = 'Relaxer, Color, and Cut';
                $deal->address = '3521 Church Avenue';
                $deal->site = 'LivingSocial';
                $deal->url = 'https://www.livingsocial.com';
 		$deal->image = 'http://www.8coupons.com/image/deal/15638105/featured';
                $deal->save();
	}
    }

    public function index() {
	$this->pageTitle='Send email to all users';
	if (isset($_GET['sendnow']) && ($_GET['sendnow']=='YES') ) {
		$users = User::all();
		$this->vars['sending'] = 'yes';
		echo 'Sending emails...';
 		foreach ($users as $theUser) {
                  $deal1 = Deal::find(1);
                  $deal2 = Deal::find(2);
                  $timestamp = strtotime($theUser->created_at);
                  $registration_date = date("F jS, Y", $timestamp);

                  //Send the email
                  $url = 'https://api.sendgrid.com/';
                  $user = '*********';
                  $pass = '*********';

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
		}
	}
	else {
		$this->vars['sending'] = 'no';
	}
  }

}
 
