<?php

require_once __DIR__ . '/../../components/connect.php';

class ContactController extends Controller
{
  public function index()
  {
    // Handle form submission
    if (isset($_POST['send'])) {
      $this->handleContactForm();
    }

    $this->view('home/contact');
  }

  private function handleContactForm()
  {
    global $conn;

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);

    // Get user_id from session if available
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Check if message already exists
    $stmt = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
    $stmt->execute([$name, $email, $number, $msg]);

    if ($stmt->rowCount() > 0) {
      $_SESSION['message'][] = 'already sent message!';
    } else {
      // Insert new message
      $stmt = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $stmt->execute([$user_id, $name, $email, $number, $msg]);

      $_SESSION['message'][] = 'sent message successfully!';
    }
  }
}
