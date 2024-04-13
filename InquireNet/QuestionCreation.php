<?php

include('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST['question']))) {
        $_SESSION['error_message'] = 'Question text is required.';
        header('Location: QuestionCreationPage.php'); 
        exit();
    }

    $questionText = $_POST['question']; // With PDO, the prepare statement will handle escaping
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

    if ($userId <= 0) {
        $_SESSION['error_message'] = 'Invalid user ID.';
        header('Location: LoginPage.php'); 
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO Questions (user_id, question_text, creation_date) VALUES (?, ?, NOW())");
    $stmt->bindParam(1, $userId, PDO::PARAM_INT);
    $stmt->bindParam(2, $questionText, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header('Location: QuestionManagement.php'); 
        exit();
    } else {
        $_SESSION['error_message'] = 'An error occurred while posting your question.';
        header('Location: QuestionCreationPage.php'); 
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="banner">
            <a href="MainPage(AfterLogin).php">
                <img src="images/InquireNetIcon.png" class="logo" >
            </a>
            <div class="search-container">
                <input type="text" placeholder="Search..." id="searchBox">
                <button type="submit">Search</button>
            </div>
            <div class="user-header-info">
                <img src="images/user-avatar.jpg" class="user-avatar">
                <span>Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></span>
                <button onclick="location.href='logout.php'">Logout</button>

            </div>
        </div>
    </header>


    <section class="container">
        <h2>Post a New Question</h2>
        <form id="questionForm" action="QuestionCreation.php" method="post">
            <label for="question">Your Question:</label>
            <textarea id="question" name="question" rows="4" required></textarea>
            <div id="questionCounter" class="char-counter">0 / 1500 </div>
            <input type="submit" value="Post Question">
        </form>
    </section>


    <footer>
        <p>&copy; 2024 InquireNet</p>
    </footer>
    <script src="js/eventHandlers.js"></script>
    <script src="js/eventRegisterQuestion.js"></script>
</body>
</html>
