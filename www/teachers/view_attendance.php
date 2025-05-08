<?php
session_start();
require 'db_connect.php';  

// Redirect if not logged in
if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php');
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch teacher's assigned class
$query = $db->prepare("SELECT assigned_class FROM teachers WHERE id = ?");
$query->execute([$teacher_id]);
$teacher = $query->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    echo "Error: Teacher not found.";
    exit();
}

$assigned_class = $teacher['assigned_class'];

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Set default date range (last 7 days)
$start_date = $_GET['start_date'] ?? date('Y-m-d', strtotime('-7 days'));
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Fetch attendance records
$query = $db->prepare("SELECT a.date, s.name, a.status 
                        FROM attendance a 
                        JOIN students s ON a.student_id = s.id 
                        WHERE a.class = ? AND a.date BETWEEN ? AND ? 
                        ORDER BY a.date DESC, s.name ASC 
                        LIMIT ? OFFSET ?");
$query->execute([$assigned_class, $start_date, $end_date, $limit, $offset]);
$records = $query->fetchAll(PDO::FETCH_ASSOC);

// Get total records count
$countQuery = $db->prepare("SELECT COUNT(*) FROM attendance a 
                            JOIN students s ON a.student_id = s.id 
                            WHERE a.class = ? AND a.date BETWEEN ? AND ?");
$countQuery->execute([$assigned_class, $start_date, $end_date]);
$total_records = $countQuery->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Fetch absentee pattern
$absenteeQuery = $db->prepare("SELECT s.name, COUNT(a.status) as absences 
                               FROM attendance a 
                               JOIN students s ON a.student_id = s.id 
                               WHERE a.class = ? AND a.date BETWEEN ? AND ? AND a.status = 'Absent' 
                               GROUP BY s.name 
                               ORDER BY absences DESC 
                               LIMIT 5");
$absenteeQuery->execute([$assigned_class, $start_date, $end_date]);
$absentees = $absenteeQuery->fetchAll(PDO::FETCH_ASSOC);

// Calculate absentee trend for prediction
$absentTrendQuery = $db->prepare("SELECT COUNT(*) / COUNT(DISTINCT date) AS avg_absences_per_day
                                  FROM attendance 
                                  WHERE class = ? AND date BETWEEN ? AND ? AND status = 'Absent'");
$absentTrendQuery->execute([$assigned_class, $start_date, $end_date]);
$avg_absences = $absentTrendQuery->fetchColumn();

// Predict future absentee trend for the next 7 days
$predicted_absences = round($avg_absences * 7);
$prediction_message = "<span class='prediction-text'>📊 Prediction: We estimate around <strong>$predicted_absences</strong> absences in the next 7 days.</span>";

// Generate AI message based on absentee pattern
$ai_message = "<span class='ai-header'>🔍 Eva's Insights:</span><br>";
if (count($absentees) > 0) {
    $ai_message .= "<span class='ai-text'>📌 The top absentees are:</span><br>";
    foreach ($absentees as $absentee) {
        $ai_message .= "<strong>• " . htmlspecialchars($absentee['name']) . "</strong> (" . $absentee['absences'] . " absences)<br>";
    }
} else {
    $ai_message .= "<span class='ai-text'>🎉 Great news! No frequent absentees detected.</span>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTrack | Attendance History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        body {
            font-family: 'Inter', system-ui;
            background-color: #F8FAFC;
        }
        .history-header {
            background: linear-gradient(135deg, #4F46E5, #10B981);
            color: white;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }
        .ai-box {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 360px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.15);
        padding: 20px;
        font-size: 14px;
        animation: fadeIn 0.5s ease-in-out;
        border-left: 6px solid #4F46E5;
        display: flex;
        align-items: flex-start;
        transition: transform 0.3s ease;
    }

    .ai-box:hover {
        transform: translateY(-5px); /* Subtle lift effect on hover */
    }

    .ai-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #4F46E5; /* Background color for AI avatar */
        color: white; /* Text color */
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 24px;
        margin-right: 15px;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
    }

    .ai-content {
        flex: 1;
        text-align: left;
    }

    .ai-header {
        font-weight: bold;
        color: #4F46E5;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .ai-text {
        font-size: 15px;
        margin-top: 5px;
        color: #333;
        line-height: 1.5; /* Improved readability */
    }

    .prediction-text {
        font-size: 15px;
        font-weight: bold;
        color: #10B981;
        margin-top: 10px;
        display: block;
        background-color: #f0f9f4; /* Light background for prediction */
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #10B981; /* Border to highlight prediction */
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>
    <div class="container py-4">
        <div class="history-header mb-4">
            <h3><i class="fas fa-calendar-alt"></i> Attendance History</h3>
            <p class="mb-0">View and filter past attendance records</p>
        </div>

        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-5">
                    <input type="date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($start_date); ?>" required>
                </div>
                <div class="col-md-5">
                    <input type="date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Student Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['date']); ?></td>
                        <td><?php echo htmlspecialchars($record['name']); ?></td>
                        <td class="<?php echo ($record['status'] == 'Absent') ? 'text-danger' : ''; ?>">
                            <?php echo htmlspecialchars($record['status']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&start_date=<?php echo htmlspecialchars($start_date); ?>&end_date=<?php echo htmlspecialchars($end_date); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- Ava AI Chatbox -->
    <div class="ai-box">
        <div class="d-flex">
            <img src="op.JPEG" class="ai-avatar" alt="Ava AI">

            <div class="ai-content">
                <?php echo $ai_message; ?>
                <br><?php echo $prediction_message; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let voices = [];

    function populateVoiceList() {
        voices = window.speechSynthesis.getVoices();

        // Find the most natural female voice (Google WaveNet preferred)
        const preferredVoices = voices.filter(voice =>
            voice.name.includes('Google UK English Female') ||
            voice.name.includes('Google US English Female') ||
            voice.name.includes('WaveNet') ||
            voice.name.includes('Female')
        );

        return preferredVoices.length > 0 ? preferredVoices[0] : voices[0]; // Fallback if no female voice found
    }

    window.speechSynthesis.onvoiceschanged = function () {
        voices = window.speechSynthesis.getVoices();
    };

    function readAIMessage(message) {
        if (!message.trim()) return; // Prevent empty messages

        // Final message with natural flow
        const finalMessage = `Hello, Boss. ${message}. I'll be here to assist with anything you need.`;

        const utterance = new SpeechSynthesisUtterance(finalMessage);
        utterance.lang = 'en-US';

        // Select the best human-like female voice
        const femaleVoice = populateVoiceList();
        if (femaleVoice) {
            utterance.voice = femaleVoice;
        } else {
            console.warn('No female voice found, using default voice.');
        }

        // **Make Eva sound 100% human**
        utterance.pitch = 1.1;  // Slightly higher pitch for a natural, friendly tone
        utterance.rate = 0.92;  // Slower rate for clarity (more human-like)
        utterance.volume = 1;   // Full volume
        utterance.onstart = () => console.log("Eva is speaking...");

        // Hide the AI box after the speech ends
        utterance.onend = () => {
            console.log("Eva has finished speaking.");
            setTimeout(() => {
                document.querySelector('.ai-box').style.display = 'none'; // Hide the popup
            }, 5000); // Wait for 5 seconds before hiding
        };

        window.speechSynthesis.speak(utterance);
    }

    // **Remove any icons or HTML elements from AI message**
    const aiMessage = <?php echo json_encode(strip_tags($ai_message)); ?>.replace(/[^A-Za-z0-9,.!? ]/g, ''); // Remove special characters

    if (aiMessage) {
        document.querySelector('.ai-box').style.display = 'block';
        readAIMessage(aiMessage);
    }
</script>
<script>
    let voices = [];

    function populateVoiceList() {
        voices = window.speechSynthesis.getVoices();

        // Find the most natural female voice (Google WaveNet preferred)
        const preferredVoices = voices.filter(voice =>
            voice.name.includes('Google UK English Female') ||
            voice.name.includes('Google US English Female') ||
            voice.name.includes('WaveNet') ||
            voice.name.includes('Female')
        );

        return preferredVoices.length > 0 ? preferredVoices[0] : voices[0]; // Fallback if no female voice found
    }

    window.speechSynthesis.onvoiceschanged = function () {
        voices = window.speechSynthesis.getVoices();
    };

    function readAIMessage(message) {
        if (!message.trim()) return; // Prevent empty messages

        // Final message with natural flow
        const finalMessage = `Hello, Boss. ${message}. I'll be here to assist with anything you need.`;

        const utterance = new SpeechSynthesisUtterance(finalMessage);
        utterance.lang = 'en-US';

        // Select the best human-like female voice
        const femaleVoice = populateVoiceList();
        if (femaleVoice) {
            utterance.voice = femaleVoice;
        } else {
            console.warn('No female voice found, using default voice.');
        }

        // **Make Eva sound 100% human**
        utterance.pitch = 1.1;  // Slightly higher pitch for a natural, friendly tone
        utterance.rate = 0.92;  // Slower rate for clarity (more human-like)
        utterance.volume = 1;   // Full volume
        utterance.onstart = () => console.log("Eva is speaking...");

        // Hide the AI box after the speech ends
        utterance.onend = () => {
            console.log("Eva has finished speaking.");
            setTimeout(() => {
                document.querySelector('.ai-box').style.display = 'none'; // Hide the popup
            }, 5000); // Wait for 5 seconds before hiding
        };

        window.speechSynthesis.speak(utterance);
    }

    // **Remove any icons or HTML elements from AI message**
    const aiMessage = <?php echo json_encode(strip_tags($ai_message)); ?>.replace(/[^A-Za-z0-9,.!? ]/g, ''); // Remove special characters

    if (aiMessage) {
        document.querySelector('.ai-box').style.display = 'block';
        readAIMessage(aiMessage);
    }
</script>
</body>
</html>