<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\PendingTasksMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPendingTasksEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:pending-tasks';
    protected $description = 'Send daily email to users with pending tasks';

    public function handle()
    {
        $users = User::with(['tasks' => function($query) {
            $query->where('status_id', 1);
        }])->get();

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->tasks->isNotEmpty()) {
                Mail::to($user->email)->send(new PendingTasksMail($user));
            }
        }
    }
}
