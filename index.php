<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenAI Whisper API</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        .recording-controls {
            background-color: #2b2b3a;
            border-radius: 10px;
            padding: 15px;
            color: #1ed2f4;
            margin-bottom: 20px;
        }

        .recording-controls button {
            background-color: #eafc40;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            margin-right: 10px;
            color: #254558;
        }

        .recording-controls audio {
            display: block;
            margin-top: 10px;
        }

        .row {
            display: flex;
        }

        .col-4 {
            flex: 1;
        }

        .col-8 {
            flex: 2;
            padding-left: 20px;
        }

        .whisper_response_display_area {
            padding: 15px;
            border: 1px solid #1ed2f4;
            border-radius: 5px;
            color: #f4f5f6;
            background-color: #254558;
        }
    </style>
</head>

<body>
    <div class="container mt-4"> 

        <div class="recording-controls">
            <div class="row">
                <div class="col-4">
                    <button id="start">Start recording</button>
                    <button id="stop" disabled>Stop recording</button>
                    <audio id="audio" controls class="d-none"></audio>
                    <form class="mt-4" enctype="multipart/form-data" id="audio_form" method="post" action="whisper_send_data.php">
                    <input type="file" name="audio" accept=".ogg,.flac,.mp3,.wav" required/>
                    <input type="submit" id="fileUpload" name="submit"/>
                    <span id="fileUploading" class="mt-2" style="display: none;">Loading..</span>
                    </form>
                </div>
                <div class="col-8">
                    <p class="whisper_response_display_area" id="whisper_response_display_area"></p>
                </div>  
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lamejs/1.2.0/lame.min.js"></script>
    <script src="input.js"></script>
</body>

</html>