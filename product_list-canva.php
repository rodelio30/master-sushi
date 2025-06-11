

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Sushi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Noto+Serif+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
            background-color: #faf7f2;
            color: #333;
        }
        .logo-text {
            font-family: 'Noto Serif JP', serif;
        }
        .category-btn.active {
            background-color: #D94F4F;
            color: white;
        }
        .category-btn:hover:not(.active) {
            background-color: rgba(217, 79, 79, 0.1);
        }
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .add-btn {
            transition: all 0.3s ease;
        }
        .add-btn:hover {
            background-color: #c73e3e;
        }
        .chopstick {
            position: relative;
            height: 40px;
            width: 4px;
            background-color: #8B4513;
            border-radius: 2px;
            transform: rotate(30deg);
        }
        .chopstick:before {
            content: "";
            position: absolute;
            height: 40px;
            width: 4px;
            background-color: #8B4513;
            border-radius: 2px;
            transform: rotate(-15deg);
            left: 8px;
        }
    </style>
</head>
<body>
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header Section -->
        <header class="flex flex-col md:flex-row justify-between items-center mb-12 border-b border-gray-200 pb-6">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="mr-4 hidden md:block">
                    <div class="chopstick"></div>
                </div>
                <div>
                    <h1 class="logo-text text-4xl font-bold text-gray-800">Master Sushi</h1>
                    <p class="text-sm text-gray-600 mt-1">From Sushi Rolls to Sauces – Everything You Crave!</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-white p-2 rounded-full shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <button class="bg-white p-2 rounded-full shadow-md relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </button>
            </div>
        </header>

        <!-- Category Filter -->
        <div class="mb-10 overflow-x-auto pb-2">
            <div class="flex space-x-2 md:space-x-4 min-w-max">
                <button class="category-btn active px-6 py-2 rounded-full shadow-md text-sm font-medium">All</button>
                <button class="category-btn px-6 py-2 bg-white rounded-full shadow-md text-sm font-medium">Sushi Rolls</button>
                <button class="category-btn px-6 py-2 bg-white rounded-full shadow-md text-sm font-medium">Platters</button>
                <button class="category-btn px-6 py-2 bg-white rounded-full shadow-md text-sm font-medium">Sides</button>
                <button class="category-btn px-6 py-2 bg-white rounded-full shadow-md text-sm font-medium">Drinks</button>
                <button class="category-btn px-6 py-2 bg-white rounded-full shadow-md text-sm font-medium">Sauces</button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Sushi Roll 1 -->
            <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                <div class="h-48 bg-gray-200 relative">
                    <svg class="absolute inset-0 w-full h-full text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <circle cx="50" cy="50" r="30" fill="#D94F4F" opacity="0.2"/>
                        <circle cx="50" cy="50" r="20" fill="#D94F4F" opacity="0.3"/>
                        <path d="M30,50 C30,40 40,30 50,30 C60,30 70,40 70,50 C70,60 60,70 50,70 C40,70 30,60 30,50 Z" fill="#333" opacity="0.8"/>
                        <circle cx="45" cy="45" r="2" fill="white"/>
                    </svg>
                    <div class="absolute top-2 right-2 bg-white bg-opacity-80 rounded-full px-2 py-1 text-xs font-medium text-gray-700">Popular</div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">California Roll</h3>
                    <p class="text-gray-600 text-sm mb-3">Crab, avocado, cucumber wrapped in seaweed and rice</p>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-lg">$8.95</span>
                        <button class="add-btn bg-red-500 text-white px-4 py-1 rounded-full text-sm font-medium">Add to Cart</button>
                    </div>
                </div>
            </div>

            <!-- Sushi Roll 2 -->
            <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                <div class="h-48 bg-gray-200 relative">
                    <svg class="absolute inset-0 w-full h-full text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <circle cx="50" cy="50" r="30" fill="#6B8E23" opacity="0.2"/>
                        <circle cx="50" cy="50" r="20" fill="#6B8E23" opacity="0.3"/>
                        <path d="M30,50 C30,40 40,30 50,30 C60,30 70,40 70,50 C70,60 60,70 50,70 C40,70 30,60 30,50 Z" fill="#333" opacity="0.8"/>
                        <circle cx="45" cy="45" r="2" fill="white"/>
                    </svg>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">Spicy Tuna Roll</h3>
                    <p class="text-gray-600 text-sm mb-3">Fresh tuna, spicy mayo, cucumber with toasted sesame seeds</p>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-lg">$9.95</span>
                        <button class="add-btn bg-red-500 text-white px-4 py-1 rounded-full text-sm font-medium">Add to Cart</button>
                    </div>
                </div>
            </div>

            <!-- Platter -->
            <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                <div class="h-48 bg-gray-200 relative">
                    <svg class="absolute inset-0 w-full h-full text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <rect x="20" y="30" width="60" height="40" rx="5" fill="#D4AF37" opacity="0.2"/>
                        <circle cx="30" cy="40" r="5" fill="#D94F4F" opacity="0.8"/>
                        <circle cx="45" cy="40" r="5" fill="#6B8E23" opacity="0.8"/>
                        <circle cx="60" cy="40" r="5" fill="#D94F4F" opacity="0.8"/>
                        <circle cx="75" cy="40" r="5" fill="#6B8E23" opacity="0.8"/>
                        <rect x="25" y="55" width="50" height="10" rx="2" fill="#333" opacity="0.8"/>
                    </svg>
                    <div class="absolute top-2 right-2 bg-white bg-opacity-80 rounded-full px-2 py-1 text-xs font-medium text-gray-700">Best Value</div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">Sushi Deluxe Platter</h3>
                    <p class="text-gray-600 text-sm mb-3">12 pieces of chef's selection sushi with miso soup</p>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-lg">$24.95</span>
                        <button class="add-btn bg-red-500 text-white px-4 py-1 rounded-full text-sm font-medium">Add to Cart</button>
                    </div>
                </div>
            </div>

            <!-- Side -->
            <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                <div class="h-48 bg-gray-200 relative">
                    <svg class="absolute inset-0 w-full h-full text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <circle cx="50" cy="50" r="30" fill="#D4AF37" opacity="0.2"/>
                        <path d="M35,45 C35,35 45,35 50,40 C55,35 65,35 65,45 C65,55 55,65 50,60 C45,65 35,55 35,45 Z" fill="#6B8E23" opacity="0.8"/>
                    </svg>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">Edamame</h3>
                    <p class="text-gray-600 text-sm mb-3">Steamed soy beans with sea salt</p>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-lg">$5.95</span>
                        <button class="add-btn bg-red-500 text-white px-4 py-1 rounded-full text-sm font-medium">Add to Cart</button>
                    </div>
                </div>
            </div>

            <!-- Drink -->
            <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                <div class="h-48 bg-gray-200 relative">
                    <svg class="absolute inset-0 w-full h-full text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <rect x="40" y="20" width="20" height="60" rx="5" fill="#D4AF37" opacity="0.1"/>
                        <rect x="42" y="25" width="16" height="50" rx="3" fill="#6B8E23" opacity="0.3"/>
                        <rect x="45" y="30" width="10" height="40" rx="2" fill="#6B8E23" opacity="0.6"/>
                    </svg>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">Green Tea</h3>
                    <p class="text-gray-600 text-sm mb-3">Traditional Japanese green tea, hot or iced</p>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-lg">$3.50</span>
                        <button class="add-btn bg-red-500 text-white px-4 py-1 rounded-full text-sm font-medium">Add to Cart</button>
                    </div>
                </div>
            </div>

            <!-- Sauce -->
            <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                <div class="h-48 bg-gray-200 relative">
                    <svg class="absolute inset-0 w-full h-full text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M40,30 L60,30 L65,70 L35,70 Z" fill="#D94F4F" opacity="0.6"/>
                        <path d="M42,35 L58,35 L62,65 L38,65 Z" fill="#D94F4F" opacity="0.8"/>
                    </svg>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-1">Spicy Mayo</h3>
                    <p class="text-gray-600 text-sm mb-3">Creamy mayo with a spicy kick, perfect for rolls</p>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-lg">$1.50</span>
                        <button class="add-btn bg-red-500 text-white px-4 py-1 rounded-full text-sm font-medium">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Category filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const categoryButtons = document.querySelectorAll('.category-btn');
            
            categoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    categoryButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.classList.add('bg-white');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    this.classList.remove('bg-white');
                });
            });

            // Add to cart functionality
            const addButtons = document.querySelectorAll('.add-btn');
            const cartCount = document.querySelector('.rounded-full.h-5.w-5');
            
            addButtons.forEach(button => {
                button.addEventListener('click', function() {
                    let currentCount = parseInt(cartCount.textContent);
                    cartCount.textContent = currentCount + 1;
                    
                    // Animation feedback
                    this.textContent = "Added!";
                    setTimeout(() => {
                        this.textContent = "Add to Cart";
                    }, 1000);
                });
            });
        });
    </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'94ddd1d365a7fd6d',t:'MTc0OTYxMDQyMi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>
