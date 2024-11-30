<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header("Location: login.html");
    exit();
}
$userName = $_SESSION['name'];
$studentID = $_SESSION['studentID']; // Assuming you have stored studentID in session after login

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stap1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch SASE scores
$sql = "SELECT mathScore, englishScore, scienceScore, aptitudeScore, total_score FROM sase_exam WHERE studentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentID); // Use "i" for integer binding
$stmt->execute();
$result = $stmt->get_result();
$scores = $result->fetch_assoc();

$stmt->close();

// Initialize an array to hold recommended courses
$recommendedCourses = [];

// Check scores and determine recommended colleges based on scores
if ($scores) {
    $mathScore = $scores['mathScore'];
    $scienceScore = $scores['scienceScore'];
    $englishScore = $scores['englishScore'];
    $aptitudeScore = $scores['aptitudeScore'];
    $totalScore = $scores['total_score'];

    // Logic for college recommendations based on scores
    if ($mathScore >= 20 || $aptitudeScore >= 10 && $englishScore >= 30 && $totalScore >= 70) {
        $recommendedCourses[] = ['collegeID' => 1, 'collegeName' => 'CICS', 'description' => 'College of Information and Computing Sciences'];
    }

    if ($englishScore >= 30 || $aptitudeScore >= 10 && $totalScore >= 60) {
        $recommendedCourses[] = ['collegeID' => 2, 'collegeName' => 'CSPEAR', 'description' => 'College of Sports'];
    }

    if ($englishScore >= 50 || $aptitudeScore >= 10 && $totalScore >= 70) {
        $recommendedCourses[] = ['collegeID' => 3, 'collegeName' => 'CSSH', 'description' => 'College of Social Sciences and Humanities'];
    }

    if ($mathScore >= 20 || $aptitudeScore >= 10 && $totalScore >= 75) {
        $recommendedCourses[] = ['collegeID' => 4, 'collegeName' => 'CBAA', 'description' => 'College of Business Administration'];
    }

    if ($englishScore >= 30 || $scienceScore >= 20 && $aptitudeScore >= 15 && $totalScore >= 70) {
        $recommendedCourses[] = ['collegeID' => 5, 'collegeName' => 'CPA', 'description' => 'College of Public Affairs'];
    }
}

// Fetch courses for each recommended college
$collegeCourses = []; // Initialize an array to hold courses for each college
foreach ($recommendedCourses as $college) {
    $collegeID = $college['collegeID'];
    $sqlCourses = "SELECT courseName FROM course WHERE departmentID = ?";
    $stmtCourses = $conn->prepare($sqlCourses);
    $stmtCourses->bind_param("i", $collegeID); // Use "i" for integer binding
    $stmtCourses->execute();
    $resultCourses = $stmtCourses->get_result();

    while ($course = $resultCourses->fetch_assoc()) {
        $collegeCourses[$collegeID][] = $course['courseName'];
    }

    $stmtCourses->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Admission and Course Recommendation</title>
    <link rel="stylesheet" type="text/css" href="blue-theme.css"
    <link rel="stylesheet" href="style-homepage.css">
</head>
<body>
    <div class="user-info">
        <p>Welcome, <span id="user-name"><?php echo htmlspecialchars($userName); ?></span>!</p>
    </div>
    
    <h1>Student Admission and Course Recommendation</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="fileUpload">Upload Requirements from Previous School</label>
        <input type="file" name="fileUpload" id="fileUpload"><br>
        <label for="PrefCourse">Preferred Courses</label><br>
        <input type="text" name="PrefCourse" id="PrefCourse">
    </form>

    <div id="sase-scores">
        <h2>SASE Scores</h2>
        <?php if ($scores): ?>
            <table border="1">
                <tr>
                    <th>Subject</th>
                    <th>Score</th>
                </tr>
                <tr>
                    <td>Math</td>
                    <td><?php echo htmlspecialchars($scores['mathScore']); ?></td>
                </tr>
                <tr>
                    <td>English</td>
                    <td><?php echo htmlspecialchars($scores['englishScore']); ?></td>
                </tr>
                <tr>
                    <td>Science</td>
                    <td><?php echo htmlspecialchars($scores['scienceScore']); ?></td>
                </tr>
                <tr>
                    <td>Aptitude</td>
                    <td><?php echo htmlspecialchars($scores['aptitudeScore']); ?></td>
                </tr>
                <tr>
                    <td>Total Score</td>
                    <td><?php echo htmlspecialchars($scores['total_score']); ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>No scores available.</p>
        <?php endif; ?>
    </div>

    <div id="recommendations">
        <h2>Recommended Colleges</h2>
        <ul id="course-list">
            <?php if (!empty($recommendedCourses)): ?>
                <?php foreach ($recommendedCourses as $course): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($course['collegeName']); ?></strong>: 
                        <?php echo htmlspecialchars($course['description']); ?>
                        <ul>
                            <?php if (!empty($collegeCourses[$course['collegeID']])): ?>
                                <?php foreach ($collegeCourses[$course['collegeID']] as $courseName): ?>
                                    <li><?php echo htmlspecialchars($courseName); ?></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>No courses available for this college.</li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No recommended colleges available based on your scores.</li>
            <?php endif; ?>
        </ul>
    </div>

    <script src="script-homepage.js"></script>
</body>
</html>