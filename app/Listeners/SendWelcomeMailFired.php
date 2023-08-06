<?php

namespace App\Listeners;

use App\Events\SendWelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Company;
use Mail;

class SendWelcomeMailFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendWelcomeMail  $event
     * @return void
     */
    public function handle(SendWelcomeMail $event)
    {
        $company = Company::find($event->companyId)->toArray();

        \Mail::raw('Sending emails with Mailgun and Laravel by using Xepos', function($message)  use ($company)
        {
            $message->subject('New Company Registered With Name : ( '. $company['name'] . ' )');
            $message->from('no-reply@xepos.com', 'Xepos');
            $message->to('ahsan.nawaz@beviable.com');
        });
    }
}
