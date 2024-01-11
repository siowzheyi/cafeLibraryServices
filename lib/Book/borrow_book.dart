import 'package:flutter/material.dart';

void main() {
  runApp(
    MaterialApp(
      title: 'Book Reservation',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: ReserveBookPage(selectedBook: ''),
    ),
  );
}

class ReserveBookPage extends StatefulWidget {
  final String selectedBook;

  ReserveBookPage({required this.selectedBook});

  @override
  _ReserveBookPageState createState() => _ReserveBookPageState();
}

class _ReserveBookPageState extends State<ReserveBookPage> {
  final TextEditingController quantityController = TextEditingController();
  final TextEditingController startDateController = TextEditingController();
  final TextEditingController endDateController = TextEditingController();

  String _selectedBook = '-Select Book-';
  int _quantity = 1;
  String _startDate = '';
  String _endDate = '';

  @override
  void initState() {
    super.initState();
    _selectedBook = widget.selectedBook;
  }

  void _increaseQuantity() {
    setState(() {
      if (_quantity < 5) {
        _quantity++;
      }
    });
  }

  void _decreaseQuantity() {
    setState(() {
      if (_quantity > 1) {
        _quantity--;
      }
    });
  }

  _selectStartTimeDate() async {
    final DateTime? pickedDate = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2000),
      lastDate: DateTime(2101),
    );

    if (pickedDate != null) {
      setState(() {
        _startDate = "${pickedDate.year}-${pickedDate.month}-${pickedDate.day}";
        startDateController.text = _startDate;
      });
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
      setState(() {
        _endDate = "${pickedDate.year}-${pickedDate.month}-${pickedDate.day}";
        endDateController.text = _endDate;
      });
    }
  }

  void _submitForm() {
    if (_selectedBook == '-Select Book-' || _quantity <= 0 ||
        _startDate.isEmpty || _endDate.isEmpty) {
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
            content: const Text('Please fill in the start and end date.'),
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
      );

      DateTime endDateTime = DateTime(
        int.parse(_endDate.split('-')[0]),
        int.parse(_endDate.split('-')[1]),
        int.parse(_endDate.split('-')[2]),
      );

      // Calculate the difference in hours
      Duration difference = endDateTime.difference(startDateTime);

      // check if the duration is exactly for 1 hour
      if (difference.inDays <= 14 && difference.inDays >= 1) {
        print('Book: $_selectedBook, Quantity: $_quantity, From: '
            '$_startDate, To: $_endDate,');
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
              content: const Text('You can borrow this book straight away.'),
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
              content: const Text('You can borrow this book up to 2 weeks only'
                  '. Please borrow at least for 1 day.'),
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
        title: const Text('Book Reservation'),
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
                _selectedBook,
                style: const TextStyle(
                  fontWeight: FontWeight.bold,
                  fontSize: 20.0,
                ),
              ),
            ),
            const Align(
              alignment: Alignment.centerLeft,
              child: Text(
                'Quantity:',
                style: TextStyle(fontSize: 16.0),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Row(
                children: [
                  IconButton(
                    icon: const Icon(Icons.remove),
                    onPressed: _decreaseQuantity,
                  ),
                  Container(
                    width: 100,
                    decoration: BoxDecoration(
                      border: Border.all(
                      color: Colors.black12),
                      borderRadius: BorderRadius.circular(8.0)
                    ),
                    child: Center(
                      child: Text(
                        _quantity.toString(),
                        style: const TextStyle(fontSize: 18),
                      ),
                    ),
                  ),
                  IconButton(
                    icon: const Icon(Icons.add),
                    onPressed: _increaseQuantity,
                  ),
                ],
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: TextField(
                keyboardType: TextInputType.datetime,
                controller: startDateController,
                readOnly: true,
                onTap: _selectStartTimeDate,
                decoration: const InputDecoration(labelText: '-From-'),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: TextField(
                keyboardType: TextInputType.datetime,
                controller: endDateController,
                readOnly: true,
                onTap: _selectEndTimeDate,
                decoration: const InputDecoration(labelText: '-To-'),
              ),
            ),
            const SizedBox(height: 20),
            const Text(
              'You are given 2 weeks to borrow this book.',
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
