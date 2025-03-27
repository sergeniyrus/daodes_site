<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        // Log information about the request
        //Log::info('Получен запрос на загрузку файла', [
        //     'file' => $request->file('upload') ? $request->file('upload')->getClientOriginalName() : 'Файл отсутствует'
        // ]);

        if ($request->hasFile('upload')) {
            try {
                $file = $request->file('upload');
                $fileContent = file_get_contents($file->getRealPath());

                // Log information about the file
                //Log::info('Загрузка файла', [
                //     'fileName' => $file->getClientOriginalName(),
                //     'fileSize' => $file->getSize(),
                //     'mimeType' => $file->getMimeType()
                // ]);

                // Initialize Guzzle client for IPFS
                $client = new Client([
                    'base_uri' => 'https://daodes.space'
                ]);

                // Send file to IPFS
                $response = $client->request('POST', '/api/v0/add', [
                    'multipart' => [
                        [
                            'name'     => 'file',
                            'contents' => $fileContent,
                            'filename' => $file->getClientOriginalName(),
                        ]
                    ]
                ]);

                // Log the IPFS response
                //Log::info('Ответ от IPFS', [
                    'statusCode' => $response->getStatusCode(),
                    'responseBody' => $response->getBody()->getContents()
                ]);

                // Parse the response and extract the image link
                $data = json_decode($response->getBody(), true);

                if (isset($data['Hash'])) {
                    $ipfsUrl = 'https://daodes.space/ipfs/' . $data['Hash'];

                    // Log successful completion
                    //Log::info('Файл успешно загружен', [
                        'fileUrl' => $ipfsUrl
                    ]);

                    // Return response in the format expected by CKEditor
                    return response()->json([
                        'uploaded' => 1,
                        'fileName' => $file->getClientOriginalName(),
                        'url' => $ipfsUrl
                    ]);
                } else {
                    // Log error if Hash is not found in the response
                    Log::error('Ошибка IPFS: Hash отсутствует в ответе', [
                        'response' => $data
                    ]);
                    return response()->json(['error' => __('message.ipfs_error_no_hash')], 500);
                }
            } catch (\Exception $e) {
                // Log error during upload
                Log::error('Ошибка загрузки в IPFS: ' . $e->getMessage());
                return response()->json(['error' => __('message.ipfs_upload_error')], 500);
            }
        }

        // Log if no file was uploaded
        Log::error('Файл не загружен', ['request' => $request->all()]);

        return response()->json(['error' => __('message.file_not_uploaded')], 400);
    }
}
