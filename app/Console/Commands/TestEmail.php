<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email=warfaraz@gmail.com}';

    protected $description = 'Test email configuration';

    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Sending test email to: {$email}...");

        try {
            \Illuminate\Support\Facades\Mail::raw('This is a test email from Laravel.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email');
            });

            $this->info('Email sent successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
        }
    }
}
