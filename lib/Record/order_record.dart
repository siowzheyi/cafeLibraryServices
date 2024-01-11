import 'package:flutter/material.dart';

import '../Welcome/home.dart';

void main() {
  runApp(OrderRecordPage());
}

class OrderRecordPage extends StatelessWidget {
  const OrderRecordPage({super.key});

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

enum Category { Beverage, Food }

class BookingRecord {
  final Category category;
  final String itemName;
  final String orderDate;
  bool isBorrowed;

  BookingRecord({required this.category, required this.itemName, required this
      .orderDate, this.isBorrowed = true});
}

class BookingPage extends StatefulWidget {
  @override
  _OrderHistoryState createState() => _OrderHistoryState();
}

class _OrderHistoryState extends State<BookingPage> {
  List<BookingRecord> bookingRecords = [
    BookingRecord(category: Category.Beverage, itemName: 'Coffee', orderDate: '21-09-2023'),
    BookingRecord(category: Category.Food, itemName: 'Pizza', orderDate: '11-12-2023'),
    BookingRecord(category: Category.Food, itemName: 'Burger', orderDate: '11-12-2023'),
    BookingRecord(category: Category.Beverage, itemName: 'Tea', orderDate: '21-09-2023'),
    BookingRecord(category: Category.Beverage, itemName: 'Smoothie', orderDate: '21-09-2023'),
    BookingRecord(category: Category.Food, itemName: 'Salad', orderDate: '21-09-2023'),
  ];

  Category selectedCategory = Category.Beverage; // Set a default category

  List<BookingRecord> getFilteredRecords() {
    return bookingRecords.where((record) => record.category ==
        selectedCategory).toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Booking Records'),
        leading: IconButton(
          onPressed: () {
            Navigator.pushReplacement(context, MaterialPageRoute(builder:
                (context) => HomePage()));
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
              selectedCategory == Category.Beverage,
              selectedCategory == Category.Food,
            ],
            onPressed: (buttonIndex) {
              setState(() {
                selectedCategory = Category.values[buttonIndex];
              });
            },
            children: const [
              Text('Beverage'),
              Text('Food'),
            ],
          ),
          Expanded(
            child: ListView.builder(
              itemCount: getFilteredRecords().length,
              itemBuilder: (context, index) {
                BookingRecord record = getFilteredRecords()[index];

                return Card(
                  margin: const EdgeInsets.all(8.0),
                  child: ListTile(
                    title: Text(record.itemName),
                    subtitle: Text('Ordered on ${record.orderDate}'),
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
