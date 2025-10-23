<?php
session_start();

// Load saved password if it exists
$adminPassword = "pratibha"; // default fallback
$adminPhone = "9211218634";  // your personal phone number

if (file_exists("admin-password.txt")) {
  $adminPassword = trim(file_get_contents("admin-password.txt"));
}

// Handle login
if (isset($_POST['password'])) {
  if ($_POST['password'] === $adminPassword) {
    $_SESSION['admin_access'] = true;
  } else {
    $error = "Incorrect password.";
  }
}

// Handle password reset
if (isset($_POST['reset'])) {
  if ($_POST['phone'] === $adminPhone) {
    file_put_contents("admin-password.txt", $_POST['new_password']);
    echo "<script>alert('✅ Password updated successfully!'); window.location.href='admin-view.php';</script>";
    exit();
  } else {
    echo "<script>alert('❌ Invalid phone number.'); window.location.href='admin-view.php?forgot=true';</script>";
    exit();
  }
}

// Show reset form if ?forgot=true is triggered
if (isset($_GET['forgot'])) {
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Reset Admin Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <form method="POST" class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm">
      <h2 class="text-xl font-bold mb-4 text-gray-800">🔑 Reset Admin Password</h2>
      <input type="text" name="phone" placeholder="Enter your phone number" required class="w-full px-4 py-2 border rounded mb-4" />
      <input type="password" name="new_password" placeholder="New Password" required class="w-full px-4 py-2 border rounded mb-4" />
      <button type="submit" name="reset" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Reset Password</button>
    </form>
  </body>
  </html>
  <?php
  exit();
}

// If not logged in, show password form
if (!isset($_SESSION['admin_access'])) {
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Admin Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <form method="POST" class="bg-white p-6 rounded-lg shadow-md w-full max-w-sm">
      <h2 class="text-xl font-bold mb-4 text-gray-800">🔐 Enter Admin Password</h2>
      <?php if (isset($error)) echo "<p class='text-red-500 mb-2'>$error</p>"; ?>
      <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded mb-4" />
      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Access Panel</button>
      <p class="mt-4 text-sm text-gray-500 text-center">
        <a href="?forgot=true" class="text-blue-600 hover:underline">Forgot Password?</a>
      </p>
    </form>
  </body>
  </html>
  <?php
  exit();
}
?>



<?php
$conn = new mysqli("localhost", "root", "", "myweb", ); // adjust port if needed

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Optional: increment visitor count on page load
$conn->query("UPDATE visitor_count SET count = count + 1 WHERE id = 1");

// Fetch current count
$result = $conn->query("SELECT count FROM visitor_count WHERE id = 1");
$visitorCount = 0;

if ($result && $row = $result->fetch_assoc()) {
  $visitorCount = $row['count'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Panel – Contact Submissions</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-100 to-white min-h-screen p-6">

  <div class="max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">📬 Contact Submissions</h1>

    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
      <table class="min-w-full table-auto">
        <thead class="bg-gray-200 text-gray-700">
          <tr>
            <th class="px-4 py-3 text-left">Full Name</th>
            <th class="px-4 py-3 text-left">Email</th>
            <th class="px-4 py-3 text-left">Phone</th>
            <th class="px-4 py-3 text-left">Subject</th>
            <th class="px-4 py-3 text-left">Message</th>
            <th class="px-4 py-3 text-left">Submitted At</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php
          $conn = new mysqli("localhost", "root", "", "myweb");
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          $result = $conn->query("SELECT * FROM contact_form ORDER BY submitted_at DESC");
          while ($row = $result->fetch_assoc()) {
            echo "<tr class='hover:bg-gray-50'>";
            echo "<td class='px-4 py-3'>" . htmlspecialchars($row['fullname']) . "</td>";
            echo "<td class='px-4 py-3'>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td class='px-4 py-3'>" . htmlspecialchars($row['phone']) . "</td>";
            echo "<td class='px-4 py-3'>" . htmlspecialchars($row['subject']) . "</td>";
            echo "<td class='px-4 py-3'>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
            echo "<td class='px-4 py-3 text-sm text-gray-500'>" . $row['submitted_at'] . "</td>";
            echo "</tr>";
          }

          $conn->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>

<div class="mt-8 bg-white border-l-4 border-blue-600 shadow-md rounded-lg p-6">
  <div class="flex items-center justify-between">
    <div class="flex items-center space-x-4">
      <div class="bg-blue-100 text-blue-600 rounded-full p-3">
        <span class="text-2xl">👁️</span>
      </div>
      <div>
        <p class="text-sm text-gray-500 uppercase font-semibold tracking-wide">Total Visitors</p>
        <p class="text-3xl font-bold text-gray-800"><?php echo $visitorCount; ?></p>
      </div>
    </div>
    <div class="text-sm text-gray-400 italic">Since launch</div>
  </div>
</div>
</body>
</html>