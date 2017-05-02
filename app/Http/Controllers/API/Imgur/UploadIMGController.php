<?php

namespace App\Http\Controllers\API\Imgur;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

class UploadIMGController extends Controller
{
  protected $apps;

  public function __construct()
  {
    $this->apps = [
      [
        'client_id'     => 'c3da86d360cfe3a',
        'client_secret' => '9b094937d94d9e4ff3311b5b6fafe5a31fff902d',
      ],
      [
        'client_id'     => 'c08a2166df6d86a',
        'client_secret' => '6b13e357d8981889971725bbdc9753182d4d5256',
      ],
    ];
  }

  public function uploadURL($url)
  {
    $api_url = 'https://api.imgur.com/3/image.json';
    $app = $this->apps[rand(0, count($this->apps)-1)];

    $client = new Client([
      'http_errors' => false,
      'verify' => false,
    ]);

    try {
      $resIMG = @$client->request('GET', $url, []);

      if ($resIMG->getStatusCode() !== 200)
      {
        return null;
      }
      $img = (string)$resIMG->getBody();
    } catch (RequestException $e) {
      return null;
    }

    if (isset($img) === false)
    {
      return null;
    }

    try {
      $res = @$client->request('POST', $api_url, [
        'headers' => [
          'Authorization' => "Client-ID {$app['client_id']}",
        ],
        'form_params' => [
          'image' => base64_encode($img),
        ],
      ]);
      if ($res->getStatusCode() !== 200)
      {
        return null;
      }
    } catch (RequestException $e) {
      return null;
    }

    $json = json_decode($res->getBody(), true);

    if (isset($json['data']) === false) {
      return null;
    }

    return $json['data']['link'];
  }
}
