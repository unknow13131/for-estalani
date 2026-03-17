// admin-script.js
// Switch sections in admin panel
function showSection(sectionId) {

    // hide all sections
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
    });

    // show selected section
    const target = document.getElementById(sectionId);
    if (target) target.classList.add('active');

    // update sidebar active state
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });

    const activeNav = document.querySelector(`[onclick="showSection('${sectionId}')"]`);
    if (activeNav) activeNav.classList.add('active');
}


document.addEventListener('DOMContentLoaded', () => {
    loadBooks();
    loadOrders();
    loadUsers();
});

// Show Add Book Form (simple placeholder)
function showAddBookForm() {
    const booksContent = document.getElementById('books-content');
    booksContent.innerHTML = `
        <form id="add-book-form">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Author</label>
                <input type="text" name="author" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" required>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" required>
            </div>
            <button class="btn btn-primary" type="submit">Add Book</button>
        </form>
    `;

    document.getElementById('add-book-form').addEventListener('submit', (e) => {
        e.preventDefault();
        alert('You can now connect this to your PHP API to insert the book!');
    });
}

// Load Books
function loadBooks() {
    fetch('fetch-books.php')
        .then(res => res.json())
        .then(data => {
            let html = `<div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>`;
            data.forEach(book => {
                html += `<tr>
                            <td>${book.id}</td>
                            <td>${book.title}</td>
                            <td>${book.author}</td>
                            <td>$${book.price}</td>
                            <td>${book.stock}</td>
                         </tr>`;
            });
            html += `</tbody></table></div>`;
            document.getElementById('books-content').innerHTML = html;
        });
}

// Load Orders
function loadOrders() {
    fetch('fetch-orders.php')
        .then(res => res.json())
        .then(data => {
            let html = `<div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>`;
            data.forEach(order => {
                html += `<tr>
                            <td>${order.id}</td>
                            <td>${order.customer_name}</td>
                            <td>$${order.total}</td>
                            <td>${order.status}</td>
                         </tr>`;
            });
            html += `</tbody></table></div>`;
            document.getElementById('orders-content').innerHTML = html;
        });
}

// Load Users
function loadUsers() {
    fetch('fetch-users.php')
        .then(res => res.json())
        .then(data => {
            let html = `<div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>`;
            data.forEach(user => {
                html += `<tr>
                            <td>${user.id}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.role}</td>
                         </tr>`;
            });
            html += `</tbody></table></div>`;
            document.getElementById('users-content').innerHTML = html;
        });
}