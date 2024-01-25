<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Library and Cafe Form</title>
 <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      padding: 20px;
    }
    form {
      max-width: 400px;
      margin: 0 auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    label, select, input[type="text"] {
      display: block;
      margin-bottom: 10px;
    }
    input[type="text"], select {
      width: 100%;
      padding: 8px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    input[type="submit"] {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
 </style>
</head>
<body>
 <form id="form" action="{{ route('library_cafe.store') }}" method="POST">
  @csrf
    <label for="entityType">Cafe or Library?</label>
    <select name="entityType" id="entityType">
      <option value="library">Library</option>
      <option value="cafe">Cafe</option>
    </select>
    <div id="name-and-address" style="display: none;">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" />
      <label for="address" id="addressDisplay">Address</label>
      <input type="text" name="address" id="address" />
    </div>
    <div id="library-dropdown" style="display: none;">
      <label for="library">Select a Library</label>
      <select name="library" id="library">
        @foreach($data as $library)
        <option value="{{ $library->id }}">{{ $library->name }}</option>
        @endforeach
      </select>
    </div>
    <input type="submit" value="Submit" />
 </form>

  
</body>
<script>
  // Toggle the visibility of the name, address, and library dropdown based on the selected entity type
  document.getElementById('entityType').addEventListener('change', function() {
    var nameAndAddressFields = document.getElementById('name-and-address');
    var libraryDropdown = document.getElementById('library-dropdown');
    var addressField = document.getElementById('address');
    var addressFieldDisplay = document.getElementById('addressDisplay');

    if (this.value === 'library') {
      nameAndAddressFields.style.display = 'block';
      libraryDropdown.style.display = 'none';
      addressField.style.display = 'block';
      addressFieldDisplay.style.display = 'block';
    } else if (this.value === 'cafe') {
      addressField.style.display = 'none';
      addressFieldDisplay.style.display = 'none';

      nameAndAddressFields.style.display = 'block';
      libraryDropdown.style.display = 'block';
      // populateLibraryOptions(); // Call function to populate library options
    } else {
      nameAndAddressFields.style.display = 'none';
      libraryDropdown.style.display = 'none';
    }
  });

</script>
</html>