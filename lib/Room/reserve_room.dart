import 'package:flutter/material.dart';

void main() {
  runApp(
      MaterialApp(
        title: 'Room Reservation System',
        theme: ThemeData(
          primarySwatch: Colors.green,
        ),
        home: ReserveRoomPage(),
      )
  );
}

class ReserveRoomPage extends StatefulWidget {
  @override
  _ReserveRoomPageState createState() => _ReserveRoomPageState();
}

class _ReserveRoomPageState extends State<ReserveRoomPage> {
  final List<String> roomOptions = ['-Select Room-', 'Discussion Room', 'Meeting Room', 'Carel Room', 'Presentation Room'];
  final TextEditingController textDateController = TextEditingController();

  String _selectedRoom = '-Select Room-';
  String _selectedDate = '-Select Date-';
  String _selectedTime = '-Select Time-';

  _selectDate() async {
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
          _selectedDate = "${pickedDate.year}-${pickedDate.month}-${pickedDate.day}";
          _selectedTime = "${pickedTime.hour}:${pickedTime.minute}";
          textDateController.text = "$_selectedDate $_selectedTime";
        });
      }
    }
  }

  void _submitForm() {
    if (_selectedRoom == '-Select Room-' || _selectedDate.isEmpty || _selectedTime.isEmpty) {
      showDialog(
        context: context,
        builder: (BuildContext context) {
          return AlertDialog(
            title: Text('Error'),
            content: Text('Please fill in all the fields.'),
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
    } else {
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
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Library Room Reservation'),
        leading: IconButton(
          icon: Icon(Icons.arrow_back),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
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
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: TextField(
                keyboardType: TextInputType.datetime,
                controller: textDateController,
                readOnly: true,
                onTap: _selectDate,
                decoration: const InputDecoration(labelText: '-Select Date and Time-'),
              ),
            ),
            SizedBox(height: 20),
            Text('Selection time for 1 hour only.'),
            SizedBox(height: 20),
            Row(
              children: [
                ElevatedButton(
                  onPressed: _submitForm,
                  child: Text('Submit'),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
