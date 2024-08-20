<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM login_system WHERE id = $id");
    $user = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $sql = "UPDATE login_system SET Name='$username', Email='$email', Password='$password' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header('Location: admin.php');
        exit();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error updating record: " . mysqli_error($conn) . "</div>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #D19C97;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: #bb8c88;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .navbar {
            margin-left: 250px;
            background-color: #D19C97;
            color: white;
        }
        .card {
            margin-bottom: 20px;
        }
        .btn-action {
            margin: 0 5px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="#users"><i class="fas fa-users"></i> Users</a>
        <a href="#payments"><i class="fas fa-dollar-sign"></i> Payments</a>
        <a href="#settings"><i class="fas fa-cogs"></i> Settings</a>
        <a href="#logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Content -->
    <div class="content">
       

        <!-- Main Content -->
        <div class="container-fluid mt-4">
            <h2>Dashboard</h2>
            <p>Welcome to the admin panel.</p>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5 class="card-title">Jumlah User</h5>
                            <h2 id="user-count">2</h2> <!-- Gantilah dengan nilai dinamis -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <h5 class="card-title">Total Payments</h5>
                            <h2 id="total-payments">IDR 3,000,000</h2> <!-- Gantilah dengan nilai dinamis -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- List Users -->
            <div id="users" class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-users"></i> List Users
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password User</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="user-list">
                                <!-- User rows akan ditambahkan secara dinamis di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payments Table -->
            <div id="payments" class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-dollar-sign"></i> Payments from Event Organizers
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Organizer Name</th>
                                    <th>Event Name</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="payments-list">
                                <!-- Payment rows akan ditambahkan secara dinamis di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal for Editing User -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action=""> 
                        <input type="hidden" id="editId" name="id" value="<?php echo $user['id']; ?>">
                        <div class="form-group">
                            <label for="editName">Name</label>
                            <input type="text" class="form-control" id="editName" name="username" value="<?php echo $user['username']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo $user['email']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="editPassword">Password User</label>
                            <input type="text" class="form-control" id="editPassword" name="password" value="<?php echo $user['password']; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Deletion Confirmation -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simulate dynamic population of user data
        const users = [
            { id: 1, name: 'Asep Suherman', email: 'asep@example.com', password: 'password123' },
            { id: 2, name: 'Siti Maryam', email: 'siti@example.com', password: 'password456' }
        ];

        const userList = document.getElementById('user-list');

        users.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.password}</td>
                <td>
                    <button class="btn btn-primary btn-sm btn-action" onclick="editUser(${user.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm btn-action" onclick="deleteUser(${user.id})"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
            userList.appendChild(row);
        });
        function editUser(id) {
    const user = users.find(u => u.id === id);
    if (user) {
        document.getElementById('editId').value = user.id;
        document.getElementById('editName').value = user.name;
        document.getElementById('editEmail').value = user.email;
        document.getElementById('editPassword').value = user.password;
        $('#editModal').modal('show');
    }
}

function deleteUser(id) {
    $('#deleteModal').modal('show');
    document.getElementById('confirmDelete').onclick = function() {
        window.location.href = `delete.php id=${id}`;
    };
}

    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
