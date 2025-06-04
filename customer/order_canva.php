<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elegant Order List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f7ff;
        }
        
        .order-card {
            transition: all 0.3s ease;
        }
        
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .status-badge {
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-delivered {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-processing {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-shipped {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
<!-- <body jf-observer-attached="true"> -->
<body>
    <div class="container mx-auto px-4 py-10 max-w-5xl">
        <header class="mb-10">
            <h1 class="text-4xl font-bold text-indigo-900 mb-2">Your Orders</h1>
            <p class="text-gray-600">Track and manage your recent purchases</p>
        </header>
        
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-wrap items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Recent Orders</h2>
                    <p class="text-gray-500 text-sm">Showing 5 of 12 orders</p>
                </div>
                
                <div class="flex space-x-2 mt-4 sm:mt-0">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition" jf-ext-button-ct="filter">
                        Filter
                    </button>
                    <button class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition" jf-ext-button-ct="sort by: recent">
                        Sort by: Recent
                    </button>
                </div>
            </div>
            
            <div class="space-y-4">
                <!-- Order 1 -->
                <div class="order-card bg-white border border-gray-100 rounded-lg p-5 hover:border-indigo-200">
                    <div class="flex flex-wrap justify-between items-start">
                        <div class="mb-4 sm:mb-0">
                            <div class="flex items-center mb-2">
                                <span class="text-lg font-semibold text-gray-800">Order #A80942</span>
                                <span class="status-badge status-delivered ml-3">Delivered</span>
                            </div>
                            <p class="text-gray-500 text-sm">Placed on May 24, 2023</p>
                        </div>
                        <div class="flex space-x-3">
                            <button class="px-4 py-2 text-sm text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition" jf-ext-button-ct="track">
                                Track
                            </button>
                            <button class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition" jf-ext-button-ct="details">
                                Details
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center">
                        <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Premium Wireless Headphones</p>
                            <p class="text-gray-500 text-sm">$149.99 • Qty: 1</p>
                        </div>
                    </div>
                </div>
                
                <!-- Order 2 -->
                <div class="order-card bg-white border border-gray-100 rounded-lg p-5 hover:border-indigo-200">
                    <div class="flex flex-wrap justify-between items-start">
                        <div class="mb-4 sm:mb-0">
                            <div class="flex items-center mb-2">
                                <span class="text-lg font-semibold text-gray-800">Order #B71023</span>
                                <span class="status-badge status-processing ml-3">Processing</span>
                            </div>
                            <p class="text-gray-500 text-sm">Placed on May 20, 2023</p>
                        </div>
                        <div class="flex space-x-3">
                            <button class="px-4 py-2 text-sm text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition" jf-ext-button-ct="track">
                                Track
                            </button>
                            <button class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition" jf-ext-button-ct="details">
                                Details
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center">
                        <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Smartphone Case</p>
                            <p class="text-gray-500 text-sm">$24.99 • Qty: 1</p>
                        </div>
                    </div>
                </div>
                
                <!-- Order 3 -->
                <div class="order-card bg-white border border-gray-100 rounded-lg p-5 hover:border-indigo-200">
                    <div class="flex flex-wrap justify-between items-start">
                        <div class="mb-4 sm:mb-0">
                            <div class="flex items-center mb-2">
                                <span class="text-lg font-semibold text-gray-800">Order #C65432</span>
                                <span class="status-badge status-shipped ml-3">Shipped</span>
                            </div>
                            <p class="text-gray-500 text-sm">Placed on May 18, 2023</p>
                        </div>
                        <div class="flex space-x-3">
                            <button class="px-4 py-2 text-sm text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition" jf-ext-button-ct="track">
                                Track
                            </button>
                            <button class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition" jf-ext-button-ct="details">
                                Details
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center">
                        <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Laptop Stand</p>
                            <p class="text-gray-500 text-sm">$49.99 • Qty: 1</p>
                        </div>
                    </div>
                </div>
                
                <!-- Order 4 -->
                <div class="order-card bg-white border border-gray-100 rounded-lg p-5 hover:border-indigo-200">
                    <div class="flex flex-wrap justify-between items-start">
                        <div class="mb-4 sm:mb-0">
                            <div class="flex items-center mb-2">
                                <span class="text-lg font-semibold text-gray-800">Order #D43210</span>
                                <span class="status-badge status-delivered ml-3">Delivered</span>
                            </div>
                            <p class="text-gray-500 text-sm">Placed on May 12, 2023</p>
                        </div>
                        <div class="flex space-x-3">
                            <button class="px-4 py-2 text-sm text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition" jf-ext-button-ct="track">
                                Track
                            </button>
                            <button class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition" jf-ext-button-ct="details">
                                Details
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center">
                        <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Design Book Collection</p>
                            <p class="text-gray-500 text-sm">$89.99 • Qty: 1</p>
                        </div>
                    </div>
                </div>
                
                <!-- Order 5 -->
                <div class="order-card bg-white border border-gray-100 rounded-lg p-5 hover:border-indigo-200">
                    <div class="flex flex-wrap justify-between items-start">
                        <div class="mb-4 sm:mb-0">
                            <div class="flex items-center mb-2">
                                <span class="text-lg font-semibold text-gray-800">Order #E98765</span>
                                <span class="status-badge status-cancelled ml-3">Cancelled</span>
                            </div>
                            <p class="text-gray-500 text-sm">Placed on May 5, 2023</p>
                        </div>
                        <div class="flex space-x-3">
                            <button class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition" jf-ext-button-ct="details">
                                Details
                            </button>
                            <button class="px-4 py-2 text-sm text-indigo-600 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition" jf-ext-button-ct="reorder">
                                Reorder
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center">
                        <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Smart Watch</p>
                            <p class="text-gray-500 text-sm">$199.99 • Qty: 1</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-center">
                <button class="px-6 py-3 bg-white border border-gray-300 text-indigo-600 rounded-lg hover:bg-gray-50 transition flex items-center" jf-ext-button-ct="view all orders">
                    <span>View All Orders</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="bg-indigo-50 rounded-xl p-6">
            <div class="flex flex-wrap items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-lg font-semibold text-indigo-900">Need help with an order?</h3>
                    <p class="text-indigo-700">Our customer service team is here to help</p>
                </div>
                <button class="px-5 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition" jf-ext-button-ct="contact support">
                    Contact Support
                </button>
            </div>
        </div>
    </div>
<!-- <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'93ada3c713203fa4',t:'MTc0NjQyMDg2NS4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script><iframe height="1" width="1" style="position: absolute; top: 0px; left: 0px; border: none; visibility: hidden;"></iframe> -->
</body></html>
