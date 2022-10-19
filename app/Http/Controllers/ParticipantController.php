<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Contest;
use App\Models\Company;
use App\Models\Vote;
use App\Models\Transaction;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\ExpressCheckout;
use IlluminateSupportFacadesHash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Mail\TransactionMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;

class ParticipantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store','participate','vote','edit','update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        
        // if (participantExist($request->contest_id)) {
        //     return back()->with('error' , 'You already participated in this contest!');
        // }

        $response = Storage::makeDirectory('/public/participants/'.date('FY'));

        $this->validate($request, [
            'name' => 'required|max:100',
            'detail' => 'required|max:2000',
            'description' => 'required|max:2000',
            'image' => 'nullable|array|min:1|max:1',
            'images' => 'nullable|array|min:1|max:5',
            'video' => 'required_with:video_thumbnail|mimes:mp4,mov,ogg,qt,ogx,oga,ogv,webm|max:20000',
            'video_thumbnail' => 'nullable|array|min:1|max:1',
        ]);  

        //dd($request);

        $filepath = '';
        $filepaths = '';
        $filepath_video = '';
        $filepath_video_thumbnail = '';
        $filePathArr = [];

        if ($request->has('video_thumbnail')) {
            //dd($request->has('image'));
            $rand = md5(microtime());
            $image       = $request->file('video_thumbnail')[0];
            $filename    = $rand.'.'.$image->getClientOriginalExtension();
            $filepath_video_thumbnail = '/participants/'.date('FY').'/'.$filename;      
            
            $image_resize = Image::make($image);

            $image_resize->save('storage/app/public/participants/'.date('FY').'/'.$filename);
        }

        if ($request->has('video')) {
            
            $rand = md5(microtime());
            $video      = $request->file('video');
            $filename_video    = $rand.'.'.$video->getClientOriginalExtension();
            $filepath_video = '/participants/'.date('FY').'/'.$filename_video;      
            
            $path = 'storage/app/public/participants/'.date('FY').'/';
            $video->move($path, $filename_video);
        }
        elseif($request->video_record != '') {

            $filepath_video = $request->video_record;
        }

        if ($request->has('image')) {
            //dd($request->has('image'));
            $rand = md5(microtime());
            $image       = $request->file('image')[0];
            $filename    = $rand.'.'.$image->getClientOriginalExtension();
            $filepath = '/participants/'.date('FY').'/'.$filename;      
            
            $image_resize = Image::make($image);

            $image_resize->save('storage/app/public/participants/'.date('FY').'/'.$filename);
        }
        elseif($request->feature_snap != '') {
            //dd($request->has('image'));

            $rand = md5(microtime());
            $image = $request->feature_snap;  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $filename = $rand.'.'.'webp';

            $filepath = '/participants/'.date('FY').'/'.$filename;      
            $image_resize = Image::make($image);
            $image_resize->save('storage/app/public/participants/'.date('FY').'/'.$filename);
        }
        
        $files = $request->file('images');

        if($request->hasFile('images'))
        {
            foreach ($files as $key => $file) {
                $rand = md5(microtime());
                $image       = $file;
                $filename    = $rand.'.'.$image->getClientOriginalExtension();
                $filepaths = '/participants/'.date('FY').'/'.$filename;      
                $image_resize = Image::make($image);
                $image_resize->save('storage/app/public/participants/'.date('FY').'/'.$filename);
                array_push( $filePathArr , $filepaths );
            }
        }
        elseif($request->has('others_snap'))
        {
            foreach ($request->others_snap as $key => $file) {
                $rand = md5(microtime());
                $image = $file;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $filename = $rand.'.'.'webp';

                $filepaths = '/participants/'.date('FY').'/'.$filename;      
                $image_resize = Image::make($image);
                $image_resize->save('storage/app/public/participants/'.date('FY').'/'.$filename);
                array_push( $filePathArr , $filepaths );
            }
        }

        //dd($filepath,json_encode($filePathArr));

        $data = $request->all();

        //dd($filepath,$data);
        
        $participant = Participant::create([
            'user_id' => auth()->user()->id,
            'contest_id' => $data['contest_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'details' => $data['detail'],
            'image' => $filepath,
            'images' => json_encode($filePathArr),
            'video' => $filepath_video,
            'video_thumbnail' => $filepath_video_thumbnail,
        ]);


        if ($participant) {

            $vote = Vote::create([
                'user_id' => auth()->user()->id,
                'contest_id' => $data['contest_id'],
                'participant_id' => $participant->id,
                'amount' => 0
            ]);

            $feature_ids = DB::table('votes')
                             ->select(DB::raw('count(*) as vote_count, participant_id'))
                             ->where('contest_id', '=', $data['contest_id'])
                             ->groupBy('participant_id')
                             ->orderByDesc('vote_count')
                             ->pluck('participant_id')
                             ->toArray();

            $tempStr = implode(',', $feature_ids);
            $participants = Participant::whereIn('id', $feature_ids)
                        ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
                        ->get();

            foreach ($participants as $key => $participant) {
                $participant->position = $key + 1;
                $participant->save();
            }

            return redirect(route('participant.show',$participant->id))->with('success' , 'You are participated successfully.');
        }
        
        return back()->with('error' , 'Please try again. Something went wrong!');
    }

    public function upload_video(Request $request)
    {
        //dd($request->file('data'));

        $response = Storage::makeDirectory('/public/participants/'.date('FY'));

        $rand = md5(microtime());
        $video      = $request->file('data');
        $filename_video    = $rand.'.'.'webm';
        $filepath_video = '/participants/'.date('FY').'/'.$filename_video;      
        
        $path = 'storage/app/public/participants/'.date('FY').'/';
        $video->move($path, $filename_video);

        return response()->json(['code'=>200, 'message'=>'Video uploaded successfully','data' => $filepath_video], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function show($slug)
    public function show($id)
    {
        //$participant = Participant::findOrFail($id);
        $participant = Participant::where('slug', $id)->orWhere('id', $id)->firstorfail();

        if ($participant->status == '0') {
            abort(404);
        }

        //dd($participant);

        $comments = $participant->comments()->get();
        $voters = $participant->votes()->latest()->take(5)->get();
        $images = $participant->images;
        $images = json_decode($images, true);
        $exist = Contest::where('id', $participant->contest->id)
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->exists();
        // echo gettype($images);
        //dd($participant->contest);

        return view('voting1', compact('participant','voters','images','exist','comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $participant = Participant::findOrFail($id);
        
        if (auth()->user()->id != $participant->user->id) {
            abort(404);
        }

        $user = $participant->user;
        $contest = $participant->contest;
        $images = $participant->images;
        $images = json_decode($images, true);
        dd($participant->contest);

        return view('participants.edit', compact('participant','contest','images','user'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request);
        
        // if (participantExist($request->contest_id)) {
        //     return back()->with('error' , 'You already participated in this contest!');
        // }

        $response = Storage::makeDirectory('/public/participants/'.date('FY'));

        $this->validate($request, [
            'name' => 'required|max:100',
            'detail' => 'required|max:2000',
            'description' => 'required|max:2000',
            'image' => 'nullable|array|min:1|max:1',
            'images' => 'nullable|array|min:1|max:5',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt,ogx,oga,ogv,webm|max:20000',
        ]);  

        //dd($request->file('image')[0]);

        $filepath = '';
        $filepaths = '';
        $filepath_video = '';
        $filePathArr = [];

        if ($request->has('video')) {
            
            $rand = md5(microtime());
            $video      = $request->file('video');
            $filename_video    = $rand.'.'.$video->getClientOriginalExtension();
            $filepath_video = '/participants/'.date('FY').'/'.$filename_video;      
            
            $path = 'storage/app/public/participants/'.date('FY').'/';
            $video->move($path, $filename_video);
        }

        if ($request->has('image')) {
            //dd($request->has('image'));
            $rand = md5(microtime());
            $image       = $request->file('image')[0];
            $filename    = $rand.'.'.$image->getClientOriginalExtension();
            $filepath = '/participants/'.date('FY').'/'.$filename;      
            
            $image_resize = Image::make($image);

            $image_resize->save('storage/app/public/participants/'.date('FY').'/'.$filename);
        }
        elseif($request->feature_snap != '') {
            //dd($request->has('image'));

            $rand = md5(microtime());
            $image = $request->feature_snap;  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $filename = $rand.'.'.'webp';

            $filepath = '/participants/'.date('FY').'/'.$filename;      
            $image_resize = Image::make($image);
            $image_resize->save('storage/app/public/participants/'.date('FY').'/'.$filename);
        }
        
        $files = $request->file('images');

        if($request->hasFile('images'))
        {
            foreach ($files as $key => $file) {
                $rand = md5(microtime());
                $image       = $file;
                $filename    = $rand.'.'.$image->getClientOriginalExtension();
                $filepaths = '/participants/'.date('FY').'/'.$filename;      
                $image_resize = Image::make($image);
                $image_resize->save('storage/app/public/participants/'.date('FY').'/'.$filename);
                array_push( $filePathArr , $filepaths );
            }
        }
        elseif($request->has('others_snap'))
        {
            foreach ($request->others_snap as $key => $file) {
                $rand = md5(microtime());
                $image = $file;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $filename = $rand.'.'.'webp';

                $filepaths = '/participants/'.date('FY').'/'.$filename;      
                $image_resize = Image::make($image);
                $image_resize->save('storage/app/public/participants/'.date('FY').'/'.$filename);
                array_push( $filePathArr , $filepaths );
            }
        }

        //dd($filepath,json_encode($filePathArr));

        $data = $request->all();

        //dd($filepath,$data);
        
        $participant = Participant::create([
            'user_id' => auth()->user()->id,
            'contest_id' => $data['contest_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'details' => $data['detail'],
            'image' => $filepath,
            'images' => json_encode($filePathArr),
            'video' => $filepath_video,
        ]);

        if ($participant) {
            return redirect(route('participant.show',$participant->id))->with('success' , 'You are participated successfully.');
        }
        
        return back()->with('error' , 'Please try again. Something went wrong!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function participate($id)
    {
        

        $contest = Contest::find($id);

        $exist = Contest::where('id', $id)
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->where('status','=','1')
            ->exists();

        return view('participants.participate',compact('contest','exist'));
    }

    public function vote(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required|max:100',
        //     'detail' => 'required|max:2000',
        // ]);  

        $participant = Participant::findOrFail($request->participant_id);
        $contest = $participant->contest;

        //dd($participant->user->id,auth()->user()->id);

        if (voteExist($contest->id , $participant->id)) {
            return back()->with('error' , 'You already voted on this participant!');
        }

        if ($participant->user->id == auth()->user()->id) {
            return back()->with('error' , 'You are not allowed to vote for yourself!');
        }

        $amount = $request->amount;
        $contest_amount = ($request->amount / 100) * 67;
        $company_amount = $request->amount - $contest_amount;

        $contest->amount = $contest->amount + $contest_amount;
        $contest->save();

        // $contest->amount = $contest->amount + $contest_amount 
        // $contest->save();
        
        // dd('sd');

        $vote = Vote::create([
            'user_id' => auth()->user()->id,
            'contest_id' => $contest->id,
            'participant_id' => $participant->id,
            'amount' => $request->amount
        ]);

        if ($vote) {
            return back()->with('success' , 'You are voted successfully.');
        }
        
        return back()->with('error' , 'Please try again. Something went wrong!');
    }

    public function handlePayment(Request $request)
    {

        $participant = Participant::findOrFail($request->participant_id);
        $contest = $participant->contest;
        $req_info = $request->all();
        if(Session::has('req_info'))
        {
            Session::forget('req_info');
        }
        Session::push('req_info', $req_info);

        // if (voteExist($contest->id , $participant->id)) {
        //     return back()->with('error' , 'You already voted on this participant!');
        // }

        // if ($participant->user->id == auth()->user()->id) {
        //     return back()->with('error' , 'You are not allowed to vote for yourself!');
        // }

        $product = [];
        $product['items'] = [
            [
                'name' => auth()->user()->name,
                'price' => $request->amount,
                'desc'  => $participant->name.' '.$participant->id,
                'qty' => 1
            ]
        ];
  
        $product['invoice_id'] = Str::random(16);
        $product['invoice_description'] = "Order #{$product['invoice_id']} Bill";
        $product['return_url'] = route('success.payment');
        $product['cancel_url'] = route('cancel.payment');
        $product['total'] = $request->amount;
  
        $paypalModule = new ExpressCheckout;
  
        $res = $paypalModule->setExpressCheckout($product);
        $res = $paypalModule->setExpressCheckout($product, true);
  
        return redirect($res['paypal_link']);
    }
   
    public function paymentCancel()
    {
        $info = Session::get('req_info');
        
        return redirect(route('participant.show',$info[0]['participant_id']))->with('error' , 'Your payment has been declined. Please try again!');
    }
  
    public function paymentSuccess(Request $request)
    {
        $info = Session::get('req_info');
        $participant = Participant::findOrFail($info[0]['participant_id']);
        $company = Company::where('key','amount')->first();
        $contest = $participant->contest;

        $paypalModule = new ExpressCheckout;
        $response = $paypalModule->getExpressCheckoutDetails($request->token);

        $amount = $info[0]['amount'];

        if ($contest->type->slug == 'monthly') {
            $kitty = ( $amount / 100 ) * 57;
            $contest_amount = $amount - $kitty;
        }
        elseif ($contest->type->slug == 'video') {
            $kitty = ( $amount / 100 ) * 57;
            $contest_amount = $amount - $kitty;
        } 
        else {
            $count = Vote::where('contest_id',$contest->id)->where('user_id',auth()->user()->id)->count();

            if ($count == 0) {
                $kitty = $amount;
                $contest_amount = 0;
            } else {
                $kitty = 0;
                $contest_amount = $amount;
            }
        }        

        // $amount = $info[0]['amount'];
        // $contest_amount = ($amount / 100) * 67;
        // $kitty = $amount - $contest_amount;
        // $company_amount = $amount - $contest_amount;

        $company_amount = $kitty;

        $contest->amount = $contest->amount + $contest_amount;
        $contest->save();

        if ($company) {
            $company->value = $company->value + $company_amount;
            $company->save();
        }else{
            $company = Company::create([
                'key' => 'amount',
                'value' => $company_amount,
            ]);
        }

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            
            $vote = Vote::create([
                'user_id' => auth()->user()->id,
                'contest_id' => $contest->id,
                'participant_id' => $participant->id,
            ]);

            if ($vote) {

                $transaction = Transaction::create([
                    'user_id' => auth()->user()->id,
                    'status' => strtoupper($response['ACK']),
                    'method' => 'paypal',
                    'amount' => $info[0]['amount'],
                    'kitty' => $kitty,
                    'type' => 'vote',
                    'transaction_token' => $response['TOKEN']
                ]);

                try 
                {
                    
                    Mail::to('theoflas@yahoo.com')->send(new TransactionMail($transaction,auth()->user(),$participant,$participant->contest));

                    Mail::to(auth()->user()->email)->send(new TransactionMail($transaction,auth()->user(),$participant,$participant->contest));
                    
                }
                catch (\Throwable $th) {
                   
                }

                $vote->transaction_id = $transaction->id;
                $vote->save();

                $feature_ids = DB::table('votes')
                                 ->select(DB::raw('count(*) as vote_count, participant_id'))
                                 ->where('contest_id', '=', $contest->id)
                                 ->groupBy('participant_id')
                                 ->orderByDesc('vote_count')
                                 ->pluck('participant_id')
                                 ->toArray();

                $tempStr = implode(',', $feature_ids);
                $participants = Participant::whereIn('id', $feature_ids)
                            ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
                            ->get();

                $contest_amount = $contest->amount;
                $first_prize = $contest_amount;

                // $first_prize = ( $contest_amount / 100 ) * 30;
                // $second_prize = ( $contest_amount / 100 ) * 20;
                // $third_prize = ( $contest_amount / 100 ) * 10;
                // $others_prize = ( $contest_amount / 100 ) * 40;
                // $participants_count = $participants->count();
                // $other_prize = $others_prize / $participants_count;

                foreach ($participants as $key => $participant) {
                    
                    if ($key == 0) {
                        $participant->amount = $first_prize;
                    }
                    else{
                        $participant->amount = 0;   
                    }
                    // else if ($key == 1) {
                    //     $participant->amount = $second_prize;
                    // } 
                    // else if ($key == 2) {
                    //     $participant->amount = $third_prize;
                    // } 
                    // else {
                    //     $participant->amount = $other_prize;
                    // }
                    $participant->position = $key + 1;
                    $participant->save();
                }

                return redirect(route('participant.show',$info[0]['participant_id']))->with('success' , 'You are voted successfully.');
            }
            
            return redirect(route('participant.show',$participant->id))->with('error' , 'Something went wrong in voting!');
        }

        $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'status' => strtoupper($response['ACK']),
            'method' => 'paypal',
            'amount' => $info[0]['amount'],
            'transaction_token' => $response['TOKEN']
        ]);
  
        return redirect(route('participant.show',$participant->id))->with('error' , 'Please try again. Something went wrong!');
    }

    public function contestants()
    {
        $contests = Contest::where('status', 1)
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->get();
        
        return view('participants.contestants',compact('contests'));
    }

    public function winners()
    {
        $participants = collect();
        
        $contests = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'monthly')->orWhere('slug', 'video');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->get();
        
        $annual_contest = Contest::where('status', 1)
            ->whereHas('type', function ($q) {
                $q->where('slug', 'annual');
            })
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->first();

        $month_year = Participant::select(DB::raw("(DATE_FORMAT(created_at, '%m-%Y')) as month_year"))
            ->orderBy('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%Y')"))
            ->pluck('month_year')
            ->toArray();

        $years = Participant::select(DB::raw("(DATE_FORMAT(created_at, '%Y')) as year"))
            ->orderBy('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))
            ->pluck('year')
            ->toArray();

        foreach ($contests as $key => $contest) {

            foreach ($month_year as $key => $my) {
                
                $my = explode('-',$my);
                $m  = $my[0];
                $y  = $my[1];

                $winner = Participant::withCount('votes')
                    ->with('user')
                    ->where('contest_id', $contest->id)
                    ->where('status', 2)
                    ->whereYear('created_at', $y)
                    ->whereMonth('created_at', $m)
                    ->orderByDesc('votes_count')
                    ->take(1)
                    ->first();

                if ($winner) {
                    $participants->push($winner);
                }
            }
        }

        foreach ($years as $key => $y) {
                
            $winner = Participant::withCount('votes')
                ->with('user')
                ->where('contest_id', $annual_contest->id)
                ->where('status', 2)
                ->whereYear('created_at', $y)
                ->orderByDesc('votes_count')
                ->take(1)
                ->first();

            if ($winner) {
                $participants->push($winner);
            }
        }
        
        $participants = $participants->sortBy('created_at',SORT_REGULAR,true);
        
        return view('participants.winners',compact('participants'));
    }

    public function paypalCheck($id)
    {
        $participant = Participant::findOrFail($id);
        $comments = $participant->comments()->get();
        $voters = $participant->votes()->latest()->take(5)->get();
        $images = $participant->images;
        $images = json_decode($images, true);
        $exist = Contest::where('id', $participant->contest->id)
            ->where('start_date','<=',now()->format('Y-m-d'))
            ->where('end_date','>=',now()->format('Y-m-d'))
            ->exists();
        // echo gettype($images);
        //dd($participant->contest);

        return view('paypalCheck', compact('participant','voters','images','exist','comments'));
    }

    public function authorizeDotNet(Request $request)
    {
        //dd($request);
        
        $participant = Participant::findOrFail($request->participant_id);
        $contest = $participant->contest;
        $req_info = $request->all();
        if(Session::has('req_info'))
        {
            Session::forget('req_info');
        }
        Session::push('req_info', $req_info);

        // if (voteExist($contest->id , $participant->id)) {
        //     return back()->with('error' , 'You already voted on this participant!');
        // }

        // if ($participant->user->id == auth()->user()->id) {
        //     return back()->with('error' , 'You are not allowed to vote for yourself!');
        // }

        $product = [];
        $product['items'] = [
            [
                'name' => auth()->user()->name,
                'price' => intval($request->amount),//$request->amount,
                'desc'  => $participant->name.' '.$participant->id,
                'qty' => 1,
                'email' => auth()->user()->email,
            ]
        ];
  
        $product['invoice_id'] = Str::random(16);
        $product['invoice_description'] = "Order #{$product['invoice_id']} Bill";
        $product['return_url'] = route('success.payment');
        $product['cancel_url'] = route('cancel.payment');
        $product['total'] = $request->amount;
  
        // $paypalModule = new ExpressCheckout;
  
        // $res = $paypalModule->setExpressCheckout($product);
        // $res = $paypalModule->setExpressCheckout($product, true);

        //dd($product , $request->session()->get('req_info'));

        return view('paypalPaymentPage', compact('req_info','product'));
    }

    public function authorizeDotNetSuccess(Request $request)
    {
        $info = Session::get('req_info');
        $participant = Participant::findOrFail($info[0]['participant_id']);
        $company = Company::where('key','amount')->first();
        $contest = $participant->contest;

        // $paypalModule = new ExpressCheckout;
        // $response = $paypalModule->getExpressCheckoutDetails($request->token);

        $amount = $info[0]['amount'];

        if ($contest->type->slug == 'monthly') {
            $kitty = ( $amount / 100 ) * 57;
            $contest_amount = $amount - $kitty;
        }
        elseif ($contest->type->slug == 'video') {
            $kitty = ( $amount / 100 ) * 57;
            $contest_amount = $amount - $kitty;
        } 
        else {
            $count = Vote::where('contest_id',$contest->id)->where('user_id',auth()->user()->id)->count();

            if ($count == 0) {
                $kitty = $amount;
                $contest_amount = 0;
            } else {
                $kitty = 0;
                $contest_amount = $amount;
            }
        }        

        $company_amount = $kitty;

        $contest->amount = $contest->amount + $contest_amount;
        $contest->save();

        // dd($contest);

        if ($company) {
            $company->value = $company->value + $company_amount;
            $company->save();
        }else{
            $company = Company::create([
                'key' => 'amount',
                'value' => $company_amount,
            ]);
        }

        
        $vote = Vote::create([
            'user_id' => auth()->user()->id,
            'contest_id' => $contest->id,
            'participant_id' => $participant->id,
        ]);

        if ($vote) {

            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'status' => $request->message,//'COMPLETED',
                'method' => $request->method,//'paypal',
                'amount' => intval($info[0]['amount']),//$info[0]['amount'],
                'kitty' => $kitty,
                'type' => 'vote',
                'transaction_token' => $request->order_id
            ]);

            try 
            {
                
                // Mail::to('theoflas@yahoo.com')->send(new TransactionMail($transaction,auth()->user(),$participant,$participant->contest));

                Mail::to(auth()->user()->email)->send(new TransactionMail($transaction,auth()->user(),$participant,$participant->contest));
                
            }
            catch (\Throwable $th) {
               
            }

            $vote->transaction_id = $transaction->id;
            $vote->save();

            $feature_ids = DB::table('votes')
                             ->select(DB::raw('count(*) as vote_count, participant_id'))
                             ->where('contest_id', '=', $contest->id)
                             ->groupBy('participant_id')
                             ->orderByDesc('vote_count')
                             ->pluck('participant_id')
                             ->toArray();

            $tempStr = implode(',', $feature_ids);
            $participants = Participant::whereIn('id', $feature_ids)
                        ->where('status', 1)
                        ->orderByRaw(DB::raw("FIELD(id, $tempStr)"))
                        ->get();

            $contest_amount = $contest->amount;
            $first_prize = $contest_amount;

            foreach ($participants as $key => $participant) {
                
                if ($key == 0) {
                    $participant->amount = $first_prize;
                }
                else{
                    $participant->amount = 0;   
                }
                
                $participant->position = $key + 1;
                $participant->save();
            }

            Session::flash('success', 'Vote submitted successfully');

            return response()->json(['code'=>200, 'message'=>'Vote submitted successfully','url' => route('participant.show',$info[0]['participant_id'])], 200);
            //return redirect(route('participant.show',$info[0]['participant_id']))->with('success' , 'You are voted successfully.');
        }
        
        Session::flash('error', 'Something went wrong in voting!');
        
        return response()->json(['code'=>404, 'message'=>'Something went wrong in voting!'], 200);
        //return redirect(route('participant.show',$participant->id))->with('error' , 'Something went wrong in voting!');

    }

}