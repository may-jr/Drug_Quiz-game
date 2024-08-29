<?php
require_once './config.php';

$query = "SELECT * FROM drugs ORDER BY RAND() LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $drug = mysqli_fetch_assoc($result);

    $question_types = ['brand_name', 'indication', 'class'];
    $question_type = $question_types[array_rand($question_types)];

    switch ($question_type) {
        case 'brand_name':
            $question = "What is the brand name of {$drug['generic_name']}?";
            $correct_answer = $drug['brand_name'];
            break;
        case 'indication':
            $question = "What is the main indication for {$drug['generic_name']}?";
            $correct_answer = $drug['indication'];
            break;
        case 'class':
            $question = "To which class of drugs does {$drug['generic_name']} belong?";
            $correct_answer = $drug['class'];
            break;
    }

    // Get wrong options
    $wrong_options_query = "SELECT $question_type FROM drugs WHERE id != {$drug['id']} ORDER BY RAND() LIMIT 3";
    $wrong_options_result = mysqli_query($conn, $wrong_options_query);
    $wrong_options = [];
    while ($row = mysqli_fetch_assoc($wrong_options_result)) {
        $wrong_options[] = $row[$question_type];
    }

    $options = array_merge([$correct_answer], $wrong_options);
    shuffle($options);

    $response = [
        'id' => $drug['id'],
        'question' => $question,
        'options' => $options
    ];

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'No question available']);
}

mysqli_close($conn);
