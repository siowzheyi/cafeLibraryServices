import 'package:cafe_library_services/Equipment/rent_equipment.dart';
import 'package:flutter/material.dart';

import '../Report/report.dart';

class EquipmentDetailsPage extends StatelessWidget {
  final String name;
  final String price;
  final String imageUrl;
  bool isAvailable;

  // Add more properties as needed for equipment details

  EquipmentDetailsPage({
    required this.name,
    required this.price,
    required this.imageUrl,
    required this.isAvailable,
    // Add more constructor parameters as needed
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(name),
        actions: [
          // Add a Report button in the app bar
          IconButton(
            icon: Icon(Icons.report),
            tooltip: 'Report this item',
            onPressed: () {
              // Navigate to the ReportPage when the button is pressed
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => ReportPage(),
                ),
              );
            },
          ),
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Image.asset(
              imageUrl,
              width: double.infinity,
              height: 900.0,
              fit: BoxFit.cover,
            ),
            SizedBox(height: 16.0),
            Text(
              'Title: $name',
              style: TextStyle(fontSize: 18.0, fontWeight: FontWeight.bold),
            ),
            Text(
              'Fee: $price',
              style: TextStyle(fontSize: 16.0),
            ),
            Text(
              '${isAvailable ? 'Available' : 'Checked out'}',
              style: TextStyle(
                fontSize: 16.0,
                color: isAvailable ? Colors.green : Colors.red,
              ),
            ),
            // Add more details as needed
            if (isAvailable)
              ElevatedButton(
                onPressed: () {
                  // Navigate to the ReserveRoom when the button is pressed
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => ReserveEquipmentPage(selectedEquipment: name,),
                    ),
                  );
                },
                child: Text('Rent'),
              ),
          ],
        ),
      ),
    );
  }
}
