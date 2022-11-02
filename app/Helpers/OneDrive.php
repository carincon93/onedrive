<?php

namespace App\Helpers;

use Illuminate\Http\Request as HttpRequest;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class OneDrive
{
    public static function generateToken()
    {
        $client = new Client();

        try {
            $headers = [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ];
            $options = [
                'form_params' => [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => 'da21a621-9bc6-4991-8203-f6a980a2d68c',
                    'client_secret' => 'KOr8Q~ArXhIrw.QyM4AoeNvWV1ycxBXJ.-_RLcxJ',
                    'scope'         => 'https://graph.microsoft.com/.default'
                ]
            ];
            $request = new Request('POST', 'https://login.microsoftonline.com/ee18a2d0-f67e-4e86-885a-2facdb945f4b/oauth2/v2.0/token', $headers);
            $response = $client->sendAsync($request, $options)->wait();
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['access_token'];
        } catch (ClientException $e) {
            $response = $e->getResponse();
            Log::debug($e->getMessage());

            abort($response->getStatusCode());
        }
    }

    public static function createRootFolder($folderName)
    {
        $client = new Client();
        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . static::generateToken()
            ];
            $body = '{
                "name": "' . $folderName . '",
                "folder": {}
            }';
            $request  = new Request('POST', 'https://graph.microsoft.com/v1.0/drive/root/children', $headers, $body);
            $response = $client->sendAsync($request)->wait();
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['id'];
        } catch (ClientException $e) {
            $response = $e->getResponse();
            Log::debug($e->getMessage());

            abort($response->getStatusCode());
        }
    }

    public static function createFolder($folderId, $folderName)
    {
        $client = new Client();
        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . static::generateToken()
            ];
            $body = '{
                "name": "' . $folderName . '",
                "folder": {}
            }';
            $request  = new Request('POST', 'https://graph.microsoft.com/v1.0/drives/b!pp2p_nj1V0-lB2HEww69cItqtLyjxexDgCmSkisg3lDVZ2CfbeRDQoNbTKegIrEW/items/' . $folderId . '/children', $headers, $body);
            $response = $client->sendAsync($request)->wait();
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['id'];
        } catch (ClientException $e) {
            $response = $e->getResponse();
            Log::debug($e->getMessage());

            abort($response->getStatusCode());
        }
    }

    public static function uploadFile($onedriveId, $file, $fileName)
    {
        try {
            $fileHandler    = fopen($file, 'r');
            $fileData       = fread($fileHandler, filesize($file));

            $client = new Client();
            $headers = [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . static::generateToken()
            ];
            $body = $fileData;
            $request = new Request('PUT', 'https://graph.microsoft.com/v1.0/drives/b!pp2p_nj1V0-lB2HEww69cItqtLyjxexDgCmSkisg3lDVZ2CfbeRDQoNbTKegIrEW/items/' . $onedriveId . ':/' . $fileName . ':/content', $headers, $body);
            $response = $client->sendAsync($request)->wait();
            $response = json_decode($response->getBody()->getContents(), true);

            return $response['id'];
        } catch (\Throwable  $e) {
            Log::debug($e->getMessage());
            abort($e->getStatusCode());
        }
    }

    public static function deleteFile($archivo)
    {
        $client = new Client();

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . static::generateToken()
            ];
            $request = new Request('DELETE', 'https://graph.microsoft.com/v1.0/drives/b!pp2p_nj1V0-lB2HEww69cItqtLyjxexDgCmSkisg3lDVZ2CfbeRDQoNbTKegIrEW/items/' . $archivo->onedrive_id, $headers);
            $client->sendAsync($request)->wait();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            Log::debug($e->getMessage());

            abort($response->getStatusCode());
        }
    }

    public static function deleteFolder($carpeta)
    {
        $client = new Client();

        try {
            $headers = [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . static::generateToken()
            ];
            $request = new Request('DELETE', 'https://graph.microsoft.com/v1.0/drives/b!pp2p_nj1V0-lB2HEww69cItqtLyjxexDgCmSkisg3lDVZ2CfbeRDQoNbTKegIrEW/items/' . $carpeta->onedrive_id, $headers);
            $client->sendAsync($request)->wait();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            Log::debug($e->getMessage());

            abort($response->getStatusCode());
        }
    }

    public static function downloadFile($archivo)
    {
        $client = new Client();

        try {
            $headers = [
                'Authorization' => 'Bearer ' . static::generateToken(),
            ];
            $headers = [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . static::generateToken()
            ];
            $request = new Request('GET', 'https://graph.microsoft.com/v1.0/drives/b!pp2p_nj1V0-lB2HEww69cItqtLyjxexDgCmSkisg3lDVZ2CfbeRDQoNbTKegIrEW/items/' . $archivo->onedrive_id . '/content', $headers);
            $response = $client->sendAsync($request)->wait();

            // $contenType = str_contains($fileName, 'pdf') ? 'Content-type: application/pdf' : 'Content-type: force-download';

            header('Content-type: force-download');
            header('Content-Disposition: inline; filename="' . $archivo->nombre . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            return $response->getBody();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            Log::debug($e->getMessage());

            abort($response->getStatusCode());
        }
    }
}
