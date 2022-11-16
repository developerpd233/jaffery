<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Contest;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\WinnerMail;
use Illuminate\Support\Facades\Mail;

class ContestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contest:cron';

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
     * @return int
     */
    public function handle()
    {
        Log::info("Cron is working fine!");

        $contests = Contest::where('status', 1)->where('end_date','<',now()->format('Y-m-d'))->get();
        $participants = collect();

        foreach ($contests as $key => $contest) {
            
            $feature_ids = DB::table('votes')
                ->select(DB::raw('count(*) as vote_count, participant_id'))
                ->where('contest_id', '=', $contest->id)
                ->groupBy('participant_id')
                ->orderByDesc('vote_count')
                ->pluck('participant_id')
                ->take(1)->toArray();

            if (count($feature_ids) > 0) {
                $tempStr = implode(',', $feature_ids);
                $featured = Participant::whereIn('id', $feature_ids)
                            ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
                            ->take(1)
                            ->first();

                $participants->push($featured);
            }
        }  

        foreach ($participants as $key => $participant) {

            $a_date = $participant->contest->end_date;
            $sd = now()->format('Y-m-d 00:00:00');
            $ed = date("Y-m-t 00:00:10", strtotime(now()->format('Y-m-d 00:00:00')));

            Log::info('Participant_id:'.$participant->id);

            try 
            {
                Mail::to($participant->user->email)->send(new WinnerMail($participant->user,$participant,$participant->contest));

                if ($participant->contest->type_id == "1") 
                {
                    $a_date = $participant->contest->end_date;

                    $contest = $participant->contest->update([
                        // 'status' => 2,
                        'start_date' => now()->format('Y-m-d 00:00:00'),
                        'end_date' => date("Y-m-t 00:00:10", strtotime(now()->format('Y-m-d 00:00:00'))),
                        'amount' => 0
                    ]);
                }
                elseif ($participant->contest->type_id == "2") 
                {
                    $b_date = $participant->contest->end_date;

                    $contest = $participant->contest->update([
                        // 'status' => 2,
                        'start_date' => now()->format('Y-m-d 00:00:00'),
                        'end_date' => date("Y-12-31 00:00:10", strtotime(now()->format('Y-m-d 00:00:00'))),
                        'amount' => 0
                    ]);
                }
                elseif ($participant->contest->type_id == "3") 
                {
                    $c_date = $participant->contest->end_date;

                    $contest = $participant->contest->update([
                        // 'status' => 2,
                        'start_date' => now()->format('Y-m-d 00:00:00'),
                        'end_date' => date("Y-m-t 00:00:10", strtotime(now()->format('Y-m-d 00:00:00'))),
                        'amount' => 0
                    ]);
                }
                
                $user = $participant->user;
                $user->amount += $participant->amount;
                $user->save();

                Log::info('Winner Email Done:'.$participant->id);
            
            }
            catch (\Throwable $th) {
                
            }
        }

        foreach ($contests as $key => $contest) {

            foreach ($contest->participants as $key => $participant) {

                $participant = $participant->update([
                    'status' => 2,
                ]);
            }
        }

        Log::info('Job completed');
    }
}