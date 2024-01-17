<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Wallet;
use App\Models\Announcement;
use App\Models\Ticket;
use App\Models\Merchant;


use Illuminate\Support\Facades\Log;
use Config;
use Mail;
use App\Services\VPayService;
use App\Services\WalletService;

class AnnouncementDeactivation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AnnouncementDeactivation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Announcement Deactivation ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $channel = Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/cron/AnnouncementDeactivation/Date')
        ]);

        $now = now();
        $now = date('Y-m-d H:i:s',strtotime($now));
        $message = PHP_EOL.'Current Date '.date('Y-m-d H:i:s').PHP_EOL;
        $announcements = Announcement::where('status',1)->whereNotNull('expired_at')->where('expired_at','<=',$now)->get();
        foreach($announcements as $announcement)
        {
            $message .= 'Announcement ID: '.$announcement->id.' has been expired. '.PHP_EOL;
            $announcement->expired_at = $now;
            $announcement->status = 0;
            $announcement->save();
        }
        
        Log::stack([$channel])->info($message);

        return Command::SUCCESS;
    }
}
