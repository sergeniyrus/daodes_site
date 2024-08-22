<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Dompdf\Dompdf;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ProcessOffers extends Command
{
    protected $signature = 'offers:process';
    protected $description = 'Process offers based on voting results every 1 minute';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Log::info('offers:process started at ' . Carbon::now());

        try {
            $now = Carbon::now();

            // Получаем предложения, которые соответствуют одному из двух условий:
            // 1. Прошло более 72 часов
            // 2. Процент "за" или "против" больше 50%
            $offers = DB::table('offers')
                ->where('state', 2)
                ->whereNotNull('start_vote')
                ->get();

            // Log::info('Found ' . count($offers) . ' offers to process.');

            foreach ($offers as $offer) {
                $id_offer = $offer->id;

                // Получаем текущее время и время начала голосования
                $startVoteTime = Carbon::parse($offer->start_vote);
                $hoursElapsed = $startVoteTime->diffInHours($now);

                // Получаем количество пользователей и голоса
                $totalUsers = DB::table('users')->count() - 2;

                $counts = DB::table('vote_users')
                    ->select(DB::raw('vote, COUNT(*) as count'))
                    ->where('id_offer', $id_offer)
                    ->groupBy('vote')
                    ->get();

                $za = 0;
                $no = 0;

                foreach ($counts as $count) {
                    switch ($count->vote) {
                        case 1:
                            $za = $count->count;
                            break;
                        case 2:
                            $no = $count->count;
                            break;
                    }
                }

                $totalVotes = $za + $no;
                $vozd = $totalUsers - $totalVotes;

                $za_percentage = $totalUsers > 0 ? ($za * 100) / $totalUsers : 0;
                $no_percentage = $totalUsers > 0 ? ($no * 100) / $totalUsers : 0;
                $vozd_percentage = $totalUsers > 0 ? ($vozd * 100) / $totalUsers : 0;

                $za_percentage = round($za_percentage, 2);
                $no_percentage = round($no_percentage, 2);
                $vozd_percentage = round($vozd_percentage, 2);

                // Проверяем условия для обновления состояния предложения
                if ($hoursElapsed > 72 || $za_percentage > 50 || $no_percentage > 50) {
                    if ($za_percentage > 50) {
                        $newState = 4; // Принято "за"
                        // Log::info("Offer ID $id_offer state updated to 4. Za percentage: $za_percentage.");
                    } elseif ($no_percentage > 50) {
                        $newState = 5; // Принято "против"
                        // Log::info("Offer ID $id_offer state updated to 5. No percentage: $no_percentage.");
                    } else {
                        // Если не превышает 50%, но прошло более 72 часов
                        $newState = $za_percentage > $no_percentage ? 4 : 5;
                        // Log::info("Offer ID $id_offer state updated due to 72 hours rule. Za percentage: $za_percentage, No percentage: $no_percentage.");
                    }

                    DB::table('offers')
                        ->where('id', $id_offer)
                        ->update(['state' => $newState]);

                    // Создаем PDF файл
                    $pdfFilePath = $this->createPdf($offer, $za_percentage, $no_percentage, $vozd_percentage);

                    if ($pdfFilePath) {
                        // Загружаем PDF на IPFS
                        $this->uploadToIPFS($pdfFilePath, $offer->id);
                    } else {
                        // Log::error("PDF file not created or not found for offer ID $id_offer");
                    }
                } else {
                    // Log::info("Offer ID $id_offer not processed. Za percentage: $za_percentage, No percentage: $no_percentage.");
                }
            }
        } catch (\Exception $e) {
            // Log::error('Error processing offers: ' . $e->getMessage());
        }

        // Log::info('offers:process finished at ' . Carbon::now());
    }

    private function createPdf($offer, $za_percentage, $no_percentage, $vozd_percentage)
    {
        try {
            // Получение комментариев и голосов
            $comments = DB::table('comments_offers')
                ->where('id_offer', $offer->id)
                ->get()
                ->map(function ($comment) {
                    return (object)[
                        'author' => DB::table('users')->where('id', $comment->id_user)->value('name'),
                        'content' => $comment->text
                    ];
                });

            $votes = DB::table('vote_users')
                ->where('id_offer', $offer->id)
                ->get()
                ->map(function ($vote) {
                    return (object)[
                        'user' => DB::table('users')->where('id', $vote->id_user)->value('name'),
                        'vote' => $vote->vote
                    ];
                });

            $dompdf = new Dompdf();
            $dompdf->loadHtml(view('pdf.offer', [
                'offer' => $offer,
                'za_percentage' => $za_percentage,
                'no_percentage' => $no_percentage,
                'vozd_percentage' => $vozd_percentage,
                'comments' => $comments,
                'votes' => $votes
            ])->render());

            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $pdfFilePath = storage_path('app/public/offer_' . $offer->id . '.pdf');
            file_put_contents($pdfFilePath, $dompdf->output());

            // Log::info("PDF file created at $pdfFilePath for offer ID " . $offer->id);

            return $pdfFilePath;
        } catch (\Exception $e) {
            // Log::error('Error creating PDF for offer ID ' . $offer->id . ': ' . $e->getMessage());
            return null;
        }
    }

    private function uploadToIPFS($file, $offerId)
    {
        try {
            $client = new Client([
                'base_uri' => 'https://daodes.space',
            ]);

            $response = $client->request('POST', '/api/v0/add', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($file, 'r'),
                        'filename' => basename($file)
                    ]
                ]
            ]);

            $body = $response->getBody();
            $data = json_decode($body, true);

            // Сохраняем CID PDF в базе данных
            DB::table('offers')->where('id', $offerId)->update(['pdf_ipfs_cid' => $data['Hash']]);

            // Log::info("PDF uploaded to IPFS for offer ID $offerId with CID " . $data['Hash']);
        } catch (\Exception $e) {
            // Log::error('Error uploading PDF to IPFS for offer ID ' . $offerId . ': ' . $e->getMessage());
        }
    }
}
