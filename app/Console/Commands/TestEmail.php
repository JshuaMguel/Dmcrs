<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestEmail extends Command
{
    protected $signature = 'test:email {email?}';
    protected $description = 'Test email configuration';

    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';
        
        $this->info('Testing email configuration...');
        $this->info('MAIL_MAILER: ' . config('mail.default'));
        $this->info('MAIL_HOST: ' . config('mail.mailers.smtp.host'));
        $this->info('MAIL_PORT: ' . config('mail.mailers.smtp.port'));
        $this->info('MAIL_USERNAME: ' . config('mail.mailers.smtp.username'));
        $this->info('MAIL_ENCRYPTION: ' . config('mail.mailers.smtp.encryption'));
        $this->info('MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
        $this->info('MAIL_FROM_NAME: ' . config('mail.from.name'));
        
        try {
            Mail::raw('This is a test email from DMCRS system.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('DMCRS Test Email')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            $this->info('✅ Test email sent successfully to: ' . $email);
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to send test email: ' . $e->getMessage());
            $this->error('Full error: ' . $e->getTraceAsString());
            return 1;
        }
    }
}