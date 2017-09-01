<?php

use GuzzleHttp\Client,
    GuzzleHttp\HandlerStack,
    GuzzleHttp\Handler\CurlHandler,
    GuzzleHttp\Subscriber\Oauth\Oauth1;

$app->post('/api/ContextIO/updateMessage', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['consumerKey','consumerSecret','accountId','messageId']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = ['consumerKey'=>'consumer_key','consumerSecret'=>'consumer_secret','accountId'=>'id','messageId'=>'message_id'];
    $optionalParams = ['folderToCopied'=>'dst_folder','sourceLabel'=>'dst_source','move'=>'move','move'=>'move','flagSeen'=>'flag_seen','flagAnswered'=>'flag_answered','flagDeleted'=>'flag_deleted','flagDraft'=>'flag_draft'];
    $bodyParams = [
       'form_params' => ['consumer_secret','consumer_key','id','message_id','dst_folder','dst_source','move','flag_seen','flag_answered','flag_deleted','flag_draft']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);

    if(!empty($data['move']) && $data['move'] == 'true')
    {
        $data['move'] = 1;
    }

    if(!empty($data['flag_seen']))
    {
        if($data['flag_seen'] == 'set')
        {
            $data['flag_seen'] = 1;
        } else {
            $data['flag_seen'] = 0;
        }
    }

    if(!empty($data['flag_answered']))
    {
        if($data['flag_answered'] == 'set')
        {
            $data['flag_answered'] = 1;
        } else {
            $data['flag_answered'] = 0;
        }
    }

    if(!empty($data['flag_flagged']))
    {
        if($data['flag_flagged'] == 'set')
        {
            $data['flag_flagged'] = 1;
        } else {
            $data['flag_flagged'] = 0;
        }
    }

    if(!empty($data['deleted']))
    {
        if($data['flag_deleted'] == 'set')
        {
            $data['flag_deleted'] = 1;
        } else {
            $data['flag_deleted'] = 0;
        }
    }

    if(!empty($data['flag_draft']))
    {
        if($data['flag_draft'] == 'set')
        {
            $data['flag_draft'] = 1;
        } else {
            $data['flag_draft'] = 0;
        }
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

    $query_str = "https://api.context.io/2.0/accounts/{$data['id']}/messages/{$data['message_id']}";

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