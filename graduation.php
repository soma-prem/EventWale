<?php
// Database connection settings
$servername = "localhost";
$username = "root";       // Default XAMPP username
$password = "";           // Default XAMPP password is empty
$database = "eventwale";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events from the database
$sql = "SELECT * FROM furniture";
$result = $conn->query($sql);

$sql2 = "SELECT * FROM decoration";
$result2 = $conn->query($sql2);

$sql3 = "SELECT * FROM juice";
$result3 = $conn->query($sql3);

$sql4 = "SELECT * FROM snacks";
$result4 = $conn->query($sql4);

$sql5 = "SELECT * FROM salads";
$result5 = $conn->query($sql5);

$sql6 = "SELECT * FROM cakes";
$result6 = $conn->query($sql6);

$sql7 = "SELECT * FROM icecream";
$result7 = $conn->query($sql7);

$sql8 = "SELECT * FROM dessert";
$result8 = $conn->query($sql8);

$sql9 = "SELECT * FROM maincourse";
$result9 = $conn->query($sql9);

$sql10 = "SELECT * FROM electronic";
$result10 = $conn->query($sql10);

$sql11 = "SELECT * FROM flowers";
$result11 = $conn->query($sql11);

$sql12 = "SELECT * FROM cradle";
$result12 = $conn->query($sql12);



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Engagement Items Selection</title>
    <style>
        :root {
            --primary-color: #FFDAB9;
            --secondary-color: #FFE5D0;
            --text-color: #333333;
            --background-color: #FFFFFF;
            --border-color: #E5E5E5;
            --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            background-image: linear-gradient(rgba(255, 192, 203, 0.192), rgba(255, 255, 255, 0.205)), url('images/bg5.jpg');
            color: var(--text-color);
        }

        .back-btn {
            position: fixed;
            top: 30px;
            left: 30px;
            padding: 12px 24px;
            background:rgb(252, 251, 251);
            color:rgb(7, 3, 0);
            text-decoration: none;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            z-index: 1000;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:before {
            content: '←';
            font-size: 20px;
        }

        .back-btn:hover {
            
            transform: translateX(-5px);
            background-color: var(--secondary-color);
            box-shadow: var(--hover-shadow);
        }

        .header {
            background-color: var(--primary-color);
            padding: 2.5rem;
            text-align: center;
            box-shadow: var(--card-shadow);
            position: relative;
            margin-bottom: 3rem;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }

        .header:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 2px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .category-filter {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            margin: 2rem 0 3rem;
            padding: 0 1rem;
        }

        .category-btn {
            padding: 12px 24px;
            background-color: var(--primary-color);
            color: #333;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            box-shadow: var(--card-shadow);
        }

        .category-btn:hover {
            transform: translateY(-2px);
            background-color: var(--secondary-color);
            box-shadow: var(--hover-shadow);
        }

        .category-btn.active {
            background-color: var(--secondary-color);
            box-shadow: var(--hover-shadow);
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2.5rem;
            padding: 1rem;
        }

        .item-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid var(--border-color);
        }

        .item-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--hover-shadow);
        }

        .item-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .item-details {
            padding: 1.5rem;
            position: relative;
            background: white;
        }

        .item-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.75rem;
            display: block;
        }

        .item-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .item-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            display: block;
            margin-bottom: 1rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background-color: #F8F8F8;
            border-radius: 8px;
            margin-top: 1rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            cursor: pointer;
            user-select: none;
            transition: all 0.3s ease;
        }

        .checkbox-wrapper:hover {
            background-color: #f0f0f0;
        }

        .custom-checkbox {
            width: 24px;
            height: 24px;
            cursor: pointer;
            border-radius: 6px;
            border: 2px solid #333;
            appearance: none;
            position: relative;
            background: white;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .custom-checkbox:checked {
            background-color: #333;
        }

        .custom-checkbox:checked:after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 16px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .selected-items {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #FFDAB9;
            padding: 1.5rem 2rem;
            box-shadow: 0 -10px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: flex-end;
            align-items: center;
            z-index: 1000;
            border-top: 1px solid var(--border-color);
        }

        .buttons-container {
            display: flex;
            gap: 1.5rem;
        }

        .proceed-btn, .cancel-btn {
            padding: 14px 32px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .proceed-btn {
            background:rgb(252, 251, 251);
            color:rgb(7, 3, 0);
            border: none;
        }

        .proceed-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }

        .proceed-btn:after {
            content: '→';
            font-size: 20px;
        }

        .cancel-btn {
            background:rgb(252, 251, 251);
            color:rgb(7, 3, 0);
            border: none;
        }

        .cancel-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .items-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 2rem;
            }

            .selected-items {
                padding: 1rem;
            }

            .buttons-container {
                width: 100%;
                justify-content: center;
                gap: 1rem;
            }

            .proceed-btn, .cancel-btn {
                padding: 12px 24px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.75rem;
            }

            .category-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .item-card {
                border-radius: 8px;
            }

            .item-image {
                height: 220px;
            }

            .item-details {
                padding: 1.25rem;
            }

            .buttons-container {
                flex-direction: column;
                width: 100%;
            }

            .proceed-btn, .cancel-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <a href="index.php#anniversary" class="back-btn">Back</a>
    <div class="header">
        <h1>Select Items for Your Anniversary</h1>
    </div>

    <div class="container">
        <div class="category-filter">
        <button class="category-btn active" data-category="furniture">Furniture</button>
            <button class="category-btn" data-category="decoration">Decoration</button>
            <button class="category-btn" data-category="Electronic">Electronic</button>
            <button class="category-btn" data-category="cakes">Cakes</button>
            <button class="category-btn" data-category="juice">juice</button>
            <button class="category-btn" data-category="snacks">snacks</button>
            <button class="category-btn" data-category="salads">salads</button>
            <button class="category-btn" data-category="ice_cream">ice cream</button>
            <button class="category-btn" data-category="dessert">dessert</button>
            <button class="category-btn" data-category="main_course">main course</button>
            <button class="category-btn" data-category="flowers">Flowers</button>
        </div>

        <div class="items-grid" style="padding: 70px;margin: 20px;">
        <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="item-card" data-category="furniture">
                            <img src="' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row["price"]) . '/piece</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
                echo "<p>No furniture items found.</p>";
            }
            ?>
       
            <?php
            if ($result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    echo '<div class="item-card" data-category="decoration">
                            <img src="' . htmlspecialchars($row2["image"]) . '" alt="' . htmlspecialchars($row2["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row2["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row2["price"]) . '</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row2["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
               
            }
            ?>
        
            <?php
            if ($result3->num_rows > 0) {
                while ($row3 = $result3->fetch_assoc()) {
                    echo '<div class="item-card" data-category="juice">
                            <img src="' . htmlspecialchars($row3["image"]) . '" alt="' . htmlspecialchars($row3["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row3["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row3["price"]) . '/glass</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row3["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
                
            }
            ?>

            <?php
            if ($result4->num_rows > 0) {
                while ($row4 = $result4->fetch_assoc()) {
                    echo '<div class="item-card" data-category="snacks">
                            <img src="' . htmlspecialchars($row4["image"]) . '" alt="' . htmlspecialchars($row4["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row4["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row4["price"]) . '/plate</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row4["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
                
            }
            ?>

            <?php
            if ($result5->num_rows > 0) {
                while ($row5 = $result5->fetch_assoc()) {
                    echo '<div class="item-card" data-category="salads">
                            <img src="' . htmlspecialchars($row5["image"]) . '" alt="' . htmlspecialchars($row5["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row5["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row5["price"]) . '/plate</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row5["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
                
            }
            ?>

            <?php
            if ($result6->num_rows > 0) {
                while ($row6 = $result6->fetch_assoc()) {
                    echo '<div class="item-card" data-category="cakes">
                            <img src="' . htmlspecialchars($row6["image"]) . '" alt="' . htmlspecialchars($row6["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row6["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row6["price"]) . '/kilo</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row6["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
                
            }
            ?>

            <?php
            if ($result7->num_rows > 0) {
                while ($row7 = $result7->fetch_assoc()) {
                    echo '<div class="item-card" data-category="ice_cream">
                            <img src="' . htmlspecialchars($row7["image"]) . '" alt="' . htmlspecialchars($row7["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row7["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row7["price"]) . '/plate</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row7["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
                
            }
            ?>

            <?php
            if ($result8->num_rows > 0) {
                while ($row8 = $result8->fetch_assoc()) {
                    echo '<div class="item-card" data-category="dessert">
                            <img src="' . htmlspecialchars($row8["image"]) . '" alt="' . htmlspecialchars($row8["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row8["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row8["price"]) . '/kilo</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row8["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
                
            }
            ?>


            <?php
            if ($result9->num_rows > 0) {
                while ($row9 = $result9->fetch_assoc()) {
                    echo '<div class="item-card" data-category="main_course">
                            <img src="' . htmlspecialchars($row9["image"]) . '" alt="' . htmlspecialchars($row9["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row9["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row9["price"]) . '/plate</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row9["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
                
            }
            ?>

        <?php
            if ($result10->num_rows > 0) {
                while ($row10 = $result10->fetch_assoc()) {
                    echo '<div class="item-card" data-category="Electronic">
                            <img src="' . htmlspecialchars($row10["image"]) . '" alt="' . htmlspecialchars($row10["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row10["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row10["price"]) . '/set</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row10["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
                
            }
            ?>

            <?php
            if ($result11->num_rows > 0) {
                while ($row11 = $result11->fetch_assoc()) {
                    echo '<div class="item-card" data-category="flowers">
                            <img src="' . htmlspecialchars($row11["image"]) . '" alt="' . htmlspecialchars($row11["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row11["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row11["price"]) . '/kilo</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row11["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
                
            }
            ?>

            <?php
            if ($result12->num_rows > 0) {
                while ($row12 = $result12->fetch_assoc()) {
                    echo '<div class="item-card" data-category="cradle">
                            <img src="' . htmlspecialchars($row12["image"]) . '" alt="' . htmlspecialchars($row12["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row12["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row12["price"]) . '/piece</span>
                            </div>
                            <div class="checkbox-wrapper">
                                <input type="checkbox" class="custom-checkbox" data-price="' . htmlspecialchars($row12["price"]) . '">
                                <label>Add to Selection</label>
                            </div>
                        </div>';
                }
            } else {
               
            }
            ?>
        </div>

        <div class="selected-items">
            <div class="buttons-container">
                <button class="cancel-btn">Cancel Selection</button>
                <button class="proceed-btn">Proceed to Booking</button>
            </div>
        </div>
    </div>

    <script>
        // Category filter functionality
        const categoryBtns = document.querySelectorAll('.category-btn');
        const itemCards = document.querySelectorAll('.item-card');

        categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                categoryBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');

                const category = btn.dataset.category;
                
                // Show/hide items based on category
                itemCards.forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Trigger click on furniture button to show furniture items by default
        document.querySelector('[data-category="furniture"]').click();

        // Cancel button functionality
        const cancelBtn = document.querySelector('.cancel-btn');
        cancelBtn.addEventListener('click', () => {
            // Uncheck all checkboxes
            document.querySelectorAll('.custom-checkbox:checked').forEach(checkbox => {
                checkbox.checked = false;
            });
        });

        // Proceed button functionality
        const proceedBtn = document.querySelector('.proceed-btn');
        proceedBtn.addEventListener('click', () => {
            const selectedItems = [];
            document.querySelectorAll('.custom-checkbox:checked').forEach(checkbox => {
                const itemCard = checkbox.closest('.item-card');
                const itemName = itemCard.querySelector('.item-name').textContent;
                const itemPrice = itemCard.querySelector('.item-price').textContent;
                const category = itemCard.dataset.category;
                selectedItems.push({
                    name: itemName,
                    price: itemPrice,
                    category: category
                });
            });

            if (selectedItems.length === 0) {
                alert('Please select at least one item before proceeding.');
                return;
            }

            // Store selected items in localStorage
            localStorage.setItem('selectedItems', JSON.stringify(selectedItems));
            window.location.href = 'bill.html';
        });

        // Checkbox wrapper click functionality
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxWrappers = document.querySelectorAll('.checkbox-wrapper');
            
            checkboxWrappers.forEach(wrapper => {
                wrapper.addEventListener('click', function() {
                    const checkbox = this.querySelector('.custom-checkbox');
                    checkbox.checked = !checkbox.checked;
                });
            });
        });
    </script>
</body>
</html>
