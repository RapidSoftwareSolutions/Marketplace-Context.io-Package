<?php

use GuzzleHttp\Client,
    GuzzleHttp\HandlerStack,
    GuzzleHttp\Handler\CurlHandler,
    GuzzleHttp\Subscriber\Oauth\Oauth1;

$app->post('/api/ContextIO/updateApplicationLevelWebhook', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['consumerKey','consumerSecret','webhookId']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = ['consumerKey'=>'consumer_key','consumerSecret'=>'consumer_secret','webhookId'=>'webhook_id'];
    $optionalParams = ['callbackUrl'=>'callback_url','failureNotifyUrl'=>'failure_notif_url','active'=>'active','filterTo'=>'filter_to','filterFrom'=>'filter_from','filterEmailList'=>'filter_cc','filterSubject'=>'filter_subject','filterThread'=>'filter_thread','filterFileName'=>'filter_file_name','filterFolderAdded'=>'filter_folder_added','filterFolderRemoved'=>'filter_folder_removed','filterToDomains'=>'filter_to_domain','filterFolderRemoved'=>'filter_folder_removed','includeBody'=>'include_body','bodyType'=>'body_type','includeHeader'=>'include_header','receiveAllChanges'=>'receive_all_changes','receiveDrafts'=>'receive_drafts','receiveHistorical'=>'receive_historical'];
    $bodyParams = [
       'form_params' => ['consumer_secret','consumer_key','callback_url','failure_notif_url','active','filter_to','filter_from','filterEmailList','filter_subject','filter_thread','filter_file_name','filter_folder_added','filter_folder_removed','filter_to_domain','include_body','body_type','include_header','receive_all_changes','receive_drafts','webhook_id']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);

    if(!empty($data['filter_to']))
    {
        $data['filter_to'] = implode(',',$data['filter_to']);
    }

    if(!empty($data['filter_from']))
    {
        $data['filter_from'] = implode(',',$data['filter_from']);
    }

    if(!empty($data['filter_cc']))
    {
        $data['filter_cc'] = implode(',',$data['filter_cc']);
    }

    if(!empty($data['filter_to_domain']))
    {
        $data['filter_to_domain'] = implode(',',$data['filter_to_domain']);
    }


    if(!empty($data['receiveDrafts']) && $data['receiveDrafts'] == 'true')
    {
        $data['receiveDrafts'] = 1;
    }

    if(!empty($data['include_body']) && $data['include_body'] == 'true')
    {
        $data['include_body'] = 1;
    }


    if(!empty($data['include_header']) && $data['include_header'] == 'true')
    {
        $data['include_header'] = 1;
    }

    if(!empty($data['receive_all_changes']) && $data['receive_all_changes'] == 'true')
    {
        $data['receive_all_changes'] = 1;
    }

    if(!empty($data['active']))
    {
        if($data['active'] == 'true')
        {
            $data['active'] = 1;
        } else {
            $data['active'] = 0;
        }

    }

    if(!empty($data['receive_historical']) && $data['receive_historical'] == 'true')
    {
        $data['receive_historical'] = 1;
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

    $query_str = "https://api.context.io/2.0/webhooks/{$data['webhook_id']}";

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