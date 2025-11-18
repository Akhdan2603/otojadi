<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Successful | OtoJadi</title>
  <link rel="stylesheet" href="css/success.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/index copy.css" />
  <link rel="icon" href="public/images/logo-otojadi.png" type="image/gif" sizes="16x16">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
  <header class="navbar">
      <div class="nav-left">
          <a href="index.php"><img src="public/images/nav_logo.png" alt="Logo OtoJadi" class="logo"></a>
      </div>
  </header>

  <section class="payment-success">
    <img src="public/images/success-icon.png" alt="Success">
    <h2>Payment Successful!</h2>
    <p>Thank you for subscribing to OtoJadi.</p>
    <p>To activate your subscription features, please contact us via WhatsApp.</p>
    <a id="waLink" target="_blank">
      <button class="btn-select">Contact via WhatsApp</button>
    </a>
  </section>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const plan = urlParams.get("plan");
    const price = urlParams.get("price");
    const waText = encodeURIComponent(`Hello,\nusername: \nemail:\nI've completed the payment for the OtoJadi subscription (${plan}) - Rp ${price}.\nPlease activate my subscription.`);
    document.getElementById("waLink").href = `https://wa.me/6285959872110?text=${waText}`;
  </script>
</body>
<footer class="footer-otojadi">
      <div class="footer-top">
      <div class="footer-links">
          <a href="index.php">Home</a>
          <span>|</span>
          <a href="#">Help Center</a>
          <span>|</span>
          <a href="#">License Terms</a>
          <span>|</span>
          <a href="#">Terms & Conditions</a>
      </div>

      <div class="footer-social">
          <a href="https://www.instagram.com/otojadi/"><i data-lucide="instagram"></i></a>
          <a href="#"><i data-lucide="twitter"></i></a>
          <a href="https://www.facebook.com/profile.php?id=61582317293988"><i data-lucide="facebook"></i></a>
      </div>
      </div>

      <p class="footer-copy">
      © 2025 OtoJadi. Trademarks and brands are the property of their respective owners.
      </p>
</footer>
</html>
