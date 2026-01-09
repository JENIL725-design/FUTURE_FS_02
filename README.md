# 🛒 NexGenStore - Modern PHP E-Commerce Cart

A robust, full-stack shopping cart application built with **PHP** and **Vanilla JavaScript**. 

This project demonstrates **advanced frontend state management** (Observer Pattern) integrated with a **PHP backend**, featuring a modern UI with glassmorphism effects, smooth animations, and a seamless AJAX-based checkout flow.

---

## 🚀 Key Features

### 🎨 Modern UI/UX
* **Glassmorphism Navbar**: Trendy, translucent navigation bar using CSS backdrop-filter.
* **Smooth Animations**: Staggered product entry animations and hover effects on cards.
* **Responsive Design**: Fully responsive layout built with **Bootstrap 5**.
* **SweetAlert2 Integration**: Professional toast notifications and confirmation popups instead of default browser alerts.

### ⚡ Core Functionality
* **State Management System**: A custom-built `store.js` (Observer Pattern) that syncs cart state across the application without page reloads.
* **AJAX Actions**: Add and remove items instantly without refreshing the page.
* **Dynamic Sorting**: Sort products by **Price (Low/High)**, **Name (A-Z)**, or **Featured** using PHP `uasort`.
* **Session-Based Cart**: Persistent shopping cart storage using PHP Sessions.
* **Checkout Validation**: Client-side form validation before order submission.

---

## 🛠️ Tech Stack

* **Frontend**: HTML5, CSS3, JavaScript (ES6+), Bootstrap 5, FontAwesome 6.
* **Backend**: PHP 8.x
* **Storage**: PHP Sessions (No SQL database required for setup).
* **Libraries**: SweetAlert2 (Notifications), Google Fonts (Poppins).

---

## 📂 Project Structure

```bash
/
├── index.php           # Redirects to products.php (optional)
├── products.php        # Main storefront with sorting and product grid
├── cart_view.php       # Shopping cart page with order summary
├── checkout.php        # Checkout form with validation
├── ajax_handler.php    # Backend API handling cart actions (Add/Remove)
├── header.php          # Global header, navbar, and CDNs
├── main.js             # Event listeners and UI interactions
├── store.js            # Custom State Management (The "Brain" of the app)
└── img/                # Folder containing product images
⚙️ Installation & SetupSince this project uses PHP Sessions, it requires a server environment.Option 1: Using XAMPP / WAMP / MAMPDownload and install XAMPP (or WAMP/MAMP).Navigate to the htdocs folder (e.g., C:\xampp\htdocs).Create a folder named nexgen-store.Clone this repository or paste the files inside that folder.Start Apache from the XAMPP Control Panel.Open your browser and go to:http://localhost/nexgen-store/products.php
Option 2: Using Built-in PHP ServerIf you have PHP installed globally, run this command in the project folder:Bashphp -S localhost:8000
Then visit http://localhost:8000/products.php in your browser.📖 How It WorksThe Store (store.js):Acts as a central "brain" for the application.When you click "Add to Cart," store.js sends an AJAX request to ajax_handler.php.It updates the global state and notifies all "subscribers" (like the Cart Badge) to update automatically.The Backend (ajax_handler.php):Receives JSON requests.Updates the $_SESSION['cart'] array.Returns the new cart count and success messages to the frontend.📷 ScreenshotsProduct GridShopping Cart(Note: Replace these placeholder links with actual screenshots of your project for a better portfolio look!)🤝 ContributingFeel free to fork this repository and submit pull requests. For major changes, please open an issue first to discuss what you would like to change.Made with ❤️ by [Jenil Makwana]
