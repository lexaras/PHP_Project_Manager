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
        <div class="head">
            <h1><a href="./?path=Employees">Employees</a></h1>
            <h1><a href="./?path=Projects">Projects</a></h1>
            <h1 id="name">Project manager</h1>
        </div>
    </header>
    <?php
    //IF PATH = Employess
    if ($path == 'Employees') {
        $sql = "SELECT employees.id, employee_name, projects.project_name
    FROM employees
    LEFT JOIN projects
        ON project_id = projects.id
    ORDER BY employees.id ASC ";
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
                    . '<td>' . '<a href="?action=delete_employee&id='  . $row['id'] . '"><button>DELETE</button></a>'
                    . '<a href="?path=employees&update=' . $row['id'] . '"><button>UPDATE</button></a>' . '</td>'
                    . '</tr>');
            }
            print("</tbody>");
            print("</table>");
        } else {
            echo "0 results";
        }

        //New employee form
    ?>
        <form action="" method="POST" class="add_employee">
            <input type="text" name="employee_name" id="employee_name" placeholder="Employee name" Required>
            <input type="submit" name="submit" value="Add new employee">
        </form>
    <?php
    }
    //IF PATH = Projects
    if ($path == 'Projects') {
        $sql = "SELECT group_concat(employees.employee_name SEPARATOR ', ')as names, projects.id, projects.project_name 
        FROM projects
        LEFT JOIN employees ON employees.project_id = projects.id
        GROUP BY projects.id ";
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
                    . '<td>' . '<a href="?action=delete_project&id='  . $row['id'] . '"><button>DELETE</button></a>'
                    . '<a href="?path=projects&update=' . $row['id'] . '"><button>UPDATE</button></a>' . '</td>'
                    . '</tr>');
            }
            print("</tbody>");
            print("</table>");
        } else {
            echo "0 results";
        }
        //New project form   
    ?>
        <form action="" method="POST" class="add_project">
            <input type="text" name="project_name" id="project_name" placeholder="Project name" Required>
            <input type="submit" name="submit" value="Add new project">
        </form><br>
    <?php
    }
    ?>
    <?php
    //code redundancy, need to be fixed
    // Functionality for UPDATE(employee/project)
    if (isset($_GET) && $_GET['update'] != '') {
        if ($_GET['path'] == 'projects') {
            $sql = "SELECT group_concat(employees.employee_name SEPARATOR ', ')as names, projects.id, projects.project_name 
            FROM projects 
            LEFT JOIN employees ON employees.project_id = projects.id
            GROUP BY projects.id ";
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
                        . '<td>' . '<a href="?action=delete_project&id='  . $row['id'] . '"><button>DELETE</button></a>'
                        . '<a href="?path=projects&update=' . $row['id'] . '"><button>UPDATE</button></a>' . '</td>'
                        . '</tr>');
                }
                print("</tbody>");
                print("</table>");
            } else {
                echo "0 results";
            }
            $id = $_GET['update'];
            $sql = "SELECT projects.* FROM projects WHERE id = $id";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    print('<form action="" name="edit" method="POST">');
                    print('<input type="number" name="project_id" value="' . $row['id'] . '">');
                    print('<input type="text" name="project_Name" value="' . $row['project_name'] . '">');
                    print('<button type="submit">UPDATE INFORMATION</button>');
                    print('</form>');
                }
            }
        }
        if ($_GET['path'] == 'employees') {
            $sql = "SELECT employees.id, employee_name, projects.project_name
            FROM employees
            LEFT JOIN projects
                ON project_id = projects.id
            ORDER BY employees.id ASC ";
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
                        . '<td>' . '<a href="?action=delete_employee&id='  . $row['id'] . '"><button>DELETE</button></a>'
                        . '<a href="?path=employees&update=' . $row['id'] . '"><button>UPDATE</button></a>' . '</td>'
                        . '</tr>');
                }
                print("</tbody>");
                print("</table>");
            } else {
                echo "0 results";
            }
            $id = $_GET['update'];
            $sql = "SELECT employees.* FROM employees WHERE id = $id";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    print('<form action="" name="edit" method="POST">');
                    print('<input type="number" name="employee_id" value="' . $row['id'] . '">');
                    print('<input type="text" name="employee_Name" value="' . $row['employee_name'] . '">');
                    print('<select name="project">');
                    print('<option "selected">Select</option>');
                    $sql = "SELECT id, project_name FROM projects";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            print('<option value="' . $row['id'] . '" >' . $row['project_name'] . '</option>');
                        }
                    }
                    print('</select>');
                    print('<button  type="submit">UPDATE INFORMATION</button>');
                    print('</form>');
                }
            }
        }
    }
    //Delete functionality for employee
    if (isset($_GET['action']) && $_GET['action'] == 'delete_employee') {
        $sql = 'DELETE FROM employees WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_GET['id']);
        $res = $stmt->execute();
        $stmt->close();
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?path=Employees");
        die();
    }
    //Delete functionality for project
    if (isset($_GET['action']) && $_GET['action'] == 'delete_project') {
        $sql = 'DELETE FROM projects WHERE id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_GET['id']);
        $res = $stmt->execute();
        $stmt->close();
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?path=Projects");
        die();
    }
    //New employee functionality
    if (isset($_POST['employee_name']) && ($_POST['employee_name'] != '')) {
        $employeeName = $_POST['employee_name'];
        $sql = 'INSERT INTO employees (employee_name) VALUES (?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $employeeName);
        $stmt->execute();
        $stmt->close();
        mysqli_close($conn);
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?path=Employees");
        die();
    };
    //New project functionality
    if (isset($_POST['project_name']) && ($_POST['project_name'] != '')) {
        $projectName = $_POST['project_name'];
        $sql = 'INSERT INTO projects (project_name) VALUES (?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $projectName);
        $stmt->execute();
        $stmt->close();
        mysqli_close($conn);
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?path=Projects");
        die();
    };
    // Update project functionality
    if (isset($_POST['project_Name']) && $_POST['project_Name'] != "") {
        $new_pr_id = $_POST['project_id'];
        $name = $_POST['project_Name'];
        $sql = "UPDATE projects SET  id= '$new_pr_id', project_name = '$name' WHERE id= $id ";
        $result = mysqli_query($conn, $sql);
        header("Location: ?path=Projects");
    };
    // Update employee functionality
    if (isset($_POST['employee_Name']) && $_POST['employee_Name'] != "") {
        $new_id = $_POST['employee_id'];
        $name = $_POST['employee_Name'];
        $project = $_POST['project'];
        $sql = "UPDATE employees SET id= '$new_id',employee_name= '$name', project_id = $project WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        header("Location: ?path=Employees");
    }
    ?>
</body>

</html>
<?php
mysqli_close($conn);
