import 'package:cafe_library_services/Record/penalty_record.dart';
import 'package:cafe_library_services/Welcome/home.dart';
import 'package:flutter/material.dart';

void main() {
  runApp(BookingRecordPage());
}

class BookingRecordPage extends StatelessWidget {
  const BookingRecordPage({Key? key}) : super(key: key);

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

  BookingRecord({
    required this.category,
    required this.itemName,
    required this.borrowedDate,
    this.isBorrowed = true,
  });
}

class BookingPage extends StatefulWidget {
  @override
  _BookingPageState createState() => _BookingPageState();
}

class _BookingPageState extends State<BookingPage> {
  List<BookingRecord> bookingRecords = [
    BookingRecord(category: Category.Room, itemName: 'Discussion Room', borrowedDate: '2023-09-21'),
    BookingRecord(category: Category.Equipment, itemName: 'Projector', borrowedDate: '2023-12-11'),
    BookingRecord(category: Category.Book, itemName: 'Pride and Prejudice', borrowedDate: '2023-12-11'),
    BookingRecord(category: Category.Book, itemName: 'To Kill a Mockingbird', borrowedDate: '2023-09-21'),
    BookingRecord(category: Category.Equipment, itemName: 'Controller', borrowedDate: '2023-09-21'),
    BookingRecord(category: Category.Equipment, itemName: 'PS4', borrowedDate: '2023-09-21'),
  ];

  Map<Category, Map<String, bool>> categoryStates = {
    Category.Book: {},
    Category.Equipment: {},
    Category.Room: {},
  };

  Category selectedCategory = Category.Book;

  List<BookingRecord> itemsWithPenalties = []; // Track items with penalties

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Booking Records'),
        leading: IconButton(
          onPressed: () {
            Navigator.pushReplacement(context, MaterialPageRoute(builder:
                (context) => HomePage(libraryId: '',)));
          },
          icon: const Icon(
            Icons.arrow_back,
          ),
        ),
      ),
      body: Column(
        children: [
          ToggleButtons(
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
            children: const [
              Text('Book'),
              Text('Equipment'),
              Text('Room'),
            ],
          ),
          Expanded(
            child: ListView.builder(
              itemCount: bookingRecords.length,
              itemBuilder: (context, index) {
                BookingRecord record = bookingRecords[index];
                if (record.category != selectedCategory) {
                  return Container(); // Skip items that don't match the
                  // selected category
                }

                // Get the borrowing state for the current record in the
                // selected category
                bool isBorrowed = categoryStates[selectedCategory]![record
                    .itemName] ?? true;

                return ListTile(
                  title: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(record.itemName),
                      ToggleButtonWidget(
                        isBorrowed: isBorrowed,
                        onToggle: (bool isBorrowing, bool hasPenalty) {
                          // Update the borrowing state for the current
                          // record in the selected category
                          categoryStates[selectedCategory]![record.itemName]
                          = isBorrowing;

                          // Update the list of items with penalties
                          if (!isBorrowing && hasPenalty) {
                            itemsWithPenalties.add(record);
                          }
                        },
                        borrowedDate: record.borrowedDate, // Pass borrowed
                        // date to ToggleButtonWidget
                      ),
                    ],
                  ),
                );
              },
            ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          // Navigate to the PenaltyPage and pass the items with penalties
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => PenaltyPage(itemsWithPenalties:
              itemsWithPenalties),
            ),
          );
        },
        child: const Icon(Icons.payment),
      ),
    );
  }
}

class ToggleButtonWidget extends StatefulWidget {
  final bool isBorrowed;
  final Function(bool, bool) onToggle;
  final String borrowedDate; // Add borrowed date parameter

  ToggleButtonWidget({
    required this.isBorrowed,
    required this.onToggle,
    required this.borrowedDate,
  });

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
      onPressed: _isBorrowing ? () => _toggleBorrowing(context) : null,
      style: ButtonStyle(
        side: MaterialStateProperty.all<BorderSide>(
          const BorderSide(
            color: Colors.black12,
          ),
        ),
      ),
      child: Text(_isBorrowing ? 'Borrowing' : 'Returned'),
    );
  }

  void _toggleBorrowing(BuildContext context) {
    // Parse the borrowed date string to DateTime
    DateTime borrowedDate = DateTime.parse(widget.borrowedDate);
    DateTime currentDate = DateTime.now();

    // Calculate the difference in days
    int daysDifference = currentDate.difference(borrowedDate).inDays;
    if (daysDifference > 14) {
      // If the duration is more than 2 weeks, show an alert for penalty
      showDialog(
        context: context,
        builder: (BuildContext context) {
          return AlertDialog(
            title: const Text('Penalty Alert'),
            content: const Text('You have a penalty for late return. '
                'Do you want to proceed with the return and pay the penalty?'),
            actions: [
              TextButton(
                onPressed: () {
                  Navigator.of(context).pop(); // Close the dialog
                },
                child: const Text('Cancel'),
              ),
              TextButton(
                onPressed: () {
                  _markItemReturned(true);
                  Navigator.of(context).pop(); // Close the dialog
                },
                child: const Text('Return'),
              ),
            ],
          );
        },
      );
    } else {
      // If the duration is within 2 weeks, show a confirmation alert
      showDialog(
        context: context,
        builder: (BuildContext context) {
          return AlertDialog(
            title: const Text('Return Confirmation'),
            content: const Text('Are you sure you want to return the item?'),
            actions: [
              TextButton(
                onPressed: () {
                  Navigator.of(context).pop(); // Close the dialog
                },
                child: const Text('Cancel'),
              ),
              TextButton(
                onPressed: () {
                  _markItemReturned(false);
                  Navigator.of(context).pop(); // Close the dialog
                },
                child: const Text('Return'),
              ),
            ],
          );
        },
      );
    }
  }

  void _markItemReturned(bool hasPenalty) {
    setState(() {
      _isBorrowing = !_isBorrowing;
    });
    widget.onToggle(_isBorrowing, hasPenalty);
  }
}