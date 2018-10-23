<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BookingStatusModel;

class CheckBookingState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:state';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //
        $input['booking_status'] = 'You';
        BookingStatusModel::create($input);
    }
}
