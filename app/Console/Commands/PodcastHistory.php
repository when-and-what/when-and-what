<?php

namespace App\Console\Commands;

use App\Jobs\PodcastUserHistory;
use App\Models\Account;
use App\Models\AccountUser;
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
    protected $description = 'Find all users who have authenticated with pocketcasts and update their history';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $account = Account::where('slug', 'pocketcasts')->first();

        $userAccounts = AccountUser::with('user')
            ->where('account_id', $account->id)
            ->get();
        foreach ($userAccounts as $userAccount) {
            PodcastUserHistory::dispatch($userAccount);
        }

        return Command::SUCCESS;
    }
}
