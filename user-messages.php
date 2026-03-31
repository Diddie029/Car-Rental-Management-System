<?php 
session_start();
include('includes/config.php');
error_reporting(0);

// Redirect to login if not logged in
if(strlen($_SESSION['login'])==0) {
    header('location:index.php');
}

// Mark message as read
if(isset($_GET['markread'])) {
    $msgid = intval($_GET['markread']);
    $useremail = $_SESSION['login'];
    $sql = "UPDATE tblmessages SET status = 1 WHERE id = :msgid AND userEmail = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':msgid', $msgid, PDO::PARAM_INT);
    $query->bindParam(':email', $useremail, PDO::PARAM_STR);
    $query->execute();
    header('location:user-messages.php');
}

// Delete/Archive message
if(isset($_GET['delete'])) {
    $msgid = intval($_GET['delete']);
    $useremail = $_SESSION['login'];
    $sql = "UPDATE tblmessages SET isArchived = 1 WHERE id = :msgid AND userEmail = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':msgid', $msgid, PDO::PARAM_INT);
    $query->bindParam(':email', $useremail, PDO::PARAM_STR);
    $query->execute();
    header('location:user-messages.php');
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<title>Car Rental Portal | My Messages</title>
<!--Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
<!--Custome Style -->
<link rel="stylesheet" href="assets/css/style.css" type="text/css">
<link rel="stylesheet" href="assets/css/modern-design.css" type="text/css">
<!--OWL Carousel slider-->
<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
<!--slick-slider -->
<link href="assets/css/slick.css" rel="stylesheet">
<!--bootstrap-slider -->
<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
<!--FontAwesome Font Style -->
<link href="assets/css/font-awesome.min.css" rel="stylesheet">

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
<style>
.message-card {
    background: #fff;
    border-left: 4px solid #0066ff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.message-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.message-card.unread {
    background: #f0f7ff;
    border-left-color: #0066ff;
    font-weight: 500;
}

.message-card.approved {
    border-left-color: #00a86b;
}

.message-card.rejected {
    border-left-color: #dc3545;
}

.message-card.pending {
    border-left-color: #ffa500;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 12px;
}

.message-title {
    font-size: 18px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.message-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.message-badge.approved {
    background: rgba(0, 168, 107, 0.1);
    color: #00a86b;
}

.message-badge.rejected {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.message-badge.pending {
    background: rgba(255, 165, 0, 0.1);
    color: #ffa500;
}

.message-badge.info {
    background: rgba(0, 102, 255, 0.1);
    color: #0066ff;
}

.message-meta {
    font-size: 13px;
    color: #666;
    margin-bottom: 12px;
    display: flex;
    gap: 20px;
}

.message-body {
    color: #555;
    line-height: 1.6;
    margin: 15px 0;
}

.message-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
}

.message-actions a {
    font-size: 13px;
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.message-actions .read-btn {
    background: #f0f7ff;
    color: #0066ff;
    border: 1px solid #0066ff;
}

.message-actions .read-btn:hover {
    background: #0066ff;
    color: #fff;
}

.message-actions .delete-btn {
    background: #ffe0e0;
    color: #dc3545;
    border: 1px solid #dc3545;
}

.message-actions .delete-btn:hover {
    background: #dc3545;
    color: #fff;
}

.no-messages {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.no-messages i {
    font-size: 48px;
    color: #ccc;
    margin-bottom: 20px;
}

.filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    border-bottom: 2px solid #e0e0e0;
}

.filter-tabs a {
    padding: 12px 20px;
    border: none;
    background: none;
    cursor: pointer;
    font-weight: 500;
    color: #666;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
    text-decoration: none;
}

.filter-tabs a.active {
    color: #0066ff;
    border-bottom-color: #0066ff;
}

.filter-tabs a:hover {
    color: #0066ff;
}

.unread-badge {
    display: inline-block;
    background: #dc3545;
    color: #fff;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    margin-left: 8px;
}

.profile-sidebar {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.sidebar-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #1a1a1a;
}

.sidebar-item {
    padding: 12px 0;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sidebar-item:last-child {
    border-bottom: none;
}

.sidebar-count {
    background: #f0f7ff;
    color: #0066ff;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 13px;
}
</style>
</head>
<body>

<!-- Start Switcher -->
<?php include('includes/colorswitcher.php');?>
<!-- /Switcher -->  

<!--Header--> 
<?php include('includes/header.php');?>
<!-- /Header --> 

<!--Page Header-->
<section class="page-header profile_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>My Messages</h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li>My Messages</li>
      </ul>
    </div>
  </div>
  <div class="dark-overlay"></div>
</section>
<!-- /Page Header--> 

<!--Profile-->
<section class="user-profile">
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <!-- User Info Sidebar -->
        <div class="profile-sidebar">
          <h4 class="sidebar-title"><i class="fa fa-bell" aria-hidden="true"></i> Messages</h4>
          
          <?php
          // Get message counts
          $useremail = $_SESSION['login'];
          
          // Total unread
          $sql = "SELECT COUNT(*) as total FROM tblmessages WHERE userEmail = :email AND status = 0 AND isArchived = 0";
          $query = $dbh->prepare($sql);
          $query->bindParam(':email', $useremail, PDO::PARAM_STR);
          $query->execute();
          $unreadCount = $query->fetch(PDO::FETCH_OBJ)->total;
          
          // Approved
          $sql = "SELECT COUNT(*) as total FROM tblmessages WHERE userEmail = :email AND messageType = 'booking_approved' AND isArchived = 0";
          $query = $dbh->prepare($sql);
          $query->bindParam(':email', $useremail, PDO::PARAM_STR);
          $query->execute();
          $approvedCount = $query->fetch(PDO::FETCH_OBJ)->total;
          
          // Rejected
          $sql = "SELECT COUNT(*) as total FROM tblmessages WHERE userEmail = :email AND messageType = 'booking_rejected' AND isArchived = 0";
          $query = $dbh->prepare($sql);
          $query->bindParam(':email', $useremail, PDO::PARAM_STR);
          $query->execute();
          $rejectedCount = $query->fetch(PDO::FETCH_OBJ)->total;
          
          // Pending
          $sql = "SELECT COUNT(*) as total FROM tblmessages WHERE userEmail = :email AND messageType = 'booking_pending' AND isArchived = 0";
          $query = $dbh->prepare($sql);
          $query->bindParam(':email', $useremail, PDO::PARAM_STR);
          $query->execute();
          $pendingCount = $query->fetch(PDO::FETCH_OBJ)->total;
          ?>
          
          <div class="sidebar-item">
            <span>Unread Messages</span>
            <span class="sidebar-count"><?php echo $unreadCount; ?></span>
          </div>
          <div class="sidebar-item">
            <span>Approved Requests</span>
            <span class="sidebar-count" style="background: rgba(0, 168, 107, 0.1); color: #00a86b;"><?php echo $approvedCount; ?></span>
          </div>
          <div class="sidebar-item">
            <span>Pending Requests</span>
            <span class="sidebar-count" style="background: rgba(255, 165, 0, 0.1); color: #ffa500;"><?php echo $pendingCount; ?></span>
          </div>
          <div class="sidebar-item">
            <span>Rejected Requests</span>
            <span class="sidebar-count" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;"><?php echo $rejectedCount; ?></span>
          </div>
        </div>
      </div>

      <div class="col-md-9">
        <!-- Filter Tabs -->
        <div class="filter-tabs">
          <a href="user-messages.php" class="active">All Messages</a>
          <a href="?filter=unread">Unread <?php if($unreadCount > 0) { ?><span class="unread-badge"><?php echo $unreadCount; ?></span><?php } ?></a>
          <a href="?filter=approved">Approved</a>
          <a href="?filter=pending">Pending</a>
          <a href="?filter=rejected">Rejected</a>
        </div>

        <!-- Messages -->
        <div class="messages-container">
          <?php
          $useremail = $_SESSION['login'];
          $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
          
          $sql = "SELECT * FROM tblmessages WHERE userEmail = :email AND isArchived = 0";
          
          if($filter == 'unread') {
              $sql .= " AND status = 0";
          } elseif($filter == 'approved') {
              $sql .= " AND messageType = 'booking_approved'";
          } elseif($filter == 'pending') {
              $sql .= " AND messageType = 'booking_pending'";
          } elseif($filter == 'rejected') {
              $sql .= " AND messageType = 'booking_rejected'";
          }
          
          $sql .= " ORDER BY createdDate DESC";
          
          $query = $dbh->prepare($sql);
          $query->bindParam(':email', $useremail, PDO::PARAM_STR);
          $query->execute();
          $results = $query->fetchAll(PDO::FETCH_OBJ);
          
          if($query->rowCount() > 0) {
              foreach($results as $result) {
                  $messageClass = $result->status == 0 ? 'unread' : 'read';
                  $typeClass = '';
                  $badgeText = '';
                  
                  switch($result->messageType) {
                      case 'booking_approved':
                          $typeClass = 'approved';
                          $badgeText = 'Approved';
                          break;
                      case 'booking_rejected':
                          $typeClass = 'rejected';
                          $badgeText = 'Rejected';
                          break;
                      case 'booking_pending':
                          $typeClass = 'pending';
                          $badgeText = 'Pending';
                          break;
                      default:
                          $typeClass = 'info';
                          $badgeText = 'Info';
                  }
                  ?>
          <div class="message-card <?php echo $messageClass; ?> <?php echo $typeClass; ?>">
            <div class="message-header">
              <h5 class="message-title"><?php echo htmlentities($result->messageTitle); ?></h5>
              <span class="message-badge <?php echo $typeClass; ?>"><?php echo $badgeText; ?></span>
            </div>
            
            <div class="message-meta">
              <span><i class="fa fa-calendar"></i> <?php echo date('d M Y, h:i A', strtotime($result->createdDate)); ?></span>
              <?php if($result->status == 0) { ?>
              <span><i class="fa fa-envelope"></i> Unread</span>
              <?php } else { ?>
              <span><i class="fa fa-check"></i> Read</span>
              <?php } ?>
            </div>
            
            <div class="message-body">
              <?php echo nl2br(htmlentities($result->messageBody)); ?>
            </div>
            
            <div class="message-actions">
              <?php if($result->status == 0) { ?>
              <a href="user-messages.php?markread=<?php echo $result->id; ?>" class="read-btn">
                <i class="fa fa-check"></i> Mark as Read
              </a>
              <?php } ?>
              <a href="user-messages.php?delete=<?php echo $result->id; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">
                <i class="fa fa-trash"></i> Delete
              </a>
            </div>
          </div>
          <?php 
              }
          } else {
              ?>
          <div class="no-messages">
            <i class="fa fa-inbox"></i>
            <h3>No Messages</h3>
            <p>You don't have any messages yet. Check back later for updates on your car rental requests.</p>
          </div>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /Profile --> 

<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer--> 

<!--Back to top-->
<div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>
<!--/Back to top--> 

<!-- Scripts --> 
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/interface.js"></script>
<script src="assets/js/bootstrap-slider.min.js"></script>
</body>
</html>
