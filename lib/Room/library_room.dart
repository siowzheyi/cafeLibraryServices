import 'package:flutter/material.dart';

void main() {
  runApp(RoomReservationApp());
}

class RoomReservationApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Room Reservation System',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: RoomReservationPage(),
    );
  }
}

class RoomReservationPage extends StatefulWidget {
  @override
  _RoomReservationPageState createState() => _RoomReservationPageState();
}

class _RoomReservationPageState extends State<RoomReservationPage> {
  final List<String> roomOptions = ['-Select Room-', 'Discussion Room', 'Meeting Room', 'Carel Room', 'Presentation Room'];
  //final List<String> dateOptions = []
  final List<String> dateOptions = ['-Select Date-', '12-Dec-2023', '13-Dec-2023', '14-Dec-2023', '15-Dec-2023', '16-Dec-2023'];
  final List<String> timeOptions = ['-Select Time-', '08:00-09:00', '09:00-10:00', '10:00-11:00', '11:00-12:00', '12:00-13:00', '13:00-14:00', '14:00-15:00', '15:00-16:00', '16:00-17:00'];
  // final DateTime dateOptions = DateTime.now(); // Selected date
  //final TimeOfDay timeOptions = TimeOfDay(hour: 10, minute: 0); // Selected time
  //final List<String> dateOptions = DateTime.now() as List<String>;
  //final List<String> timeOptions = TimeOfDay(hour: 10, minute: 0) as List<String>;

  String _selectedRoom = '-Select Room-';
  String _selectedDate = '-Select Date-';
  String _selectedTime = '-Select Time-';

  get selectedDate => null;

  // void selectDate(BuildContext context) async {
  //   final DateTime? picked = await showDatePicker(
  //     context: context,
  //     initialDate: selectedDate ?? DateTime.now(),
  //     firstDate: DateTime(2022),
  //     lastDate: DateTime(2023),
  //   );
  //   if (picked != null && picked != selectedDate) {
  //     setState(() {
  //       _selectedDate = picked as String;
  //     });
  //   }
  // }

  void _submitForm() {
    print('Room: $_selectedRoom, Date: $_selectedDate, Time: $_selectedTime');
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Success'),
          content: Text('Your reservation has been submitted.'),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
              },
              child: Text('OK'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Library Room Reservation'),
      ),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Column(
          children: <Widget>[
            DropdownButtonFormField(
              value: _selectedRoom,
              onChanged: (newValue) => setState(() {
                _selectedRoom = newValue!;
              }),
              items: roomOptions.map((String value) {
                return DropdownMenuItem<String>(
                  value: value,
                  child: Text(value),
                );
              }).toList(),
            ),
            DropdownButtonFormField(
              items: dateOptions.map((String value) {
                return DropdownMenuItem<String>(
                  value: value,
                  child: Text(value),
                );
              }).toList(),
              value: _selectedDate,
              onChanged: (newValue) {
                setState(() {
                  _selectedDate = newValue!;
                });
              },
            ),
            DropdownButtonFormField(
              items: timeOptions.map((String value) {
                return DropdownMenuItem<String>(
                  value: value,
                  child: Text(value),
                );
              }).toList(),
              value: _selectedTime,
              onChanged: (newValue) {
                setState(() {
                  _selectedTime = newValue!;
                });
              },
            ),
            SizedBox(height: 20),
            Text('Selection time for 1 hour only.'),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: _submitForm,
              child: Text('Submit'),
            ),
          ],
        ),
      ),
    );
  }
}