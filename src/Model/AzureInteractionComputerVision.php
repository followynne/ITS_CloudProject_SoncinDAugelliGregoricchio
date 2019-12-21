<?php

declare(strict_types=1);
namespace AzureClasses;
chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';
require_once 'HTTP/Request2.php';

use Dotenv\Dotenv;
/**
 * This class access and operates with Azure Computer Vision API.
 */
class AzureInteractionComputerVision {

  private $ocpApimSubscriptionKey;

  /**
   * Constructor made via PHP-DI configuration.
   */
  function __construct(Dotenv $dotenv){
    $dotenv->load();
    $this->ocpApimSubscriptionKey = $_ENV['COMPUTERVISION_KEY'];
  }

  /**
   * The function gets a @binaryimage and makes an HTTP Request2
   * to Computer Vision API, to analyze an image and
   * return en tags for it.
   */
function getTagsFromComputerVisionAnalysis($binaryimage){

    // You must use the same location in your REST call as you used to obtain
    // your subscription keys!
    $uriBase = 'https://francecentral.api.cognitive.microsoft.com/vision/v2.0/';
    $imagebinarydata = $binaryimage;

    $request = new \Http_Request2($uriBase . '/analyze');

    $url = $request->getUrl();
    $headers = array(
        'Content-Type' => 'application/octet-stream',
        'Ocp-Apim-Subscription-Key' => $this->ocpApimSubscriptionKey
    );
    $request->setHeader($headers);
    $parameters = array(
        'visualFeatures' => 'Tags',
        'details' => '',
        'language' => 'en'
    );
    $url->setQueryVariables($parameters);
    $request->setMethod(\HTTP_Request2::METHOD_POST);
    $request->setBody($imagebinarydata);

    try
    {
        $response = $request->send();
        return json_decode($response->getBody());
    }
    catch (HttpException $ex)
    {
        echo "<pre>" . $ex . "</pre>";
    }
  }
}
