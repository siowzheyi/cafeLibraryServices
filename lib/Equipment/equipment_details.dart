import 'package:cafe_library_services/Equipment/rent_equipment.dart';
import 'package:flutter/material.dart';
import '../Report/report.dart';

class EquipmentDetailsPage extends StatelessWidget {
  final String name;
  final String picture;

  // Add more properties as needed for equipment details

  EquipmentDetailsPage({
    required this.name,
    required this.picture,
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
            icon: const Icon(Icons.report),
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
            Image.network(
              picture,
              width: double.infinity,
              height: 500.0,
              fit: BoxFit.cover,
              errorBuilder: (context, error, stackTrace) {
                // Handle image loading error
                return const Icon(Icons.error);
              },
            ),
            const SizedBox(height: 16.0),
            Text(
              name,
              style: const TextStyle(fontSize: 18.0, fontWeight: FontWeight
                  .bold),
            ),
            // Add more details as needed
            ElevatedButton(
              onPressed: () {
                // Navigate to the ReserveRoom when the button is pressed
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => ReserveEquipmentPage(
                      selectedEquipment: name,),
                  ),
                );
              },
              child: const Text('Rent'),
            ),
          ],
        ),
      ),
    );
  }
}
