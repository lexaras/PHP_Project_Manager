<?php
$path = $_GET['path'];
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "project_manager";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//Delete functionality for employee
if (isset($_GET['action']) && $_GET['action'] == 'delete_employee') {
    $sql = 'DELETE FROM employee WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $res = $stmt->execute();
    $stmt->close();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?path=Employees");
    die();
}
//Delete functionality for project
if (isset($_GET['action']) && $_GET['action'] == 'delete_project') {
    $sql = 'DELETE FROM project WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $res = $stmt->execute();
    $stmt->close();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?path=Projects");
    die();
}
//New employee functionality
if(isset($_POST['employee_name']) && ($_POST['employee_name'] != '')){
        $employeeName = $_POST['employee_name'];
        $sql = 'INSERT INTO employee (employee_name) VALUES (?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $employeeName);
        $stmt->execute();
        $stmt->close();
        mysqli_close($conn);
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?path=Employees");
        die(); 
    } ;
//New project functionality
if(isset($_POST['project_name']) && ($_POST['project_name'] != '')){
    $projectName = $_POST['project_name'];
    $sql = 'INSERT INTO project (project_name) VALUES (?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $projectName);
    $stmt->execute();
    $stmt->close();
    mysqli_close($conn);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?path=Projects");
    die(); 
} ;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>Project_Manager</title>
</head>
<body>
    <header>
        <div class="header">
            <h1><a href="./?path=Employees">Employees</a></h1>
            <h1><a href="./?path=Projects">Projects</a></h1>
            <h1 id="name">Project manager</h1>
        </div>
    </header>
    <br>
    <?php
    //IF PATH = Employess
    if ($path == 'Employees') {
        $sql = "SELECT employee.id, employee_name, project.project_name
    FROM employee
    LEFT JOIN project
        ON project_id = project.id
    ORDER BY employee.id ASC ";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            print("<table>");
            print("<thead>");
            print("<tr><th>Id</th><th>Name</th><th>Projects</th><th>Actions</th></tr>");
            print("</thead>");
            print("<tbody>");
            while ($row = mysqli_fetch_assoc($result)) {
                print('<tr>'
                    . '<td>' . $row['id'] . '</td>'
                    . '<td>' . $row['employee_name'] . '</td>'
                    . '<td>' . $row['project_name'] . '</td>'
                    . '<td>' . '<a href="?action=delete_employee&id='  . $row['id'] . '"><button>DELETE</button></a>' .
                    '<a href="?action=update_employee&id='  . $row['id'] . '"><button>UPDATE</button></a>' . '</td>'
                    . '</tr>');
            }
            print("</tbody>");
            print("</table>");
        } else {
            echo "0 results";
        }
    ?>
    <!-- New employee form -->
    <form action="" method="POST" class="add_employee">
    <input type="text" name="employee_name" id="employee_name" placeholder="Employee name" Required>
    <input type="submit" name="submit" value="Add new employee">
    </form><br>
    <?php
    }
    //IF PATH = Projects
    if ($path == 'Projects') {
        $sql = "SELECT group_concat(employee.employee_name SEPARATOR ', ')as names, project.id, project.project_name 
        FROM project 
        LEFT JOIN employee ON employee.project_id = project.id
        GROUP BY project.id ";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            print("<table>");
            print("<thead>");
            print("<tr><th>Id</th><th>Project name</th><th>Employees </th><th>Actions</th></tr>");
            print("</thead>");
            print("<tbody>");
            while ($row = mysqli_fetch_assoc($result)) {
                print('<tr>'
                    . '<td>' . $row['id'] . '</td>'
                    . '<td>' . $row['project_name'] . '</td>'
                    . '<td>' . $row['names'] . '</td>'
                    . '<td>' . '<a href="?action=delete_project&id='  . $row['id'] . '"><button>DELETE</button></a>' . '</td>'
                    . '</tr>');
            }
            print("</tbody>");
            print("</table>");
        } else {
            echo "0 results";
        }
        ?>
    <!-- New employee form -->
    <form action="" method="POST" class="add_project">
    <input type="text" name="project_name" id="project_name" placeholder="Project name" Required>
    <input type="submit" name="submit" value="Add new project">
    </form><br>
    <?php
    }
    ?>
</body>

</html>
<?php
mysqli_close($conn);
