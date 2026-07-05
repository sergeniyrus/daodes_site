<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AIController extends Controller
{
    public function patches()
    {
        return response()->json(
            json_decode(file_get_contents(storage_path('ai_patch_queue.json')), true)
        );
    }

    public function approve(Request $request)
    {
        $id = $request->id;

        $queue = json_decode(file_get_contents(storage_path('ai_patch_queue.json')), true);

        foreach ($queue as $item) {
            if ($item['id'] === $id && $item['status'] === 'pending') {

                file_put_contents(
                    base_path('ai_last_patch.txt'),
                    json_encode($item['patch'], JSON_PRETTY_PRINT)
                );

                exec("php /var/www/daodes/ai/engine/run_apply.php " . base_path('ai_last_patch.txt'));

                $item['status'] = 'approved';
            }
        }

        file_put_contents(storage_path('ai_patch_queue.json'), json_encode($queue));

        return response()->json(['status' => 'approved']);
    }

    public function reject(Request $request)
    {
        $id = $request->id;

        $queue = json_decode(file_get_contents(storage_path('ai_patch_queue.json')), true);

        foreach ($queue as &$item) {
            if ($item['id'] === $id) {
                $item['status'] = 'rejected';
            }
        }

        file_put_contents(storage_path('ai_patch_queue.json'), json_encode($queue));

        return response()->json(['status' => 'rejected']);
    }
}