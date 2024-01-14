import 'package:cafe_library_services/Welcome/login.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:cafe_library_services/Welcome/home.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Library Selection',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: LibrarySelectionPage(),
    );
  }
}

Future<String?> getToken() async {
  SharedPreferences prefs = await SharedPreferences.getInstance();
  return prefs.getString('token');
}

class LibrarySelectionPage extends StatefulWidget {
  @override
  _LibrarySelectionPageState createState() => _LibrarySelectionPageState();
}

class _LibrarySelectionPageState extends State<LibrarySelectionPage> {
  TextEditingController libraryIdController = TextEditingController();
  String libraryIdError = '';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(''),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(
                builder: (context) => LoginPage(),
              ),
            );
          },
        ),
      ),
      body: Container(
        decoration: BoxDecoration(
          image: DecorationImage(
            image: AssetImage('assets/library.jpg'),
            fit: BoxFit.cover,
          ),
        ),
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Card(
                elevation: 3,
                color: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10.0),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(8.0),
                  child: TextField(
                    controller: libraryIdController,
                    decoration: InputDecoration(
                      labelText: 'Library ID',
                      errorText: libraryIdError,
                      labelStyle: TextStyle(color: Colors.black),
                      errorStyle: TextStyle(color: Colors.red),
                      hintStyle: TextStyle(color: Colors.black12),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.zero,
                      ),
                    ),
                  ),
                ),
              ),
              SizedBox(height: 16.0),
              ElevatedButton(
                onPressed: () {
                  // Validate and search for the library
                  if (validateLibraryId()) {
                    // Save library ID to shared preferences
                    saveLibraryId(libraryIdController.text.trim());

                    // Perform the database search logic here
                    // For simplicity, assume the library is found
                    navigateToLibraryHome();
                  }
                },
                child: Text('Search Library'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  bool validateLibraryId() {
    String libraryId = libraryIdController.text.trim();
    if (libraryId.isEmpty) {
      setState(() {
        libraryIdError = 'Library ID cannot be empty';
      });
      return false;
    } else {
      setState(() {
        libraryIdError = '';
      });
      return true;
    }
  }

  void saveLibraryId(String libraryId) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString('libraryId', libraryId);
  }

  void navigateToLibraryHome() async {
    String libraryId = await getLibraryIdFromSharedPreferences();
    String? token = await getToken();

    if (token != null) {
      Map<String, String> headers = {'Authorization': 'Bearer $token'};

      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (context) => HomePage(libraryId: libraryId, headers: headers),
        ),
      );
    } else {
      Text('Invalid library ID, try again.');
    }
  }
}
