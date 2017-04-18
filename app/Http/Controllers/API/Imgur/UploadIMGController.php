<?php

namespace App\Http\Controllers\API\Imgur;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class UploadIMGController extends Controller
{
  protected $apps;

  public function __construct()
  {
    $this->apps[] = [
      'client_id'     => 'c3da86d360cfe3a',
      'client_secret' => '9b094937d94d9e4ff3311b5b6fafe5a31fff902d',
    ];
  }

  public function uploadURL($url)
  {
    $api_url = 'https://api.imgur.com/3/image.json';
    $app = $this->apps[rand(0, count($this->apps)-1)];
    $img = file_get_contents($url);

    $client = new Client([
      'http_errors' => false,
      'headers' => [
        'Authorization' => "Client-ID {$app['client_id']}",
      ],
    ]);

    $res = $client->request('post', $api_url, [
      'form_params' => [
        'image' => base64_encode($img),
      ],
    ]);

    if ($res->getStatusCode() !== 200)
    {
      return null;
    }

    $json = json_decode($res->getBody(), true);

    if (isset($json['data']) === false) {
      return null;
    }

    return $json['data']['link'];
  }
}
