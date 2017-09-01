<?php

use GuzzleHttp\Client,
    GuzzleHttp\HandlerStack,
    GuzzleHttp\Handler\CurlHandler,
    GuzzleHttp\Subscriber\Oauth\Oauth1;

$app->post('/api/Context.io/getFolderMessages', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['consumerKey','consumerSecret','accountId','sourceLabel','folder']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = ['consumerKey'=>'consumer_key','consumerSecret'=>'consumer_secret','accountId'=>'id','sourceLabel'=>'label','folder'=>'folder'];
    $optionalParams = ['includeThreadSize'=>'include_thread_size','includeBody'=>'include_body','includeHeaders'=>'include_headers','includeFlags'=>'include_flags','bodyType'=>'body_type','flagSeen'=>'flag_seen','limit'=>'limit','offset'=>'offset'];
    $bodyParams = [
       'query' => ['consumer_secret','consumer_key','id','label','include_thread_size','include_body','include_headers','include_flags','body_type','flag_seen','limit','offset']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);



    if(!empty($data['include_body']) && $data['include_body'] == 'true')
    {
        $data['include_body'] = 1;
    }

    if(!empty($data['include_headers']) )
    {
        if($data['include_headers'] == 'true')
        {
            $data['include_headers'] = 1;
        } else {
            $data['include_headers'] = 0;
        }

    }

    if(!empty($data['include_flags']) && $data['include_flags'] == 'true')
    {
        $data['include_flags'] = 1;
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

    if(!empty($data['include_thread_size']) && $data['include_thread_size'] == 'true')
    {
        $data['include_thread_size'] = 1;
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


    $query_str = "https://api.context.io/2.0/accounts/{$data['id']}/sources/{$data['label']}/folders/{$data['folder']}/messages";

    $requestParams = \Models\Params::createRequestBody($data, $bodyParams);
    $requestParams['headers'] = [];

    try {
        $resp = $client->get($query_str, $requestParams);
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