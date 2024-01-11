import 'package:flutter/material.dart';

void main() {
  runApp(
    MaterialApp(
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: ReportPage(),
    ),
  );
}

class ReportPage extends StatefulWidget {
  const ReportPage({Key? key}) : super(key: key);

  @override
  _ReportPageState createState() => _ReportPageState();
}

class _ReportPageState extends State<ReportPage> {
  final TextEditingController itemNameController = TextEditingController();
  final TextEditingController issueDescriptionController =
  TextEditingController();

  void _submitReport() {
    // Handle the logic to submit the report
    String itemName = itemNameController.text;
    String issueDescription = issueDescriptionController.text;

    // Print the details for demonstration purposes
    print('Item Name: $itemName');
    print('Issue Description: $issueDescription');

    // Add logic to send the report to the server or perform other actions
  }

  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Report Item'),
      ),
      body: Form(
        key: _formKey, // Add this key to your Form
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Item Details',
                style: TextStyle(
                  fontSize: 24.0,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8.0),
              TextFormField(
                controller: itemNameController,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter the item name';
                  }
                  return null;
                },
                decoration: const InputDecoration(
                  labelText: 'Item Name',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 16.0),
              const Text(
                'Issue Description',
                style: TextStyle(
                  fontSize: 24.0,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8.0),
              TextFormField(
                controller: issueDescriptionController,
                maxLines: 5,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please describe the issue';
                  }
                  return null;
                },
                decoration: const InputDecoration(
                  labelText: 'Describe the issue',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 20.0),
              Row(
                children: [
                  ElevatedButton(
                    onPressed: () {
                      // Validate returns true if the form is valid, or false
                      // otherwise.
                      if (_formKey.currentState!.validate()) {
                        // If the form is valid, display a snackbar

                        // you'd often call a server or save the information
                        // in a database.
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Report submitted!')),
                        );
                      }
                    },
                    child: const Text('Submit Report'),
                  ),
                  const SizedBox(width: 16.0,),
                  ElevatedButton(
                    onPressed: (){
                      Navigator.pop(context);
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.red,
                    ),
                    child: const Text('Cancel'),
                  )
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
