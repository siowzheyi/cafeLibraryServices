import 'package:cafe_library_services/Equipment/rent_equipment.dart';
import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../Report/equipment_report.dart';

class EquipmentDetailsScreen extends StatelessWidget {
  final int id;
  final String name;
  final String picture;

  EquipmentDetailsScreen({
    required this.id,
    required this.name,
    required this.picture,
  });

  Future<void> setEquipmentId(int equipmentId) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString('equipmentId', equipmentId.toString());
  }

  Future<String> getEquipmentId() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString('equipmentId') ??
        ''; // Default to an empty string if not found
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(name),
        actions: [
          // Add a Report button in the app bar
          IconButton(
            icon: const Icon(Icons.report),
            tooltip: 'Report this equipment',
            onPressed: () async {
              await setEquipmentId(id);
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => EquipmentReportPage(equipmentId: '',),
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
