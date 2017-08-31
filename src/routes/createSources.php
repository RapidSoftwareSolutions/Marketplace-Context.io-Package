<?php

use GuzzleHttp\Client,
    GuzzleHttp\HandlerStack,
    GuzzleHttp\Handler\CurlHandler,
    GuzzleHttp\Subscriber\Oauth\Oauth1;

$app->post('/api/Context.io/createSources', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['consumerKey','consumerSecret','accountId','email','username','server','useSsl','port','type']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = ['consumerKey'=>'consumer_key','consumerSecret'=>'consumer_secret','accountId'=>'id','email'=>'email','username'=>'username','server'=>'server','useSsl'=>'use_ssl','port'=>'port','type'=>'type'];
    $optionalParams = ['originIp'=>'origin_ip','syncAllFolders'=>'sync_all_folders','expungeOnDeletedFlag'=>'expunge_on_deleted_flag','password'=>'password','providerRefreshToken'=>'provider_refresh_token','providerConsumerKey'=>'provider_consumer_key','callbackUrl'=>'callback_url','statusCallbackUrl'=>'status_callback_url','rawFileList'=>'raw_file_list'];
    $bodyParams = [
       'query' => ['consumer_secret','consumer_key','id','email','server','username','use_ssl','port','type','sync_all_folders','expunge_on_deleted_flag','provider_refresh_token','password','provider_consumer_key','callback_url','status_callback_url','origin_ip','raw_file_list']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);

    if(!empty($data['sync_all_folders']) && $data['sync_all_folders'] == 'off')
    {
        $data['sync_all_folders'] = 1;
    }

    if(!empty($data['expunge_on_deleted_flag']) && $data['expunge_on_deleted_flag'] == 'on')
    {
        $data['expunge_on_deleted_flag'] = 1;
    }

    if(!empty($data['raw_file_list']) && $data['raw_file_list'] == 'off')
    {
        $data['raw_file_list'] = 1;
    }





    $stack = HandlerStack::create();
    $middleware = new Oauth1([
        'consumer_key' => $data['consumer_key'],
        'consumer_secret' => $data['consumer_secret'],
    ]);

    $stack->push($middleware);

    $client = new Client([
        'handler' => $stack,
        'auth' => 'oauth'
    ]);

    $query_str = "https://api.context.io/2.0/accounts/{$data['id']}/sources";

    $requestParams = \Models\Params::createRequestBody($data, $bodyParams);
    $requestParams['headers'] = [];

    try {
        $resp = $client->post($query_str, $requestParams);
        $responseBody = $resp->getBody()->getContents();

        if(in_array($resp->getStatusCode(), ['200', '201', '202', '203', '204'])) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
            if(empty($result['contextWrites']['to'])) {
                $result['contextWrites']['to']['status_msg'] = "Api return no results";
            }
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if(empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ServerException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if(empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ConnectException $exception) {

        $responseBody = $exception->getResponse()->getBody(true);
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';

    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);

});