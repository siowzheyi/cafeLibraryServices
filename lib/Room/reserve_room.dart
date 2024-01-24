import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import '../Booking/booking_listing.dart';
import '../Controller/connection.dart';
import 'dart:convert';

void main() {
  runApp(
    MaterialApp(
      title: 'Room Reservation',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: ReserveRoomPage(selectedRoom: ''),
    ),
  );
}

class ReserveRoomPage extends StatefulWidget {
  final String selectedRoom;

  ReserveRoomPage({required this.selectedRoom});

  @override
  _ReserveRoomPageState createState() => _ReserveRoomPageState();
}

class _ReserveRoomPageState extends State<ReserveRoomPage> {
  final TextEditingController quantityController = TextEditingController();
  final TextEditingController startTimeDateController = TextEditingController();
  final TextEditingController endTimeDateController = TextEditingController();

  Future<void> postRentRoom(String type, int quantity, int roomId, String
  startBookedAt, String endBookedAt) async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String token = prefs.getString('token') ?? '';
    String roomId = prefs.getString('roomId') ?? '';
    try {
      var headers = {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      };

      var requestBody = {
        'type': 'room',
        'quantity': 1,
        'room_id': roomId,
        'start_booked_at': startBookedAt,
        'end_booked_at': endBookedAt,
      };

      var response = await http.post(
        Uri.parse(API.rent),
        headers: headers,
        body: json.encode(requestBody),
      );

      if (response.statusCode == 200) {
        print(response.body);
      } else {
        print('Error statusCode: ${response.statusCode}, Reason Phrase: '
            '${response.reasonPhrase}');
      }
    } catch (error) {
      print('Error: $error');
    }
  }

  String _selectedRoom = '-Select Room-';
  String _startDate = '';
  String _startTime = '';
  String _endDate = '';
  String _endTime = '';

  @override
  void initState() {
    super.initState();
    _selectedRoom = widget.selectedRoom;
  }

  _selectStartTimeDate() async {
    final DateTime? pickedDate = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2000),
      lastDate: DateTime(2101),
    );

    if (pickedDate != null) {
      final TimeOfDay? pickedTime = await showTimePicker(
        context: context,
        initialTime: TimeOfDay.now(),
      );

      if (pickedTime != null) {
        setState(() {
          _startDate = "${pickedDate.year}-${pickedDate.month}-${pickedDate
              .day}";
          _startTime = "${pickedTime.hour}:${pickedTime.minute}";
          startTimeDateController.text = "$_startDate $_startTime";
        });
      }
    }
  }

  _selectEndTimeDate() async {
    final DateTime? pickedDate = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2000),
      lastDate: DateTime(2101),
    );

    if (pickedDate != null) {
      final TimeOfDay? pickedTime = await showTimePicker(
        context: context,
        initialTime: TimeOfDay.now(),
      );

      if (pickedTime != null) {
        setState(() {
          _endDate = "${pickedDate.year}-${pickedDate.month}-${pickedDate.day}";
          _endTime = "${pickedTime.hour}:${pickedTime.minute}";
          endTimeDateController.text = "$_endDate $_endTime";
        });
      }
    }
  }

  void _submitForm() {
    if (_selectedRoom == '-Select Room-' ||
        _startDate.isEmpty ||
        _startTime.isEmpty ||
        _endDate.isEmpty ||
        _endTime.isEmpty) {
      showDialog(
        context: context,
        builder: (BuildContext context) {
          return AlertDialog(
            title: const Text(
              '',
              style: TextStyle(
                color: Colors.red,
              ),
            ),
            content: const Text('Please fill in all the fields.'),
            actions: [
              TextButton(
                onPressed: () {
                  Navigator.of(context).pop();
                },
                child: const Text('OK'),
              ),
            ],
          );
        },
      );
    } else {
      DateTime startDateTime = DateTime(
        int.parse(_startDate.split('-')[0]),
        int.parse(_startDate.split('-')[1]),
        int.parse(_startDate.split('-')[2]),
        int.parse(_startTime.split(':')[0]),
        int.parse(_startTime.split(':')[1]),
      );

      DateTime endDateTime = DateTime(
        int.parse(_endDate.split('-')[0]),
        int.parse(_endDate.split('-')[1]),
        int.parse(_endDate.split('-')[2]),
        int.parse(_endTime.split(':')[0]),
        int.parse(_endTime.split(':')[1]),
      );

      // Calculate the difference in hours
      Duration difference = endDateTime.difference(startDateTime);

      // check if the duration is exactly for 1 hour
      if (difference.inHours == 1 && difference.inMinutes == 60) {
        print('Room: $_selectedRoom, From: $_startDate, $_startTime, To: '
            '$_endDate, $_endTime');
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text(
                'Success',
                style: TextStyle(
                  color: Colors.green,
                ),
              ),
              content: const Text('Your reservation has been submitted.'),
              actions: [
                TextButton(
                  onPressed: () async {
                    SharedPreferences prefs = await SharedPreferences
                        .getInstance();
                    String roomId = prefs.getString('roomId') ?? '';
                    int parsedId = int.tryParse(roomId) ?? 0;
                    String start = startTimeDateController.text;
                    String end = endTimeDateController.text;
                    await postRentRoom(
                        'room',
                        1,
                        parsedId,
                        start,
                        end);
                    Navigator.of(context).pop();
                    Navigator.pushReplacement(
                      context,
                      MaterialPageRoute(
                        builder: (context) => BookingListing(),
                      ),
                    );
                  },
                  child: const Text('OK'),
                ),
              ],
            );
          },
        );
      } else {
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text(
                'Try again',
                style: TextStyle(
                  color: Colors.red,
                ),
              ),
              content: const Text('Please select exactly a 1-hour time slot.'),
              actions: [
                TextButton(
                  onPressed: () {
                    Navigator.of(context).pop();
                  },
                  child: const Text('OK'),
                ),
              ],
            );
          },
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Room Reservation'),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: <Widget>[
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Text(
                _selectedRoom,
                style: const TextStyle(
                  fontWeight: FontWeight.bold,
                  fontSize: 20.0,
                ),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: TextField(
                keyboardType: TextInputType.datetime,
                controller: startTimeDateController,
                readOnly: true,
                onTap: _selectStartTimeDate,
                decoration: const InputDecoration(labelText: '-From-'),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: TextField(
                keyboardType: TextInputType.datetime,
                controller: endTimeDateController,
                readOnly: true,
                onTap: _selectEndTimeDate,
                decoration: const InputDecoration(labelText: '-To-'),
              ),
            ),
            const SizedBox(height: 20),
            const Text(
              'Selection time for 1 hour only.',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 16.0,
              ),
            ),
            const SizedBox(height: 20),
            Row(
              children: [
                ElevatedButton(
                  onPressed: _submitForm,
                  child: const Text('Submit'),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
