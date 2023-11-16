// ------------------------------------------------------------------------------------------------------------------------
//
//  Description:    OpenAI Whisper API
//  Author:         PIGMIL
//  Website:        https://pigmil.com
//
// ------------------------------------------------------------------------------------------------------------------------

let chunks = [];
let mediaRecorder;

let startButton = document.getElementById("start");
let stopButton = document.getElementById("stop");
let audioForm = document.getElementById("audio_form");


// Initial state
stopButton.style.backgroundColor = "#2b2b3a"; // Gray color
stopButton.disabled = true;

function getRequest(){
  let blob = new Blob(chunks, { type: "audio/webm" });
  chunks = [];
  let audioURL = URL.createObjectURL(blob);
  document.getElementById("audio").src = audioURL;

  // Create a new FormData object.
  var formData = new FormData();

  // Create a blob file object from the blob.
  var file = new File([blob], Math.floor(1000 + Math.random() * 9000)+".webm", {
    type: "audio/webm",
  });

  // Append the audio file to the form data.
  formData.append("audio", file);

  console.log("Sending audio file to server...");

  // Send the audio file to your server.
  fetch("whisper_send_data.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("HTTP error " + response.status);
      }

      return response.text(); // Return the response as text
    })
    .then((data) => {
      // Process the response here
      let whisper_received_data = JSON.parse(data); 
      HandleRequest(whisper_received_data.text);

    })
    .catch(function (error) {
      console.error("Error sending audio file to server:", error);
      return false;
    });

    return false;
}

function HandleRequest(prompt){  

  console.log("Sending transcription to server..."); 
  console.log(prompt);
  document.getElementById("whisper_response_display_area").textContent =
  prompt;   
  
  return false;
}

audioForm.addEventListener("submit", function(e) { 
  e.preventDefault();

  const form = e.currentTarget;
  const formData = new FormData(form); 

  var fileUpload = document.getElementById('fileUpload');
  var fileUploading = document.getElementById('fileUploading');

  fileUpload.style.display = "none";
  fileUploading.style.display = "block";

  console.log("Sending audio file to server...");

  // Send the audio file to your server.
  fetch("whisper_send_data.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("HTTP error " + response.status);
      }

      return response.text(); // Return the response as text
    })
    .then((data) => {
      // Process the response here
      let whisper_received_data = JSON.parse(data); 
      HandleRequest(whisper_received_data.text); 
      fileUpload.style.display = "block";
      fileUploading.style.display = "none";

    })
    .catch(function (error) {
      console.error("Error sending audio file to server:", error); 
      fileUpload.style.display = "block";
      fileUploading.style.display = "none";
      return false;
    });

});

startButton.addEventListener("click", () => {
  navigator.mediaDevices.getUserMedia({ audio: true }).then((stream) => {
    mediaRecorder = new MediaRecorder(stream, { mimeType: "audio/webm" });
    mediaRecorder.start();

    startButton.style.backgroundColor = "#2b2b3a"; // Gray color
    startButton.disabled = true;

    stopButton.style.backgroundColor = "#1ed2f4"; // Main color
    stopButton.disabled = false;

    mediaRecorder.ondataavailable = (e) => {
      chunks.push(e.data);
    };

    mediaRecorder.onstop = (e) => {
      getRequest();
    };

    console.log("Recording started...");
  });
});

stopButton.addEventListener("click", () => {
  if (mediaRecorder) {
    mediaRecorder.stop();
  }
  console.log("Recording stopped...");

  stopButton.style.backgroundColor = "#2b2b3a"; // Gray color
  stopButton.disabled = true;

  startButton.style.backgroundColor = "#1ed2f4"; // Main color
  startButton.disabled = false;
});
