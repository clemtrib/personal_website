<?php

namespace App\Console\Commands;

use App\Models\Timeslot;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 *
 * @example php artisan app:timeslots-command
 */
class TimeslotsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:timeslots-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Timeslots table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        echo '=== Clean Timeslots table ===' . PHP_EOL;
        $i = 0;
        $timeslots = Timeslot::where('end_datetime', '<', Carbon::now())
            ->whereNull('summary')
            ->whereNull('recipient_fullname')
            ->whereNull('recipient_email')
            ->get();

        foreach ($timeslots as $timeslot) {
            $timeslot->delete();
            $i++;
        }
        echo sprintf('--> %d entrées supprimées <---' . PHP_EOL, $i);
    }
}
