<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        // Логируем информацию о запросе
        Log::info('Received file upload request', [
            'file' => $request->file('upload') ? $request->file('upload')->getClientOriginalName() : 'No file'
        ]);

        if ($request->hasFile('upload')) {
            try {
                $file = $request->file('upload');
                $fileContent = file_get_contents($file->getRealPath());

                // Логируем информацию о файле
              Log::info('Uploading file', [
                    'fileName' => $file->getClientOriginalName(),
                    'fileSize' => $file->getSize(),
                    'mimeType' => $file->getMimeType()
                ]);

                // Инициализация клиента Guzzle для работы с IPFS
                $client = new Client([
                    'base_uri' => 'https://daodes.space'
                ]);

                // Отправка файла на IPFS
                $response = $client->request('POST', '/api/v0/add', [
                    'multipart' => [
                        [
                            'name'     => 'file',
                            'contents' => $fileContent,
                            'filename' => $file->getClientOriginalName(),
                        ]
                    ]
                ]);

                // Логируем ответ от IPFS
               Log::info('IPFS Response', [
                    'statusCode' => $response->getStatusCode(),
                    'responseBody' => $response->getBody()->getContents()
                ]);

                // Парсим ответ и извлекаем ссылку на изображение
                $data = json_decode($response->getBody(), true);

                if (isset($data['Hash'])) {
                    $ipfsUrl = 'https://daodes.space/ipfs/' . $data['Hash'];

                    // Логируем успешное завершение
                   Log::info('File uploaded successfully', [
                        'fileUrl' => $ipfsUrl
                    ]);

                    // Возвращаем ответ в формате, ожидаемом CKEditor
                    return response()->json([
                        'uploaded' => 1,
                        'fileName' => $file->getClientOriginalName(),
                        'url' => $ipfsUrl
                    ]);
                } else {
                    // Логируем ошибку, если Hash не найден в ответе
                    Log::error('IPFS error: No Hash in response', [
                        'response' => $data
                    ]);
                    return response()->json(['error' => 'No valid response from IPFS'], 500);
                }
            } catch (\Exception $e) {
                // Логируем ошибку при загрузке
               Log::error('IPFS upload error: ' . $e->getMessage());
                return response()->json(['error' => 'File not uploaded due to server error'], 500);
            }
        }

        // Логируем, если файл не был отправлен
        Log::error('File not uploaded', ['request' => $request->all()]);

        return response()->json(['error' => 'File not uploaded'], 400);
    }
}
