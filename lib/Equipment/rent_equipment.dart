import 'package:flutter/material.dart';

void main() {
  runApp(
    MaterialApp(
      title: 'Equipment Reservation System',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: ReserveEquipmentPage(selectedEquipment: ''),
    ),
  );
}

class ReserveEquipmentPage extends StatefulWidget {
  final String selectedEquipment;

  ReserveEquipmentPage({required this.selectedEquipment});

  @override
  _ReserveEquipmentPageState createState() => _ReserveEquipmentPageState();
}

class _ReserveEquipmentPageState extends State<ReserveEquipmentPage> {
  final TextEditingController startTimeDateController = TextEditingController();
  final TextEditingController endTimeDateController = TextEditingController();

  String _selectedEquipment = '-Select Equipment-';
  String _startDate = '';
  String _startTime = '';
  String _endDate = '';
  String _endTime = '';

  @override
  void initState() {
    super.initState();
    _selectedEquipment = widget.selectedEquipment;
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
    if (_selectedEquipment == '-Select Equipment-' ||
        _startDate.isEmpty ||
        _startTime.isEmpty ||
        _endDate.isEmpty ||
        _endTime.isEmpty) {
      showDialog(
        context: context,
        builder: (BuildContext context) {
          return AlertDialog(
            title: const Text(
              'Error',
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
        print('Equipment: $_selectedEquipment, From: $_startDate, $_startTime'
            ', To: $_endDate, $_endTime');
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
        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              title: const Text(
                'Error',
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
        title: const Text('Equipment Reservation'),
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
                _selectedEquipment,
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
