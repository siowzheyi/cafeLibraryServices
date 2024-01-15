<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faulty Item Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h2, h3 {
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        select, input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
            width: 50%;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .edit-btn {
            background-color: #2196F3;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h2>Faulty Item Management</h2>

    <!-- Faulty Edit Section -->
    <form id="faultyEditForm">
        <h3>Faulty Edit</h3>

        <label for="faultType">Choose Fault Type:</label>
        <select id="faultType" name="faultType" onchange="populateFaultDetails()">
            <option value="room">Room</option>
            <option value="book">Book</option>
            <option value="equipment">Equipment</option>
        </select>

        <label for="faultDetails">Choose Fault ID:</label>
        <select id="faultDetails" name="faultDetails"></select>

        <label for="remark">Remark:</label>
        <input type="text" id="remark" name="remark">

        <button type="button" onclick="submitFaultyEdit()">Submit</button>
    </form>

    <!-- Details Faulty Section -->
    <h3>Details Faulty</h3>

    <table id="detailsTable">
        <thead>
            <tr>
                <th>Fault Type</th>
                <th>Fault ID</th>
                <th>Remark</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        function populateFaultDetails() {
            var faultType = document.getElementById("faultType").value;
            var faultDetailsSelect = document.getElementById("faultDetails");

            faultDetailsSelect.innerHTML = "";

            //example data to display //retrieve it from database
        
            var options;
            if (faultType === "room") {
                options = ["Room 101", "Room 102", "Room 103"];
            } else if (faultType === "book") {
                options = ["Book 001", "Book 002", "Book 003"];
            } else if (faultType === "equipment") {
                options = ["Equipment A", "Equipment B", "Equipment C"];
            }

            options.forEach(function (option) {
                var optionElement = document.createElement("option");
                optionElement.value = option;
                optionElement.text = option;
                faultDetailsSelect.add(optionElement);
            });
        }

        function submitFaultyEdit() {
            var faultType = document.getElementById("faultType").value;
            var faultDetails = document.getElementById("faultDetails").value;
            var remark = document.getElementById("remark").value;

            var detailsTable = document.getElementById("detailsTable").getElementsByTagName('tbody')[0];
            var newRow = detailsTable.insertRow();

            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            var cell3 = newRow.insertCell(2);
            var cell4 = newRow.insertCell(3);

            cell1.innerHTML = faultType;
            cell2.innerHTML = faultDetails;
            cell3.innerHTML = remark;
            cell4.innerHTML = '<button class="edit-btn" onclick="editRemark(this)">Edit</button>';

            document.getElementById("faultType").value = "";
            document.getElementById("faultDetails").innerHTML = "";
            document.getElementById("remark").value = "";
        }

        function editRemark(button) {
            var row = button.parentNode.parentNode;
            var currentRemark = row.cells[2].innerHTML;

            var newRemark = prompt("Edit:", currentRemark);
            if (newRemark !== null) {
                row.cells[2].innerHTML = newRemark;
            }
        }
    </script>

</body>
</html>
