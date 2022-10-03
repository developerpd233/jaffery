@extends('layouts.app')
@section('meta_title', 'Participate')
@section('meta_description', 'Participate')
@section('page_title', $contest->title)

@section('css')

<link type="text/css" rel="stylesheet" href="{{ asset('image-uploader/dist/image-uploader.min.css') }}">

@endsection

@section('content')

@include('flash_message')

<section class="participant3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                @if (!$exist)
                    <div class="alert alert-danger alert-block mt-3">
                        <button type="button" class="close" data-dismiss="alert">×</button>    
                        <strong>This contest not active now.</strong>
                    </div>
                @elseif (participantExist($contest->id))
                    <div class="alert alert-danger alert-block mt-3">
                        <button type="button" class="close" data-dismiss="alert">×</button>    
                        <strong>You already participated in this contest.</strong>
                    </div>
                @endif

                <form method="POST" action="{{ route('participant.store') }}" enctype="multipart/form-data">
                @csrf

                    <input type="hidden" name="contest_id" value="{{ $contest->id }}">

                    <div class="main_partispnt">
                        <h2>Participants Details</h2>
                        <div class="partispnt_detail">
                            <label>Name</label>
                            <input type="name" name="name" value="{{ old('name') }}" required> 
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror     
                        </div>
                        <div class="partispnt_detail">
                            <label>Description</label>
                            <textarea name="description" required>{{ old('description') }}</textarea> 
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="partispnt_detail">
                            <label>Details</label>
                            <textarea name="detail" required>{{ old('detail') }}</textarea> 
                            @error('detail')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="uploder">
                             <div class="uploder-img uploadimg1 feat-img abc">
                                <h3>Featured Image</h3>
                                <div class="img">
                                    <div class="input-field">
                                        <div class="input-image" style="padding-top: .5rem;"></div>
                                    </div>
                                    <!-- <img src="{{ url('img/Group05185.png') }}">
                                    <input type="file" name="image" accept="image/*" required> -->
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <input type="hidden" name="feature_snap" value="">
                                </div>
                             </div>
                            
                            @if($contest->type->slug == 'video')
                                <div class="uploder-img video-img1">
                                    <h3>Upload Video</h3>
                                    <div class="img">
                                        <img src="{{ url('img/Group05185.png') }}">
                                        <input type="file" id="video-input" name="video" accept="video/*" required>
                                        @error('video')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <i class="fa fa-times d-none cross" aria-hidden="true"></i>
                                    <video id="videoUpload" class="d-none" width="320" height="240" controls>
                                        <source src="">
                                    </video>
                                </div>
                            @else
                                <div class="uploder-img uploadimg2">
                                    <h3>Multiple Images</h3>
                                    <div class="img">
                                        <div class="input-field">
                                            <div class="input-images" style="padding-top: .5rem;"></div>
                                        </div>
                                        <!-- <img src="{{ url('img/Group05185.png') }}">
                                        <input type="file" name="images[]" accept="image/*" required multiple> -->
                                        @error('images')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div id="other_images"></div>
                                 </div>
                            @endif
                            
                         </div>
                         <div class="part-btn">
                            
                            <!-- @if ( $exist && !participantExist($contest->id))
                                <button type="submit">Submit</button>
                            @endif -->

                            <button type="submit">Submit</button>
                            @if($contest->type->slug != 'video')
                                <button type="button" id="show-camera">Camera</button>
                            @else
                                <button type="button" id="show-video-camera">Camera</button>
                            @endif
                         </div>
                    </div>
                    <!-- <div class="input-field">
                        <label class="active">Photo</label>
                        <div class="input-image" style="padding-top: .5rem;"></div>
                    </div>
                    <div class="input-field">
                        <label class="active">Photos</label>
                        <div class="input-images" style="padding-top: .5rem;"></div>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
</section>

<div id="screenshot" style="text-align:center;" class="d-none">
  <div class="vid-img">   
    <video class="videostream" autoplay style="width:400px;height:230px;"></video>
    <img id="screenshot-img" style=" width: 400px;height: 226px;object-fit: contain;margin-top: 3px;">
  </div>  
  <div class="btns">
    <p><button class="capture-button d-none">Capture video</button></p>
    <p><button id="screenshot-button">Take screenshot</button></p>
    <p><button id="set-featured-button">Set Featured Image</button></p>
    @if($contest->type->slug != 'video')
        <p><button id="set-others-button">Set Other Image</button></p>
    @endif
    
  </div>
