<?php

use GuzzleHttp\Client,
    GuzzleHttp\HandlerStack,
    GuzzleHttp\Handler\CurlHandler,
    GuzzleHttp\Subscriber\Oauth\Oauth1;

$app->post('/api/ContextIO/getAllMessages', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['consumerKey','consumerSecret','accountId']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = ['consumerKey'=>'consumer_key','consumerSecret'=>'consumer_secret','accountId'=>'id'];
    $optionalParams = ['subject'=>'subject','emails'=>'email','to'=>'to','from'=>'from','emailOfContactCC'=>'cc','emailOfContactBCC'=>'bcc','folder'=>'folder','source'=>'source','fileName'=>'file_name','fileSizeMin'=>'file_size_min','fileSizeMax'=>'file_size_max','dateBefore'=>'date_before','dateAfter'=>'date_after','indexedBefore'=>'indexed_before','indexedAfter'=>'indexed_after','includeThreadSize'=>'include_thread_size','includeBody'=>'include_body','includeHeaders'=>'include_headers','includeFlags'=>'include_flags','bodyType'=>'body_type','includeSource'=>'include_source','sortOrder'=>'sort_order','limit'=>'limit','offset'=>'offset'];
    $bodyParams = [
       'query' => ['consumer_secret','consumer_key','id','subject','email','to','from','cc','bcc','folder','source','file_name','file_size_min','file_size_max','date_before','date_after','indexed_before','indexed_after','include_thread_size','include_body','include_headers','include_flags','body_type','include_source','sort_order','limit','offset']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);

    if(!empty($data['email']))
    {
        $data['email'] = implode(',',$data['email']);
    }

    if(!empty($data['date_before']))
    {
        $data['date_before'] = strtotime($data['date_before']);
    }

    if(!empty($data['date_after']))
    {
        $data['date_after'] = strtotime($data['date_after']);
    }

    if(!empty($data['indexed_before']))
    {
        $data['indexed_before'] = strtotime($data['indexed_before']);
    }

    if(!empty($data['indexed_after']))
    {
        $data['indexed_after'] = strtotime($data['indexed_after']);
    }

    if(!empty($data['include_thread_size']) && $data['include_thread_size'] == 'true')
    {
        $data['include_thread_size'] = 1;
    }

    if(!empty($data['include_body']) && $data['include_body'] == 'true')
    {
        $data['include_body'] = 1;
    }

    if(!empty($data['include_headers']) && $data['include_headers'] != 'raw')
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

    if(!empty($data['include_source']) && $data['include_source'] == 'true')
    {
        $data['include_source'] = 1;
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

    $query_str = "https://api.context.io/2.0/accounts/{$data['id']}/messages";

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
