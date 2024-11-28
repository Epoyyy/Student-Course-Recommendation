<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sase_score = $_POST['sase_score'] ?? 0;

    try {
        // Step 1: Find the student details with the given SASE score
        $stmt = $pdo->prepare("
            SELECT s.name, se.examDate
            FROM student s
            JOIN sase_exam se ON s.studentID = se.studentID
            WHERE se.total_score = :sase_score
        ");
        $stmt->bindParam(':sase_score', $sase_score, PDO::PARAM_INT);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            echo json_encode(['error' => 'No student found with this SASE score.']);
            exit;
        }

        // Step 2: Get recommended courses based on the SASE score
        $stmt = $pdo->prepare("
            SELECT courseName, description
            FROM course
            WHERE :sase_score >= recommendedMinScore
        ");
        $stmt->bindParam(':sase_score', $sase_score, PDO::PARAM_INT);
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Step 3: Format the response
        $response = [
            'name' => $student['name'],
            'examDate' => $student['examDate'],
            'courses' => $courses,
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
