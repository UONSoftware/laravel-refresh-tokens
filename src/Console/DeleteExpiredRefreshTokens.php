<?php


namespace UonSoftware\RefreshTokens\Console;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use UonSoftware\RefreshTokens\RefreshToken;

class DeleteExpiredRefreshTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:remove';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes expired refresh tokens from the database';

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
     * @return mixed
     */
    public function handle()
    {
        try {
            DB::beginTransaction();

            $numberOfDeleted = RefreshToken::query()
                ->where('expires', '<=', now())
                ->delete();
            if ($numberOfDeleted >= 0) {
                DB::commit();
            }
            $this->output->writeln('Expired refresh tokens have been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->output->error($e->getMessage());
            return 0;
        }
    }
}