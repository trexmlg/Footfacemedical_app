<?php

use App\Models\Event;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Event::query()->whereNull('start_at')->chunkById(100, function ($events) {
            foreach ($events as $event) {
                $baseStart = $event->start ?: now()->toDateString();
                $baseEnd = $event->end ?: $baseStart;

                $event->update([
                    'start_at' => $baseStart . ' 08:00:00',
                    'end_at' => $baseEnd . ' 09:00:00',
                ]);
            }
        });
    }

    public function down(): void
    {
        // no-op: keep generated datetime values
    }
};
