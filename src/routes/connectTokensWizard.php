<?php


use GuzzleHttp\Client,
    GuzzleHttp\HandlerStack,
    GuzzleHttp\Handler\CurlHandler,
    GuzzleHttp\Subscriber\Oauth\Oauth1;


$app->post('/api/ContextIO/connectTokensWizard', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['consumerKey','consumerSecret','callbackUrl']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = ['consumerKey'=>'consumer_key','consumerSecret'=>'consumer_secret','callbackUrl'=>'callback_url'];
    $optionalParams = ['email'=>'email','firstName'=>'first_name','lastName'=>'last_name','sourceCallbackUrl'=>'source_callback_url','sourceExpungeOnDeletedFlag'=>'source_expunge_on_deleted_flag','sourceSyncAllFolders'=>'source_sync_all_folders','sourceSyncFolders'=>'source_sync_folders','sourceRawFileList'=>'source_raw_file_list','statusCallbackUrl'=>'status_callback_url'];
    $bodyParams = [
       'query' => ['consumer_secret','consumer_key','callback_url','email','first_name','last_name','source_callback_url','source_expunge_on_deleted_flag','source_sync_all_folders','source_sync_folders','source_raw_file_list','status_callback_url']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);

    if(!empty($data['source_expunge_on_deleted_flag']) && $data['source_expunge_on_deleted_flag'] == 'on')
    {
        $data['source_expunge_on_deleted_flag'] = 1;
    }

    if(!empty($data['source_sync_all_folders']) && $data['source_sync_all_folders'] == 'off')
    {
        $data['source_sync_all_folders'] = 1;
    }

    if(!empty($data['source_raw_file_list']) && $data['source_raw_file_list'] == 'off')
    {
        $data['source_raw_file_list'] = 1;
    }

    //source_raw_file_list


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
    $query_str = "https://api.context.io/2.0/connect_tokens";

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