<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    #footer {
    background: #222;
    color: #fff;
}

#footer a:hover {
    text-decoration: underline;
}
.img-footer{
    margin: 0 0 10px 0;
}
.footer-p{
    font-size: 17px;
    opacity: 0.8;
    
}
ul{
     list-style-type: none;
     opacity: 0.8;
}
.footer-end{
    background-color: #031926;
    padding: 10px;
    border-top: #f5f5f5 solid 2px;
    text-align: center;
    color: #f5f5f5;
}
</style>
<body>
    <!--  footer -->
  <footer class="container-fluid py-5 bg-dark " id="footer">
    <div class="row px-5">
        <div class="col-md-4 mb-4">
            <img class="img-footer" src="src/user_dashboard/logotext.png" width="200" alt="">
            <p class="footer-p mt-3">
                At Pet Haven, every animal deserves a loving home. Adopt a friend today and change a life.
            </p>
        </div>

        <div class="col-md-4 mb-4">
            <h4>Explore</h4>
            <ul class="list-unstyled">
                <li><a href="pet.php" class="text-light text-decoration-none">Pets</a></li>
                <li><a href="about.php" class="text-light text-decoration-none">About Us</a></li>
                <li><a href="contact.php" class="text-light text-decoration-none">Contact</a></li>
            </ul>
        </div>

        <div class="col-md-4 mb-4">
            <h4>Contact</h4>
            <ul class="list-unstyled">
                <li>Villa Victorias, Brgy. 13, Victorias City</li>
                <li>Negros Occidental, Philippines</li>
                <li>Pethaven@gmail.com</li>
                <li>+63 912 345 6789</li>
            </ul>
        </div>
    </div>
</footer>

<div class="footer-end"> Copyright © 2025 Pet Haven Adoption Center - All Rights Reserved</div>
</body>
</html>