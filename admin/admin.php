<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

require_once(__DIR__ . '/../config/configuration.php');

// Fetch user data from the database
function getUsers() {
    global $conn;
    $stmt = $conn->prepare("SELECT id, full_name FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $users;
}

// Function to insert semester and subject data
function insertSemesterSubjects($semester_name, $subjects, $grades, $selected_users) {
    global $conn;
    // Insert the semester
    $stmt = $conn->prepare("INSERT INTO semesters (semester_name) VALUES (?)");
    $stmt->bind_param("s", $semester_name);
    $stmt->execute();
    $semester_id = $stmt->insert_id;
    $stmt->close();
    
    // Insert the subjects and grades with user IDs
    $stmt = $conn->prepare("INSERT INTO subjects (semester_id, subject_name, grade, user_id) VALUES (?, ?, ?, ?)");
    foreach ($selected_users as $user_id) {
        for ($i = 0; $i < count($subjects); $i++) {
            $stmt->bind_param("isii", $semester_id, $subjects[$i], $grades[$i], $user_id);
            $stmt->execute();
        }
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $semester_name = $_POST['semester_name'];
    $subjects = [
        $_POST['subject1'],
        $_POST['subject2'],
        $_POST['subject3'],
        $_POST['subject4'],
        $_POST['subject5'],
        $_POST['subject6']
    ];
    $grades = [
        $_POST['grade1'],
        $_POST['grade2'],
        $_POST['grade3'],
        $_POST['grade4'],
        $_POST['grade5'],
        $_POST['grade6']
    ];
    $selected_users = $_POST['selected_users'];
    
    insertSemesterSubjects($semester_name, $subjects, $grades, $selected_users);
    echo "Semester and subjects added successfully.";
}

$users = getUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Dashboard</h2>
        <form action="" method="POST">
            <div class="mb-4">
                <label for="semester_name" class="block text-gray-700">Semester Name</label>
                <input type="text" name="semester_name" id="semester_name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <table class="w-full mb-4 border-collapse">
                <thead>
                    <tr>
                        <th class="border p-2">Subject Name</th>
                        <th class="border p-2">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                    <tr>
                        <td class="border p-2">
                            <input type="text" name="subject<?php echo $i; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                        </td>
                        <td class="border p-2">
                            <input type="text" name="grade<?php echo $i; ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                        </td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
            <label for="selected_users" class="block text-gray-700">Select Users</label>
            <select name="selected_users[]" id="selected_users" multiple class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['full_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Add Semester and Subjects</button>
        </form>
    </div>
</div>

</body>
</html>
