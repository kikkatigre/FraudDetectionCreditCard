<?php /** 
* Generated by
* 
*      _____ _          __  __      _     _
*     / ____| |        / _|/ _|    | |   | |
*    | (___ | | ____ _| |_| |_ ___ | | __| | ___ _ __
*     \___ \| |/ / _` |  _|  _/ _ \| |/ _` |/ _ \ '__|
*     ____) |   < (_| | | | || (_) | | (_| |  __/ |
*    |_____/|_|\_\__,_|_| |_| \___/|_|\__,_|\___|_|
*
* The code generator that works in many programming languages
*
*			https://www.skaffolder.com
*
*
* You can generate the code from the command-line
*       https://npmjs.com/package/skaffolder-cli
*
*       npm install -g skaffodler-cli
*
*   *   *   *   *   *   *   *   *   *   *   *   *   *   *   *
*
* To remove this comment please upgrade your plan here: 
*      https://app.skaffolder.com/#!/upgrade
*
* Or get up to 70% discount sharing your unique link:
*       https://beta.skaffolder.com/#!/register?friend=5cf5597ca2001a6261fbe61c
*
* You will get 10% discount for each one of your friends
* 
*/
?>
<?php

require_once './db/dbFraudDetectionCreditCard_dbManager.php';

require './lib/JWT/JWT.php';

use \Firebase\JWT\JWT;

$app = new \Slim\Slim();


// Login Action
$app->post('/login',	function () use ($app){

	$body = json_decode($app->request()->getBody());
	
	// Quesry for user on db
	$params = array (
		'username'	    => isset($body->username)?$body->username:'',
		'password'	    => isset($body->password)?$body->password:'',
	);
	$user = makeQuery( "SELECT * FROM user WHERE username=:username AND password=:password LIMIT 1" , $params, false);
	
	if($user == null) {
		$app->response()->status(401);
		echo '{ "message": "Not Authorized" }';
		return;
	}

	$user->password = null;

	// Get roles
	$params = array (
		'user_id'	    => $user->_id
	);
	$roles = makeQuery( "SELECT * FROM roles WHERE _user=:user_id" , $params, false);
	$user->roles = [];
	foreach ($roles as $role) {
		array_push($user->roles, $role->role);
	}

	// Generate JWT token
	global $jwt_secret_key;
	$token = JWT::encode($user, $jwt_secret_key);
	$user->token = $token;

	// Print result
	echo json_encode($user);

});

// Verify Token action
$app->post('/verifyToken',	function () use ($app) {
	$body = json_decode($app->request()->getBody());
	
	// Token non provided
	if (!isset($body->token)) {
		$app->response()->status(403);
		echo '{ "success": false, "message": "No token provided"}';
		return;
	}
	
	// Decode token
	$token = $body->token;
	global $jwt_secret_key;

	try {
		$decoded = JWT::decode($token, $jwt_secret_key, array('HS256'));
	} catch (Exception $err) {
		// Token not valid
		$app->response()->status(401);
		echo '{ "success": false, "message": "Failed to authenticate token"}';
		return;
	}

	// Token valid
	echo json_encode($decoded);

});

?>