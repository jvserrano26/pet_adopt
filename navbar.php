<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Haven</title>

    <!-- BOOTSTRAP CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f5f5;
        }

        #nav {
            padding: 20px 0 0 0;
            background-color: #469387;
        }

        .nav-link {
            color: black;
            transition: 0.5s;
            font-size: 17px;
            margin-left:10px;
            margin-right:10px;
        }

        .nav-link:hover {
            color: #efc65d;
        }

        /* ✅ DYNAMIC ACTIVE CLASS */
        .nav-link.active {
            color: #efc65d !important;
            font-weight: bold;
        }

        .navbar-nav {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        .bell {
            margin-left: auto;
            animation: shake 0.82s cubic-bezier(.36, .07, .19, .97) both infinite;
        }

        .bell button {
            text-align: center;
            border: none;
            background-color: transparent;
            color: black;
            margin: 0 20px 0 0;
            font-size: 30px;
        }
          .img-logo{
            margin: 0 0px 0 70px;
            
        }
    </style>
</head>

<body>
    <section class="container-fluid" id="nav">
        <nav class="navbar navbar-expand-md navbar-fixed-top">
            <div class="container-fluid">

                <img src="src/user_dashboard/logotext.png" alt="" width="130px" class="img-logo">

                <!-- Toggler Button -->
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span style="font-size: 28px; color: #d2b68a;">☰</span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">

                        <!-- ✅ Dynamic active class -->
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'user_dashboard.php' ? 'active' : '' ?>" 
                            href="user_dashboard.php">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pet.php' ? 'active' : '' ?>" 
                            href="pet.php">Pet</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>" 
                            href="about.php">About Us</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>" 
                            href="contact.php">Contact</a>
                        </li>

                        <li class="nav-item bell">
                            <button id="statusBtn" onclick="showStatusModal()" data-bs-toggle="modal" data-bs-target="#notifModal">🔔</button>
                        </li>

                        <li class="nav-item">
                            <a href="logout.php" class="btn btn-dark btn-sm">Logout</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </section>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