</div>



@if($contest->type->slug == 'video')

<div id="video-section" class="d-none">
    <div class="left">
        <button id="startButton" class="button">
            Start
        </button>
        <h2>Preview</h2>
        <video id="preview" width="160" height="120" autoplay muted></video>
    </div>
    <div class="right">
      <button id="stopButton" class="button">
        Stop
      </button>
      <h2>Recording</h2>
      <video id="recording" width="160" height="120" controls></video>
      <button id="downloadButton" class="button">
        Download
      </button>
    </div>
    <div class="bottom">
      <pre id="log"></pre>
    </div>
</div>

@endif

@endsection

@section('script')

<script>
    
$('body').on('click','#set-featured-button',function(){

    var snap = $('#screenshot-img').attr('src');
    var featured_img = $('.uploadimg1 .uploaded');

    var count = $('.uploadimg1 .uploaded .uploaded-image').length;

    if (count < 1) 
    {
        var html = `<div class="uploaded-image" data-index="0"><img src="${snap}"></div>`;

        featured_img.empty();
        featured_img.append(html);
        $('input[name="feature_snap"]').val(snap);
        $('.uploadimg1 .upload-text').css('display','none');
    } 
    else {
        alert('Maximum 1 pictures allow!');
    }
});

$('body').on('click','#set-others-button',function(){

    var snap = $('#screenshot-img').attr('src');
    var others_img = $('.uploadimg2 .uploaded');
    var count = $('.uploadimg2 .uploaded .uploaded-image').length;

    if (count < 5) 
    {
        var html = `<div class="uploaded-image" data-index="${count}"><img src="${snap}"></div>`;

        others_img.append(html);
        $('#other_images').append(`<input type="hidden" name="others_snap[]" value="${snap}">`);
        $('input[name="feature_snap"]').val(snap);
        $('.uploadimg2 .upload-text').css('display','none');
    } 
    else {
        alert('Maximum 5 pictures allow!');
    }
});

$('body').on('click','#show-camera',function(){

    $('#screenshot').removeClass('d-none');        

    function hasGetUserMedia() {
      return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
    }
    if (hasGetUserMedia()) {
      // Good to go!
    } else {
      alert("getUserMedia() is not supported by your browser");
    }

    /////////////////
    const hdConstraints = {
      video: { width: { min: 1280 }, height: { min: 720 } },
    };

    navigator.mediaDevices.getUserMedia(hdConstraints).then((stream) => {
      video.srcObject = stream;
    });

    const captureVideoButton = document.querySelector(
      "#screenshot .capture-button"
    );
    const screenshotButton = document.querySelector("#screenshot-button");
    const img = document.querySelector("#screenshot img");
    const video = document.querySelector("#screenshot video");

    const canvas = document.createElement("canvas");

    //////////////////

    captureVideoButton.onclick = function () {
      navigator.mediaDevices
        .getUserMedia(hdConstraints)
        .then(handleSuccess)
        .catch(handleError);
    };

    screenshotButton.onclick = video.onclick = function () {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext("2d").drawImage(video, 0, 0);
      // Other browsers will fall back to image/png
      img.src = canvas.toDataURL("image/webp");
    };

    function handleSuccess(stream) {
      screenshotButton.disabled = false;
      video.srcObject = stream;
    }
    
});

//video camera//

