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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
        }

        .header {
            background-color: #FFDAB9;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
            padding: 70px 20px;
        }

        .item-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .item-card:hover {
            transform: translateY(-5px);
        }

        .item-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
        }

        .item-name {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .item-price {
            color: #e67e22;
            font-weight: bold;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .selected-items {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #FFDAB9;
            padding: 1rem;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-price {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .buttons-container {
            display: flex;
            gap: 1rem;
        }

        .proceed-btn, .cancel-btn {
            padding: 0.8rem 2rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .proceed-btn {
            background: #e67e22;
            color: white;
            border: none;
        }

        .proceed-btn:hover {
            background: #d35400;
        }

        .cancel-btn {
            background: white;
            color: #e67e22;
            border: 2px solid #e67e22;
        }

        .cancel-btn:hover {
            background: #fff3e0;
        }

        .category-filter {
            margin: 2rem 0;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            padding: 0 1rem;
        }

        .category-btn {
            padding: 0.8rem 1.5rem;
            border: 2px solid #FFDAB9;
            background: white;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            min-width: 120px;
            text-align: center;
        }

        .category-btn.active {
            background: #FFDAB9;
            color: #333;
            transform: scale(1.05);
        }

        /* Add back button styles */
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 12px 24px;
            background: rgba(230, 126, 34, 0.9);
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .back-btn:hover {
            background: rgba(211, 84, 0, 0.95);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <a href="index.php#anniversary" class="back-btn">← Back</a>
    <div class="header">
        <h1>Select Items for Your Freshers Party</h1>
    </div>

    <div class="container">
        <div class="category-filter">
            <button class="category-btn active" data-category="furniture">Furniture</button>
            <button class="category-btn" data-category="cakes">Cakes</button>
            <button class="category-btn" data-category="juice">juice</button>
            <button class="category-btn" data-category="snacks">snacks</button>
            <button class="category-btn" data-category="salads">salads</button>
            <button class="category-btn" data-category="ice_cream">ice cream</button>
            <button class="category-btn" data-category="dessert">dessert</button>
            <button class="category-btn" data-category="main_course">main course</button>
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
                                <span class="item-price">₹' . htmlspecialchars($row2["price"]) . '/piece</span>
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
                                <span class="item-price">₹' . htmlspecialchars($row3["price"]) . '/piece</span>
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
                                <span class="item-price">₹' . htmlspecialchars($row4["price"]) . '/piece</span>
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
                                <span class="item-price">₹' . htmlspecialchars($row5["price"]) . '/piece</span>
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
                                <span class="item-price">₹' . htmlspecialchars($row6["price"]) . '/piece</span>
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
                                <span class="item-price">₹' . htmlspecialchars($row7["price"]) . '/piece</span>
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
                                <span class="item-price">₹' . htmlspecialchars($row8["price"]) . '/piece</span>
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
                                <span class="item-price">₹' . htmlspecialchars($row9["price"]) . '/piece</span>
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
                    echo '<div class="item-card" data-category="electronic">
                            <img src="' . htmlspecialchars($row10["image"]) . '" alt="' . htmlspecialchars($row10["name"]) . '" class="item-image">
                            <div class="item-details">
                                <span class="item-name">' . htmlspecialchars($row10["name"]) . '</span>
                                <span class="item-price">₹' . htmlspecialchars($row10["price"]) . '/piece</span>
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
                                <span class="item-price">₹' . htmlspecialchars($row11["price"]) . '/piece</span>
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
            <div class="total-price"></div>
            <div class="buttons-container">
                <button class="cancel-btn">Cancel Selection</button>
                <button class="proceed-btn">Proceed to Booking</button>
            </div>
        </div>
    </div>

    <script>
        // Filter functionality
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
                    if (card.dataset.category === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Trigger click on furniture button to show furniture items by default
        document.querySelector('[data-category="furniture"]').click();

        /*Calculate total price
        const checkboxes = document.querySelectorAll('.custom-checkbox');
        const totalPriceElement = document.querySelector('.total-price');*/

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });

        /*function updateTotal() {
            let total = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    total += parseInt(checkbox.dataset.price);
                }
            });
            totalPriceElement.textContent = `Total: ₹${total}`;
        }*/

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

        // Cancel selection functionality
        const cancelBtn = document.querySelector('.cancel-btn');
        cancelBtn.addEventListener('click', () => {
            // Uncheck all checkboxes
            document.querySelectorAll('.custom-checkbox:checked').forEach(checkbox => {
                checkbox.checked = false;
            });
            // Update total
            updateTotal();
        });
    </script>
</body>
</html>
