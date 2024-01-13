/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

function uploadFile() {
    var fileInput = document.getElementById('fileInput');
    var fileTable = document.getElementById('fileTable');
  
    var file = fileInput.files[0];
  
    if (file) {
      var reader = new FileReader();
  
      reader.onload = function (e) {
        var content = e.target.result;
        displayFileContent(content);
      };
  
      reader.readAsText(file);
    } else {
      alert('Please select a file.');
    }
  }
  
  function displayFileContent(content) {
    var rows = content.split('\n');
    var tableHTML = '<tr><th>Column 1</th><th>Column 2</th></tr>';
  
    for (var i = 0; i < rows.length; i++) {
      var cells = rows[i].split(',');
      tableHTML += '<tr>';
  
      for (var j = 0; j < cells.length; j++) {
        tableHTML += '<td>' + cells[j] + '</td>';
      }
  
      tableHTML += '</tr>';
    }
  
    document.getElementById('fileTable').innerHTML = tableHTML;
  }
  
  
