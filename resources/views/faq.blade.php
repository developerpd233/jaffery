  
@extends('layouts.app')
@section('meta_title', 'FAQ')
@section('meta_description', 'FAQ')
@section('page_title', 'FAQ')
{{-- @section('page_banner', $page->image, 1200, 211) --}}

@section('content')
{{-- @if (View::hasSection('page_banner')) style="background-image: url(@yield('page_banner'))" @endif --}}

<section class="faq">
    <div class="container-fluid">
        <h2>FAQ</h2>
        <ul>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>Logo; ARE YOU ONE IN A MILLION? What does this mean?</div>
                <div class="faq-ans">Goal; to have one million entrants register and participate in the annual contest;   looking to achieve by years end.  The person who has the most votes by years end on December 31st 2022 at 12.59; that person, will be awarded the one million dollar prize.</div>
            </li>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>What if you do not achieve your goal, “one million participants”?</div>
                <div class="faq-ans">The kitty builds by the number of registered participants.  The registered Participant at years end with the most votes will be awarded the amount accumulated in the kitty.</div>
            </li>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>How do you enter the annual contests?</div>
                <div class="faq-ans">
                    HOW TO ENTER First, Signup to our website.  Then, take a selfie or as many as you want to get the best shot, but keep in mind you can only enter 5 into the contest.  Next, upload your carefully crafted shots with the best angles into your profile and hit enter.   Don’t forget to enter a description of yourself.  Don’t be afraid to say you’re the best!  I know I am!  HAVE FUN!  Share this on your Social Media Platforms.  Have your Family, Friends,, and Followers vote for you. Then you simply vote.  You won’t be officially entered into the contest until you have at least 1 vote on your photo
                </div>
            </li>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>Once I sign up and register then what?</div>
                <div class="faq-ans">
                    Show off of course.  Share your picture and videos on social media and get others to vote for you.  Use those Instagram followers, friends on Facebook, Twitter family, and those charismatic people on You Tuber.
                </div>
            </li>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>Is this the only Contests?</div>
                <div class="faq-ans">
                    No! We have seven additional contests that change on a Monthly basis.
                </div>
            </li>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>What type of contests are offered?</div>
                <div class="faq-ans">
                    We have a Video contest that runs throughout the year, but changes on a Monthly basis.   Each video loaded is active for the Month and will expire at Months end when the contests expires.  Participants can upload a different video every Month if they choose.  We offer six additional contests that we will update the audience each Month of what our next Month’s contest will be.
                </div>
            </li>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>What can be expected for the six Contests?</div>
                <div class="faq-ans">
                    Best Example; the Next Month coming up, we will advise the audience (Social media Platforms, Facebook, Instagram, Twitter and YouTube), that we will be having a contest.   Men of McDonald’s.  We will put time contest starts and finishes.  We would like to see all of the Men whom work for McDonald Companies participate.  
                    We can have Baby Girl & Baby Boy Contests, Young Men, & Young women Contests. <br> We want to bring variety.  We can also run a special contests, (Men of McDonald’s) based on audience suggestions.   These contests change Monthly.
                </div>
            </li>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>What type of prizes are offered?</div>
                <div class="faq-ans">
                    One prize per contest.  No runners up, no participation award. Each monthly contest generates a monetary prize based on the number of participants and number of votes entered.  This monetary prize will be awarded to the participant with the most number of votes.
                </div>
            </li>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>How do I find the Contests?</div>
                <div class="faq-ans">
                    Home Landing page at top look for Contest. Join Contests button.
                </div>
            </li>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>How do I join a Contests?</div>
                <div class="faq-ans">
                    Home Landing page at top Center look for Button “Join The Contest”,
                    Or at top look for Button Contests. Either option will get you to where you are able to participate in the contests.<br><br>
                    Join The Contest, Menu will pop up and give you option to check circle, this will take you to that contest.<br><br> 
                    Home Landing page at top look for Contest. Contests button. Use this and it will open all Contest viewing page. Each contest has a button View Contests. Use this button to open the contest you wish to participate in. Here you will see a join Contest Button. If you wish to peruse and vote just tap on any picture.
                </div>
            </li>
            <li>
                <div class="faq-ques"><i class="fa fa-minus"></i><i class="fa fa-plus"></i></i>How do I vote for a Contestant?</div>
                <div class="faq-ans">
                    Tap on any picture and this will open the current contestants’ info and the vote now button on lower section of page. Use vote Now button and proceed.
                </div>
            </li>
        </ul>
    </div>
</section>


@endsection

@section('script')

<script>
jQuery(function () {
    jQuery(".faq li:not(:first-child) .faq-ans").css("display", "none");

    jQuery(".faq li:nth-child(1) .faq-ques").addClass("open");

    jQuery(".faq li .faq-ques").click(function () {
        jQuery(".open").not(this).removeClass("open").next().slideUp(300);
    jQuery(this).toggleClass("open").next().slideToggle(300);
    });
});
</script>

@endsection