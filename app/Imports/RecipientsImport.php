<?php

namespace App\Imports;

use App\Models\Recipient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class RecipientsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public int $imported = 0;
    public int $skipped = 0;

    /**
     * Преобразует строку CSV/XLSX в модель Recipient.
     * Считает количество добавленных и пропущенных записей.
     */
    public function model(array $row)
    {
        if (empty($row['email']) || empty($row['name'])) {
            $this->skipped++;
            return null;
        }

        $email = trim($row['email']);

        if (Recipient::where('email', $email)->exists()) {
            $this->skipped++;
            return null;
        }

        $this->imported++;

        return new Recipient([
            'name'  => trim($row['name']),
            'email' => $email,
        ]);
    }
}
