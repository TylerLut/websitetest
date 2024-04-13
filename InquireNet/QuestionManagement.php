<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: MainPage.php');
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT Q.question_id, Q.question_text, Q.creation_date,
           (SELECT COUNT(*) FROM Votes WHERE question_id = Q.question_id AND vote_type = 1) AS upvotes,
           (SELECT COUNT(*) FROM Votes WHERE question_id = Q.question_id AND vote_type = 0) AS downvotes
    FROM Questions Q
    WHERE Q.user_id = ?
    ORDER BY Q.creation_date DESC
");
$stmt->execute([$userId]);

$userQuestions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="banner">
            <a href="MainPage(AfterLogin).html">
                <img src="images/InquireNetIcon.png" class="logo" >
            </a>
            <div class="search-container">
                <input type="text" placeholder="Search..." id="searchBox">
                <button type="submit">Search</button>
            </div>
            <div class="user-header-info">
                <img src="images/user-avatar.jpg" class="user-avatar">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?></span>
                <button onclick="location.href='logout.php'">Logout</button>

            </div>
        </div>
    </header>
    

    <section class="container">
    <h2>Your Questions</h2>
    <div class="user-questions">
        <?php foreach ($userQuestions as $question): ?>
            <div class="question-item">
                <h3><?php echo htmlspecialchars($question['question_text']); ?></h3>
                <img src="images/profile.png" class="profile-img">
                <a href="QuestionDetail.php?question_id=<?php echo $question['question_id']; ?>">
                    <?php echo htmlspecialchars($question['question_text']); ?>
                </a>
                <div class="question-info">
                    <span>Date: <?php echo $question['creation_date']; ?></span>
                    <span><img src="images/Upvote.png" class="upvote-icon"> <?php echo $question['upvotes']; ?></span>
                    <span><img src="images/Downvote.png" class="downvote-icon"> <?php echo $question['downvotes']; ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>


    <footer>
        <p>&copy; 2024 InquireNet</p>
    </footer>
</body>
</html>
