import 'dart:io';
import 'package:cafe_library_services/Controller/connection.dart';
import 'package:cafe_library_services/Report/book_report_listing.dart';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() {
  runApp(MaterialApp(
    theme: ThemeData(
      primarySwatch: Colors.green,
    ),
    debugShowCheckedModeBanner: false,
    home: BookReportPage(bookId: ''),
  ));
}

class BookReportPage extends StatefulWidget {
  final String bookId;

  BookReportPage({required this.bookId});

  @override
  _BookReportPageState createState() => _BookReportPageState();
}

class _BookReportPageState extends State<BookReportPage> {
  TextEditingController nameController = TextEditingController();
  TextEditingController descriptionController = TextEditingController();
  File? _image;

  Future<void> _getImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);

    if (pickedFile != null) {
      setState(() {
        _image = File(pickedFile.path);
      });
    }
  }

  Future<void> postBookReport(String token, String bookId, String itemName, String issueDescription, File imageFile) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String token = prefs.getString('token') ?? '';
    String bookId = prefs.getString('bookId') ?? '';
    try {
      var headers = {
        "Content-Type": "application/json",
        'Authorization': 'Bearer $token',
      };

      var request = http.MultipartRequest('POST', Uri.parse(API.report));
      request.headers.addAll(headers);

      request.fields.addAll({
        'type': 'book',
        'book_id': bookId,
        'name': itemName,
        'description': issueDescription,
      });

      if (imageFile != null) {
        request.files.add(await http.MultipartFile.fromPath('picture', imageFile.path));
      }

      http.StreamedResponse response = await request.send();

      if (response.statusCode == 200) {
        print(await response.stream.bytesToString());
      } else {
        print('Error statusCode::${response.reasonPhrase}');
      }
    } catch (error) {
      print('Error: $error');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Report Book'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: SingleChildScrollView(
          scrollDirection: Axis.vertical,
          child: Column(
            children: [
              TextField(
                controller: nameController,
                decoration: InputDecoration(labelText: 'Book name'),
              ),
              TextField(
                controller: descriptionController,
                decoration: InputDecoration(labelText: 'Description'),
              ),
              SizedBox(height: 10),
              _image == null
                  ? ElevatedButton(
                onPressed: _getImage,
                child: Text('Add Image'),
              )
                  : Image.file(_image!),
              SizedBox(height: 20),
              ElevatedButton(
                onPressed: () async {
                  SharedPreferences prefs = await SharedPreferences.getInstance();
                  String bookId = prefs.getString('bookId') ?? '';
                  String itemName = nameController.text;
                  String issueDescription = descriptionController.text;
                  if (_image != null) {
                    await postBookReport(
                      'book',
                      bookId,
                      itemName,
                      issueDescription,
                      _image!,
                    );
                    showDialog(
                      context: context,
                      builder: (BuildContext context) {
                        return AlertDialog(
                          title: Text('Report Submitted'),
                          content: Text('Thank you for submitting the report!'),
                          actions: [
                            TextButton(
                              onPressed: () {
                                // Navigate to the ReportListPage
                                Navigator.pushReplacement(
                                  context,
                                  MaterialPageRoute(builder: (context) => BookReportListPage()),
                                );
                              },
                              child: Text('OK'),
                            ),
                          ],
                        );
                      },
                    );
                  } else {
                    // Handle the case where no image is selected
                    print('Please select an image before submitting the report.');
                  }
                },
                child: Text('Submit Report'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
