<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = $_POST['question_id'];
    $answer = $_POST['answer'];

    $query = "SELECT * FROM drugs WHERE id = $question_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $drug = mysqli_fetch_assoc($result);
        $correct_answer = '';

        if (strpos($answer, $drug['brand_name']) !== false) {
            $correct_answer = $drug['brand_name'];
        } elseif (strpos($answer, $drug['indication']) !== false) {
            $correct_answer = $drug['indication'];
        } elseif (strpos($answer, $drug['class']) !== false) {
            $correct_answer = $drug['class'];
        }

        $is_correct = ($answer === $correct_answer);

        echo json_encode([
            'correct' => $is_correct,
            'correct_answer' => $correct_answer
        ]);
    } else {
        echo json_encode(['error' => 'Question not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

mysqli_close($conn);
