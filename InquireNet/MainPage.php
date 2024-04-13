<?php
include('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username']; 
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT user_id, username, password FROM Users WHERE username = ?");
    $stmt->execute([$username]);
    if ($user = $stmt->fetch()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: MainPage.php"); 
            exit();
        } else {
            $loginError = "Invalid username or password.";
        }
    } else {
        $loginError = "Invalid username or password.";
    }
}

$recentQuestionsQuery = "
SELECT Q.question_id, Q.question_text, Q.creation_date, COUNT(A.answer_id) AS answer_count
FROM Questions Q
LEFT JOIN Answers A ON Q.question_id = A.question_id
GROUP BY Q.question_id
ORDER BY Q.creation_date DESC
LIMIT 20";

$recentQuestionsResult = $pdo->query($recentQuestionsQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="banner">
            <a href="MainPage.php">
                <img src="images/InquireNetIcon.png" class="logo">
            </a>
            <div class="search-container">
                <input type="text" placeholder="Search..." id="searchBox">
                <button type="submit">Search</button>
            </div>
            <div class="user-header-info">
                <?php if (isset($_SESSION['username'])): ?>
                    <img src="images/user-avatar.jpg" class="user-avatar">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <button onclick="location.href='logout.php'">Logout</button>
                    <nav>
                        <button onclick="location.href='QuestionManagement.php'">Question Management</button>
                    </nav>
                <?php else: ?>
                    <button onclick="location.href='LoginPage.php'">Login</button>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <section class="container">
        <?php if (!isset($_SESSION['username'])): ?>
            <?php if (isset($loginError)): ?>
                <p class="error-text"><?php echo $loginError; ?></p>
            <?php endif; ?>
            <section class="login-form">
                <h2>Login</h2>
                <form id="loginForm" method="post" action="MainPage.php">
                    <input id='username' type="text" name="username" placeholder="Username">
                    <input id='password' type="password" name="password" placeholder="Password">
                    <input type="submit" value="Login">
                </form>
                <a href="SignUp.php">Don't have an account? Sign up</a>
            </section>
        <?php endif; ?>

        <h2>20 Most Recent Questions</h2>
        <ul>
        <?php while($question = $recentQuestionsResult->fetch(PDO::FETCH_ASSOC)): ?>
                <li>
                    <img src="images/profile.png" class="user-avatar">
                    <a href="QuestionDetail.php?question_id=<?php echo $question['question_id']; ?>">
                        <?php echo htmlspecialchars($question['question_text']); ?>
                    </a>
                    <div class="question-info">
                        <span>Date: <?php echo $question['creation_date']; ?></span>
                        <span>Answers: <?php echo $question['answer_count']; ?></span>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    </section>

    <footer>
        <p>&copy; 2024 InquireNet</p>
    </footer>
</body>
</html>
