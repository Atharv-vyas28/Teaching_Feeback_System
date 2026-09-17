<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\FeedbackSession;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Server-side automatic feedback closure. Students are also rejected by
// FeedbackSession::isAcceptingResponses() if a request arrives after deadline.
Schedule::call(function () {
    FeedbackSession::expired()->update([
        'status' => 'closed',
        'closed_at' => now(),
    ]);
})->everyMinute()->name('feedback-session-status-sync')->withoutOverlapping();
