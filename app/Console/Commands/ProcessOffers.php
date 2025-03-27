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
    //Log::info('offers:process started at ' . Carbon::now('UTC'));

    try {
        $now = Carbon::now('UTC'); // Текущее время в UTC
        //Log::info('Current Time: ' . $now);

        $offers = DB::table('offers')
            ->where('state', 2)
            ->whereNotNull('start_vote')
            ->get();

        //Log::info('Found ' . count($offers) . ' offers to process.');

        foreach ($offers as $offer) {
            $offer_id = $offer->id;
            
            // Преобразуем время начала голосования в UTC
            $startVoteTime = Carbon::parse($offer->start_vote)->setTimezone('UTC');
           // Log::info("Start Time: $startVoteTime");

            // Разница в секундах
            $secondsElapsed = $startVoteTime->diffInSeconds($now);
            $hoursElapsed = round($secondsElapsed / 3600, 2); // Округляем до 2 знаков

           // Log::info("Offer ID $offer_id started at {$offer->start_vote}, Hours elapsed: $hoursElapsed");

            $totalUsers = DB::table('users')->count() - 2;
            $counts = DB::table('offer_votes')
                ->select(DB::raw('vote, COUNT(*) as count'))
                ->where('offer_id', $offer_id)
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
            $za_percentage = $totalUsers > 0 ? ($za * 100) / $totalUsers : 0;
            $no_percentage = $totalUsers > 0 ? ($no * 100) / $totalUsers : 0;
            $za_percentage = round($za_percentage, 2);
            $no_percentage = round($no_percentage, 2);

           // Log::info("Processing offer ID $offer_id. Hours elapsed: $hoursElapsed, Za: $za_percentage%, No: $no_percentage%.");

            if ($hoursElapsed > 72 || $za_percentage > 30 || $no_percentage > 30) {
                if ($za_percentage > 30) {
                    $newState = 4; // Принято "за"
                    //Log::info("Offer ID $offer_id state updated to 4 (Za > 50%).");
                } elseif ($no_percentage > 30) {
                    $newState = 5; // Принято "против"
                    //Log::info("Offer ID $offer_id state updated to 5 (No > 50%).");
                } elseif ($hoursElapsed > 72) {
                    $newState = $za_percentage >= $no_percentage ? 4 : 5;
                    //Log::info("Offer ID $offer_id state updated due to time limit (72 hours passed).");
                }

                if (isset($newState)) {
                    DB::table('offers')
                        ->where('id', $offer_id)
                        ->update(['state' => $newState]);

                    $pdfFilePath = $this->createPdf($offer, $za_percentage, $no_percentage, $totalUsers - $totalVotes);

                    if ($pdfFilePath) {
                        $ipfsUrl = $this->uploadToIPFS($pdfFilePath, $offer->id);
                        if ($ipfsUrl) {
                            //Log::info("PDF uploaded to IPFS for offer ID $offer_id: $ipfsUrl");
                        } else {
                            Log::error("PDF upload to IPFS failed for offer ID $offer_id.");
                        }
                    } else {
                        Log::error("PDF not created for offer ID $offer_id.");
                    }
                }
            } else {
               // Log::info("Offer ID $offer_id not processed. Za percentage: $za_percentage%, No percentage: $no_percentage%.");
            }
        }
    } catch (\Exception $e) {
        Log::error('Error processing offers: ' . $e->getMessage());
    }

   // Log::info('offers:process finished at ' . Carbon::now('UTC'));
}


    private function createPdf($offer, $za_percentage, $no_percentage, $vozd_percentage)
    {
        try {
            $comments = DB::table('comments_offers')
                ->where('offer_id', $offer->id)
                ->get()
                ->map(function ($comment) {
                    return (object)[
                        'author' => DB::table('users')->where('id', $comment->user_id)->value('name'),
                        'content' => $comment->text
                    ];
                });

            $votes = DB::table('offer_votes')
                ->where('offer_id', $offer->id)
                ->get()
                ->map(function ($vote) {
                    return (object)[
                        'user' => DB::table('users')->where('id', $vote->user_id)->value('name'),
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
            Log::error('Error creating PDF for offer ID ' . $offer->id . ': ' . $e->getMessage());
            return null;
        }
    }

    private function uploadToIPFS($filePath, $offerId)
{
    $client = new Client(['base_uri' => 'https://daodes.space']);

    if (!file_exists($filePath)) {
      //  Log::error("PDF file not found for offer ID $offerId");
        return null;
    }

    try {
        // Отправка файла на IPFS
        $response = $client->request('POST', '/api/v0/add', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => basename($filePath)
                ]
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data['Hash'])) {
            $ipfsUrl = 'https://daodes.space/ipfs/' . $data['Hash'];
         //   Log::info("PDF uploaded to IPFS for offer ID $offerId: $ipfsUrl");

            // Обновляем поле pdf_ipfs_cid в таблице offers
            DB::table('offers')
                ->where('id', $offerId)
                ->update(['pdf_ipfs_cid' => $data['Hash']]);

            return $ipfsUrl;
        } else {
          //  Log::error("Error uploading PDF to IPFS for offer ID $offerId");
            return null;
        }
    } catch (\Exception $e) {
       // Log::error("Error during IPFS upload for offer ID $offerId: " . $e->getMessage());
        return null;
    }
}
}
