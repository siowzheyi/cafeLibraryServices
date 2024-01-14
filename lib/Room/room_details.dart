import 'package:flutter/material.dart';
import 'package:cafe_library_services/Room/reserve_room.dart';
import '../Report/report.dart';

class RoomDetailsScreen extends StatelessWidget {
  final String roomNo;
  final String picture;
  final String type;

  // Add more properties as needed for room details

  RoomDetailsScreen({
    required this.roomNo,
    required this.picture,
    required this.type,
    // Add more constructor parameters as needed
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(roomNo),
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
            // Replace this with your room image
            Image.network(
              picture,
              width: double.infinity,
              height: 500.0,
              fit: BoxFit.cover,
            ),
            const SizedBox(height: 16.0),
            Text(
              roomNo,
              style: const TextStyle(fontSize: 18.0, fontWeight: FontWeight
                  .bold),
            ),
            Text(
              type,
              style: const TextStyle(fontSize: 18.0),
            ),
            // Add more details as needed
            ElevatedButton(
              onPressed: () {
                // Navigate to the ReserveRoom when the button is pressed
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (context) => ReserveRoomPage(selectedRoom:
                    roomNo,),
                  ),
                );
              },
              child: const Text('Reserve'),
            ),
          ],
        ),
      ),
    );
  }
}
