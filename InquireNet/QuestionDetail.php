<?php
include('db.php');
session_start();

$questionId = isset($_GET['question_id']) ? (int)$_GET['question_id'] : null;
$question = null;
$answers = [];

if ($questionId) {
    $stmt = $pdo->prepare("SELECT question_text FROM Questions WHERE question_id = ?");
    $stmt->execute([$questionId]);
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    $answersStmt = $pdo->prepare("
    SELECT a.*, u.username,
           COALESCE(SUM(v.vote_type = 1), 0) AS up_votes,
           COALESCE(SUM(v.vote_type = 0), 0) AS down_votes
    FROM Answers a
    LEFT JOIN Users u ON a.user_id = u.user_id
    LEFT JOIN Votes v ON a.answer_id = v.answer_id
    WHERE a.question_id = ?
    GROUP BY a.answer_id
    ");
    $answersStmt->execute([$questionId]);
    $answers = $answersStmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newAnswer']) && $questionId) {
    $newAnswer = trim($_POST['newAnswer']);
    if (!empty($newAnswer)) { 
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
        
        $insertStmt = $pdo->prepare("INSERT INTO Answers (question_id, user_id, answer_text) VALUES (?, ?, ?)");
        $insertStmt->execute([$questionId, $userId, $newAnswer]);
        
        if ($insertStmt->rowCount() > 0) {
            $_SESSION['message'] = 'Answer posted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to post your answer.';
        }
        header("Location: QuestionDetail.php?question_id=$questionId"); 
        exit();
    } else {
        $_SESSION['error'] = 'Your answer cannot be empty.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Question Detail</title>
</head>
<body>
    <header>
        <div class="banner">
            <a href="MainPage(AfterLogin).php">
                <img src="images/InquireNetIcon.png" class="logo">
            </a>
            <div class="search-container">
                <input type="text" placeholder="Search..." id="searchBox">
                <button type="submit">Search</button>
            </div>
            <div class="user-header-info">
                <img src="images/user-avatar.jpg" class="user-avatar">
                <span>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?></span>
                <button onclick="location.href='logout.php'">Logout</button>

            </div>
        </div>
    </header>

    <section class="container">
        <h2><?php echo htmlspecialchars($question['question_text']); ?></h2>
        
     
        <?php if(isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
            </div>
        <?php elseif(isset($_SESSION['error'])): ?>
            <div class="error">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="answers">
    <?php foreach ($answers as $answer): ?>
        <div class="answer">
            <img src="/images/profile.png" class="profile-img">
            <div class="answer-content">
                <div class="answer-details">
                    <span><?php echo htmlspecialchars($answer['username']); ?></span>
                    <span>Date: <?php echo htmlspecialchars($answer['creation_date']); ?></span>
                </div>
                <p><?php echo nl2br(htmlspecialchars($answer['answer_text'])); ?></p>
                <button onclick="vote(<?php echo $answer['answer_id']; ?>, true)">Upvote</button>
                <span id="up-votes-<?php echo $answer['answer_id']; ?>"><?php echo $answer['up_votes']; ?></span>
                <button onclick="vote(<?php echo $answer['answer_id']; ?>, false)">Downvote</button>
                <span id="down-votes-<?php echo $answer['answer_id']; ?>"><?php echo $answer['down_votes']; ?></span>
            </div>
        </div>
    <?php endforeach; ?>

            <div class="answer-form">
                <form id='answerForm' action="" method="post">
                    <p>Your Answer:</p>
                    <textarea id="new-answer" name="newAnswer" rows="4" required></textarea>
                    <div id="answerCounter" class="char-counter">0 / 1500</div>
                    <input type="submit" value="Post Answer">
                </form>
            </div>
        </div>
    </section>
    
    <footer>
        <p>&copy; 2024 InquireNet</p>
    </footer>
    <script src="js/eventHandlers.js"></script>
    <script src="js/eventRegisterAnswer.js"></script>
</body>
</html>