$('body').on('click','#show-video-camera',function(){

    $('#screenshot').removeClass('d-none');
    $('#video-section').removeClass('d-none');        

    function hasGetUserMedia() {
      return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
    }
    if (hasGetUserMedia()) {
      // Good to go!
    } else {
      alert("getUserMedia() is not supported by your browser");
    }

    /////////////////
    const hdConstraints = {
      video: { width: { min: 1280 }, height: { min: 720 } },
    };

    navigator.mediaDevices.getUserMedia(hdConstraints).then((stream) => {
      video.srcObject = stream;
    });

    //const video = document.querySelector("#preview");

    const captureVideoButton = document.querySelector(
      "#screenshot .capture-button"
    );
    const screenshotButton = document.querySelector("#screenshot-button");
    const img = document.querySelector("#screenshot img");
    const video = document.querySelector("#screenshot video");

    const canvas = document.createElement("canvas");

    //////////////////

    captureVideoButton.onclick = function () {
      navigator.mediaDevices
        .getUserMedia(constraints)
        .then(handleSuccess)
        .catch(handleError);
    };

    screenshotButton.onclick = video.onclick = function () {
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext("2d").drawImage(video, 0, 0);
      // Other browsers will fall back to image/png
      img.src = canvas.toDataURL("image/webp");
    };

    function handleSuccess(stream) {
      screenshotButton.disabled = false;
      video.srcObject = stream;
    }

    /////////// VIDEO SECTION ///////////////

    let preview = document.getElementById("preview");
    let recording = document.getElementById("recording");
    let startButton = document.getElementById("startButton");
    let stopButton = document.getElementById("stopButton");
    let downloadButton = document.getElementById("downloadButton");
    let logElement = document.getElementById("log");

    let recordingTimeMS = 20000;

    function log(msg) {
      logElement.innerHTML += msg + "\n";
    }

    function wait(delayInMS) {
      return new Promise(resolve => setTimeout(resolve, delayInMS));
    }

    function startRecording(stream, lengthInMS) {
      let recorder = new MediaRecorder(stream);
      let data = [];

      recorder.ondataavailable = event => data.push(event.data);
      recorder.start();
      //log(recorder.state + " for " + (lengthInMS/20000) + " seconds...");
      log("Allow recording for 20 seconds...");

      let stopped = new Promise((resolve, reject) => {
        recorder.onstop = resolve;
        recorder.onerror = event => reject(event.name);
      });

      let recorded = wait(lengthInMS).then(
        () => recorder.state == "recording" && recorder.stop()
      );

      return Promise.all([
        stopped,
        recorded
      ])
      .then(() => data);
    }

    function stop(stream) {
      stream.getTracks().forEach(track => track.stop());
    }

    startButton.addEventListener("click", function() {
      navigator.mediaDevices.getUserMedia({
        video: true,
        audio: true
      }).then(stream => {
        console.log(stream);
        preview.srcObject = stream;
        downloadButton.href = stream;
        preview.captureStream = preview.captureStream || preview.mozCaptureStream;
        return new Promise(resolve => preview.onplaying = resolve);
      }).then(() => startRecording(preview.captureStream(), recordingTimeMS))
      .then (recordedChunks => {
        console.log(recordedChunks);
        let recordedBlob = new Blob(recordedChunks, { type: "video/webm" });
        console.log(recordedBlob);
        recording.src = URL.createObjectURL(recordedBlob);
        downloadButton.href = recording.src;
        downloadButton.download = "RecordedVideo.webm";

        log("Successfully recorded " + recordedBlob.size + " bytes of " +
            recordedBlob.type + " media.");
      })
      .catch(log);
    }, false);

    stopButton.addEventListener("click", function() {
      stop(preview.srcObject);
    }, false);


    
});

</script>


<!-- include jQuery library -->
<script defer type="text/javascript" src="{{ asset('image-uploader/dist/image-uploader.min.js') }}"></script>

<script>
    jQuery(document).ready(function(){
        jQuery('.input-image').imageUploader(
        {
            //preloaded: preloaded,
            imagesInputName: 'image',
            preloadedInputName: 'old',
            maxSize: 5 * 1024 * 1024,
            maxFiles: 1
        });
    });
    jQuery(document).ready(function(){
        jQuery('.input-images').imageUploader(
        {
            //preloaded: preloaded,
            imagesInputName: 'images',
            preloadedInputName: 'old',
            maxSize: 25 * 1024 * 1024,
            maxFiles: 5
        });
    });
</script>

<script>
$("#video-input").on('change',function(event){
    let file = event.target.files[0];
    let blobURL = URL.createObjectURL(file);
    $("#videoUpload").attr('src',blobURL);
    $("#videoUpload").removeClass('d-none');
    $(".cross").removeClass('d-none');
});

$(".cross").on('click',function(event){
    $("#videoUpload").addClass('d-none');
    $(".cross").addClass('d-none');
});
</script>

@endsection