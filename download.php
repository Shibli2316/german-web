<?php
session_start();
require_once('db_connect.php');
require('fpdf.php'); // Include FPDF library

// Check if user is logged in


if (!isset($_SESSION['email'])) {
    header('location: authenticate.php');
    die('User not logged in');
}

// Fetch all words, meanings, and sentences
$sql = "SELECT w1, w2, w3, w4, w5, m1, m2, m3, m4, m5, s1, s2, s3, s4, s5 FROM word";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("No words found in the database.");
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Word List', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);

// Header row for the table
$pdf->Cell(60, 10, 'Word', 1, 0, 'C');
$pdf->Cell(60, 10, 'Meaning', 1, 0, 'C');
$pdf->Cell(60, 10, 'Sentence', 1, 1, 'C'); // Move to the next line

$pdf->SetFont('Arial', '', 12);

while ($row = $result->fetch_assoc()) {
    // Loop through all word columns (w1 to w5)
    for ($i = 1; $i <= 5; $i++) {
        $word = $row["w$i"];
        $meaning = $row["m$i"];
        $sentence = $row["s$i"];

        if (!empty($word)) {
            // Add the word, meaning, and sentence in a tabular format
            $pdf->Cell(60, 10, $word, 1, 0, 'C');
            $pdf->Cell(60, 10, $meaning, 1, 0, 'C');
            $pdf->Cell(60, 10, $sentence, 1, 1, 'C');
        }
    }
}

$stmt->close();
$conn->close();

// Output PDF for download
$pdf->Output('D', 'word_list.pdf');
?>
