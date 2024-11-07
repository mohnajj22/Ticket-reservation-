<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "event_ticketing_system";

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully!";
} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
}

// You can use $conn for your database queries here

// Close the connection (optional, as it will close when the script ends)
$conn = null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticketing System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #333;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        .nav {
            background-color: #444;
            padding: 1rem;
        }

        .nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            margin: 0 0.5rem;
        }

        .nav a:hover {
            background-color: #555;
        }

        .main {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            flex: 1 1 300px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1rem;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .event-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .event-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            display: none;
        }

        @media (max-width: 768px) {
            .nav {
                flex-direction: column;
            }
            
            .nav a {
                display: block;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Event Ticketing System</h1>
    </div>

    <div class="nav">
        <a href="#" onclick="showSection('home')">Home</a>
        <a href="#" onclick="showSection('events')">Events</a>
        <a href="#" onclick="showSection('login')">Login</a>
        <a href="#" onclick="showSection('register')">Register</a>
    </div>

    <div class="container">
        <div id="home" class="section">
            <h2>Welcome to Event Ticketing System</h2>
            <p>Find and book tickets for your favorite events!</p>
        </div>

        <div id="events" class="section" style="display: none;">
            <h2>Available Events</h2>
            <div class="event-list">
                <!-- Events will be loaded dynamically -->
            </div>
        </div>

        <div id="login" class="section" style="display: none;">
            <div class="card">
                <h2>Login</h2>
                <form id="loginForm" onsubmit="return handleLogin(event)">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" required>
                    </div>
                    <button type="submit" class="btn">Login</button>
                </form>
            </div>
        </div>

        <div id="register" class="section" style="display: none;">
            <div class="card">
                <h2>Register</h2>
                <form id="registerForm" onsubmit="return handleRegister(event)" action="#">
                    <div class="form-group">
                        <label for="regName">Name</label>
                        <input type="text"  name= "name" id="regName" required>
                    </div>
                    <div class="form-group">
                        <label for="regEmail">Email</label>
                        <input type="email" name="email" id="regEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="regPassword">Password</label>
                        <input type="password" name="password" id="regPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="regPhone">Phone Number</label>
                        <input type="tel"  name= "phone" id="regPhone" required>
                    </div>
                    <button type="submit" name="register" class="btn">Register</button>
                </form>
            </div>
        </div>
    </div>

    <div class="notification" id="notification"></div>

    <div class="footer">
        <p>&copy; 2024 Event Ticketing System. All rights reserved.</p>
    </div>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }

        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#f44336';
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        async function handleLogin(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('index.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'login',
                        email: email,
                        password: password
                    })
                });

                const data = await response.json();
                if (data.success) {
                    showNotification('Login successful!');
                    // Redirect or update UI
                } else {
                    showNotification('Login failed!', 'error');
                }
            } catch (error) {
                showNotification('An error occurred!', 'error');
            }
        }

        async function handleRegister(event) {
            event.preventDefault();
            // Similar implementation to handleLogin
        }

        // Load events when the page loads
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const response = await fetch('index.php?action=getEvents');
                const events = await response.json();
                const eventList = document.querySelector('.event-list');
                
                events.forEach(event => {
                    const eventCard = document.createElement('div');
                    eventCard.className = 'event-card';
                    eventCard.innerHTML = `
                        <h3>${event.eventTitle}</h3>
                        <p>Date: ${new Date(event.eventDate).toLocaleDateString()}</p>
                        <p>Price: $${event.ticketPrice}</p>
                        <button class="btn" onclick="bookTicket('${event.eventId}')">Book Now</button>
                    `;
                    eventList.appendChild(eventCard);
                });
            } catch (error) {
                showNotification('Failed to load events!', 'error');
            }
        });

        async function bookTicket(eventId) {
            // Implementation for booking tickets
        }
    </script>
</body>
</html>
