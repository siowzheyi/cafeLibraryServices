import 'package:flutter/material.dart';
import 'package:cafe_library_services/Room/reserve_room.dart';

import '../Report/report.dart';

class RoomDetailsPage extends StatelessWidget {
  final String name;
  final String imageUrl;
  bool isAvailable;

  // Add more properties as needed for room details

  RoomDetailsPage({
    required this.name,
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
            // Replace this with your room image
            Image.asset(
              imageUrl,
              width: double.infinity,
              height: 500.0,
              fit: BoxFit.cover,
            ),
            SizedBox(height: 16.0),
            Text(
              'Name: $name',
              style: TextStyle(fontSize: 18.0, fontWeight: FontWeight.bold),
            ),
            Text(
              '${isAvailable ? 'Available' : 'In used'}',
              style: TextStyle(
                fontSize: 16.0,
                color: isAvailable ? Colors.green : Colors.red,
              ),
            ),
            // Add more details as needed
            ElevatedButton(
              onPressed: () {
                // Navigate to the ReserveRoom when the button is pressed
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => ReserveRoomPage(),
                  ),
                );
              },
              child: Text('Reserve'),
            ),
          ],
        ),
      ),
    );
  }
}
