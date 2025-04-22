<?php

namespace App\Helpers;

use App\Models\POSTerminal\PosTerminal;
use Exception;

class PosNameGenerator
{
    /**
     * @throws Exception
     */
    public static function generate(): string
    {
        $segment = "POS-";
        $maxRetries = 100; // Maximum attempts to generate a unique code
        $attempts = 0;

        do {
            $last_inserted_pos = PosTerminal::query()->latest('id')->withTrashed()->first();
            $code = '';

            if ($last_inserted_pos) {
                $last_code = str_replace($segment, '', $last_inserted_pos->name);
                $new_code = (int) $last_code + rand(1, 10);
                $code = $segment . str_pad($new_code, 4, '0', STR_PAD_LEFT);
            } else {
                $code = $segment . '0001';
            }

            $exists = PosTerminal::query()->where('name', $code)->withTrashed()->exists();
            $attempts++;
        } while ($exists && $attempts < $maxRetries);

        if ($attempts >= $maxRetries) {
            throw new Exception("Unable to generate a unique POS Terminal code after $maxRetries attempts.");
        }

        return $code;
    }

}
