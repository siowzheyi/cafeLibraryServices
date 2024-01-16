import 'dart:io';
import 'package:cafe_library_services/Controller/connection.dart';
import 'package:flutter/material.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'equipment_report_listing.dart';

void main() {
  runApp(MaterialApp(
    theme: ThemeData(
      primarySwatch: Colors.green,
    ),
    debugShowCheckedModeBanner: false,
    home: EquipmentReportPage(equipmentId: ''),
  ));
}

class EquipmentReportPage extends StatefulWidget {
  final String equipmentId;

  EquipmentReportPage({required this.equipmentId});

  @override
  _EquipmentReportPageState createState() => _EquipmentReportPageState();
}

class _EquipmentReportPageState extends State<EquipmentReportPage> {
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

  Future<void> postEquipmentReport(String type, String equipmentId, String itemName, String issueDescription, File imageFile) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String token = prefs.getString('token') ?? '';
    String equipmentId = prefs.getString('equipmentId') ?? '';
    try {
      var headers = {
        "Content-Type": "application/json",
        'Authorization': 'Bearer $token',
      };

      var request = http.MultipartRequest('POST', Uri.parse(API.report));
      request.headers.addAll(headers);

      request.fields.addAll({
        'type': 'equipment',
        'equipment_id': equipmentId,
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

  void _removeImage() {
    setState(() {
      _image = null;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Report Equipment'),
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
              TextFormField(
                controller: nameController,
                decoration: InputDecoration(labelText: 'Equipment name'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter the equipment name';
                  }
                  return null; // Return null if the input is valid
                },
              ),
              TextFormField(
                controller: descriptionController,
                decoration: InputDecoration(labelText: 'Description'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter the description';
                  }
                  return null; // Return null if the input is valid
                },
              ),
            SizedBox(height: 10),
            _image == null
                ? ElevatedButton(
              onPressed: _getImage,
              child: Text('Add Image'),
            )
                : Column(
              children: [
                Image.file(_image!),
                SizedBox(height: 10),
                ElevatedButton(
                  onPressed: _removeImage,
                  child: Text('Remove Picture'),
                ),
              ],
            ),
              SizedBox(height: 20),
              ElevatedButton(
                onPressed: () async {
                  SharedPreferences prefs = await SharedPreferences.getInstance();
                  String equipmentId = prefs.getString('equipmentId') ?? '';
                  String itemName = nameController.text;
                  String issueDescription = descriptionController.text;
                  if (_image != null) {
                    await postEquipmentReport(
                      'equipment',
                      equipmentId,
                      itemName,
                      issueDescription,
                      _image!, // Pass the selected image file
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
                                  MaterialPageRoute(builder: (context) => EquipmentReportListPage()),
                                );
                              },
                              child: Text('OK'),
                            ),
                          ],
                        );
                      },
                    );
                  } else {
                    Fluttertoast.showToast(
                      msg: 'Please select an image before submitting the report.'
                    );
                  }
                },
                child: Text('Submit Report'),
              ),
            ],
          ),
        )
      ),
    );
  }
}
