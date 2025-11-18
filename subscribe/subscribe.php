<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plans - Otojadi</title>
    <link rel="stylesheet" href="..\css\Subs-style.css">
</head>

<body>

    <?php
    // --- PHP DYNAMIC DATA ---
    $nama_toko = "Otojadi"; // Brand name

    $paket = [
        '1' => ['harga_per_bulan' => 9000, 'total' => 9000, 'best_value' => false],
        '3' => ['harga_per_bulan' => 8333, 'total' => 25000, 'best_value' => false],
        '6' => ['harga_per_bulan' => 8000, 'total' => 48000, 'best_value' => true], // 6 months is the best value
        '12' => ['harga_per_bulan' => 8166, 'total' => 98000, 'best_value' => false]
    ];
    ?>

    <nav class="navbar">
        <div class="img">
            <a href="index.php"><img src="..\public/images/nav_logo.png" alt="" style="height: 70px;"></a>
        </div>
    </nav>

    <div class="announcement-bar">
        GET UNLIMITED ACCESS TO ALL TEMPLATES
    </div>

    <div class="container">

        <div class="header-section">
            <h1>Full Access to Professional Presentation Templates</h1>
            <p>Choose the subscription plan that best suits your presentation needs.</p>
        </div>

        <div class="pricing-grid">

            <div class="pricing-card">
                <h2>1 Month</h2>
                <div class="price">
                    <?php echo "Rp " . number_format($paket['1']['harga_per_bulan'], 0, ',', '.'); ?>
                    <span>/month</span>
                </div>
                <div class="price-total">
                    Billed once at Rp <?php echo number_format($paket['1']['total'], 0, ',', '.'); ?>
                </div>

                <ul class="features-list">
                    <li><span class="icon">&#10003;</span> Unlimited downloads access</li>
                    <li><span class="icon">&#10003;</span> Hundreds of templates</li>
                    <li><span class="icon">&#10003;</span> 100% easily editable templates</li>
                    <li><span class="icon">&#10003;</span> Professional & modern designs</li>
                    <li><span class="icon">&#10003;</span> Simple commercial license</li>
                    <li><span class="icon">&#10003;</span> New templates added weekly</li>
                </ul>
                <a href="#" class="btn-subscribe">Start Subscribing</a>
            </div>

            <div class="pricing-card">
                <h2>3 Months</h2>
                <div class="price">
                    <?php echo "Rp " . number_format($paket['3']['harga_per_bulan'], 0, ',', '.'); ?>
                    <span>/month</span>
                </div>
                <div class="price-total">
                    Billed every 3 months at Rp <?php echo number_format($paket['3']['total'], 0, ',', '.'); ?>
                </div>

                <ul class="features-list">
                    <li><span class="icon">&#10003;</span> Unlimited downloads access</li>
                    <li><span class="icon">&#10003;</span> Hundreds of templates</li>
                    <li><span class="icon">&#10003;</span> 100% easily editable templates</li>
                    <li><span class="icon">&#10003;</span> Professional & modern designs</li>
                    <li><span class="icon">&#10003;</span> Simple commercial license</li>
                    <li><span class="icon">&#10003;</span> New templates added weekly</li>
                </ul>
                <a href="#" class="btn-subscribe">Start Subscribing</a>
            </div>

            <div class="pricing-card best-value">
                <div class="best-value-badge">BEST VALUE</div>
                <h2>6 Months</h2>
                <div class="price">
                    <?php echo "Rp " . number_format($paket['6']['harga_per_bulan'], 0, ',', '.'); ?>
                    <span>/month</span>
                </div>
                <div class="price-total">
                    Billed every 6 months at Rp <?php echo number_format($paket['6']['total'], 0, ',', '.'); ?>
                </div>

                <ul class="features-list">
                    <li><span class="icon">&#10003;</span> Unlimited downloads access</li>
                    <li><span class="icon">&#10003;</span> Hundreds of templates</li>
                    <li><span class="icon">&#10003;</span> 100% easily editable templates</li>
                    <li><span class="icon">&#10003;</span> Professional & modern designs</li>
                    <li><span class="icon">&#10003;</span> Simple commercial license</li>
                    <li><span class="icon">&#10003;</span> New templates added weekly</li>
                </ul>
                <a href="#" class="btn-subscribe">Start Subscribing</a>
            </div>

            <div class="pricing-card">
                <h2>12 Months</h2>
                <div class="price">
                    <?php echo "Rp " . number_format($paket['12']['harga_per_bulan'], 0, ',', '.'); ?>
                    <span>/month</span>
                </div>
                <div class="price-total">
                    Billed every 12 months at Rp <?php echo number_format($paket['12']['total'], 0, ',', '.'); ?>
                </div>

                <ul class="features-list">
                    <li><span class="icon">&#10003;</span> Unlimited downloads access</li>
                    <li><span class="icon">&#10003;</span> Hundreds of templates</li>
                    <li><span class="icon">&#10003;</span> 100% easily editable templates</li>
                    <li><span class="icon">&#10003;</span> Professional & modern designs</li>
                    <li><span class="icon">&#10003;</span> Simple commercial license</li>
                    <li><span class="icon">&#10003;</span> New templates added weekly</li>
                </ul>
                <a href="#" class="btn-subscribe">Start Subscribing</a>
            </div>

        </div> </div> <div class="trust-section-wrapper">

        <div class="trust-section-grid">
            <div class="column">
                <h3>Trusted Payment Methods</h3>
                <p>We accept all popular Indonesian payment methods. Quick and easy.</p>
                <div class="payment-methods">
                    <img class="payment-logo-img" title="QRIS" alt="QRIS" src="..\public\images\qris.jpg">
                    <img class="payment-logo-img" title="DANA" alt="DANA" src="..\public\images\dana.jpeg">
                    <img class="payment-logo-img" title="ShopeePay" alt="ShopeePay" src="..\public\images\spay.jpg">
                    <img class="payment-logo-img" title="OVO" alt="OVO" src="..\public\images\ovo.jpeg">
                </div>
            </div>
            <div class="column">
                <h3>Secure Payments</h3>
                <p>All transactions are processed through a secure and certified encrypted payment gateway, ensuring your financial data's safety.</p>
            </div>
            <div class="column">
                <h3>Cancel Anytime</h3>
                <p>No long-term contracts. You can easily cancel your subscription anytime through your account page.</p>
            </div>
        </div>

       
    </div> <div class="faq-container">
        <h2>Frequently Asked Questions (FAQs)</h2>

        <details class="faq-item">
            <summary>What is Otojadi?</summary>
            <div>
                <p>Otojadi is a subscription platform providing unlimited download access to thousands of professional, 100% editable presentation templates (PowerPoint & Google Slides) for your business, academic, and creative needs.</p>
            </div>
        </details>

        <details class="faq-item">
            <summary>Are there any download limits?</summary>
            <div>
                <p>None! As long as you have an active subscription, you can download as many templates as you need with no daily, weekly, or monthly limits.</p>
            </div>
        </details>

        <details class="faq-item">
            <summary>How does the licensing work?</summary>
            <div>
                <p>Every template you download comes with a simple commercial license. You are free to use them for personal projects and client projects. However, you are not allowed to resell or redistribute the original template files.</p>
            </div>
        </details>

        <details class="faq-item">
            <summary>Can I cancel at any time?</summary>
            <div>
                <p>Absolutely. You can cancel your plan at any time. If you cancel, you can still use your account and all downloaded templates until the end of your current billing period.</p>
            </div>
        </details>

    </div> <footer class="footer">
        &copy; <?php echo date("Y"); ?> <?php echo $nama_toko; ?>. All Rights Reserved.
    </footer>

</body>
</html>