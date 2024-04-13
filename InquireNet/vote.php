<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'], $_POST['answerId'], $_POST['voteType'], $_POST['questionId'])) {
    $answerId = (int)$_POST['answerId'];
    $voteType = $_POST['voteType'] === 'true' ? 1 : 0;
    $userId = (int)$_SESSION['user_id'];
    $questionId = (int)$_POST['questionId'];

    $stmt = $pdo->prepare("SELECT vote_id FROM Votes WHERE user_id = ? AND answer_id = ?");
    $stmt->execute([$userId, $answerId]);
    $exists = $stmt->fetch();

  
    if ($exists) {
        $stmt = $pdo->prepare("UPDATE Votes SET vote_type = ? WHERE user_id = ? AND answer_id = ?");
        $success = $stmt->execute([$voteType, $userId, $answerId]);
    } else {

        $stmt = $pdo->prepare("INSERT INTO Votes (answer_id, user_id, vote_type) VALUES (?, ?, ?)");
        $success = $stmt->execute([$answerId, $userId, $voteType]);
    }

    if ($success) {
        $_SESSION['flash_message'] = 'Your vote has been recorded.';
    } else {
        $_SESSION['flash_message'] = 'Failed to record your vote.';
    }

    header("Location: QuestionDetail.php?question_id=$questionId");
    exit();
}
?>
