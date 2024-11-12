<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Apple Macintosh Computer Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,200;0,500;1,200;1,500&display=swap">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <h1>Apple Macintosh Computer Inventory</h1>
    </header>
    <main>
        <?php
        require_once 'includes/config.php';

        // Create connection using constants defined in config.php
        $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to fetch data from the 'macOS_Versions' table
        $sql_macOS_Versions = "SELECT * FROM macOS_Versions";
        $result_macOS_Versions = $conn->query($sql_macOS_Versions);

        // Query to fetch data from 'macOS_Releases' table
        $sql_macOS_Releases = "SELECT CONCAT(Version_Name, ' (', Release_Name, ')') AS `Version Name (Release Name)`, Year_Released AS `Year Released`
        FROM macOS_Releases;";
        $result_macOS_Releases = $conn->query($sql_macOS_Releases);

        // SQL query to fetch data from the 'Current_Inventory' table
        $sql_current_inventory = "SELECT * FROM Current_Inventory";
        $result_current_inventory = $conn->query($sql_current_inventory);

        // Query for fetching data from table 'Supported_OS' table
        $sql = "SELECT * FROM Supported_OS";
        $result_supported_OS = $conn->query($sql);
        ?>

        <section>
            <h2>How Many Versions of macOS Have Been Released?</h2>
            <div>
                <p>There have been <b>21</b> versions of macOS released thus far.</p>
            </div>
        </section>

        <section>
            <h2>Show the Version Name, Release Name, Official Darwin OS Number, Date Announced, Date Released, and Date of Latest Release of All macOS Versions, Listed by Date Order</h2>
            <div>
                <?php
                if ($result_macOS_Versions->num_rows > 0) {
                    // Start table and headers
                    echo "<table>";
                    echo "<thead><tr><th>Version Name</th><th>Release Name</th><th>Official Darwin OS Number</th><th>Date Announced</th><th>Date Released</th><th>Date Latest Release</th></tr></thead><tbody>";

                    // Output data for each row
                    while ($row = $result_macOS_Versions->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Version_Name'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Release_Name'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Official_Darwin_OS_Number'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Date_Announced'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Date_Released'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Date_of_Latest_Release'] ?? '') . "</td>";
                        echo "</tr>";
                    }

                    // End table
                    echo "</tbody></table>";
                } else {
                    echo "No macOS version records found.";
                }
                ?>
            </div>
        </section>

        <section>
            <h2>Show the Version Name (Release Name) and Year Released of all macOS Versions, Listed by Date Released</h2>
            <div>
                <?php
                if ($result_macOS_Releases->num_rows > 0) {
                    // Start table and headers
                    echo "<table>";
                    echo "<thead><tr><th>Version Name (Release Name)</th><th>Year Released</th></tr></thead><tbody>";

                    // Output data for each row
                    while ($row = $result_macOS_Releases->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Version Name (Release Name)']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Year Released']) . "</td>";
                        echo "</tr>";
                    }

                    // End table
                    echo "</tbody></table>";
                } else {
                    echo "No macOS release records found.";
                }
                ?>
        </section>

        <section>
            <h2>Show the Current Inventory (Excluding Comments)</h2>
            <div>
                <?php if ($result_current_inventory->num_rows > 0) {
                    // Start table and headers
                    echo "<table>";
                    echo "<thead>
                        <tr>
                            <th>Model Name</th>
                            <th>Model Identifier</th>
                            <th>Model Number</th>
                            <th>Part Number</th>
                            <th>Serial Number</th>
                            <th>Darwin OS Number</th>
                            <th>Latest Supporting Darwin OS Number</th>
                            <th>URL</th>
                        </tr>
                    </thead>
                    <tbody>";

                    // Output data for each row
                    while ($row = $result_current_inventory->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Model_Name'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Model_Identifier'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Model_Number'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Part_Number'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Serial_Number'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Darwin_OS_Number'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['Latest_Supporting_Darwin_OS_Number'] ?? '') . "</td>";
                        $url = htmlspecialchars($row['URL'] ?? '');
                        if ($url) {
                            echo "<td><a href='$url' target='_blank'>Link</a></td>";
                        } else {
                            echo "<td>No URL available</td>";
                        }

                        echo "</tr>";
                    }

                    // End table
                    echo "</tbody>
                </table>";
                } else {
                    echo "No current inventory records found.";
                }
                ?>
            </div>
        </section>

        <section>
            <h2>Show the Model, Installed/Original OS, and the Last Supported OS For the Current Inventory</h2>
            <?php
            if ($result_supported_OS->num_rows > 0) {
                // Start the HTML table
                echo "<table border='1'>";
                echo "<thead>
                <tr>
                    <th>Model</th>
                    <th>Installed/Original OS</th>
                    <th>Last Supported OS</th>
                </tr>
              </thead>";
                echo "<tbody>";

                while ($row = $result_supported_OS->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Model']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Installed_Original_OS']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Last_Supported_OS']) . "</td>";
                    echo "</tr>";
                }

                // End the table
                echo "</tbody></table>";
            } else {
                echo "No records found.";
            }
            ?>
        </section>

        <?php
        // Close the connection
        $conn->close();
        ?>
    </main>
</body>

</html>
