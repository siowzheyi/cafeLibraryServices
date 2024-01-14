import 'package:cafe_library_services/Beverage/beverage_listing.dart';
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
      title: 'Cafe Selection',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: CafeSelectionScreen(),
    );
  }
}

Future<String?> getToken() async {
  SharedPreferences prefs = await SharedPreferences.getInstance();
  return prefs.getString('token');
}

class CafeSelectionScreen extends StatefulWidget {
  @override
  _CafeSelectionScreenState createState() => _CafeSelectionScreenState();
}

class _CafeSelectionScreenState extends State<CafeSelectionScreen> {
  TextEditingController cafeIdController = TextEditingController();
  String cafeIdError = '';

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
                builder: (context) => HomePage(libraryId: ''),
              ),
            );
          },
        ),
      ),
      body: Container(
        decoration: BoxDecoration(
          image: DecorationImage(
            image: AssetImage('assets/cafe.jpg'),
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
                    controller: cafeIdController,
                    decoration: InputDecoration(
                      labelText: 'Cafe ID',
                      errorText: cafeIdError,
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
                  // Validate and search for the cafe
                  if (validateCafeId()) {
                    // Save cafe ID to shared preferences
                    saveCafeId(cafeIdController.text.trim());

                    // Perform the database search logic here
                    // For simplicity, assume the cafe is found
                    navigateToCafeHome();
                  }
                },
                child: Text('Search Cafe'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  bool validateCafeId() {
    String cafeId = cafeIdController.text.trim();
    if (cafeId.isEmpty) {
      setState(() {
        cafeIdError = 'Cafe ID cannot be empty';
      });
      return false;
    } else {
      setState(() {
        cafeIdError = '';
      });
      return true;
    }
  }

  void saveCafeId(String cafeId) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString('cafeId', cafeId);
  }

  void navigateToCafeHome() async {
    String cafeId = await getCafeIdFromSharedPreferences();
    String? token = await getToken();

    if (token != null) {
      Map<String, String> headers = {'Authorization': 'Bearer $token'};

      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (context) => BeverageListScreen(cafeId: cafeId, headers: headers),
        ),
      );
    } else {
      Text('Invalid cafe ID, try again.');
    }
  }
}
