import 'package:flutter/material.dart';

void main() {
  runApp(BookingRecordPage());
}

class BookingRecordPage extends StatelessWidget {
  const BookingRecordPage({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Booking Records',
      theme: ThemeData(
        primarySwatch: Colors.green,
      ),
      home: BookingPage(),
    );
  }
}

enum Category { Book, Equipment, Room }

class BookingRecord {
  final Category category;
  final String itemName;
  final String borrowedDate;
  bool isBorrowed;

  BookingRecord({required this.category, required this.itemName, required this.borrowedDate, this.isBorrowed = true});
}

class BookingPage extends StatefulWidget {
  @override
  _BookingPageState createState() => _BookingPageState();
}

class _BookingPageState extends State<BookingPage> {
  List<BookingRecord> bookingRecords = [
    BookingRecord(category: Category.Room, itemName: 'Discussion Room', borrowedDate: '21-09-2023'),
    BookingRecord(category: Category.Equipment, itemName: 'Projector', borrowedDate: '11-12-2023'),
    BookingRecord(category: Category.Book, itemName: 'Pride and Prejudice', borrowedDate: '11-12-2023'),
    BookingRecord(category: Category.Book, itemName: 'To Kill a Mockingbird', borrowedDate: '21-09-2023'),
    BookingRecord(category: Category.Equipment, itemName: 'Controller', borrowedDate: '21-09-2023'),
    BookingRecord(category: Category.Equipment, itemName: 'PS4', borrowedDate: '21-09-2023'),
  ];

  Map<Category, Map<String, bool>> categoryStates = {
    Category.Book: {},
    Category.Equipment: {},
    Category.Room: {},
  };

  Category selectedCategory = Category.Book;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Booking Records'),
      ),
      body: Column(
        children: [
          ToggleButtons(
            children: [
              Text('Book'),
              Text('Equipment'),
              Text('Room'),
            ],
            isSelected: [
              selectedCategory == Category.Book,
              selectedCategory == Category.Equipment,
              selectedCategory == Category.Room,
            ],
            onPressed: (buttonIndex) {
              setState(() {
                selectedCategory = Category.values[buttonIndex];
              });
            },
          ),
          Expanded(
            child: ListView.builder(
              itemCount: bookingRecords.length,
              itemBuilder: (context, index) {
                BookingRecord record = bookingRecords[index];
                if (record.category != selectedCategory) {
                  return Container(); // Skip items that don't match the selected category
                }

                // Get the borrowing state for the current record in the selected category
                bool isBorrowed = categoryStates[selectedCategory]![record.itemName] ?? true;

                return ListTile(
                  title: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(record.itemName),
                      ToggleButtonWidget(
                        isBorrowed: isBorrowed,
                        onToggle: (bool isBorrowing) {
                          // Update the borrowing state for the current record in the selected category
                          categoryStates[selectedCategory]![record.itemName] = isBorrowing;
                        },
                      ),
                    ],
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}

class ToggleButtonWidget extends StatefulWidget {
  final bool isBorrowed;
  final Function(bool) onToggle;

  ToggleButtonWidget({required this.isBorrowed, required this.onToggle});

  @override
  _ToggleButtonWidgetState createState() => _ToggleButtonWidgetState();
}

class _ToggleButtonWidgetState extends State<ToggleButtonWidget> {
  bool _isBorrowing = true;

  @override
  void initState() {
    super.initState();
    _isBorrowing = widget.isBorrowed;
  }

  @override
  Widget build(BuildContext context) {
    return TextButton(
      onPressed: _isBorrowing ? _toggleBorrowing : null,
      child: Text(_isBorrowing ? 'Borrowing' : 'Returned'),
      style: ButtonStyle(
        side: MaterialStateProperty.all<BorderSide>(
          BorderSide(
            color: Colors.black12,
          ),
        ),
      ),
    );
  }

  void _toggleBorrowing() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: Text('Return Item',
            style: TextStyle(
                color: Colors.red
            ),),
          content: Text('Are you sure you want to mark this item as returned? This action cannot be undone.'),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(); // Close the dialog
              },
              child: Text('Cancel'),
            ),
            TextButton(
              onPressed: () {
                setState(() {
                  _isBorrowing = false;
                });
                widget.onToggle(_isBorrowing);
                Navigator.of(context).pop(); // Close the dialog
              },
              child: Text('Return'),
            ),
          ],
        );
      },
    );
  }
}
