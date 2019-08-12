<?php
    namespace Google\Cloud\Samples\Dialogflow;
    use Google\Cloud\Dialogflow\V2\SessionsClient;
    use Google\Cloud\Dialogflow\V2\TextInput;
    use Google\Cloud\Dialogflow\V2\QueryInput;
    require __DIR__.'/vendor/autoload.php';

    $PROEJCT_ID = "chatbotbasico";
    $QUERY = $_GET['query'];
    $SESSION_ID =  uniqid();  /* SESSION MUST BE A RANDOM NUMBER*/

    function detect_intent_texts($projectId, $text, $sessionId, $languageCode = 'es-ES') {

        /* NEW CLIENT */
        $test = array('credentials' => 'credentials.json');
        $sessionsClient = new SessionsClient($test);
        $session = $sessionsClient->sessionName($projectId, $sessionId);
        // printf('Session path: %s' . PHP_EOL, $session);

        /* CREATE TEXT INPUT*/
        $textInput = new TextInput();
        $textInput->setText($text);
        $textInput->setLanguageCode($languageCode);
 
        /* CREATE QUERY INPUT */
        $queryInput = new QueryInput();
        $queryInput->setText($textInput);
 
        /* GET RESPONSE */
        $response = $sessionsClient->detectIntent($session, $queryInput);
        $queryResult = $response->getQueryResult();
        $queryText = $queryResult->getQueryText();
        $intent = $queryResult->getIntent();
        $displayName = $intent->getDisplayName();
        $confidence = $queryResult->getIntentDetectionConfidence();
        $fulfilmentText = $queryResult->getFulfillmentText();
 
        /* OUTPUT RELEVANT INFO */
        // print(str_repeat("=", 20) . PHP_EOL);
        // printf('Query text: %s' . PHP_EOL, $queryText);
        // printf('Detected intent: %s (confidence: %f)' . PHP_EOL, $displayName, $confidence);
        // print(PHP_EOL);
        // printf('Fulfilment text: %s' . PHP_EOL, $fulfilmentText);
        echo $fulfilmentText;

        $sessionsClient->close();
    }
    detect_intent_texts($PROEJCT_ID, $QUERY, $SESSION_ID);
?>