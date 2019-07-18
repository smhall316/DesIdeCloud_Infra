<?php
    session_start();
    require_once "functions.php";

    // Check if the case explorer was requested to refresh the table
    if (isset($_POST['action']) && !empty($_POST['action'])) {
        if ($_POST['action'] == 'refresh_case_explorer') {
            build_case_explorer_table();
        }else if ($_POST['action'] == 'delete_cases') {
            delete_cases($_POST['value']);
        }
    }

    function build_case_explorer_table() {

        // Get the users cases from database
        $query = "SELECT * FROM cases WHERE owner='". $_SESSION['user'] . "';";
        $result = query_msql($query);
    
        // Debugging
        //error_log("CASE_EXPLORER.PHP ::: QUERY ::: " . $query);
        
        // Write the header row
        echo "<tr class='case_explorer_table_header'>";
        echo "  <th class='select_case'  ><input type='checkbox' name='caseselectall' value='allcases'></th>";
        echo "  <th class='case_name'    >Case Name</th>";
        echo "  <th class='case_desc'    >Case Description</th>";
        echo "  <th class='case_created' >Created</th>";
        echo "  <th class='case_modified'>Last Modified</th>";
        echo "  <th class='case_id'      >Case ID</th>";
        echo "</tr>";

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "  <td class='select_case'  ><input type='checkbox' name='caseselect" . $row['case_id'] . "' value='caseid" . $row['case_id'] ."'></td>";
                echo "  <td class='case_name'    >" . $row['name']         . "</td>";
                echo "  <td class='case_desc'    >" . $row['description']  . "</td>";
                echo "  <td class='case_created' >" . $row['datecreated']  . "</td>";
                echo "  <td class='case_modified'>" . $row['datemodfied']  . "</td>";
                echo "  <td class='case_id'      >" . $row['case_id']      . "</td>";
                echo "</tr>";
            }
        }
    }

    function delete_cases($values) {

        // Case ID's were passed as a string of case id's seperated by commas
        $case_ids = explode(",", $values);

        for ($i=0; $i < count($case_ids)-1; $i++) {
            $query = "DELETE FROM cases WHERE owner='". $_SESSION['user'] . "' AND case_id='" . $case_ids[$i] . "'";
            error_log("Delete Query Is :: " . $query);
            $result = query_msql($query);
        }

        // Debugging
        //error_log("DELETE CASE :: QUERY RESULTS :: " . $result);

        // Redirect back to the case explorer to avoid double posts (POST/REDIRECT/GET)
        error_log("I am about to redirect");
        header('Location: main.php');
        error_log("I redirected");
    }
    
?>

