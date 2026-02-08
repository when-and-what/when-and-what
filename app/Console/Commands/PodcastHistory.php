<?php

namespace App\Console\Commands;

use App\Jobs\PodcastUserHistory;
use App\Models\Account;
use App\Models\AccountUser;
use App\Models\User;
use Illuminate\Console\Command;

class PodcastHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'podcasts:history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Find all users who have authenticated with pocketcasts and update their history if it's midnight";

    /**
     * TODO: This is not an efficient way to grab each user at midnight
     ** but should scale for a while... so this sounds like a problem for the future 🙃.
     */
    public function handle()
    {
        $account = Account::where('slug', 'pocketcasts')->first();

        $userAccounts = AccountUser::with('user')
            ->where('account_id', $account->id)
            ->get();
        foreach ($userAccounts as $userAccount) {
            $now = now()->shiftTimezone($userAccount->user->timezone);
            if ($now->format('G') === 0) {
                PodcastUserHistory::dispatch($userAccount);
            }
        }

        return Command::SUCCESS;
    }
}
