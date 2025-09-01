<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Contact - Hospital Appointment Management System</title>
    <style>
        /* Body and Background */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('assets/images/hospital_bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        /* Navbar like Home page */
        .navbar {
            display: flex;
            justify-content: flex-end;
             background-color: rgba(0, 123, 127, 0.85);
            padding: 25px 35px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        body::before {
         content: "";
         position: fixed;
         top: 0; left: 0; right: 0; bottom: 0;
         background-color: rgba(255, 255, 255, 0.7);
         z-index: -1;
}

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 30px;
            margin: 0;
            padding: 0;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        

        /* Contact Container */
        .contact-container {
            max-width: 900px;
            margin: 60px auto;
            padding: 25px;
            background-color: rgba(255, 255, 255, 0.75);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        .contact-container h1 {
            text-align: left;
            color: #004085;
            margin-bottom: 25px;
        }

        .contact-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
        }

        .contact-info, .contact-form {
            flex: 1 1 400px;
        }

        .contact-info h3, .contact-form h3 {
            color: #004085;
            margin-bottom: 12px;
        }

        .contact-info p {
            margin: 6px 0;
            font-size: 15px;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .contact-form input[type="submit"] {
            background-color: #004085;
            color: white;
            border: none;
            cursor: pointer;
        }

        .contact-form input[type="submit"]:hover {
            background-color: #002752;
        }

        .map {
            margin-top: 15px;
            border-radius: 5px;
            overflow: hidden;
        }

        .back-home {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #004085;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-home:hover {
            background-color: #002752;
        }

        /* Footer like Home page */
        footer.footer {
            background-color: rgba(0, 123, 127, 0.85);
            color: white;
            text-align: center;
            padding: 50px 0;
            margin-top: 40px;
        }

        @media(max-width: 768px) {
            .contact-grid {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="authentication/select_role.php">Login</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
        </ul>
    </nav>

    <!-- Contact Container -->
    <div class="contact-container">
        <h1>Contact Us</h1>

        <div class="contact-grid">
            <!-- Contact Info -->
            <div class="contact-info">
                <h3>Get in Touch</h3>
                <p><strong>Address:</strong> 123 Health St, Colombo, Sri Lanka</p>
                <p><strong>Phone:</strong> +94 123 456 789</p>
                <p><strong>Email:</strong> info@hospital.com</p>
                <p><strong>Working Hours:</strong> Mon - Sat: 8:00 AM - 8:00 PM</p>

                <div class="map">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3163.106622291381!2d79.86124341508905!3d6.927078895017066!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25902df3bfc1b%3A0x6f7ff1ed0f8db55b!2sColombo%2C%20Sri%20Lanka!5e0!3m2!1sen!2sus!4v1692025612345!5m2!1sen!2sus" 
                        width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form">
                <h3>Send a Message</h3>
                <form action="send_message.php" method="POST">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                    <input type="submit" value="Send Message">
                </form>
            </div>
        </div>

      
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>© 2025 Hospital Appointment Management System. All rights reserved.</p>
    </footer>

</body>
</html>

