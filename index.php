<?php

require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();


$app = new \Slim\Slim();

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

/**
 * 取得随机信息(DP-001-001) 
 */
$app->get('/random', function() use($app) {
            
            try {
                $app->response()->header('Content-Type', 'application/json');
                $req = $app->request();

                session_start();
                $sessid = session_id();


                $rand_number1 = rand(100000, 999999);
                $number1 = (string) $rand_number1;
                
                $rand_number2 = rand(100000, 999999);
                $number2 = (string) $rand_number2;
                
                $rand_number3 = rand(1000, 9999);
                $number3 = (string) $rand_number3;
                
                $_SESSION['MAC_KEY'] = $number1 . $number2 . $number3;
                $returnValue['macKey'] = $number1 . $number2 . $number3;
                
                $returnValue['version'] = $versionCode;
                $returnValue['url'] = $versionUrl;
                $returnValue['versionContent'] = $versionContent;
                
                $returnValue['token'] = $cripCommon->getToken();
                $returnValue['sessionID'] = $sessid;
                
                echo urldecode(json_encode($returnValue));
            } catch (ResourceNotFoundException $e) {
                $app->response()->status(404);
                $returnArr['errMsgNo'] = '401004';
                $returnArr['errMsgContent'] = $errMsgList['401004'];
                echo json_encode($returnArr);
            } catch (\Slim\Exception\Stop $e) {
                $app->response()->status(304);
            } catch (Exception $e) {
                $app->response()->status(400);
                $app->response()->header('X-Status-Reason', $e->getMessage());
                $returnArr['errMsgNo'] = '400001';
                $returnArr['errMsgContent'] = $errMsgList['400001'];
                echo json_encode($returnArr);
            }
        });
       
$app->run();
