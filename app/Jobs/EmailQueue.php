<?php
namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;
use DB;
use Auth;
class EmailQueue implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $tries = 1;
    protected $request;
    protected $email_list;
    protected $subject;


    /**
      * @var integer get current logged in user ID.
    */
    private $user_id; 
    public function __construct($request,$email_list,$subject)
    {
      $this->request = $request;
      $this->email_list = $email_list;
      $this->subject = $subject;
      if (isset(Auth::user()->id)) {
        $this->user_id = Auth::user()->id;
    }
}
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

       $subject =  $this->subject;
       $email_list = $this->email_list;
     $send_mail = Mail::send('mail.mail', 
        ['request' => $this->request, 
        'changed_by' => Auth::user()->name], 
        function ($message) use ($subject, $email_list) {
        foreach ($email_list as $key => $to):
            $message->to($to['email'])->subject($subject);
               // $message->to('supriya@choicetechlab.com')->subject($subject);
            $message->from('noreply@choicetechlab.com', 'UIDAI');
        endforeach;
            //   $message->to($email_list)->subject($subject);

    });

 }



}