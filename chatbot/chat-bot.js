$(function () {
  var INDEX = 0;
  var recognition;
  // var uniqueSessionId;
  // var dialogflowUrl = "https://api.dialogflow.com/v1/";
  // var dialogflowAccessToken = "***********REPLACE**********";
  sessionStorage.setItem('session_id', Math.random());
  // var URL = window.location.href;
  // var URL = 'https://aspacei.net/dios padre'

  // var getParqueinUrl = function (url) {
  //     return url.slice(20, url.length);
  // }

  // NUEVO CAMBIO DE PRUEBA
  var initializeSession = function () {
    $(".chat-logs").empty();
    uniqueSessionId = getUniqueChatSessionId();
    enableInput();
    $("#loading").show();
    if (window.location.href.includes("parque_detalle")) {
      talkToDialogFlowApi($("#id_parques").val());
    } else {
      talkToDialogFlowApi("Hola");
    }
  };

  function sendChatMessage(message) {
    $("#loading").show();
    talkToDialogFlowApi(message);
    generateMessage(message, 'user');
  }

  function generateMessage(msg, type) {
    INDEX++;
    var str = "";
    str += "<div id='cm-msg-" + INDEX + "' class=\"chat-msg " + type + "\">";
    str += "          <span class=\"msg-avatar\">";
    if (type === 'bot') {
      str += "            <img src=\"chatbot/chatbot/b_logo.png\">";
    } else {
      str += "            <img src=\"chatbot/chatbot/user-logo.png\">";
    }
    str += "          <\/span>";
    str += "          <div class=\"cm-msg-text\">";
    str += msg;
    str += "          <\/div>";
    str += "        <\/div>";
    $(".chat-logs").append(str);
    $("#cm-msg-" + INDEX).hide().fadeIn(300);
    if (type === 'user') {
      $("#chat-input").val('');
    }
    $(".chat-logs").stop().animate({
      scrollTop: $(".chat-logs")[0].scrollHeight
    }, 1000);
  }

  // Hola soy ASPI ! ¿Sobre cuál parque te gustaría saber información?
  // Hola soy ASPI! ¿Te gustaría conocer información del algún parque? Dime que parque te interesa

  function talkToDialogFlowApi(message) {
    // let hostname = window.location.hostname;
    $.ajax({
      type: "POST",
      url: `https://aspacei.net/chatbot/chatbot/tokenAccess.php?query=${message}&session=${sessionStorage.getItem('session_id')}`,
      success: dialogFlowSuccessResponse,
      error: dialogFlowErrorResponse
    });
  }

  var dialogFlowSuccessResponse = function (response) {
    $("#loading").hide();
    enableInput();
    generateMessage(response, 'bot');

  };

  var dialogFlowErrorResponse = function (data) {
    $("#loading").hide();
    disableInput();
    generateMessage(data.status.errorType, 'bot');
  };

  var enableInput = function () {
    $("#chat-input").focus();
    $("#chatBotForm").children().prop("disabled", false);
    $('#micSpan').show();
  };

  var disableInput = function () {
    $("#chatBotForm").children().prop("disabled", true);
    $('#micSpan').hide();
  };

  $(document).delegate(".chat-btn", "click", function () {
    var msg = $(this).attr("chat-value");
    enableInput();

    talkToDialogFlowApi(msg);
    console.log(msg)
    generateMessage(msg, 'user');
  });

  $('#tip-close').click(function () {
    $('.tool_tip').remove();
  });

  $("#chat-circle, #tip-tool").click(function () {
    $('.tool_tip').remove();
    $("#chat-circle").toggle('scale');
    $(".chat-box").toggle('scale');
    $(".chat-logs").stop().animate({
      scrollTop: $(".chat-logs")[0].scrollHeight
    }, 500);
  });

  $(".chat-box-toggle").click(function () {
    $("#chat-circle").toggle('scale');
    $(".chat-box").toggle('scale');
  });

  $("#chat-submit").click(function (e) {
    e.preventDefault();
    var msg = $("#chat-input").val();
    if (msg.trim() === '') {
      return false;
    }

    sendChatMessage(msg);
  });

  $("#refresh").click(function () {
    initializeSession();
  });

  $("#mic").click(function (event) {
    switchRecognition();
  });

  function stopRecognition() {
    if (recognition) {
      recognition.stop();
      recognition = null;
    }

    updateRec();
  }

  function startRecognition() {
    recognition = new webkitSpeechRecognition();
    recognition.onstart = function (event) {
      updateRec();
    };
    recognition.onresult = function (event) {
      var text = "";
      for (var i = event.resultIndex; i < event.results.length; ++i) {
        text += event.results[i][0].transcript;
      }
      console.log(text);
      sendChatMessage(text);
      stopRecognition();
    };
    recognition.onend = function () {
      stopRecognition();
    };
    recognition.lang = "en-US";
    recognition.start();
  }

  function switchRecognition() {
    if (recognition) {
      stopRecognition();
    } else {
      startRecognition();
    }
  }

  function updateRec() {
    $("#mic").text(recognition ? "stop" : "mic");
  }

  function getUniqueChatSessionId() {
    var s4 = function () {
      return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
    };
    return s4() + s4() + "-" + s4() + "-" + s4() + "-" +
      s4() + "-" + s4() + s4() + s4();
  }

  initializeSession();
});
