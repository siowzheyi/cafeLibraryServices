import 'package:cafe_library_services/Report/room_report.dart';
import 'package:flutter/material.dart';
import 'package:cafe_library_services/Room/reserve_room.dart';
import 'package:shared_preferences/shared_preferences.dart';

class RoomDetailsScreen extends StatelessWidget {
  final int id;
  final String roomNo;
  final String picture;
  final String type;

  RoomDetailsScreen({
    required this.id,
    required this.roomNo,
    required this.picture,
    required this.type,
  });

  Future<void> setRoomId(int roomId) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString('roomId', roomId.toString());
  }

  Future<String> getRoomId() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString('roomId') ??
        ''; // Default to an empty string if not found
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(roomNo),
        actions: [
          // Add a Report button in the app bar
          IconButton(
            icon: const Icon(Icons.report),
            tooltip: 'Report this room',
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => RoomReportPage(roomId: ''),
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
